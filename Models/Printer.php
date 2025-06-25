<?php

namespace App\Modules\SalesPurchases\Models;

use App\Modules\Base\Traits\HasActivity;
use App\Modules\Base\Traits\HasCreator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Mike42\Escpos\Printer as PosPrinter;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class Printer extends Model
{
    //
    use HasCreator, HasActivity;
    protected $table = 'sp_printers';
    protected $guarded = ['id'];

    public function generatePrinterText(array $data, int $width = 32): string
    {
        $content = '';

        $init        = "\x1B\x40";          // Initialize
        $cut         = "\x1D\x56\x01";      // Full cut
        $feed        = "\x1B\x64\x03";      // Feed 3 lines
        $openDrawer  = "\x1B\x70\x00\x19\xFA"; // Open drawer

        // Kirim command binary ESC/POS
        $content .= $init;
        $content .= $openDrawer;

        // Header
        $content .= str_pad(strtoupper($data['toko']['nama']), $width, ' ', STR_PAD_BOTH) . "\n";
        $content .= str_pad($data['toko']['alamat'], $width, ' ', STR_PAD_BOTH) . "\n";
        $content .= str_pad("Telp: ".$data['toko']['telepon'], $width, ' ', STR_PAD_BOTH) . "\n";
        $content .= str_repeat('-', $width) . "\n";
        
        $content .= str_pad("No.     : " . $data['code'], $width, ' ', STR_PAD_RIGHT) . "\n";
        $content .= str_pad("Kasir   : " . $data['kasir'], $width, ' ', STR_PAD_RIGHT) . "\n";
        $content .= str_pad("Tanggal : " . (new \Carbon\Carbon($data['tanggal']))->format('d-m-Y H:i:s'), $width, ' ', STR_PAD_RIGHT) . "\n";
        $content .= str_pad("Member  : " . $data['member'], $width, ' ', STR_PAD_RIGHT) . "\n";
        
        $content .= str_repeat('-', $width) . "\n";
        $content .= str_pad('Item', $width - strlen('Subtotal'), ' ', STR_PAD_RIGHT) . 'Subtotal' . "\n";
        $content .= str_repeat('-', $width) . "\n";

        $total = 0;
        foreach ($data['items'] as $item) {
            $name = $item['name'];
            $qty = $item['qty'];
            $price = number_format($item['base_price']);
            $subtotal = $qty * $item['base_price'];
            $total += $subtotal;
            $subtotalFormatted = number_format($subtotal);

            $content .= $name . "\n";
            $qtyPrice = "{$qty} x {$price}";
            $content .= str_pad($qtyPrice, $width - strlen($subtotalFormatted), ' ', STR_PAD_RIGHT) . $subtotalFormatted . "\n";
        }

        $content .= str_repeat('-', $width) . "\n";
        $totalFormatted = number_format($total);
        $bayarFormatted = number_format($data['bayar']);
        $kembaliFormatted = number_format($data['bayar'] - $total);

        $content .= str_pad('TOTAL', $width - strlen($totalFormatted), ' ', STR_PAD_RIGHT) . $totalFormatted . "\n";
        $content .= str_pad('BAYAR', $width - strlen($bayarFormatted), ' ', STR_PAD_RIGHT) . $bayarFormatted . "\n";
        $content .= str_pad('KEMBALI', $width - strlen($kembaliFormatted), ' ', STR_PAD_RIGHT) . $kembaliFormatted . "\n";
        $content .= str_repeat('-', $width) . "\n";

        $footerText = env('PRINT_FOOTER_TEXT', "Terima kasih\nSilahkan datang kembali");
        foreach (explode("\n", $footerText) as $line) {
            $content .= str_pad($line, $width, ' ', STR_PAD_BOTH) . "\n";
        }

        $content .= $feed;
        if ($this->auto_cut == 'YES') {
            $content .= $cut;
        }

        return $content;
    }


    public function printString($data)
    {
        return $this->generatePrinterText($data, $this->paper_size);
    }

    public function printStruk($data)
    {
        if(in_array($this->type, ['WINDOWS','NETWORK','USB']))
        {
            return $this->directPrintStruk($data);
        }

        else if($this->type == 'NOSTRA-SOCKET')
        {
            $printString = $this->printString($data);
            file_put_contents('print.txt', $printString);
            $connectionString = $this->connection_string;
            $connectionString = explode(':', $connectionString);
            
            $host = $connectionString[0]; // Ganti dengan IP/host server socket
            $port = $connectionString[1];                    // Ganti dengan port server socket
            $uniqId = $connectionString[2];         // ID unik printer ini

            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            if (!$socket) {
                die("Gagal membuat socket\n");
            }

            if (!socket_connect($socket, $host, $port)) {
                die("Gagal konek ke server\n");
            }
            // Kirim uniq_id untuk registrasi
            socket_write($socket, '{"type":"print","uniq_id":"'.$uniqId.'"}'."\n");

            socket_close($socket);
        }
    }

    public function directPrintStruk($data)
    {
        try {
            // Inisialisasi konektor sesuai tipe
            switch ($this->type) {
                case 'WINDOWS':
                    $connector = new \Mike42\Escpos\PrintConnectors\WindowsPrintConnector($this->connection_string);
                    break;
                case 'NETWORK':
                    $connector = new \Mike42\Escpos\PrintConnectors\NetworkPrintConnector($this->connection_string);
                    break;
                case 'USB':
                    $connector = new \Mike42\Escpos\PrintConnectors\FilePrintConnector($this->connection_string);
                    break;
                default:
                    throw new \Exception("Jenis koneksi printer tidak didukung.");
            }

            $printer = new PosPrinter($connector);
            $paperSize = $this->paper_size;

            // Header toko
            $printer->setJustification(PosPrinter::JUSTIFY_CENTER);
            $printer->setTextSize(2, 2);
            $printer->text($data['toko']['nama'] . "\n");
            $printer->setTextSize(1, 1);
            $printer->text($data['toko']['alamat'] . "\n");
            $printer->text("Telp: " . $data['toko']['telepon'] . "\n\n");

            // Info transaksi
            $printer->setJustification(PosPrinter::JUSTIFY_LEFT);
            $printer->text("No.    : " . $data['code'] . "\n");
            $printer->text("Kasir  : " . $data['kasir'] . "\n");
            $printer->text("Tanggal: " . (new \Carbon\Carbon($data['tanggal']))->format('d-m-Y H:i:s') . "\n");
            $printer->text(str_repeat("-", $paperSize) . "\n");

            // Items
            $total = 0;
            foreach ($data['items'] as $item) {
                $subtotal = $item['total_price'];
                $total += $subtotal;

                // Baris 1: Nama Produk
                $printer->text($item['name'] . "\n");

                // Baris 2: Harga x Qty        Subtotal (rata kanan)
                $line = number_format($item['base_price'], 0, ',', '.') . " x " . $item['qty'];
                $sub = number_format($subtotal, 0, ',', '.');
                $padding = $paperSize - strlen($line) - strlen($sub);
                $printer->text($line . str_repeat(' ', max($padding, 1)) . $sub . "\n");
            }

            $printer->text(str_repeat("-", $paperSize) . "\n");

            // Total, Bayar, Kembali
            $bayar = $data['bayar'];
            $kembali = $bayar - $total;

            $printer->setJustification(PosPrinter::JUSTIFY_RIGHT);
            $printer->text("Total    : Rp " . number_format($total, 0, ',', '.') . "\n");
            $printer->text("Bayar    : Rp " . number_format($bayar, 0, ',', '.') . "\n");
            $printer->text("Kembali  : Rp " . number_format($kembali, 0, ',', '.') . "\n\n");

            // Footer
            $printer->setJustification(PosPrinter::JUSTIFY_CENTER);
            $footerText = env('PRINT_FOOTER_TEXT', "Terima kasih\nSilahkan datang kembali\n");
            $printer->text($footerText);

            $printer->feed(1);
            if ($this->auto_cut == 'YES') {
                $printer->cut();
            }

            // $printer->pulse();

            $printer->close();
        } catch (\Exception $e) {
            Log::error("Printer Error: " . $e->getMessage());
            echo $e->getMessage();
        }

        try {
            shell_exec('echo -e "\x1B\x70\x00\x3C\xFF" > /dev/usb/lp0');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function doPrint($text)
    {
        try {
            // Inisialisasi konektor sesuai tipe
            switch ($this->type) {
                case 'WINDOWS':
                    $connector = new \Mike42\Escpos\PrintConnectors\WindowsPrintConnector($this->connection_string);
                    break;
                case 'NETWORK':
                    $connector = new \Mike42\Escpos\PrintConnectors\NetworkPrintConnector($this->connection_string);
                    break;
                case 'USB':
                    $connector = new \Mike42\Escpos\PrintConnectors\FilePrintConnector($this->connection_string);
                    break;
                default:
                    throw new \Exception("Jenis koneksi printer tidak didukung.");
            }

            $printer = new PosPrinter($connector);
            // Header toko
            $printer->text($text);

            $printer->feed(1);
            if ($this->auto_cut == 'YES') {
                $printer->cut();
            }

            // $printer->pulse();

            $printer->close();
        } catch (\Exception $e) {
            Log::error("Printer Error: " . $e->getMessage());
            echo $e->getMessage();
        }

        try {
            shell_exec('echo -e "\x1B\x70\x00\x3C\xFF" > /dev/usb/lp0');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function escpos()
    {
        try {
            // Inisialisasi konektor sesuai tipe
            switch ($this->type) {
                case 'WINDOWS':
                    $connector = new \Mike42\Escpos\PrintConnectors\WindowsPrintConnector($this->connection_string);
                    break;
                case 'NETWORK':
                    $connector = new \Mike42\Escpos\PrintConnectors\NetworkPrintConnector($this->connection_string);
                    break;
                case 'USB':
                    $connector = new \Mike42\Escpos\PrintConnectors\FilePrintConnector($this->connection_string);
                    break;
                default:
                    throw new \Exception("Jenis koneksi printer tidak didukung.");
            }

            return new PosPrinter($connector);

        } catch (\Exception $e) {
            Log::error("Printer Error: " . $e->getMessage());
            echo $e->getMessage();
        }
    }

    public function testPulse($int = 0)
    {
        $printer = $this->escpos();
        $printer->pulse($int);
        $printer->close();

        try {
            shell_exec('echo -e "\x1B\x70\x00\x3C\xFF" > /dev/usb/lp0');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

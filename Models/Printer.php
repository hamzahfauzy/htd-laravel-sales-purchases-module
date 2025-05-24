<?php

namespace App\Modules\SalesPurchases\Models;

use App\Modules\Base\Traits\HasActivity;
use App\Modules\Base\Traits\HasCreator;
use Illuminate\Database\Eloquent\Model;
use Mike42\Escpos\Printer as PosPrinter;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class Printer extends Model
{
    //
    use HasCreator, HasActivity;
    protected $table = 'sp_printers';
    protected $guarded = ['id'];

    public function printStruk($data)
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

            $printer->pulse();

            $printer->close();
        } catch (\Exception $e) {
            \Log::error("Printer Error: " . $e->getMessage());
            echo $e->getMessage();
        }
    }
}

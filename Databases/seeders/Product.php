<?php

namespace App\Modules\SalesPurchases\Databases\seeders;

use App\Modules\Inventory\Models\Item;
use App\Modules\Inventory\Models\ItemConversion;
use App\Modules\SalesPurchases\Models\Price;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Product extends Seeder
{
    public function run(): void
    {
        $inputFileName = public_path('modules/salespurchases/dataproducts.xlsx');
        try {
            $spreadsheet = IOFactory::load($inputFileName);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();
            foreach ($data as $i => $row) {
                if ($i == 0) continue;
                $code = $row[0]; 
                $name = $row[1];
                $unit = empty($row[2]) ? 'PCS' : $row[2];
                $unit2 = $row[3];
                $conversion2 = $row[4];
                $unit3 = $row[5];
                $conversion3 = $row[6];
                $unit4 = $row[7];
                $conversion4 = $row[8];
                $unit5 = $row[9];
                $conversion5 = $row[10];
                $purchase_price = $row[21];
                $amount1 = $row[23];
                $qty1 = $row[24];
                $amount2 = $row[25];
                $qty2 = $row[26];
                $amount3 = $row[27];
                $qty3 = $row[28];
                $low_stock_alert = 20;

                if(empty($name)) continue;

                if(Item::where('code', $code)->exists()) continue;

                $itemModel = Item::create([
                    'code' => $code,
                    'name' => $name,
                    'unit' => $unit,
                    'low_stock_alert' => $low_stock_alert,
                ]);

                if($unit2 && $unit2 != 0 && $unit2 != $unit)
                {
                    ItemConversion::create([
                        'item_id' => $itemModel->id,
                        'unit' => $unit2,
                        'value' => $conversion2
                    ]);
                }

                if($unit3 && $unit3 != 0 && $unit3 != $unit)
                {
                    ItemConversion::create([
                        'item_id' => $itemModel->id,
                        'unit' => $unit3,
                        'value' => $conversion3
                    ]);
                }
                
                if($unit4 && $unit4 != 0 && $unit4 != $unit)
                {
                    ItemConversion::create([
                        'item_id' => $itemModel->id,
                        'unit' => $unit4,
                        'value' => $conversion4
                    ]);
                }
                
                if($unit5 && $unit5 != 0 && $unit5 != $unit)
                {
                    ItemConversion::create([
                        'item_id' => $itemModel->id,
                        'unit' => $unit5,
                        'value' => $conversion5
                    ]);
                }

                Price::create([
                    'product_id' => $itemModel->id,
                    'unit' => $unit,
                    'purchase_price' => $purchase_price,
                    'amount_1' => $amount1,
                    'min_qty_1' => $qty1,
                    'amount_2' => $amount2,
                    'min_qty_2' => $qty2,
                    'amount_3' => $amount3,
                    'min_qty_3' => $qty3,
                ]);

                echo $code . " - ". $name ." import success\n";
            }
            
            echo "Successfully imported " . count($data)-1 . " items";
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            die('Error loading file: ' . $e->getMessage());
        }
    }
}
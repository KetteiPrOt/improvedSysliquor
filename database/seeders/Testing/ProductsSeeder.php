<?php

namespace Database\Seeders\Testing;

use App\Models\Product;
use App\Models\SalePrice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultMinimunStock = 12;
        $products = ['jhonnie rojo', 'jhonnie negro'];
        // Type: Whisky
        $type = 1;
        // Presentation: 750ml
        $presentation = 3;

        foreach($products as $product){
            // Register Product
            $product = Product::create([
                'name' => str_replace(
                    "ñ", "Ñ", strtoupper($product)
                ),
                'minimun_stock' => $defaultMinimunStock,
                'type_id' => $type,
                'presentation_id' => $presentation,
            ]);
            // Save sale prices
            $unitsIds = [1,2, 3];
            foreach($unitsIds as $unitsId){
                SalePrice::create([
                    'price' => 1.00,
                    'units_number_id' => $unitsId,
                    'product_id' => $product->id
                ]);
            }
            // Register initial inventory
            // $this->registerInitialInventory($product);
        }
    }

    // public function registerInitialInventory(Product $product): void
    // {
    //     // Store Invoice
    //     $userId = User::where('name', 'Fernando Joel Mero Trávez')->first()->id;
    //     $movementCategoryId = MovementCategory::income()->id;
    //     $invoice = Invoice::create([
    //         'number' => null,
    //         'date' => date('Y-m-d'),
    //         'user_id' => $userId,
    //         'person_id' => null,
    //         'movement_category_id' => $movementCategoryId
    //     ]);  

    //     // Start Inventory
    //     $initialInventoryId = MovementType::initialInventory()->id;
    //     $amount_input = 200;
    //     $unitary_price_input = 10.00;
    //     $totalPrice = round(
    //         $amount_input * $unitary_price_input,
    //         2,
    //         PHP_ROUND_HALF_UP
    //     );

    //     $data = [
    //         'unitary_price' => $unitary_price_input,
    //         'amount' => $amount_input,
    //         'total_price' => $totalPrice,
    //         'movement_type_id' => $initialInventoryId,
    //         'product_id' => $product->id,
    //         'invoice_id' => $invoice->id
    //     ];
    //     $movement = Movement::create($data);
    //     Balance::create([
    //         'amount' => $amount_input,
    //         'unitary_price' => $unitary_price_input,
    //         'total_price' => $totalPrice,
    //         'movement_id' => $movement->id
    //     ]);
    //     $product = Product::find($product->id);
    //     $product->started_inventory = true;
    //     $product->save();
    // }
}

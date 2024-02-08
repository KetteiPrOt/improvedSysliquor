<?php

namespace Database\Seeders;

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
        $types = [
            'WHISKY' =>  1,
            'VINO' =>  2,
            'RON' =>  3,
            'AGUARDIENTE' =>  4,
            'TEQUILA' =>  5,
            'COCKTAIL' =>  6,
            'ESPUMANTE' => 7,
            'SANGRIA' => 8,
            'VODKA' => 9
        ];
        $presentations = [
            200 => 1,
            375 => 2,
            750 => 3,
            1000 => 4,
            1500 => 5,
            600 => 6,
            700 => 7
        ];

        $products = $this->defineProducts();
        foreach($products as $product){
            $productId = Product::create([
                'name' => str_replace(
                    "ñ", "Ñ", strtoupper($product[0])
                ),
                'minimun_stock' => $defaultMinimunStock,
                'type_id' => $types[
                    $product[1]
                ],
                'presentation_id' => $presentations[
                    $product[2]
                ],
            ])->id;
            // Save sale prices
            $unitsIds = [1,2, 3];
            foreach($unitsIds as $unitsId){
                SalePrice::create([
                    'price' => 1.00,
                    'units_number_id' => $unitsId,
                    'product_id' => $productId
                ]);
            }
        }
    }

    private function defineProducts(): array
    {
        return [
            ['frontera', 'AGUARDIENTE', 750],
            ['antioqueño azul', 'AGUARDIENTE', 375],
            ['antioqueño azul', 'AGUARDIENTE', 750],
            ['antioqueño azul', 'AGUARDIENTE', 1000],
            ['antioqueño real', 'AGUARDIENTE', 750],
            ['antioqueño rojo', 'AGUARDIENTE', 750],
            ['antioqueño verde', 'AGUARDIENTE', 750],
            ['bacardi carta blanca', 'WHISKY', 750],
            ['bacardi carta oro', 'WHISKY', 750],
            ['black and white', 'WHISKY', 750],
            ['black owl', 'WHISKY', 750],
            ['black williams', 'WHISKY', 750],            
            ['black williams', 'WHISKY', 1000],
            ['blue nun 24k gold', 'ESPUMANTE', 750],
            ['boones exotic berry', 'VINO', 750],
            ['boones apple', 'VINO', 750],
            ['buchanans deluxe', 'WHISKY', 750],
            ['buchanans master', 'WHISKY', 750],
            ['caña manabita negra', 'AGUARDIENTE', 375],
            ['caña manabita negra', 'AGUARDIENTE', 750],
            ['caña manabita roja', 'AGUARDIENTE', 200],
            ['caña manabita roja', 'AGUARDIENTE', 375],
            ['caña manabita roja', 'AGUARDIENTE', 750],
            ['caña rose', 'AGUARDIENTE', 600],
            ['carupano black', 'WHISKY', 750],
            ['chivas regal rojo', 'WHISKY', 750],
            ['clan mcgregor', 'WHISKY', 750],
            ['san miguel mojito rojo', 'COCKTAIL', 750],
            ['san miguel mojito verde', 'COCKTAIL', 750],
            ['piña colada yachting', 'COCKTAIL', 700],
            ['crema sabor a whisky colds', 'COCKTAIL', 750],
            ['cristal', 'AGUARDIENTE', 375],
            ['cristal', 'AGUARDIENTE', 750],
            ['cristal seco', 'AGUARDIENTE', 375],
            ['cristal seco', 'AGUARDIENTE', 750],
            ['don castelo', 'WHISKY', 1000],
            ['blue nun 24k rose', 'ESPUMANTE', 750],
            ['capriccio novecento (dorado)', 'ESPUMANTE', 750],
            ['grand duval', 'ESPUMANTE', 750],
            ['grand old par', 'WHISKY', 750],
            ['grand old par', 'WHISKY', 1000],
            ['grants azul', 'WHISKY', 750],
            ['grants rojo', 'WHISKY', 750],
            ['grants verde', 'WHISKY', 750],
            ['highland legend', 'WHISKY', 750],
            ['jagermeiter', 'WHISKY', 750],
            ['jhon morris black', 'WHISKY', 750],
            ['jhon morris black', 'WHISKY', 1000],
            ['jhon morris blue', 'WHISKY', 1000],
            ['jhonnie dorado', 'WHISKY', 1000],
            ['jhonnie rojo', 'WHISKY', 750],
            ['jhonnie rojo', 'WHISKY', 1000],
            ['john barr negro', 'WHISKY', 750],
            ['john barr rojo', 'WHISKY', 750],
            ['mora ardiente', 'AGUARDIENTE', 375],
            ['mr allen', 'WHISKY', 1000],
            ['norteño especial', 'AGUARDIENTE', 375],
            ['norteño especial', 'AGUARDIENTE', 750],
            ['old times black', 'WHISKY', 750],
            ['old times red', 'WHISKY', 750],
            ['passport scotch', 'WHISKY', 750],
            ['piña colada zhumir', 'COCKTAIL', 750],
            ['red williams', 'WHISKY', 750],
            ['pon pon', 'RON', 375],
            ['romanosky azul', 'COCKTAIL', 750],
            ['romanosky rosa', 'COCKTAIL', 750],
            ['100 fuegos', 'RON', 750],
            ['abuelo', 'RON', 750],
            ['caballo viejo', 'RON', 750],
            ['cartavio black', 'RON', 750],
            ['castillo blanco', 'RON', 750],
            ['cubanero oro', 'RON', 750],
            ['estelar', 'RON', 750],
            ['la cueva', 'RON', 750],
            ['viejo de caldas', 'RON', 375],
            ['viejo de caldas', 'RON', 750],
            ['russ kaya naranja', 'WHISKY', 750],
            ['russ kaya pink', 'WHISKY', 750],
            ['russ kaya rojo', 'WHISKY', 750],
            ['russ kaya tricolor', 'WHISKY', 750],
            ['russ kaya verde', 'WHISKY', 750],
            ['russ kaya azul', 'WHISKY', 750],
            ['russ kaya blanco', 'WHISKY', 750],
            ['san miguel gold', 'RON', 750],
            ['san miguel silver', 'RON', 750],
            ['sandy mac', 'WHISKY', 750],
            ['fiesta brava', 'SANGRIA', 1000],
            ['siberian azul', 'VODKA', 750],
            ['siberian azul', 'VODKA', 375],
            ['siberian rojo', 'VODKA', 750],
            ['siberian rojo', 'VODKA', 375],
            ['siberian verde', 'VODKA', 750],
            ['siberian verde', 'VODKA', 375],
            ['skyy', 'VODKA', 375],
            ['something special', 'WHISKY', 750],
            ['something special', 'WHISKY', 1000],
            ['special queen', 'WHISKY', 750],
            ['tapa roja', 'AGUARDIENTE', 750],
            ['azteca blanco', 'TEQUILA', 750],
            ['azteca oro', 'TEQUILA', 750],
            ['cartago blanco', 'TEQUILA', 1000],
            ['el charro aguijon', 'TEQUILA', 750],
            ['el charro oro', 'TEQUILA', 750],
            ['el charro reposado', 'TEQUILA', 750],
            ['el charro silver', 'TEQUILA', 750],
            ['jose cuervo blanco', 'TEQUILA', 750],
            ['jose cuervo oro', 'TEQUILA', 750],
            ['tropico seco', 'AGUARDIENTE', 375],
            ['tropico seco', 'AGUARDIENTE', 750],
            ['vat 69', 'WHISKY', 750],
            ['venetto 1500ml', 'COCKTAIL', 1500],
            ['3 medallas', 'VINO', 750],
            ['anthony durazno', 'VINO', 750],
            ['anthony manzana', 'VINO', 750],
            ['anthony mora', 'VINO', 750],
            ['bicicleta merlot (morado)', 'VINO', 750],
            ['bicicleta merlot (reserva)', 'VINO', 750],
            ['klaus', 'VINO', 750],
            ['calvet reserve bordeaux', 'VINO', 750],
            ['calvet varietals cabernet sauvignon', 'VINO', 750],
            ['calvet varietals merlot', 'VINO', 750],
            ['casillero del diablo', 'VINO', 750],
            ['catador', 'VINO', 750],
            ['cruzares', 'VINO', 750],
            ['diablo', 'VINO', 750],
            ['fraile', 'VINO', 750],
            ['frontera', 'VINO', 750],
            ['gato negro', 'VINO', 750],
            ['jp chenet', 'VINO', 750],
            ['la catedra', 'VINO', 750],
            ['la vid blend', 'VINO', 750],
            ['lambrusco', 'VINO', 750],
            ['lambrusco rosato', 'VINO', 750],
            ['lambrusco roso', 'VINO', 750],
            ['miraflores', 'VINO', 750],
            ['reservados', 'VINO', 750],
            ['tinto klaus', 'VINO', 750],
            ['blanco tocornal', 'VINO', 750],
            ['tinto tocornal', 'VINO', 750],
            ['tinto tocornal carmenere (naranja)', 'VINO', 750],
            ['tribu', 'VINO', 750],
            ['undurraga', 'VINO', 750],
            ['viejo viñedo', 'VINO', 750],
            ['viña maipo', 'VINO', 750],
            ['finlandia', 'VINO', 750],
            ['smirnoff', 'VINO', 750],
            ['astilla de roble', 'WHISKY', 750],
            ['dixons azul', 'WHISKY', 750],
            ['dixons verde', 'WHISKY', 750],
            ['royal blend', 'WHISKY', 750],
            ['lawsons', 'WHISKY', 750],
            ['zhumir pink', 'COCKTAIL', 750],
        ];
    }
}

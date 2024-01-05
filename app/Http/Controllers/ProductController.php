<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\UnitsNumber;
use App\Models\Type;
use App\Models\Presentation;
use App\Models\SalePrice;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    public function index(Request $request){
        $search = $request->get('search');
        if($search){
            $products = Product::searchByTag($search);
            $formBag['search'] = $search;
        } else {            
            $products = null;
            $formBag = null;
        }
        return view('entities.products.index', [
            'products' => $products,
            'formBag' => $formBag
        ]);
    }

    public function create(Request $request){
        return view('entities.products.create', [
            'types' => Type::orderBy('used', 'desc')->get(),
            'presentations' => Presentation::orderBy('used', 'desc')->get(),
            'success' => $request->get('success') ?? null
        ]);
    }

    public function store(StoreProductRequest $request){
        $validated = $request->validated();
        // Save product
        $product_id = Product::create([
            'name' => $validated['name'],
            'minimun_stock' => $validated['minimun_stock'],
            'type_id' => $validated['type'],
            'presentation_id' => $validated['presentation']
        ])->id;
        // Save sale prices
        foreach($validated['sale_prices'] as $units => $salePrice){
            SalePrice::create([
                'price' => $salePrice,
                'units_number_id' => UnitsNumber::where('units', $units)->first()->id,
                'product_id' => $product_id
            ]);
        }
        return redirect()->route('products.create', ['success' => true]);
    }

    public function show(Product $product){
        return view('entities.products.show', ['product' => $product]);
    }

    public function edit(Product $product){
        return view('entities.products.edit', [
            'product' => $product,
            'types' => Type::orderBy('used', 'desc')->get(),
            'presentations' => Presentation::orderBy('used', 'desc')->get()
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product){
        $validated = $request->validated();
        // Update sale prices
        foreach($product->salePrices as $salePrice){
            $salePrice->update([
                'price' => $validated['sale_prices'][$salePrice->unitsNumber->units]
            ]);
        }
        // Update product
        $product->update([
            'name' => $validated['name'],
            'minimun_stock' => $validated['minimun_stock'],
            'type_id' => $validated['type'],
            'presentation_id' => $validated['presentation']
        ]);
        return redirect()->route('products.show', $product->id);
    }

    public function destroy(Product $product){
        $product->delete();
        return redirect()->route('products.index');
    }
}

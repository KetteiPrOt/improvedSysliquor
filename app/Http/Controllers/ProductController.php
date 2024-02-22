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
use App\Models\Invoice;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request){
        $validator = Validator::make($request->all(), [
            'search' => 'string|min:2|max:255'
        ], attributes: ['search' => 'Buscar']);
        if($validator->fails()){
            return redirect()
                ->route('products.index')
                ->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();
        if(isset($validated['search'])){
            $search = $validated['search'];
            $products = Product::searchByTag($search);
            $formBag['search'] = $search;
        } else {            
            $products = Product::paginate(25);
            $formBag = null;
        }
        return view('entities.products.index', [
            'products' => $products,
            'formBag' => $formBag
        ]);
    }

    public function create(Request $request){
        return view('entities.products.create', [
            'types' => Type::where('active', true)->orderBy('name')->get(),
            'presentations' => Presentation::orderBy('content')->get(),
            'success' => $request->get('success') ?? null
        ]);
    }

    public function store(StoreProductRequest $request){
        $validated = $request->validated();
        // Save product
        $product = Product::create([
            'name' => str_replace(
                "ñ", "Ñ", strtoupper($validated['name'])
            ),
            'minimun_stock' => $validated['minimun_stock'],
            'type_id' => $validated['type'],
            'presentation_id' => $validated['presentation']
        ]);
        // Save sale prices
        foreach($validated['sale_prices'] as $key => $salePrice){
            $unitsNumbers = [1, 6, 12];
            // If don't have (is null) sale price, take the last in the array
            $salePrice = $salePrice ?? (
                $validated['sale_prices'][1] ?? $validated['sale_prices'][0]
            );
            SalePrice::create([
                'price' => $salePrice,
                'units_number_id' => UnitsNumber::where('units', $unitsNumbers[$key])->value('id'),
                'product_id' => $product->id
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
            'types' => Type::where('active', true)->orderBy('name')->get(),
            'presentations' => Presentation::orderBy('content')->get()
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product){
        $validated = $request->validated();
        // Update sale prices
        $salePrices = $product->salePrices;
        foreach($validated['sale_prices'] as $key => $newSalePrice){
            $salePrices->get($key)->update([
                'price' => $newSalePrice
            ]);
        }
        // Update product
        $product->update([
            'name' => str_replace(
                "ñ", "Ñ", strtoupper($validated['name'])
            ),
            'minimun_stock' => $validated['minimun_stock'],
            'type_id' => $validated['type'],
            'presentation_id' => $validated['presentation']
        ]);
        return redirect()->route('products.show', $product->id);
    }

    public function destroy(Product $product){
        $invoiceIds = $product->movements()
                            ->rightJoin('invoices', 'invoices.id', '=', 'movements.invoice_id')
                            ->select('invoices.id')
                            ->pluck('id')
                            ->toArray();
        $invoices = Invoice::whereIn('id', $invoiceIds)->get();
        $product->delete();
        $this->purgeEmptyInvoices($invoices);
        return redirect()->route('products.index');
    }

    private function purgeEmptyInvoices($invoices): void
    {
        foreach($invoices as $invoice){
            $movementsCount = $invoice->movements->count();
            if(!($movementsCount > 0)){
                $invoice->delete();
            }
        }
    }
}

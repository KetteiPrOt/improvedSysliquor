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

class ProductController extends Controller
{
    public function index(Request $request){
        $search = $request->get('search');
        if($search){
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
            'types' => Type::orderBy('used', 'desc')->get(),
            'presentations' => Presentation::orderBy('content')->get(),
            'success' => $request->get('success') ?? null
        ]);
    }

    public function store(StoreProductRequest $request){
        $validated = $request->validated();
        // Save product
        $product_id = Product::create([
            'name' => strtoupper($validated['name']),
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

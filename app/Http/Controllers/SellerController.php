<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search') ?? null;
        $search = is_string($search) ? $search : null;
        if($search){
            $sellers = Seller::join('persons', 'sellers.person_id', '=', 'persons.id')
                            ->select('sellers.*')
                            ->whereRaw("persons.name LIKE ?", ["%$search%"])->paginate(25);
        } else {
            $sellers = Seller::paginate(25);
        }
        return view('entities.sellers.index', [
            'sellers' => $sellers,
            'search' => $search
        ]);
    }

    public function create()
    {
        return redirect()->route('register');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function createPagination(
        $collection,
        Request $request,
        int $perPage
    ): LengthAwarePaginator
    {
        $currentPage = 1;
        if($request->has('page')){
            if(is_numeric($request->input('page'))){
                if(intval($request->input('page')) > 0){
                    $currentPage = $request->input('page');
                }
            }
        }
        $total = $collection->count();
        $startingPoint = ($currentPage * $perPage) - $perPage;
        $collection = $collection->slice($startingPoint, $perPage);
        return new LengthAwarePaginator(
            $collection,
            $total,
            $perPage,
            options: [
                'path' => $request->url(),
            ]
        );
    }
}

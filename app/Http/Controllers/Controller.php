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

    protected function paginate($collection, $perPage, $currentPage, $path): LengthAwarePaginator
    {
        $start = $perPage * ($currentPage - 1);
        $paginated = new LengthAwarePaginator(
            $collection->slice($start, $perPage),
            $collection->count(),
            $perPage,
            $currentPage,
            options: [
                'path' => $path,
            ]
        );
        return $paginated;
    }
}

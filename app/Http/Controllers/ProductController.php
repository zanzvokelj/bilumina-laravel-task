<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BiluminaProductService;

class ProductController extends Controller
{
    public function index(Request $request, BiluminaProductService $service)
    {
        $filters = [
            'brand' => $request->query('brand'),
            'color' => $request->query('color'),
            'min_price' => $request->query('min_price'),
            'max_price' => $request->query('max_price'),
        ];

        $data = $service->fetchProducts($filters);

        $products = $data['products'];
        $groupName = $data['groupName'];
        $brands = $data['brands'];
        $colors = $data['colors'];

        $sort = $request->query('sort');

        if ($sort === 'asc') {
            $products = $products->sortBy('price')->values();
        }

        if ($sort === 'desc') {
            $products = $products->sortByDesc('price')->values();
        }

        return view('products', [
            'products' => $products,
            'groupName' => $groupName,
            'brands' => $brands,
            'colors' => $colors,
            'currentSort' => $sort
        ]);
    }
}

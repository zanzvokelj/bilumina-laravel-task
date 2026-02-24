<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $apiKey = config('services.bilumina.key');

$response = Http::get(
    'https://egi.bilumina.com/mw/api/v1/items/get',
    [
        'key' => $apiKey,
    ]
);

        if (!$response->successful()) {
            return view('products', [
                'products' => [],
                'groupName' => 'Napaka pri pridobivanju podatkov'
            ]);
        }

        $data = $response->json();

        $targetGroupId = 30284;

        $groups = $data['rootGroup']['groups'] ?? [];

        $selectedGroup = collect($groups)->firstWhere('id', $targetGroupId);

        if (!$selectedGroup || !isset($selectedGroup['items'])) {
            return view('products', [
                'products' => [],
                'groupName' => 'Skupina ni najdena'
            ]);
        }

        $products = collect($selectedGroup['items'])
            ->values()
            ->map(function ($item) use ($data) {

                $image = $item['gallery'][0]['imageUrl'] ?? null;

                return [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'stock' => $item['stock'],
                    'image' => $image
                        ? $data['cdnUrl']['itemSmall'] . $image
                        : null,
                ];
            });

        // Sorting
        $sort = $request->query('sort');

        if ($sort === 'asc') {
            $products = $products->sortBy('price')->values();
        }

        if ($sort === 'desc') {
            $products = $products->sortByDesc('price')->values();
        }

        return view('products', [
            'products' => $products,
            'groupName' => $selectedGroup['nameSmall'],
            'currentSort' => $sort
        ]);
    }
}
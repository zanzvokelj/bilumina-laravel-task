<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class BiluminaProductService
{
    protected string $apiUrl = 'https://egi.bilumina.com/mw/api/v1/items/get';

    public function fetchProducts(array $filters = []): array
    {
        $apiKey = config('services.bilumina.key');

        $response = Http::timeout(5)
            ->retry(3, 100)
            ->get($this->apiUrl, [
                'key' => $apiKey,
            ]);

        $response->throw();

        $data = $response->json();

        $targetGroupId = 30284;

        $groups = $data['rootGroup']['groups'] ?? [];

        $selectedGroup = collect($groups)->firstWhere('id', $targetGroupId);

        if (!$selectedGroup || !isset($selectedGroup['items'])) {
            return [
                'products' => collect(),
                'groupName' => 'Skupina ni najdena',
            ];
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
                    'brand' => $item['brand'],
                    'color' => $item['color'],
                    'image' => $image
                        ? $data['cdnUrl']['itemSmall'] . $image
                        : null,
                ];
            });

        if (!empty($filters['brand'])) {
            $products = $products->where('brand', $filters['brand']);
        }

        if (!empty($filters['color'])) {
            $products = $products->where('color', $filters['color']);
        }

        if (!empty($filters['min_price'])) {
            $products = $products->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $products = $products->where('price', '<=', $filters['max_price']);
        }

        $brands = $products->pluck('brand')->unique()->sort()->values();
        $colors = $products->pluck('color')->unique()->sort()->values();



        return [
            'products' => $products,
            'groupName' => $selectedGroup['nameSmall'],
            'brands' => $brands,
            'colors' => $colors,
        ];
    }
}

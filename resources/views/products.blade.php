<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>{{ $groupName ?? 'Izdelki' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen">

<div class="max-w-7xl mx-auto px-6 py-10">

    <!-- Header -->
    <div class="flex items-center justify-between pb-6 border-b border-gray-200">

        <h1 class="text-3xl font-semibold text-gray-800">
            {{ $groupName ?? 'Izdelki' }}
        </h1>

        <!-- Filters -->
        <form method="GET" class="flex items-center gap-3">

            <!-- Brand -->
            <select name="brand" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                <option value="">Brand</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>
                        {{ $brand }}
                    </option>
                @endforeach
            </select>

            <!-- Color -->
            <select name="color" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                <option value="">Barva</option>
                @foreach($colors as $color)
                    <option value="{{ $color }}" {{ request('color') == $color ? 'selected' : '' }}>
                        {{ $color }}
                    </option>
                @endforeach
            </select>

            <!-- Min price -->
            <input
                type="number"
                name="min_price"
                placeholder="Min €"
                value="{{ request('min_price') }}"
                class="border border-gray-300 rounded-md px-3 py-2 text-sm w-24"
            >

            <!-- Max price -->
            <input
                type="number"
                name="max_price"
                placeholder="Max €"
                value="{{ request('max_price') }}"
                class="border border-gray-300 rounded-md px-3 py-2 text-sm w-24"
            >

            <!-- Sort -->
            <select name="sort" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
                <option value="">Cena</option>
                <option value="asc" {{ $currentSort === 'asc' ? 'selected' : '' }}>
                    Naraščajoče
                </option>
                <option value="desc" {{ $currentSort === 'desc' ? 'selected' : '' }}>
                    Padajoče
                </option>
            </select>

            <button
                type="submit"
                class="bg-blue-600 text-white text-sm px-4 py-2 rounded-md"
            >
                Filtriraj
            </button>

            <a
                href="{{ url('/') }}"
                class="bg-gray-200 text-gray-700 text-sm px-4 py-2 rounded-md hover:bg-gray-300"
            >
                Počisti
            </a>

        </form>

    </div>

    <!-- Empty state -->
    @if($products->isEmpty())

        <div class="mt-8 rounded-lg border border-gray-200 bg-white p-6 text-gray-600">
            Ni najdenih artiklov za izbrano skupino.
        </div>

    @else

        <!-- Grid -->
        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">

            @foreach($products as $product)

                <div class="relative bg-white rounded-xl shadow-sm hover:shadow-lg transition duration-300 p-5 flex flex-col">

                    @if($product['image'])
                        <img
                            src="{{ $product['image'] }}"
                            alt="{{ $product['name'] }}"
                            class="w-full aspect-square object-contain bg-gray-50 rounded-lg mb-4"
                        >
                    @endif

                    <!-- Title -->
                    <h2 class="font-semibold text-lg leading-snug min-h-14">
                        {{ $product['name'] }}
                    </h2>

                    <!-- Price -->
                    <p class="mt-auto text-gray-800 font-bold text-lg pt-4">
                        {{ number_format($product['price'], 2, ',', '.') }} €
                    </p>

                    <!-- Out of stock -->
                    @if($product['stock'] == 0)
                        <span class="absolute top-4 right-4 bg-red-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                            Ni na zalogi
                        </span>
                    @endif

                </div>

            @endforeach

        </div>

    @endif

</div>

</body>
</html>

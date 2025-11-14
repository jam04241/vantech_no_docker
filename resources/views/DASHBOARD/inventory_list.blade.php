@extends('SIDEBAR.layouts')
@section('name', 'Inventory List')
@section('content')
    <div class="flex items-center gap-2 w-full sm:w-auto flex-1" id="filter-container">
        <input type="text" name="search" placeholder="Search inventory..." value="{{ request('search') }}"
            hx-get="{{ route('inventory.list') }}" hx-trigger="input changed delay:300ms, search"
            hx-target="#product-table-container" hx-include="#filter-container" hx-swap="innerHTML"
            class="w-1/3 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out">

        <select name="category" hx-get="{{ route('inventory.list') }}" hx-trigger="change"
            hx-target="#product-table-container" hx-include="#filter-container" hx-swap="innerHTML"
            class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white">
            <option value="" {{ request('category') == '' ? 'selected' : '' }}>All Categories</option>
            @isset($categories)
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->category_name }}
                    </option>
                @endforeach
            @endisset
        </select>

        <select name="brand" hx-get="{{ route('inventory.list') }}" hx-trigger="change" hx-target="#product-table-container"
            hx-include="#filter-container" hx-swap="innerHTML"
            class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white">

            <option value="" {{ request('brand') == '' ? 'selected' : '' }}>All Brands</option>
            @isset($brands)
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                        {{ $brand->brand_name }}
                    </option>
                @endforeach
            @endisset
        </select>
    </div>
    <div class="py-6 rounded-xl" id="product-table-container">
        @include('partials.productTable_InventList')
    </div>
@endsection
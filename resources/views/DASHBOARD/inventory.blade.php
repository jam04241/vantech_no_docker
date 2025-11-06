@extends('SIDEBAR.layouts')
@section('name', 'Inventory')
@section('content')
    <div class="py-6 rounded-xl">
        <div class="flex flex-col sm:flex-row justify-between gap-3">
            <!-- Search and Category -->
            <div class="flex items-center gap-2 w-full sm:w-auto flex-1">
                <input type="text" placeholder="Search inventory..."
                    class="w-1/2 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                <select
                    class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white">
                    <option value="">All Categories</option>
                    <option value="processors">Processors (CPU)</option>
                    <option value="motherboards">Motherboards</option>
                    <option value="graphics-cards">Graphics Cards (GPU)</option>
                    <option value="memory">Memory (RAM)</option>
                    <option value="storage">Storage (SSD/HDD)</option>
                </select>
            </div>

            <!-- Add Product Button -->
            <div>
                <a id="addProductBtn" href="{{ route('product.add') }}"
                    class="bg-[#46647F] text-white px-4 py-2 rounded-lg shadow hover:bg-[#3B4A5A] focus:ring-2 focus:ring-[#3B4A5A] transition duration-150 ease-in-out whitespace-nowrap">
                    Add Product
                </a>
            </div>
        </div>
    </div>


@endsection

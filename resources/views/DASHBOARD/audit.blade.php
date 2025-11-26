@extends('SIDEBAR.layouts')
@section('title', 'Audit Logs')

@section('content')
    <div class="p-6">

        <!-- PAGE HEADER -->
        <div class="flex justify-end items-center mb-6">
            <button
                class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition">
                <span class="material-icons text-sm">Download</span>
                <span>Export Logs</span>
            </button>
        </div>

        <!-- FILTER BAR -->
        <div class="bg-white p-5 shadow-sm border border-gray-200 rounded-xl mb-6">
            <form class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">

                <!-- Left side: filters -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 flex-1">
                    <!-- Search -->
                    <div class="flex flex-col">
                        <label class="text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="search"
                            class="p-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-400"
                            placeholder="Search logs...">
                    </div>

                    <!-- Date From -->
                    <div class="flex flex-col">
                        <label class="text-sm font-medium text-gray-700 mb-1">Date From</label>
                        <input type="date"
                            class="p-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-400">
                    </div>

                    <!-- Date To -->
                    <div class="flex flex-col">
                        <label class="text-sm font-medium text-gray-700 mb-1">Date To</label>
                        <input type="date"
                            class="p-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-400">
                    </div>
                </div>

                <!-- Right side: actions -->
                <div class="flex justify-end gap-2">
                    <button type="reset" class="px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Clear Filters
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Apply Filters
                    </button>
                </div>

            </form>
        </div>

        <!-- TABLE -->
        <div class="bg-white shadow-sm border border-gray-200 rounded-xl p-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">#</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Product</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Brand</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Category</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Supplier</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Price</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Previous Stocks</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">New Stocks</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Total</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Status</th>
                            <th class="px-4 py-3 text-left font-medium text-gray-600">Date Updated</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        <!-- SAMPLE RECORD -->
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-gray-700">1</td>
                            <td class="px-4 py-3 text-gray-700">Shampoo</td>
                            <td class="px-4 py-3 text-gray-700">Dove</td>
                            <td class="px-4 py-3 text-gray-700">Hair Care</td>
                            <td class="px-4 py-3 text-gray-700">ABC Supplier</td>
                            <td class="px-4 py-3 text-gray-700">₱150</td>
                            <td class="px-4 py-3 text-gray-700">50</td>
                            <td class="px-4 py-3 text-gray-700">10</td>
                            <td class="px-4 py-3 text-gray-700">60</td>
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-full">
                                    Updated
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-700">2025-11-26 10:32 AM</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
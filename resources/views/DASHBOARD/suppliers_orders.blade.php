@extends('SIDEBAR.layouts')

@section('title', 'Suppliers Orders')
@section('name', 'PURCHASE ORDERS')

@section('content')
    <div class="bg-white border rounded-lg p-6 shadow-sm">
        <!-- SweetAlert Notifications -->
        @if(session('success'))
            <div class="mb-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        {{-- Header Section with Search and Add Button --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            {{-- Search Form --}}
            <form method="GET" action="{{ route('suppliers.list') }}" class="flex-1 max-w-md">
                <div class="relative">
                    <input type="text" id="searchInput" name="search" value="{{ request('search') }}"
                        placeholder="Find order..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 shadow-sm"
                        aria-label="Search orders">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    @if(request('search'))
                        <a href="{{ route('suppliers.list') }}"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition duration-200"
                            title="Clear search">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </form>

            <a href="{{ route('Supplier.CreateOrders') }}"
                class="inline-flex items-center gap-2 px-5 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 shadow-lg font-medium hover:shadow-xl transform hover:-translate-y-0.5"
                aria-label="Create new purchase order">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                New Purchase Order
            </a>
        </div>

        <div class="border-t border-gray-200 my-6"></div>

        {{-- Page Title --}}
        <div class="flex flex-col sm:flex-row justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Purchase Orders</h2>
                <p class="text-gray-600 mt-1">Manage and track all purchase orders</p>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div
                class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-5 border border-blue-200 shadow-sm hover:shadow-md transition duration-200">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-500 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Orders</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-5 border border-yellow-200 shadow-sm hover:shadow-md transition duration-200">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-500 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending Orders</p>
                        <p class="text-2xl font-bold text-gray-900" id="pendingOrdersCount">{{ $pendingOrders }}</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-5 border border-green-200 shadow-sm hover:shadow-md transition duration-200">
                <div class="flex items-center">
                    <div class="p-3 bg-green-500 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Received Orders</p>
                        <p class="text-2xl font-bold text-gray-900" id="receivedOrdersCount">{{ $receivedOrders }}</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-5 border border-red-200 shadow-sm hover:shadow-md transition duration-200">
                <div class="flex items-center">
                    <div class="p-3 bg-red-500 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Cancelled Orders</p>
                        <p class="text-2xl font-bold text-gray-900" id="cancelledOrdersCount">{{ $cancelledOrders }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Card --}}
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 items-end">
                {{-- Status Filter --}}
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Status</label>
                    <select id="statusFilter"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-white">
                        <option value="">All Status</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Received" {{ request('status') == 'Received' ? 'selected' : '' }}>Received</option>
                        <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                {{-- Date Filter --}}
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Order Date</label>
                    <input type="date" id="dateFilter" value="{{ request('date') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-white">
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-100 text-gray-700 text-base">
                    <tr>
                        <th class="p-4 font-semibold">Order ID</th>
                        <th class="p-4 font-semibold">Date</th>
                        <th class="p-4 font-semibold">Supplier</th>
                        <th class="p-4 font-semibold">Bundle Name</th>
                        <th class="p-4 font-semibold">Bundle Type</th>
                        <th class="p-4 font-semibold">Bundle Quantity</th>
                        <th class="p-4 font-semibold">Quantity</th>
                        <th class="p-4 font-semibold">Unit Price</th>
                        <th class="p-4 font-semibold">Total</th>
                        <th class="p-4 font-semibold">Status</th>
                        <th class="p-4 font-semibold">Actions</th>
                    </tr>
                </thead>

                <tbody class="text-base" id="ordersTableBody">
                    @forelse($purchaseOrders as $order)
                        <tr class="border-t hover:bg-gray-50 transition" data-status="{{ $order->status }}"
                            data-order-id="{{ $order->id }}">
                            <td class="p-4 text-blue-600 font-semibold">#{{ $order->id }}</td>
                            <td class="p-4">{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</td>
                            <td class="p-4">
                                @if($order->supplier)
                                    {{ $order->supplier->company_name }}
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($order->bundle)
                                    {{ $order->bundle->bundle_name }}
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($order->bundle)
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs capitalize">
                                        {{ $order->bundle->bundle_type }}
                                    </span>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($order->bundle)
                                    <span class="p-4 text-blue-600 font-semibold">
                                        {{ $order->bundle->quantity_bundles }}
                                    </span>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="p-4">{{ $order->quantity_ordered }}</td>
                            <td class="p-4">₱{{ number_format($order->unit_price, 2) }}</td>
                            <td class="p-4 font-semibold">₱{{ number_format($order->total_price, 2) }}</td>
                            <td class="p-4">
                                @if($order->status == 'Pending')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium"
                                        id="status-badge-{{ $order->id }}">Pending</span>
                                @elseif($order->status == 'Received')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium"
                                        id="status-badge-{{ $order->id }}">Received</span>
                                @elseif($order->status == 'Cancelled')
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium"
                                        id="status-badge-{{ $order->id }}">Cancelled</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="flex space-x-2">
                                    @if($order->status == 'Pending')
                                        <button class="confirm-order-btn border bg-green-600 text-white px-3 py-1 rounded"
                                            onclick="confirmOrder({{ $order->id }})">
                                            Confirm
                                        </button>
                                        <button class="cancel-order-btn border bg-red-600 text-white px-3 py-1 rounded"
                                            onclick="cancelOrder({{ $order->id }})">
                                            Cancel
                                        </button>
                                    @elseif($order->status == 'Received')
                                        <button class="border bg-gray-400 text-white px-3 py-1 rounded cursor-not-allowed" disabled>
                                            Done
                                        </button>
                                        <button class="border bg-gray-400 text-white px-3 py-1 rounded cursor-not-allowed" disabled>
                                            Cancelled
                                        </button>
                                    @elseif($order->status == 'Cancelled')
                                        <button class="border bg-gray-400 text-white px-3 py-1 rounded cursor-not-allowed" disabled>
                                            Confirm
                                        </button>
                                        <button class="border bg-gray-400 text-white px-3 py-1 rounded cursor-not-allowed" disabled>
                                            Cancelled
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="p-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mb-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p class="text-lg font-medium mb-2">No purchase orders found</p>
                                    <p class="text-sm mb-4">Get started by creating your first purchase order</p>
                                    <a href="{{ route('Supplier.CreateOrders') }}"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                                        Create Purchase Order
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination with Filters -->
        @if($purchaseOrders->hasPages())
            <div class="mt-6">
                {{ $purchaseOrders->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Get filter elements
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const dateFilter = document.getElementById('dateFilter');

            // Function to apply filters and reload page
            function applyFilters() {
                const search = searchInput.value;
                const status = statusFilter.value;
                const date = dateFilter.value;

                // Build URL with filters
                let url = new URL(window.location.href);
                let params = new URLSearchParams(url.search);

                if (search) {
                    params.set('search', search);
                } else {
                    params.delete('search');
                }

                if (status) {
                    params.set('status', status);
                } else {
                    params.delete('status');
                }

                if (date) {
                    params.set('date', date);
                } else {
                    params.delete('date');
                }

                // Reload page with new filters
                window.location.href = url.pathname + '?' + params.toString();
            }

            // Add event listeners for real-time filtering
            statusFilter.addEventListener('change', applyFilters);
            dateFilter.addEventListener('change', applyFilters);

            // Add debounced search to prevent too many requests
            let searchTimeout;
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(applyFilters, 500); // Wait 500ms after user stops typing
            });

            // Set initial search value from URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('search')) {
                searchInput.value = urlParams.get('search');
            }
        });

        // Simple SweetAlert Functions - No Hover Effects
        function confirmOrder(id) {
            Swal.fire({
                title: "Confirm Order?",
                text: "Do you want to confirm this order?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#16a34a",
                cancelButtonColor: "#6b7280",
                confirmButtonText: "Yes, Confirm",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    updateOrderStatus(id, 'confirm');
                }
            });
        }

        function cancelOrder(id) {
            Swal.fire({
                title: "Cancel Order?",
                text: "This action cannot be undone!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc2626",
                cancelButtonColor: "#6b7280",
                confirmButtonText: "Yes, Cancel",
                cancelButtonText: "Keep Order"
            }).then((result) => {
                if (result.isConfirmed) {
                    updateOrderStatus(id, 'cancel');
                }
            });
        }

        // Function to update order status via AJAX
        function updateOrderStatus(orderId, action) {
            const endpoint = action === 'confirm' ? 'confirm' : 'cancel';

            fetch(`/purchase/${orderId}/${endpoint}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ _method: 'PUT' })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message and then reload page
                        Swal.fire({
                            title: 'Success!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonColor: '#16a34a',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Reload the page to update all records and statistics
                                window.location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.message || `Failed to ${action} order.`,
                            icon: 'error',
                            confirmButtonColor: '#dc2626',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: `Failed to ${action} order. Please try again.`,
                        icon: 'error',
                        confirmButtonColor: '#dc2626',
                        confirmButtonText: 'OK'
                    });
                });
        }
    </script>

    <style>
        /* Remove ALL hover effects from SweetAlert buttons */
        .swal2-confirm:hover,
        .swal2-cancel:hover {
            transform: none !important;
            box-shadow: none !important;
            filter: none !important;
            background-color: inherit !important;
        }

        /* Remove hover effects from table buttons */
        .confirm-order-btn:hover,
        .cancel-order-btn:hover {
            transform: none;
            box-shadow: none;
            background-color: inherit;
        }

        /* Ensure SweetAlert buttons maintain their original colors */
        .swal2-confirm {
            background-color: #16a34a !important;
        }

        .swal2-confirm.swal2-styled[style*="background-color: rgb(220, 38, 38)"] {
            background-color: #dc2626 !important;
        }

        .swal2-cancel {
            background-color: #6b7280 !important;
        }
    </style>
@endsection
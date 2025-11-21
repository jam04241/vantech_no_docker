@extends('POS_SYSTEM.sidebar.app')

@section('title', 'POS')
@section('name', 'POS')
<style>
    .scrollbar-hide {
        overflow-y: auto;
        -ms-overflow-style: none;
        /* IE and Edge */
        scrollbar-width: none;
        /* Firefox */
    }

    .scrollbar-hide::-webkit-scrollbar {
        display: none;
        /* Chrome, Safari, Opera */
    }
</style>
@section('content_items')
    <div class="flex items-center space-between gap-2 w-full sm:w-auto flex-1">

        {{-- Category Filter --}}
        <select id="categoryFilter"
            class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white">
            <option value="" selected hidden>Select Category</option>
            <option value="all">All Categories</option>
            @isset($categories)
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                @endforeach
            @endisset
        </select>

        {{-- Brand Filter --}}
        <select id="brandFilter"
            class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white">
            <option value="" selected hidden>Select Brand</option>
            <option value="all">All Brands</option>
            @isset($brands)
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                @endforeach
            @endisset
        </select>

        <div class="gap-3">
            <a href="{{ route('customer.addCustomer') }}"
                class=" px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2
                                                                                                                                            focus:ring-indigo-500 transition duration-150 ease-in-out">Add
                Customer</a>
        </div>
    </div>
    <div class="flex flex-col lg:flex-row gap-6 mt-6">

        <!-- LEFT SIDE: PRODUCTS DISPLAY (4 columns) -->
        <div class="container flex-1 overflow-y-auto scrollbar-hide">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6" id="productsGrid">
                @forelse($grouped as $product)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition product-card"
                        data-category="{{ $product->category_id ?? '' }}" data-brand="{{ $product->brand_id ?? '' }}"
                        data-serial="{{ $product->serial_number ?? '' }}">
                        <div class="p-4">

                            <h3 class="text-lg font-semibold text-gray-800">{{ $product->product_name }}</h3>
                            <p class="text-gray-600 text-sm mt-2">
                                <b>Brand:</b> {{ $product->brand?->brand_name ?? 'N/A' }}
                            </p>
                            <p class="text-gray-600 text-sm mt-1">
                                <b>Type:</b> {{ $product->category?->category_name ?? 'N/A' }}
                            </p>
                            <p class="text-gray-600 text-sm mt-1">
                                <b>Stock:</b> <span class="font-semibold">{{ $product->stock }}</span>
                            </p>
                            <p class="text-sm mt-1">
                                <b>Availability:</b>
                                <span class="font-semibold {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                </span>
                            </p>
                            <div class="mt-3 flex justify-between items-center">
                                <span class="text-indigo-600 font-bold text-base">
                                    ₱{{ number_format($product->price, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="bg-white rounded-xl shadow-md p-6 text-center text-gray-500">
                            No products available yet.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- 
                                RIGHT SIDE: RECEIPT WITH TAB SWITCHER 
                                Logic: Handles barcode scanning and order management
                                Basis: Serial number for scanning, but groups items by product name in order list
                                Key Feature: Multiple serials of same product increase quantity (prevents duplicate lines)
                                Example: Scan Dell Monitor ABC123 (Qty: 1) + Dell Monitor XYZ789 (Qty: 2 - same line)
                            -->
        <div class="container w-full lg:w-1/2">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden h-fit sticky top-6">
                <!-- Barcode Input -->
                <div class="p-4 bg-gradient-to-r from-indigo-50 to-blue-50">
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Scan Barcode / Serial No.</label>
                    <input type="text" id="productSerialNo" placeholder="Scan product barcode here..."
                        class="w-full px-4 py-3 border-2 border-indigo-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-600 transition duration-150 ease-in-out text-sm font-medium"
                        autofocus />
                    <input type="hidden" id="scannedSerialNumbers" />
                    <p class="text-xs text-gray-500 mt-2">Auto-input enabled • Automatic calculation</p>
                </div>
                <!-- Tab Buttons -->
                <div class="flex border-b border-gray-200">
                    <button onclick="switchTabWithConfirm('purchase')" id="purchaseTab"
                        class="flex-1 py-3 px-4 text-center font-semibold transition-all duration-200 border-b-2 border-indigo-600 text-indigo-600 bg-white">
                        Purchase
                    </button>
                    <button onclick="switchTabWithConfirm('quotation')" id="quotationTab"
                        class="flex-1 py-3 px-4 text-center font-semibold transition-all duration-200 border-b-2 border-transparent text-gray-500 bg-gray-50 hover:bg-gray-100">
                        Quotation
                    </button>
                </div>

                <!-- Tab Content Container -->
                <div class="p-6">

                    <!-- PURCHASE TAB CONTENT -->
                    <div id="purchaseContent" class="tab-content">
                        <h2 class="text-xl font-extrabold text-gray-900 mb-4 border-b pb-2"> Purchase</h2>

                        <!-- Customer Info -->
                        <div class="mb-4 text-sm">
                            <p class="text-gray-700">Order #: <span class="font-semibold text-gray-900">Serial Number</span>
                            </p>
                            <p class="text-gray-700">Date: <span
                                    class="font-semibold text-gray-900">{{ now()->format('M d, Y h:i A') }}</span></p>
                        </div>

                        <!-- Order List -->
                        <div class="mb-4">
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">Order Items</h3>
                            <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                                <div
                                    class="grid grid-cols-12 gap-1 px-3 py-2 bg-gray-100 text-xs font-semibold text-gray-700 border-b border-gray-200">
                                    <div class="col-span-5">Product</div>
                                    <div class="col-span-3 text-center">Price</div>
                                    <div class="col-span-3 text-right">Subtotal</div>
                                    <div class="col-span-1 text-center">Remove</div>
                                </div>
                                <ul id="purchaseOrderList"
                                    class="divide-y divide-gray-200 max-h-56 overflow-y-auto scrollbar-hide">
                                    <!-- Items will be added dynamically here -->
                                </ul>
                            </div>
                            <p id="emptyOrderMsg" class="text-center text-gray-500 text-sm py-4">No items added yet. Scan a
                                barcode to start.</p>
                        </div>

                        <!-- Summary -->
                        <div class="border-t border-gray-300 pt-4 text-sm space-y-3">
                            <div class="flex justify-between items-center bg-blue-50 px-3 py-2 rounded">
                                <span class="text-gray-700 font-medium">Subtotal</span>
                                <span class="font-bold text-gray-900 text-lg">₱<span
                                        id="purchaseSubtotalDisplay">0.00</span></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">Discount</span>
                                <div class="flex gap-2 items-center">
                                    <input type="number" id="purchaseDiscountInput" value="0"
                                        class="w-20 px-2 py-1 border border-gray-300 rounded text-right focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        step="0.01" oninput="updatePurchaseTotal()" placeholder="0.00" />
                                    <span class="text-red-500 font-medium w-24 text-right">-₱<span
                                            id="purchaseDiscountDisplay">0.00</span></span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">VAT (12%)</span>
                                <span class="font-medium text-gray-900">₱<span id="purchaseVAT">0.00</span></span>
                            </div>
                            <div
                                class="flex justify-between font-extrabold text-gray-900 text-lg border-t-2 border-indigo-300 pt-3 mt-3 bg-gradient-to-r from-indigo-50 to-blue-50 px-3 py-2 rounded">
                                <span>Total</span>
                                <span>₱<span id="purchaseTotalDisplay">0.00</span></span>
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="mt-5">
                            <button id="checkout-btn"
                                class="w-full bg-indigo-600 text-white py-3 rounded-lg text-base font-semibold shadow hover:bg-indigo-700 transition duration-150 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 110 2h-3V6a1 1 0 011-1v3a1 1 0 011 1H10a1 1 0 01-1 1H7a1 1 0 00-1 1h-3V11a1 1 0 00 1-1V7a1 1 0 011-1h2V3a2 2 0 012 2z" />
                                </svg>
                                Proceed to Checkout
                            </button>
                        </div>
                    </div>

                    <!-- QUOTATION TAB CONTENT -->
                    <div id="quotationContent" class="tab-content hidden">
                        <h2 class="text-xl font-extrabold text-gray-900 mb-4 border-b pb-2"> Quotation</h2>

                        <!-- Customer Info (No Customer Name) -->
                        <div class="mb-4 text-sm">
                            <p class="text-gray-700">Order #: <span class="font-semibold text-gray-900">Serial Number</span>
                            </p>
                            <p class="text-gray-700">Date: <span
                                    class="font-semibold text-gray-900">{{ now()->format('M d, Y h:i A') }}</span></p>
                        </div>

                        <!-- Order List -->
                        <div class="mb-4">
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">Order Items</h3>
                            <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                                <div
                                    class="grid grid-cols-12 gap-1 px-3 py-2 bg-gray-100 text-xs font-semibold text-gray-700 border-b border-gray-200">
                                    <div class="col-span-5">Product</div>
                                    <div class="col-span-3 text-center">Price</div>
                                    <div class="col-span-3 text-right">Subtotal</div>
                                    <div class="col-span-1 text-center">Remove</div>
                                </div>
                                <ul id="quotationOrderList"
                                    class="divide-y divide-gray-200 max-h-56 overflow-y-auto scrollbar-hide">
                                    <!-- Items will be added dynamically here -->
                                </ul>
                            </div>
                            <p id="emptyQuotationMsg" class="text-center text-gray-500 text-sm py-4">No items added yet.
                                Scan a barcode to start.</p>
                        </div>

                        <!-- Summary -->
                        <div class="border-t border-gray-300 pt-4 text-sm space-y-3">
                            <div class="flex justify-between items-center bg-blue-50 px-3 py-2 rounded">
                                <span class="text-gray-700 font-medium">Subtotal</span>
                                <span class="font-bold text-gray-900 text-lg">₱<span
                                        id="quotationSubtotalDisplay">0.00</span></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">Discount</span>
                                <div class="flex gap-2 items-center">
                                    <input type="number" id="quotationDiscountInput" value="0"
                                        class="w-20 px-2 py-1 border border-gray-300 rounded text-right focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        step="0.01" oninput="updateQuotationTotal()" placeholder="0.00" />
                                    <span class="text-red-500 font-medium w-24 text-right">-₱<span
                                            id="quotationDiscountDisplay">0.00</span></span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">VAT (12%)</span>
                                <span class="font-medium text-gray-900">₱<span id="quotationVAT">0.00</span></span>
                            </div>
                            <div
                                class="flex justify-between font-extrabold text-gray-900 text-lg border-t-2 border-green-300 pt-3 mt-3 bg-gradient-to-r from-green-50 to-emerald-50 px-3 py-2 rounded">
                                <span>Total</span>
                                <span>₱<span id="quotationTotalDisplay">0.00</span></span>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="mt-5 flex flex-col gap-2">
                            <button id="print-quotation-btn"
                                class="w-full bg-green-600 text-white py-3 rounded-lg text-base font-semibold shadow hover:bg-green-700 transition duration-150 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Proceed to Print Quotation
                            </button>
                            <button id="add-pc-build-btn"
                                class="w-full bg-indigo-600 text-white py-3 rounded-lg text-base font-semibold shadow hover:bg-indigo-700 transition duration-150 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Add to PC BUILD
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Tab Switching Script -->
    <script>
        // Store order items
        let orderItems = [];
        const allProducts = @json($grouped);

        // Filter products by category and brand
        function filterProducts() {
            const categoryFilter = document.getElementById('categoryFilter').value;
            const brandFilter = document.getElementById('brandFilter').value;
            const productCards = document.querySelectorAll('.product-card');

            productCards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                const cardBrand = card.getAttribute('data-brand');

                const categoryMatch = categoryFilter === '' || categoryFilter === 'all' || cardCategory === categoryFilter;
                const brandMatch = brandFilter === '' || brandFilter === 'all' || cardBrand === brandFilter;

                if (categoryMatch && brandMatch) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Add product to order (populate serial number)
        function addProductToOrder(element, serial, name, price) {
            // Check if serial number already exists in order (basis is serial number, not brand)
            const scannedSerials = document.getElementById('scannedSerialNumbers').value.split(',').filter(s => s);
            if (scannedSerials.includes(serial)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Duplicate Product',
                    html: `<p>Product with serial <strong>${serial}</strong> has been input already</p>`,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#f59e0b',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }

            const serialInput = document.getElementById('productSerialNo');
            serialInput.value = serial;
            serialInput.focus();
            // Trigger input event to process the barcode
            serialInput.dispatchEvent(new Event('input'));
        }

        // Process barcode input with validation
        document.getElementById('productSerialNo').addEventListener('input', function (e) {
            const serialNo = this.value.trim();

            if (!serialNo) return;

            // Find product by serial number
            const product = allProducts.find(p => p.serial_number === serialNo);

            if (!product) {
                return; // Wait for more input or let user press enter
            }

            // Check if serial number already exists in order
            const scannedSerials = document.getElementById('scannedSerialNumbers').value.split(',').filter(s => s);
            if (scannedSerials.includes(serialNo)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Duplicate Product',
                    html: `<p>Product with serial <strong>${serialNo}</strong> has been input already</p>`,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#f59e0b',
                    timer: 2000,
                    showConfirmButton: false
                });
                this.value = '';
                this.focus();
                return;
            }

            // Validate product exists and has stock
            if (!product || product.stock <= 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Product Not Available',
                    html: `<p>The product <strong>${serialNo}</strong> is either:</p>
                                                                                           <ul style="text-align: left; margin: 10px 0;">
                                                                                           <li>Not registered in the inventory</li>
                                                                                           <li>Out of stock</li>
                                                                                           </ul>`,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ef4444',
                    timer: 2000,
                    showConfirmButton: false
                });
                this.value = '';
                this.focus();
                return;
            }

            // Add product to order
            addItemToOrder(product, serialNo);

            // Clear input and keep focus
            this.value = '';
            this.focus();
        });

        // Add item to order list
        function addItemToOrder(product, serialNo) {
            // Add serial number to tracked list
            const scannedSerials = document.getElementById('scannedSerialNumbers').value;
            const newSerials = scannedSerials ? scannedSerials + ',' + serialNo : serialNo;
            document.getElementById('scannedSerialNumbers').value = newSerials;

            // Each scanned item is unique by serial number - add as separate line item
            orderItems.push({
                id: product.id,
                name: product.product_name,
                serialNo: serialNo,
                price: product.price,
                qty: 1,
                stock: product.stock
            });

            // Update display
            updateOrderDisplay();

            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Product Added!',
                html: `<p><strong>${product.product_name}</strong> added to order</p>`,
                timer: 2000,
                showConfirmButton: false,
                position: 'top-end'
            });
        }

        // Update order display in both tabs
        function updateOrderDisplay() {
            const purchaseList = document.getElementById('purchaseOrderList');
            const quotationList = document.getElementById('quotationOrderList');
            const emptyPurchaseMsg = document.getElementById('emptyOrderMsg');
            const emptyQuotationMsg = document.getElementById('emptyQuotationMsg');

            let html = '';
            let subtotal = 0;

            orderItems.forEach((item, index) => {
                const itemSubtotal = item.price; // Each serial = 1 unit, so subtotal = price
                subtotal += itemSubtotal;

                html += `
                                                                                    <li class="py-3 px-3 hover:bg-gray-100 transition">
                                                                                        <div class="grid grid-cols-12 gap-1 items-center text-xs">
                                                                                            <div class="col-span-5">
                                                                                                <p class="font-medium text-gray-900 truncate">${item.name}</p>
                                                                                                <p class="text-gray-500 text-xs">SN: ${item.serialNo}</p>
                                                                                            </div>
                                                                                            <div class="col-span-3 text-center">
                                                                                                <span class="text-gray-700 font-semibold">₱${item.price.toFixed(2)}</span>
                                                                                            </div>
                                                                                            <div class="col-span-3 text-right">
                                                                                                <span class="font-semibold text-gray-900">₱${itemSubtotal.toFixed(2)}</span>
                                                                                            </div>
                                                                                            <div class="col-span-1 text-center">
                                                                                                <button onclick="removeItem(${index})" class="text-red-500 hover:text-red-700 font-bold text-lg">−</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </li>
                                                                                `;
            });

            purchaseList.innerHTML = html;
            quotationList.innerHTML = html;

            // Show/hide empty messages
            emptyPurchaseMsg.style.display = orderItems.length === 0 ? 'block' : 'none';
            emptyQuotationMsg.style.display = orderItems.length === 0 ? 'block' : 'none';

            // Update subtotal
            document.getElementById('purchaseSubtotalDisplay').textContent = subtotal.toFixed(2);
            document.getElementById('quotationSubtotalDisplay').textContent = subtotal.toFixed(2);

            // Recalculate totals
            updatePurchaseTotal();
            updateQuotationTotal();
        }

        // Note: Quantity management removed - each serial number represents one unique unit

        // Remove item from order (remove entire line item since each serial is unique)
        function removeItem(index) {
            // Remove serial number from tracked list
            const serialNo = orderItems[index].serialNo;
            const scannedSerials = document.getElementById('scannedSerialNumbers').value.split(',').filter(s => s);
            const updatedSerials = scannedSerials.filter(s => s !== serialNo).join(',');
            document.getElementById('scannedSerialNumbers').value = updatedSerials;

            // Remove item from order
            orderItems.splice(index, 1);
            updateOrderDisplay();
        }

        // Update Purchase Total
        function updatePurchaseTotal() {
            const subtotal = parseFloat(document.getElementById('purchaseSubtotalDisplay').textContent) || 0;
            const discount = parseFloat(document.getElementById('purchaseDiscountInput').value) || 0;
            const vat = (subtotal - discount) * 0.12;
            const total = subtotal - discount + vat;

            document.getElementById('purchaseDiscountDisplay').textContent = discount.toFixed(2);
            document.getElementById('purchaseVAT').textContent = vat.toFixed(2);
            document.getElementById('purchaseTotalDisplay').textContent = total.toFixed(2);
        }

        // Update Quotation Total
        function updateQuotationTotal() {
            const subtotal = parseFloat(document.getElementById('quotationSubtotalDisplay').textContent) || 0;
            const discount = parseFloat(document.getElementById('quotationDiscountInput').value) || 0;
            const vat = (subtotal - discount) * 0.12;
            const total = subtotal - discount + vat;

            document.getElementById('quotationDiscountDisplay').textContent = discount.toFixed(2);
            document.getElementById('quotationVAT').textContent = vat.toFixed(2);
            document.getElementById('quotationTotalDisplay').textContent = total.toFixed(2);
        }

        // Event listeners for filters
        document.getElementById('categoryFilter').addEventListener('change', filterProducts);
        document.getElementById('brandFilter').addEventListener('change', filterProducts);

        // Switch tab with confirmation if items exist
        function switchTabWithConfirm(tabName) {
            const scannedSerials = document.getElementById('scannedSerialNumbers').value.split(',').filter(s => s);

            // If no items scanned, just switch
            if (scannedSerials.length === 0) {
                switchTab(tabName);
                return;
            }

            // Show confirmation alert
            Swal.fire({
                icon: 'question',
                title: 'Switch Tab?',
                html: `<p>You have <strong>${scannedSerials.length}</strong> scanned product(s). Are you sure you want to switch tabs?</p>
                                                                           <p style="font-size: 0.9em; color: #666; margin-top: 10px;">Your current transaction will be removed.</p>`,
                showCancelButton: true,
                confirmButtonColor: '#6366f1',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Switch',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Clear order items and scanned serials
                    orderItems = [];
                    document.getElementById('scannedSerialNumbers').value = '';
                    switchTab(tabName);
                    updateOrderDisplay();
                }
            });
        }

        function switchTab(tabName) {
            // Hide all tab contents
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active styles from all tabs
            const purchaseTab = document.getElementById('purchaseTab');
            const quotationTab = document.getElementById('quotationTab');

            purchaseTab.classList.remove('border-indigo-600', 'text-indigo-600', 'bg-white');
            purchaseTab.classList.add('border-transparent', 'text-gray-500', 'bg-gray-50', 'hover:bg-gray-100');

            quotationTab.classList.remove('border-indigo-600', 'text-indigo-600', 'bg-white');
            quotationTab.classList.add('border-transparent', 'text-gray-500', 'bg-gray-50', 'hover:bg-gray-100');

            // Show selected tab content and apply active styles
            if (tabName === 'purchase') {
                document.getElementById('purchaseContent').classList.remove('hidden');
                purchaseTab.classList.add('border-indigo-600', 'text-indigo-600', 'bg-white');
                purchaseTab.classList.remove('border-transparent', 'text-gray-500', 'bg-gray-50', 'hover:bg-gray-100');
            } else if (tabName === 'quotation') {
                document.getElementById('quotationContent').classList.remove('hidden');
                quotationTab.classList.add('border-indigo-600', 'text-indigo-600', 'bg-white');
                quotationTab.classList.remove('border-transparent', 'text-gray-500', 'bg-gray-50', 'hover:bg-gray-100');
            }
        }

        // Generalized Confirmation Handler
        function showConfirmation(title, text, icon, confirmButtonText, confirmButtonColor, actionType) {
            const productSerialNo = document.getElementById('productSerialNo').value.trim();

            // Validate product serial number
            if (!productSerialNo && actionType === 'checkout') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Product Serial No. Required',
                    text: 'Please enter a product serial number before proceeding.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#6366f1'
                });
                return;
            }

            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: confirmButtonColor,
                cancelButtonColor: '#d33',
                confirmButtonText: confirmButtonText,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show processing timer
                    showProcessingTimer(actionType, productSerialNo);
                }
            });
        }

        // Generalized Processing Timer
        function showProcessingTimer(actionType, productSerialNo = '') {
            let timerInterval;
            let title = '';
            let html = '';

            if (actionType === 'checkout') {
                title = '🛒 Processing Purchase';
                html = `<p>Product Serial: <strong>${productSerialNo}</strong></p><p>Processing order in <b></b> seconds...</p>`;
            } else if (actionType === 'print') {
                title = '🖨️ Preparing Quotation';
                html = 'Generating quotation document in <b></b> seconds...';
            } else if (actionType === 'pcbuild') {
                title = '🖥️ Adding to PC Build';
                html = 'Adding items to PC build in <b></b> seconds...';
            }

            Swal.fire({
                title: title,
                html: html,
                timer: 3000,
                timerProgressBar: true,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    const timer = Swal.getPopup().querySelector("b");
                    timerInterval = setInterval(() => {
                        const secondsLeft = Math.ceil(Swal.getTimerLeft() / 1000);
                        timer.textContent = secondsLeft;
                    }, 100);
                },
                willClose: () => {
                    clearInterval(timerInterval);
                }
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                    showSuccessMessage(actionType, productSerialNo);
                }
            });
        }

        // Success Message
        function showSuccessMessage(actionType, productSerialNo = '') {
            if (actionType === 'checkout') {
                Swal.fire({
                    icon: 'success',
                    title: 'Purchase Complete!',
                    html: `<p>Order for product <strong>${productSerialNo}</strong> has been processed successfully.</p>`,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#6366f1'
                });
            } else if (actionType === 'print') {
                Swal.fire({
                    icon: 'success',
                    title: 'Quotation Ready!',
                    text: 'The quotation document is ready to print.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#10b981'
                });
            } else if (actionType === 'pcbuild') {
                Swal.fire({
                    icon: 'success',
                    title: 'Added to PC Build!',
                    text: 'Items have been successfully added to PC build.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#6366f1'
                });
            }
        }

        // Event Listeners
        document.getElementById('checkout-btn').addEventListener('click', function () {
            showConfirmation(
                'Confirm Purchase',
                'Are you sure you want to proceed with this purchase?',
                'question',
                'Yes, Proceed!',
                '#6366f1',
                'checkout'
            );
        });

        document.getElementById('print-quotation-btn').addEventListener('click', function () {
            showConfirmation(
                'Confirm Print',
                'Are you sure you want to print this quotation?',
                'question',
                'Yes, Print!',
                '#10b981',
                'print'
            );
        });

        document.getElementById('add-pc-build-btn').addEventListener('click', function () {
            showConfirmation(
                'Confirm Add to PC Build',
                'Are you sure you want to add these items to PC build?',
                'question',
                'Yes, Add!',
                '#6366f1',
                'pcbuild'
            );
        });
    </script>
@endsection
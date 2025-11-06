@extends('SIDEBAR.layouts')

@section('btn')
    <a href="{{ route('inventory') }}"
        class="inline-flex items-center gap-2 bg-[#2F3B49] text-white px-4 py-2 rounded-lg shadow hover:bg-[#3B4A5A] focus:ring-2 focus:ring-[#3B4A5A] transition duration-150 ease-in-out">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back
    </a>
@endsection

@section('name', 'Add Product')

@section('content')
        {{-- Action buttons --}}
    <div class="flex items-center gap-3 mb-6">
    <button id="openBrandModal"
        class="inline-flex items-center gap-2 px-4 py-2 border border-black rounded-lg shadow-sm bg-white text-black hover:bg-gray-100 focus:ring-2 focus:ring-black transition duration-200 ease-in-out">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Add Brand
    </button>

    <button id="openCategoryModal"
         class="inline-flex items-center gap-2 px-4 py-2 border border-black rounded-lg shadow-sm bg-white text-black hover:bg-gray-100 focus:ring-2 focus:ring-black transition duration-200 ease-in-out">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Add Category
    </button>

        <!-- Push camera button to the far right -->
        <div class="ml-auto">
            <button id="openCamera"
                 class="inline-flex items-center gap-2 px-4 py-2 border border-black rounded-lg shadow-sm bg-white text-black hover:bg-gray-100 focus:ring-2 focus:ring-black transition duration-200 ease-in-out">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Open Camera
            </button>
        </div>
    </div>

        {{-- Main Product Form --}}
        <div class="bg-white p-8 rounded-xl shadow-lg w-full border border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b-2 border-indigo-200 pb-3 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                Add New Product
            </h2>

            <form action="" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                {{-- PRODUCT INFORMATION --}}
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Product Information
                    </h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Product Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                placeholder="Enter product name" required>
                        </div>

                        <div>
                            <label for="serial_number" class="block text-sm font-medium text-gray-700 mb-2">Serial
                                Number</label>
                            <input type="text" id="serial_number" name="serial_number"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                placeholder="Enter serial number (optional)">
                        </div>
                    </div>
                </div>

                {{-- RELATIONAL FIELDS --}}
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                        Relations
                    </h3>
                    <div class="grid md:grid-cols-3 gap-6">
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category <span
                                    class="text-red-500">*</span></label>
                            <select id="category_id" name="category_id"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                required>
                                <option value="">Select Category</option>
                                {{-- Options populated dynamically --}}
                            </select>
                        </div>

                        <div>
                            <label for="brand_id" class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                            <select id="brand_id" name="brand_id"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
                                <option value="">Select Brand</option>
                                {{-- Options populated dynamically --}}
                            </select>
                        </div>

                        <div>
                            <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
                            <select id="supplier_id" name="supplier_id"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
                                <option value="">Select Supplier</option>
                                {{-- Options populated dynamically --}}
                            </select>
                        </div>
                    </div>
                </div>

                {{-- STOCK INFO --}}
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        Stock Information
                    </h3>
                    <div class="grid md:grid-cols-3 gap-6">

                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (₱) <span
                                    class="text-red-500">*</span></label>
                            <input type="number" id="price" name="price" step="0.01"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                placeholder="Enter price" required>
                        </div>

                        <div>
                            <label for="warranty" class="block text-sm font-medium text-gray-700 mb-2">Warranty</label>
                            <input type="text" id="warranty" name="warranty"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                placeholder="e.g. 1 year">
                        </div>
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Product Image</label>
                            <div class="relative">
                                <input type="file" id="image" name="image" accept="image/*"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            </div>
                        </div>
                    </div>
                </div>



                {{-- SUBMIT --}}
                <div class="flex justify-end pt-6 border-t border-gray-200">
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Product
                    </button>
                </div>
            </form>
        </div>

        {{-- ================= MODALS ================= --}}

        {{-- Brand Modal --}}
        <div id="brandModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 transition-opacity duration-300">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-8 relative transform scale-95 transition-transform duration-300"
                id="brandModalContent">
                <button id="closeBrandModal"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div class="flex items-center gap-3 mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Add New Brand</h3>
                </div>
                <form action="" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="brand_name" class="block text-sm font-medium text-gray-700 mb-2">Brand Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="brand_name" id="brand_name"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                            placeholder="Enter brand name" required>
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" id="cancelBrandModal"
                            class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:ring-2 focus:ring-gray-500 transition duration-200">Cancel</button>
                        <button type="submit"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 shadow-lg">Save
                            Brand</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Category Modal --}}
        <div id="categoryModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 transition-opacity duration-300">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-8 relative transform scale-95 transition-transform duration-300"
                id="categoryModalContent">
                <button id="closeCategoryModal"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div class="flex items-center gap-3 mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Add New Category</h3>
                </div>
                <form action="" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="category_name" class="block text-sm font-medium text-gray-700 mb-2">Category Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="category_name" id="category_name"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200"
                            placeholder="Enter category name" required>
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" id="cancelCategoryModal"
                            class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:ring-2 focus:ring-gray-500 transition duration-200">Cancel</button>
                        <button type="submit"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200 shadow-lg">Save
                            Category</button>
                    </div>
                </form>
            </div>
        </div>
        <script>
            const brandModal = document.getElementById('brandModal');
            const categoryModal = document.getElementById('categoryModal');

            document.getElementById('openBrandModal').addEventListener('click', () => brandModal.classList.remove('hidden'));
            document.getElementById('openCategoryModal').addEventListener('click', () => categoryModal.classList.remove('hidden'));

            document.getElementById('closeBrandModal').addEventListener('click', () => brandModal.classList.add('hidden'));
            document.getElementById('closeCategoryModal').addEventListener('click', () => categoryModal.classList.add('hidden'));

            // Optional: Close modal by clicking outside
            window.addEventListener('click', (e) => {
                if (e.target === brandModal) brandModal.classList.add('hidden');
                if (e.target === categoryModal) categoryModal.classList.add('hidden');
            });
        </script>
@endsection
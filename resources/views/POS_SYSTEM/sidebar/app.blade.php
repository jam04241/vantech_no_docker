<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('name', 'Default Title')</title>
    {{-- Tailwind & Vite --}}
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<style>
    .scrollbar-hide {
        overflow-y: auto;
        -ms-overflow-style: none;
        /* IE and Edge */
        scrollbar-width: none;
        /* Firefox */
    }

    .scrollbar-hide ::-webkit-scrollbar {
        display: none;
        /* Chrome, Safari, Opera */
    }
</style>

<body class="bg-gray-50 h-screen flex">

    <!-- Sidebar -->
    <div class="w-40 bg-white shadow-md h-full flex flex-col items-center p-5">

        <!-- Back Button -->
        <a href="{{ route('inventory') }}"
            class="inline-flex items-center gap-2 bg-[#2F3B49] text-white px-4 py-2 rounded-lg shadow hover:bg-[#3B4A5A] focus:ring-2 focus:ring-[#3B4A5A] transition duration-150 ease-in-out">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back
        </a>

        <ul class="navbar w-full space-y-4 mt-10 overflow-y-auto scrollbar-hide">
            <li value="ALL">
                <button type="button"
                    class="category-btn w-full flex flex-col items-center p-4 border-2 border-[#3B4A5A] rounded-lg shadow-md text-[#3B4A5A] bg-white hover:bg-[#3B4A5A] transition-all duration-150 ease-in-out"
                    aria-current="false">
                    <span class="text-sm font-semibold"> ALL </span>
                </button>
            </li>
            @isset($categories)
                @if($categories->count() > 0)
                    @foreach ($categories as $category)
                        @if(!empty($category->category_name))
                            <li value="{{ $category->id }}">
                                <button type="button" data-category-id="{{ $category->id }}"
                                    class="category-btn w-full flex flex-col items-center p-4 border-2 border-[#3B4A5A] rounded-lg shadow-md text-[#3B4A5A] bg-white hover:bg-[#3B4A5A] transition-all duration-150 ease-in-out"
                                    aria-current="false">
                                    <span class="text-sm font-semibold">{{ $category->category_name }}</span>
                                </button>
                            </li>
                        @endif
                    @endforeach
                @else
                    <li class="w-full p-4 text-center">
                        <p class="text-sm text-gray-500">No categories available</p>
                    </li>
                @endif
            @else
                <li class="w-full p-4 text-center">
                    <p class="text-sm text-gray-500">Categories not loaded</p>
                </li>
            @endisset
        </ul>


    </div>

    <!-- Main Content Area -->
    <main class="flex-1 p-10 overflow-auto">
        @yield('content_items')
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categoryButtons = document.querySelectorAll('.category-btn');

            categoryButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const categoryId = this.getAttribute('data-category-id');
                    const isActive = this.classList.contains('bg-[#3B4A5A]');

                    if (isActive) {
                        // Remove active state - return to default
                        this.classList.remove('bg-[#3B4A5A]', 'border-[#3B4A5A]', 'text-white', 'hover:bg-[#3B4A5A]');
                        this.classList.add('bg-white', 'border-[#3B4A5A]', 'text-[#3B4A5A]', 'hover:bg-[#3B4A5A]');
                        this.setAttribute('aria-current', 'false');
                    } else {
                        // Add active state
                        this.classList.remove('bg-white', 'border-[#3B4A5A]', 'text-[#3B4A5A]', 'hover:bg-[#3B4A5A]');
                        this.classList.add('bg-[#3B4A5A]', 'border-[#3B4A5A]', 'text-white', 'hover:bg-[#3B4A5A]');
                        this.setAttribute('aria-current', 'true');
                    }

                    // Optional: Remove active state from other buttons (single selection)
                    // Uncomment the code below if you want only one category selected at a time
                    /*
                    if (!isActive) {
                        categoryButtons.forEach(btn => {
                            if (btn !== this) {
                                btn.classList.remove('bg-[#3B4A5A]', 'border-[#3B4A5A]', 'text-white', 'hover:bg-[#3B4A5A]');
                                btn.classList.add('bg-white', 'border-[#3B4A5A]', 'text-[#3B4A5A]', 'hover:bg-[#3B4A5A]');
                                btn.setAttribute('aria-current', 'false');
                            }
                        });
                    }
                    */

                    // You can add additional functionality here, like filtering products
                    console.log('Category selected:', categoryId, 'Active:', !isActive);
                });
            });
        });
    </script>
</body>

</html>
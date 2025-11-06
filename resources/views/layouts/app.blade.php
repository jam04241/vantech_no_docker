<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="transform transition-transform duration-300 fixed inset-y-0 left-0 z-50 w-[280px] lg:w-64 -translate-x-full lg:translate-x-0">
            <!-- Sidebar Background -->
            <div class="flex-1 flex flex-col min-h-screen bg-[#151F28] shadow-xl">
                <!-- Logo Section -->
                <div class="flex flex-col items-center p-4 bg-[#1B2835]">
                    <div class="w-full flex justify-center items-center">
                        <img src="{{ asset('images/computer.png') }}" alt="Vantech Logo" class="w-auto h-16 max-w-[200px] object-contain mx-auto">
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
                    <!-- Dashboard Link -->
                    <a href="" 
                       class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-[#1B2835] transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-[#1B2835] text-white' : 'text-gray-100 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- Buy Link -->
                    <a href="" 
                       class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-[#1B2835] transition-all duration-200 {{ request()->routeIs('buy') ? 'bg-[#1B2835] text-white' : 'text-gray-100 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Buy</span>
                    </a>

                    <!-- Quotation Link -->
                    <a href="" 
                       class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-[#1B2835] transition-all duration-200 {{ request()->routeIs('quotation') ? 'bg-[#1B2835] text-white' : 'text-gray-100 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Quotation</span>
                    </a>

                    <!-- Inventory Link -->
                    <a href="" 
                       class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-[#1B2835] transition-all duration-200 {{ request()->routeIs('inventory') ? 'bg-[#1B2835] text-white' : 'text-gray-100 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <span>Inventory</span>
                    </a>

                    <!-- Suppliers Link -->
                    <a href="" 
                       class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-[#1B2835] transition-all duration-200 {{ request()->routeIs('suppliers') ? 'bg-[#1B2835] text-white' : 'text-gray-100 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Suppliers</span>
                    </a>

                    <!-- Audit Log Link -->
                    <a href="" 
                       class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-[#1B2835] transition-all duration-200 {{ request()->routeIs('audit-log') ? 'bg-[#1B2835] text-white' : 'text-gray-100 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>Audit log</span>
                    </a>

                    <!-- Log Out Link -->
                    <a href="" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-[#1B2835] transition-all duration-200 text-gray-100 hover:text-white">
                        <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Log out</span>
                    </a>
                </nav>

                
                <form id="logout-form" action="" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </aside>

        <!-- Overlay for mobile -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden" onclick="toggleSidebar()"></div>

        <!-- Mobile Menu Button -->
        <div class="lg:hidden fixed top-0 left-0 z-50 p-4">
            <button type="button" onclick="toggleSidebar()" class="text-gray-500 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
@include('NAVBAR.nav')
    <main class="flex-1 lg:ml-64 min-h-screen bg-gray-50 pt-12">
            <div class="py-4">
                <div class="max-w-7xl mx-auto px-2 sm:px-6 md:px-8">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('aside');
            const overlay = document.getElementById('sidebar-overlay');
            
            // Toggle transform class for slide effect
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; 
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = ''; 
            }
        }

        window.addEventListener('resize', () => {
            const sidebar = document.querySelector('aside');
            const overlay = document.getElementById('sidebar-overlay');
            if (window.innerWidth >= 1024) { // lg breakpoint
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const sidebar = document.querySelector('aside');
                const overlay = document.getElementById('sidebar-overlay');
                if (!sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            }
        });
    </script>
</body>
</html>

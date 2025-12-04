@extends('SIDEBAR.layouts')
@section('title', 'Staff Management')
@section('name', 'Staff Management')

@section('content')
    <div class="bg-white border rounded-lg p-6 shadow-sm">
        {{-- Header Section with Search and Add Button --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            {{-- Search Bar --}}
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <input type="text" id="searchInput" name="search" value="{{ request('search') }}"
                        placeholder="Search employees by name, role, address..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 shadow-sm"
                        aria-label="Search employees">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    @if(request('search'))
                        <a href="{{ route('staff.index') }}"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition duration-200"
                            title="Clear search">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Add Employee Button --}}
            <a href="{{ route('add.employee') }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 shadow-lg font-medium hover:shadow-xl transform hover:-translate-y-0.5"
                aria-label="Add a new employee">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add New Employee
            </a>
        </div>

        <div class="border-t border-gray-200 my-6"></div>

        {{-- Page Title --}}
        <div class="flex flex-col sm:flex-row justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Staff Management</h2>
                <p class="text-gray-600 mt-1">Manage your staff and their information</p>
            </div>
        </div>

        {{-- Filter Section --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            {{-- Filter by Role --}}
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-2">Role</label>
                <select id="roleFilter"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-white">
                    <option value="">All Roles</option>
                    <option value="Staff">Staff</option>
                    <option value="Technical">Technical</option>
                    <option value="Cashier">Cashier</option>
                    <option value="Assistant">Assistant</option>
                </select>
            </div>

            {{-- Results Info --}}
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 border border-indigo-200 rounded-lg">
                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
                <span class="text-sm font-medium text-indigo-600">
                    <span class="font-bold">{{ $employees->total() }}</span> total employees
                </span>
            </div>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        {{-- Staff Table Card --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wide">
                                Employee</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wide">
                                Address</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wide">
                                Contact</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wide">Role
                            </th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wide">
                                Gender</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wide">
                                Actions</th>
                        </tr>
                    </thead>

                    <tbody id="employeesTable" class="divide-y divide-gray-200">
                        @forelse($employees as $employee)
                            <tr class="hover:bg-gray-50 transition" data-employee-id="{{ $employee->id }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="h-10 w-10 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center font-semibold text-sm">
                                            {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $employee->first_name }}
                                                {{ $employee->last_name }}
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $employee->email ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $employee->street }}</div>
                                    <div class="text-sm text-gray-600">{{ $employee->barangay }}, {{ $employee->city }}</div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $employee->phone_number }}</div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-3 py-1 inline-flex text-xs font-semibold rounded-full
                                                                {{ $employee->role == 'Staff' ? 'bg-purple-100 text-purple-800' : ($employee->role == 'Technical' ? 'bg-pink-100 text-pink-800' : ($employee->role == 'Cashier' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800')) }}">
                                        {{ $employee->role }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-3 py-1 inline-flex text-xs font-semibold rounded-full
                                                                {{ strtolower($employee->gender) == 'male' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                        {{ ucfirst($employee->gender) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="openEditModal({{ $employee->id }})"
                                        class="inline-flex items-center gap-2 px-3 py-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mb-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                        </svg>
                                        <p class="text-lg font-medium text-gray-600 mb-2">No employees found</p>
                                        <p class="text-gray-500 mb-4">Get started by adding your first employee.</p>
                                        <a href="{{ route('add.employee') }}"
                                            class="text-indigo-600 hover:text-indigo-700 font-medium">Add New Employee</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($employees->hasPages())
                <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-200 px-6 py-4">
                    <div class="text-sm text-gray-600">
                        Showing {{ $employees->firstItem() }} to {{ $employees->lastItem() }} of {{ $employees->total() }}
                        results
                    </div>

                    <nav class="flex items-center space-x-2">
                        @if ($employees->onFirstPage())
                            <button class="px-4 py-2 bg-gray-100 text-gray-400 rounded cursor-not-allowed" disabled>
                                ← Previous
                            </button>
                        @else
                            <a href="{{ $employees->previousPageUrl() }}"
                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-200">
                                ← Previous
                            </a>
                        @endif

                        <div class="flex space-x-1">
                            @foreach ($employees->getUrlRange(1, $employees->lastPage()) as $page => $url)
                                @if ($page == $employees->currentPage())
                                    <span class="px-4 py-2 bg-indigo-600 text-white rounded font-medium">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}"
                                        class="px-4 py-2 bg-gray-100 text-gray-600 rounded hover:bg-gray-200 transition">{{ $page }}</a>
                                @endif
                            @endforeach
                        </div>

                        @if ($employees->hasMorePages())
                            <a href="{{ $employees->nextPageUrl() }}"
                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-200">
                                Next →
                            </a>
                        @else
                            <button class="px-4 py-2 bg-gray-100 text-gray-400 rounded cursor-not-allowed" disabled>
                                Next →
                            </button>
                        @endif
                    </nav>
                </div>
            @endif
        </div>
    </div>

    {{-- Edit Employee Modal --}}
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-auto transform scale-95 transition-all duration-300">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">Edit Employee</h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition duration-200">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Content --}}
                <div id="modalContent" class="p-6">
                    {{-- Loading spinner --}}
                    <div class="flex justify-center items-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                        <span class="ml-2 text-gray-600">Loading employee data...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden form template for employee data -->
    <div id="employeeData" class="hidden">
        @foreach($employees as $employee)
            <div class="employee-template" data-id="{{ $employee->id }}" data-first-name="{{ $employee->first_name }}"
                data-last-name="{{ $employee->last_name }}" data-street="{{ $employee->street }}"
                data-barangay="{{ $employee->barangay }}" data-city="{{ $employee->city }}"
                data-phone="{{ $employee->phone_number }}" data-gender="{{ $employee->gender }}"
                data-role="{{ $employee->role }}">
            </div>
        @endforeach
    </div>

    <script>
        // Simple client-side search & filter
        const searchInput = document.getElementById('searchInput');
        const roleFilter = document.getElementById('roleFilter');

        if (searchInput) searchInput.addEventListener('input', filterEmployees);
        if (roleFilter) roleFilter.addEventListener('change', filterEmployees);

        function filterEmployees() {
            const search = searchInput.value.toLowerCase();
            const role = roleFilter.value;
            const rows = document.querySelectorAll('#employeesTable tr');

            rows.forEach(row => {
                if (row.cells.length === 1) return; // skip empty row message

                const name = row.cells[0].textContent.toLowerCase();
                const address = row.cells[1].textContent.toLowerCase();
                const rowRole = row.cells[3].textContent.trim();

                const matchesSearch = name.includes(search) || address.includes(search);
                const matchesRole = !role || rowRole === role;

                row.style.display = (matchesSearch && matchesRole) ? '' : 'none';
            });
        }

        // Modal functions
        function openEditModal(employeeId) {
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');

            // Find the employee data from hidden templates
            const employeeTemplate = document.querySelector(`.employee-template[data-id="${employeeId}"]`);

            if (employeeTemplate) {
                // Get all employee data from data attributes
                const employeeData = {
                    id: employeeTemplate.getAttribute('data-id'),
                    first_name: employeeTemplate.getAttribute('data-first-name'),
                    last_name: employeeTemplate.getAttribute('data-last-name'),
                    street: employeeTemplate.getAttribute('data-street'),
                    barangay: employeeTemplate.getAttribute('data-barangay'),
                    city: employeeTemplate.getAttribute('data-city'),
                    phone_number: employeeTemplate.getAttribute('data-phone'),
                    gender: employeeTemplate.getAttribute('data-gender'),
                    role: employeeTemplate.getAttribute('data-role')
                };

                // Create the edit form HTML
                const formHTML = `
                            <form action="/employees/${employeeData.id}" method="POST" class="space-y-6">
                                @csrf
                                @method('PUT')

                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="edit_first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                                        <input type="text" id="edit_first_name" name="first_name" value="${employeeData.first_name || ''}" 
                                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                    </div>

                                    <div>
                                        <label for="edit_last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                                        <input type="text" id="edit_last_name" name="last_name" value="${employeeData.last_name || ''}" 
                                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <h4 class="text-sm font-semibold text-gray-800 border-b border-gray-200 pb-2">Address Information</h4>
                                    <div class="grid md:grid-cols-3 gap-4">
                                        <div>
                                            <label for="edit_street" class="block text-sm font-medium text-gray-700 mb-1">Street *</label>
                                            <input type="text" id="edit_street" name="street" value="${employeeData.street || ''}" 
                                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                        </div>

                                        <div>
                                            <label for="edit_barangay" class="block text-sm font-medium text-gray-700 mb-1">Barangay *</label>
                                            <input type="text" id="edit_barangay" name="barangay" value="${employeeData.barangay || ''}" 
                                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                        </div>

                                        <div>
                                            <label for="edit_city" class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                                            <input type="text" id="edit_city" name="city" value="${employeeData.city || ''}" 
                                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="edit_phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                                        <input type="tel" id="edit_phone_number" name="phone_number" value="${employeeData.phone_number || ''}" 
                                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                                        <div class="flex gap-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="gender" value="male" 
                                                    ${employeeData.gender === 'male' ? 'checked' : ''} 
                                                    class="text-indigo-600 focus:ring-indigo-500">
                                                <span class="ml-2 text-gray-700">Male</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="gender" value="female" 
                                                    ${employeeData.gender === 'female' ? 'checked' : ''} 
                                                    class="text-indigo-600 focus:ring-indigo-500">
                                                <span class="ml-2 text-gray-700">Female</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="edit_role" class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                                    <select id="edit_role" name="role" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                        <option value="">Select Role</option>
                                        <option value="Staff" ${employeeData.role === 'Staff' ? 'selected' : ''}>Staff</option>
                                        <option value="Assistant" ${employeeData.role === 'Assistant' ? 'selected' : ''}>Assistant</option>
                                        <option value="Technical" ${employeeData.role === 'Technical' ? 'selected' : ''}>Technical</option>
                                        <option value="Cashier" ${employeeData.role === 'Cashier' ? 'selected' : ''}>Cashier</option>
                                    </select>
                                </div>

                                <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                                    <button type="button" onclick="closeEditModal()" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                        Update Employee
                                    </button>
                                </div>
                            </form>
                        `;

                // Insert the form into the modal
                document.getElementById('modalContent').innerHTML = formHTML;

                // Trigger animation
                setTimeout(() => {
                    document.getElementById('editModal').querySelector('.max-w-2xl').classList.remove('scale-95');
                    document.getElementById('editModal').querySelector('.max-w-2xl').classList.add('scale-100');
                }, 10);
            } else {
                document.getElementById('modalContent').innerHTML = `
                            <div class="p-6 text-center text-red-600">
                                <p>Error loading employee data. Please try again.</p>
                                <button onclick="closeEditModal()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                    Close
                                </button>
                            </div>
                        `;
            }
        }

        function closeEditModal() {
            const modalContent = document.getElementById('editModal').querySelector('.max-w-2xl');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');

            setTimeout(() => {
                document.getElementById('editModal').classList.add('hidden');
                document.getElementById('editModal').classList.remove('flex');
            }, 200);
        }

        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeEditModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeEditModal();
            }
        });
    </script>
@endsection
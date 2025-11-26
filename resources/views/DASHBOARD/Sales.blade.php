@extends('SIDEBAR.layouts')
@section('title', 'Sales')
@section('name', 'Sales')
@section('content')
    <div class="px-2 py-4">
        <h1 class="text-4xl font-bold mb-6">Sales Dashboard</h1>

        <!-- Date Range Filter -->
        <div class="mb-6 flex gap-4 flex-wrap items-center">
            <div class="flex gap-2 items-center">
                <input type="date" id="startDate"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="date" id="endDate"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button id="filterBtn"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Filter</button>
                <button id="clearBtn"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">Clear</button>
            </div>
            <div class="text-sm text-gray-600 ml-auto">Showing results for selected date range</div>
        </div>

        <!-- Key Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Sales</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2" id="totalSales">₱0.00</p>
                        <p class="text-green-600 text-xs mt-2" id="totalSalesChange">—</p>
                    </div>
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Orders</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2" id="totalOrders">0</p>
                        <p class="text-green-600 text-xs mt-2" id="totalOrdersChange">—</p>
                    </div>
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Avg Order Value</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2" id="avgOrderValue">₱0.00</p>
                        <p class="text-green-600 text-xs mt-2" id="avgOrderValueChange">—</p>
                    </div>
                    <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Revenue</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2" id="revenue">₱0.00</p>
                        <p class="text-green-600 text-xs mt-2" id="revenueChange">—</p>
                    </div>
                    <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Sales Trend</h2>
                <canvas id="salesTrendChart"></canvas>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Top Products (by quantity sold)</h2>
                <canvas id="topProductsChart"></canvas>
            </div>
        </div>

        <!-- Recent Transactions Table -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Recent Transactions</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Order ID</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Customer</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Amount</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Items</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Date</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody id="transactionsTable">
                        <!-- populated by JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let salesTrendChart, topProductsChart;

        function moneyFormat(value) {
            return '₱' + Number(value).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        async function loadSalesData() {
            const start = document.getElementById('startDate').value;
            const end = document.getElementById('endDate').value;

            // Build query string only with provided dates
            let url = '/api/sales-data';
            const params = new URLSearchParams();
            if (start) params.append('start', start);
            if (end) params.append('end', end);
            if ([...params].length) url += '?' + params.toString();

            try {
                const res = await fetch(url);
                if (!res.ok) throw new Error('Failed to fetch sales data');
                const data = await res.json();

                // Update metrics
                document.getElementById('totalSales').textContent = moneyFormat(data.totalSales);
                document.getElementById('totalOrders').textContent = data.totalOrders;
                document.getElementById('avgOrderValue').textContent = moneyFormat(data.avgOrderValue);
                document.getElementById('revenue').textContent = moneyFormat(data.totalSales);

                // Sales trend
                salesTrendChart.data.labels = data.salesTrendLabels.length ? data.salesTrendLabels : ['No data'];
                salesTrendChart.data.datasets[0].data = data.salesTrend.length ? data.salesTrend : [0];
                salesTrendChart.update();

                // Top products
                topProductsChart.data.labels = data.topProductsLabels.length ? data.topProductsLabels : ['No products'];
                topProductsChart.data.datasets[0].data = data.topProductsValues.length ? data.topProductsValues : [0];
                topProductsChart.update();

                // Recent transactions
                const tbody = document.getElementById('transactionsTable');
                tbody.innerHTML = '';
                data.recentTransactions.forEach(tx => {
                    const tr = document.createElement('tr');
                    tr.className = 'border-b hover:bg-gray-50';
                    tr.innerHTML = `
                            <td class="px-4 py-3 text-gray-600">${tx.order_ref}</td>
                            <td class="px-4 py-3 text-gray-600">${tx.customer}</td>
                            <td class="px-4 py-3 font-semibold text-gray-800">${moneyFormat(tx.amount)}</td>
                            <td class="px-4 py-3 text-gray-600">${tx.items}</td>
                            <td class="px-4 py-3 text-gray-600">${tx.date}</td>
                            <td class="px-4 py-3"><span class="px-3 py-1 ${tx.status.toLowerCase() === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'} rounded-full text-xs font-semibold">${tx.status}</span></td>
                        `;
                    tbody.appendChild(tr);
                });

            } catch (err) {
                console.error(err);
                // Optionally show UI feedback
            }
        }

        function initializeCharts() {
            // Sales Trend
            const salesCtx = document.getElementById('salesTrendChart').getContext('2d');
            salesTrendChart = new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Sales (₱)',
                        data: [],
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59,130,246,0.08)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { display: true } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { callback: (v) => '₱' + Number(v).toLocaleString() }
                        }
                    }
                }
            });

            // Top Products (horizontal bar)
            const topCtx = document.getElementById('topProductsChart').getContext('2d');
            topProductsChart = new Chart(topCtx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Units Sold',
                        data: [],
                        borderRadius: 6,
                        borderSkipped: false
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true }
                    }
                }
            });

            loadSalesData();
        }

        document.addEventListener('DOMContentLoaded', function () {
            initializeCharts();

            document.getElementById('filterBtn').addEventListener('click', function () {
                loadSalesData();
            });

            document.getElementById('clearBtn').addEventListener('click', function () {
                document.getElementById('startDate').value = '';
                document.getElementById('endDate').value = '';
                loadSalesData();
            });
        });
    </script>
@endsection
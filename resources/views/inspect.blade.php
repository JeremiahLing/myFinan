<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Inspect Report Management 📊') }}
        </h2>
    </x-slot>

    <div class="bg-purple-900 text-white flex flex-col items-center py-10 animate__animated animate__fadeInUp" style="background-color: #CCAAFFaa;">
        <!-- Year Selector Dropdown -->
        <select id="year-selector" class="p-4 rounded-lg bg-white text-gray-800 border-gray-300 mb-4" style="min-width: 200px;">
            <option value="" disabled selected>Please Select Year</option>
            @for ($i = date('Y'); $i >= date('Y') - 10; $i--)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>

        <h1 class="p-4 text-2xl font-bold mb-4 text-gray-800 rounded-lg" style="background-color: #CCAAFFaa;">
            Overview for Year <span id="current-year">{{ $year }}</span>
        </h1>
        
        <div class="bg-white text-gray-800 p-4 rounded-lg shadow-lg mb-6">
            <h2 class="p-4 text-lg font-semibold text-gray-800 rounded-lg">
                Total Budget for <span id="budget-year">{{ $year }}</span>: 
                <span id="total-budget" class="text-green-600">{{ number_format($totalBudget, 2) }}</span>
            </h2>
        </div>

        <div class="p-6 min-h-screen container rounded-lg items-center" style="background-color: #FFFFFFaa;">
            <canvas id="yearChart"></canvas>
        </div>
    </div>

    <!-- Details Modal -->
    <div id="detailsModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-75 hidden">
        <div class="bg-white rounded-lg shadow-lg w-3/4 p-6 relative">
            <button id="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800" style="font-size: 2.0rem; padding: 0.5rem;">&times;</button>
            <h3 id="modalTitle" class="text-2xl font-semibold mb-4"></h3>
            <div id="modalContent" class="text-gray-800">
                <p>Loading details...</p>
            </div>
            <div class="mt-4">
                <button id="navigateButton" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Go to Page
                </button>
            </div>
        </div>
    </div>

    <!--Chart-->
    <script>
        let yearChart;

        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('yearChart').getContext('2d');
            const yearSelector = document.getElementById('year-selector');
            const currentYear = document.getElementById('current-year');
            const modal = document.getElementById('detailsModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalContent = document.getElementById('modalContent');
            const closeModal = document.getElementById('closeModal');
            const navigateButton = document.getElementById('navigateButton');

            const chartData = @json($chartData);

            if (!yearChart) {
                yearChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [
                            {
                                label: 'Expenses',
                                data: chartData.expenses,
                                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1,
                            },
                            {
                                label: 'Incomes',
                                data: chartData.incomes,
                                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Expense and Income Overview',
                                font: {
                                    size: 18,
                                    weight: 'bold',
                                },
                            },
                        },
                        onClick: (e) => {
                            const activePoints = yearChart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, true);
                            if (activePoints.length > 0) {
                                const { datasetIndex, index } = activePoints[0];
                                const month = chartData.labels[index];
                                const type = datasetIndex === 0 ? 'Expenses' : 'Incomes';

                                showDetailsModal(type, month);
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Month',
                                    font: {
                                        size: 14,
                                        weight: 'bold',
                                    },
                                },
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Amount (RM)',
                                    font: {
                                        size: 14,
                                        weight: 'bold',
                                    },
                                },
                            },
                        },
                    },
                });
            }

            const showDetailsModal = (type, month) => {
                modalTitle.textContent = `${type} Details for ${month}`;
                modalContent.innerHTML = `<p>Loading details...</p>`;
                modal.classList.remove('hidden');

                fetch(`/inspect-details?type=${type}&month=${month}&year=${currentYear.textContent}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to fetch details');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.details && data.details.length > 0) {
                            const tableRows = data.details
                                .map(
                                    item => `
                                        <tr class="border-b">
                                            <td class="p-2">${item.item_name || 'N/A'}</td>
                                            <td class="p-2">${item.quantity || 'N/A'}</td>
                                            <td class="p-2">${item.description || 'N/A'}</td>
                                            <td class="p-2">${item.date}</td>
                                            <td class="p-2">RM${item.amount}</td>
                                        </tr>
                                    `
                                )
                                .join('');

                            modalContent.innerHTML = `
                                <table class="w-full border-collapse border border-gray-300">
                                    <thead>
                                        <tr class="bg-gray-100 border-b">
                                            <th class="p-2 text-left">Item Name</th>
                                            <th class="p-2 text-left">Quantity</th>
                                            <th class="p-2 text-left">Description</th>
                                            <th class="p-2 text-left">Date</th>
                                            <th class="p-2 text-left">Amount (RM)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${tableRows}
                                    </tbody>
                                </table>
                            `;

                            navigateButton.onclick = function () {
                                const url = type === 'Expenses' ? `/expense/history` : `/income/history`;
                                window.location.href = url;
                            };
                        } else {
                            modalContent.innerHTML = `<p>No details available for ${type} in ${month}.</p>`;
                        }
                    })
                    .catch(error => {
                        modalContent.innerHTML = `<p>Error loading details: ${error.message}</p>`;
                    });
            };

            closeModal.addEventListener('click', () => {
                modal.classList.add('hidden');
            });

            yearSelector.addEventListener('change', function () {
                const selectedYear = this.value;

                if (!selectedYear) {
                    alert('Please select a valid year.');
                    return;
                }

                fetch(`${window.location.origin}/inspect-report?year=${selectedYear}`)
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.error || 'Unknown error');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    currentYear.textContent = selectedYear;
                    document.getElementById('budget-year').textContent = selectedYear;
                    document.getElementById('total-budget').textContent = `RM ${parseFloat(data.totalBudget).toFixed(2)}`;

                    yearChart.data.labels = data.chartData.labels;
                    yearChart.data.datasets[0].data = data.chartData.expenses;
                    yearChart.data.datasets[1].data = data.chartData.incomes;
                    yearChart.update();
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    alert('Failed to fetch data for the selected year. Please try again later.');
                });
            });
        });
    </script>

    <!--Footer-->
    @include('components.footer')
</x-app-layout>


<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Tasks Graph -->
    <div class="relative overflow-hidden rounded-lg bg-white px-4 pb-12 pt-5 shadow sm:px-6 sm:pt-6">
        <dt>
            <div class="absolute rounded-md p-3" style="background-color: #ca8a04">
                <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                </svg>
            </div>
            <p class="ml-16 truncate text-sm font-medium text-gray-500">Tasks per User</p>
        </dt>
        <dd class="ml-16 pb-6 sm:pb-7">
            <div x-data="{
                chartData: @entangle('taskData'),
                init() {
                    this.renderChart();
                    $watch('chartData', () => this.renderChart());
                },
                renderChart() {
                    const ctx = this.$refs.canvas.getContext('2d');
                    if (this.chart) {
                        this.chart.destroy();
                    }
                    this.chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: this.chartData.map(item => item.name),
                            datasets: [{
                                label: 'Number of Tasks',
                                data: this.chartData.map(item => item.count),
                                backgroundColor: this.chartData.map(() => 'rgba(202, 138, 4, 0.5)'),
                                borderColor: this.chartData.map(() => 'rgb(202, 138, 4)'),
                                borderWidth: 1
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    grid: {
                                        display: true,
                                        drawBorder: true,
                                        drawOnChartArea: true,
                                        drawTicks: true,
                                    },
                                    ticks: {
                                        stepSize: 1,
                                        precision: 0
                                    }
                                },
                                y: {
                                    grid: {
                                        display: false
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return `Tasks: ${context.raw}`;
                                        }
                                    }
                                }
                            },
                            layout: {
                                padding: {
                                    right: 20
                                }
                            }
                        }
                    });
                }
            }" class="mt-4 h-96">
                <canvas x-ref="canvas"></canvas>
            </div>
            <div class="absolute inset-x-0 bottom-0 bg-gray-50 px-4 py-4 sm:px-6">
                <div class="text-sm">
                    <a href="#" class="font-medium text-[#ca8a04] hover:text-[#b7973d]">View all<span class="sr-only"> task statistics</span></a>
                </div>
            </div>
        </dd>
    </div>

    <!-- Todos Graph -->
    <div class="relative overflow-hidden rounded-lg bg-white px-4 pb-12 pt-5 shadow sm:px-6 sm:pt-6">
        <dt>
            <div class="absolute rounded-md p-3" style="background-color: #ca8a04">
                <svg class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="ml-16 truncate text-sm font-medium text-gray-500">Todos per User</p>
        </dt>
        <dd class="ml-16 pb-6 sm:pb-7">
            <div x-data="{
                chartData: @entangle('todoData'),
                init() {
                    this.renderChart();
                    $watch('chartData', () => this.renderChart());
                },
                renderChart() {
                    const ctx = this.$refs.canvas.getContext('2d');
                    if (this.chart) {
                        this.chart.destroy();
                    }
                    this.chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: this.chartData.map(item => item.name),
                            datasets: [{
                                label: 'Number of Todos',
                                data: this.chartData.map(item => item.count),
                                backgroundColor: this.chartData.map(() => 'rgba(202, 138, 4, 0.5)'),
                                borderColor: this.chartData.map(() => 'rgb(202, 138, 4)'),
                                borderWidth: 1
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    grid: {
                                        display: true,
                                        drawBorder: true,
                                        drawOnChartArea: true,
                                        drawTicks: true,
                                    },
                                    ticks: {
                                        stepSize: 1,
                                        precision: 0
                                    }
                                },
                                y: {
                                    grid: {
                                        display: false
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return `Todos: ${context.raw}`;
                                        }
                                    }
                                }
                            },
                            layout: {
                                padding: {
                                    right: 20
                                }
                            }
                        }
                    });
                }
            }" class="mt-4 h-96">
                <canvas x-ref="canvas"></canvas>
            </div>
            <div class="absolute inset-x-0 bottom-0 bg-gray-50 px-4 py-4 sm:px-6">
                <div class="text-sm">
                    <a href="#" class="font-medium text-[#ca8a04] hover:text-[#b7973d]">View all<span class="sr-only"> todo statistics</span></a>
                </div>
            </div>
        </dd>
    </div>
</div>
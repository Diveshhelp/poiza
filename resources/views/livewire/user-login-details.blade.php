<div class="ml-2">
    <div class="mb-6 ">
        <div class="flex justify-between items-center mb-4">
            <div class="ml-2">
                <h1 class="text-xl font-bold text-gray-900">{{ $user->name }}'s Login Details</h1>
                <p class="text-gray-600">{{ $user->email }}</p>
            </div>
            <div class="flex space-x-4">
                <select wire:model="selectedPeriod" class="rounded-md border-gray-300 shadow-sm">
                    <option value="7">Last 7 days</option>
                    <option value="30">Last 30 days</option>
                    <option value="90">Last 90 days</option>
                    <option value="365">Last year</option>
                </select>
            </div>
        </div>

        <!-- User Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Total Logins</dt>
                <dd class="text-2xl font-bold text-gray-900">{{ number_format($userStatistics['total_logins']) }}</dd>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Avg/Day</dt>
                <dd class="text-2xl font-bold text-gray-900">{{ $userStatistics['avg_logins_per_day'] }}</dd>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Last Login</dt>
                <dd class="text-sm font-medium text-gray-900">
                    {{ $userStatistics['last_login'] ? $userStatistics['last_login']->diffForHumans() : 'Never' }}
                </dd>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">Unique IPs</dt>
                <dd class="text-2xl font-bold text-gray-900">{{ $userStatistics['unique_ips'] }}</dd>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">First Login</dt>
                <dd class="text-sm font-medium text-gray-900">
                    {{ $userStatistics['first_login_in_period'] ? $userStatistics['first_login_in_period']->format('M j, Y') : 'N/A' }}
                </dd>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Daily Pattern Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Daily Login Pattern</h3>
            <div style="height: 300px;">
                <canvas id="dailyPatternChart" wire:ignore></canvas>
            </div>
        </div>

        <!-- IP Addresses -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">IP Addresses</h3>
            <div class="space-y-3 max-h-80 overflow-y-auto">
                @forelse($ipAddresses as $ip)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <div class="font-medium text-gray-900">{{ $ip->ip_address }}</div>
                        <div class="text-sm text-gray-500">
                            Last used: {{ \Carbon\Carbon::parse($ip->last_used)->diffForHumans() }}
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-medium text-gray-900">{{ $ip->login_count }}</div>
                        <div class="text-sm text-gray-500">logins</div>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-8">
                    No IP addresses found for this period.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Login History Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Login History</h3>
                <div class="max-w-xs">
                    <input wire:model.debounce.300ms="filterByIp" 
                           type="text" 
                           placeholder="Filter by IP..." 
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th wire:click="sortBy('logged_in_at')"
                class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-150">
                <div class="flex items-center space-x-1">
                    <span>Date & Time</span>
                    @if($sortBy === 'logged_in_at')
                        <span class="text-gray-400 text-sm">
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        </span>
                    @endif
                </div>
            </th>
            <th wire:click="sortBy('ip_address')"
                class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-150">
                <div class="flex items-center space-x-1">
                    <span>IP Address</span>
                    @if($sortBy === 'ip_address')
                        <span class="text-gray-400 text-sm">
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        </span>
                    @endif
                </div>
            </th>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                <span class="hidden sm:inline">User Agent</span>
                <span class="sm:hidden">Device</span>
            </th>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                <span class="hidden md:inline">Location</span>
                <span class="md:hidden">Loc</span>
            </th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-100">
        @forelse($loginLogs as $log)
        <tr class="hover:bg-gray-50 transition-colors duration-150">
            {{-- Date & Time Column --}}
            <td class="px-3 py-2 text-xs text-gray-900">
                <div class="space-y-0.5">
                    <div class="font-medium text-gray-900">
                        {{ $log->logged_in_at->format('M j, Y') }}
                    </div>
                    <div class="text-gray-500 flex items-center space-x-1">
                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ $log->logged_in_at->format('g:i A') }}</span>
                    </div>
                    <div class="text-xs text-gray-400">
                        {{ $log->logged_in_at->diffForHumans() }}
                    </div>
                </div>
            </td>

            {{-- IP Address Column --}}
            <td class="px-3 py-2 text-xs">
                <div class="space-y-1">
                    <div class="flex items-center space-x-1">
                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                        </svg>
                        <span class="font-mono text-gray-900">{{ $log->ip_address }}</span>
                    </div>
                    {{-- IP Type Badge --}}
                    @if(filter_var($log->ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE))
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Public
                        </span>
                    @else
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Private
                        </span>
                    @endif
                </div>
            </td>

            {{-- User Agent / Device Column --}}
            <td class="px-3 py-2 text-xs text-gray-500">
                <div class="space-y-1">
                    {{-- Browser Detection --}}
                    @php
                        $userAgent = $log->user_agent;
                        $browser = 'Unknown';
                        $os = 'Unknown';
                        $device = 'Desktop';
                        
                        // Simple browser detection
                        if (str_contains($userAgent, 'Chrome')) $browser = 'Chrome';
                        elseif (str_contains($userAgent, 'Firefox')) $browser = 'Firefox';
                        elseif (str_contains($userAgent, 'Safari')) $browser = 'Safari';
                        elseif (str_contains($userAgent, 'Edge')) $browser = 'Edge';
                        
                        // Simple OS detection
                        if (str_contains($userAgent, 'Windows')) $os = 'Windows';
                        elseif (str_contains($userAgent, 'Mac')) $os = 'macOS';
                        elseif (str_contains($userAgent, 'Linux')) $os = 'Linux';
                        elseif (str_contains($userAgent, 'Android')) $os = 'Android';
                        elseif (str_contains($userAgent, 'iOS')) $os = 'iOS';
                        
                        // Simple device detection
                        if (str_contains($userAgent, 'Mobile') || str_contains($userAgent, 'Android')) $device = 'Mobile';
                        elseif (str_contains($userAgent, 'Tablet') || str_contains($userAgent, 'iPad')) $device = 'Tablet';
                    @endphp
                    
                    <div class="flex items-center space-x-1">
                        {{-- Device Icon --}}
                        @if($device === 'Mobile')
                            <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a1 1 0 001-1V4a1 1 0 00-1-1H8a1 1 0 00-1 1v16a1 1 0 001 1z"></path>
                            </svg>
                        @elseif($device === 'Tablet')
                            <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        @else
                            <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        @endif
                        <span class="font-medium text-gray-900">{{ $browser }}</span>
                    </div>
                    
                    <div class="text-gray-400">{{ $os }}</div>
                    
                    {{-- Device Type Badge --}}
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium 
                        @if($device === 'Mobile') bg-green-100 text-green-800
                        @elseif($device === 'Tablet') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ $device }}
                    </span>
                    
                    {{-- Full User Agent on Hover --}}
                    <div class="hidden sm:block">
                        <div class="text-xs text-gray-400 truncate max-w-xs" title="{{ $userAgent }}">
                            {{ Str::limit($userAgent, 40) }}
                        </div>
                    </div>
                </div>
            </td>

            {{-- Location Column (Optional) --}}
            <td class="px-3 py-2 text-xs text-gray-500">
                <div class="space-y-1">
                    {{-- This would require a GeoIP service --}}
                    <div class="flex items-center space-x-1">
                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-gray-400">Unknown</span>
                    </div>
                    {{-- You can integrate with MaxMind GeoIP or similar service --}}
                    <div class="text-xs text-gray-400">
                        {{-- Country flag and name would go here --}}
                    </div>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="px-3 py-8 text-center text-sm text-gray-500">
                <div class="flex flex-col items-center space-y-2">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <div>No login records found for the selected period.</div>
                    <div class="text-xs text-gray-400">Try selecting a different time range.</div>
                </div>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
{{-- Mobile-First Card Layout (Alternative) --}}
<div class="block sm:hidden space-y-3">
    @forelse($loginLogs as $log)
    <div class="bg-white border border-gray-200 rounded-lg p-4">
        <div class="flex justify-between items-start mb-2">
            <div class="text-sm font-medium text-gray-900">
                {{ $log->logged_in_at->format('M j, Y') }}
            </div>
            <div class="text-xs text-gray-500">
                {{ $log->logged_in_at->format('g:i A') }}
            </div>
        </div>
        <div class="space-y-1">
            <div class="flex items-center text-xs text-gray-600">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                </svg>
                {{ $log->ip_address }}
            </div>
            <div class="text-xs text-gray-500 truncate">
                {{ $log->user_agent }}
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-8 text-gray-500">
        No login records found.
    </div>
    @endforelse
</div>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $loginLogs->links() }}
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let dailyPatternChart = null;

    // Chart data from Livewire
    const userChartData = {
        dailyPattern: @json($dailyPattern ?? collect()),
        user: @json([
            'name' => $user->name,
            'email' => $user->email,
            'id' => $user->id
        ])
    };

    console.log('User chart data:', userChartData);

    function initUserCharts() {
        console.log('Initializing user charts...');
        
        // Check if Chart.js is loaded
        if (typeof Chart === 'undefined') {
            console.error('Chart.js is not loaded');
            return;
        }

        // Destroy existing chart
        if (dailyPatternChart) {
            dailyPatternChart.destroy();
            dailyPatternChart = null;
        }

        // Daily Pattern Chart
        const dailyPatternCtx = document.getElementById('dailyPatternChart');
        if (dailyPatternCtx) {
            console.log('Creating daily pattern chart');
            
            const dailyLabels = userChartData.dailyPattern.map(item => item.date) || [];
            const dailyData = userChartData.dailyPattern.map(item => item.login_count) || [];
            
            console.log('Daily pattern data:', { dailyLabels, dailyData });
            
            // Show message if no data
            if (dailyLabels.length === 0) {
                const ctx = dailyPatternCtx.getContext('2d');
                ctx.clearRect(0, 0, dailyPatternCtx.width, dailyPatternCtx.height);
                ctx.fillStyle = '#9CA3AF';
                ctx.font = '14px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('No login data available for this period', dailyPatternCtx.width / 2, dailyPatternCtx.height / 2);
                return;
            }
            
            dailyPatternChart = new Chart(dailyPatternCtx, {
                type: 'line',
                data: {
                    labels: dailyLabels,
                    datasets: [{
                        label: `${userChartData.user.name}'s Logins`,
                        data: dailyData,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.1,
                        fill: true,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                title: function(tooltipItems) {
                                    return 'Date: ' + tooltipItems[0].label;
                                },
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y + ' login' + (context.parsed.y !== 1 ? 's' : '');
                                }
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Date'
                            },
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Number of Logins'
                            },
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            },
                            grid: {
                                display: true
                            }
                        }
                    },
                    animation: {
                        duration: 300
                    }
                }
            });
            console.log('Daily pattern chart created successfully');
        } else {
            console.error('Daily pattern chart canvas not found');
        }
    }

    // Multiple initialization attempts
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(initUserCharts, 100);
    });

    document.addEventListener('livewire:load', function () {
        setTimeout(initUserCharts, 200);
    });

    // Listen for Livewire updates and refresh chart
    document.addEventListener('livewire:update', function () {
        // Update chart data from the new response
        const newDailyPattern = @json($dailyPattern ?? collect());
        
        if (dailyPatternChart && newDailyPattern && newDailyPattern.length > 0) {
            dailyPatternChart.data.labels = newDailyPattern.map(item => item.date);
            dailyPatternChart.data.datasets[0].data = newDailyPattern.map(item => item.login_count);
            dailyPatternChart.update('none');
        } else if (newDailyPattern && newDailyPattern.length === 0) {
            // Reinitialize chart if no data to show empty state
            setTimeout(initUserCharts, 100);
        }
    });

    // Fallback initialization
    window.addEventListener('load', function() {
        setTimeout(function() {
            if (!dailyPatternChart) {
                console.log('Fallback user chart initialization');
                initUserCharts();
            }
        }, 1000);
    });

    // Debug function
    window.debugUserCharts = function() {
        console.log('Daily pattern chart:', dailyPatternChart);
        console.log('User chart data:', userChartData);
        console.log('Canvas element:', document.getElementById('dailyPatternChart'));
        initUserCharts();
    };

    // Clean up on page unload
    window.addEventListener('beforeunload', function() {
        if (dailyPatternChart) {
            dailyPatternChart.destroy();
        }
    });
</script>
</div>

@extends('layouts.app')

@section('body_class', 'bg-[#0f172a] [.theme-light_&]:bg-gray-50')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white [.theme-light_&]:text-gray-900 tracking-tight">Analytics Dashboard</h1>
            <p class="text-gray-400 mt-1 [.theme-light_&]:text-gray-500">Performance metrics and insights</p>
        </div>
        
        <form action="{{ route('analytics.dashboard') }}" method="GET" class="flex flex-wrap items-end gap-3 bg-[#1e293b] [.theme-light_&]:bg-white [.theme-light_&]:shadow-sm p-3 rounded-lg border border-white/5 [.theme-light_&]:border-gray-200">
            <div>
                <label for="start_date" class="block text-xs font-medium text-gray-400 mb-1 [.theme-light_&]:text-gray-600">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" 
                    class="bg-[#0f172a] [.theme-light_&]:bg-gray-50 border border-white/10 [.theme-light_&]:border-gray-300 rounded px-3 py-1.5 text-sm text-white [.theme-light_&]:text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
            </div>
            <div>
                <label for="end_date" class="block text-xs font-medium text-gray-400 mb-1 [.theme-light_&]:text-gray-600">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" 
                    class="bg-[#0f172a] [.theme-light_&]:bg-gray-50 border border-white/10 [.theme-light_&]:border-gray-300 rounded px-3 py-1.5 text-sm text-white [.theme-light_&]:text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-1.5 rounded text-sm font-medium transition-colors">
                Filter
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Chart 1: Most Profitable Services -->
        <div class="bg-[#1e293b] [.theme-light_&]:bg-white rounded-xl border border-white/5 [.theme-light_&]:border-gray-200 p-6 shadow-xl [.theme-light_&]:shadow-md">
            <h3 class="text-lg font-semibold text-white [.theme-light_&]:text-gray-900 mb-4">Most Profitable Services</h3>
            <div class="relative h-64 w-full">
                <canvas id="profitableServicesChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Profit Segmentation -->
        <div class="bg-[#1e293b] [.theme-light_&]:bg-white rounded-xl border border-white/5 [.theme-light_&]:border-gray-200 p-6 shadow-xl [.theme-light_&]:shadow-md">
            <h3 class="text-lg font-semibold text-white [.theme-light_&]:text-gray-900 mb-4">Profit by Age & Client Type</h3>
            <div class="relative h-64 w-full">
                <canvas id="segmentationChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart 3 / Table: Analyst Performance -->
    <div class="bg-[#1e293b] [.theme-light_&]:bg-white rounded-xl border border-white/5 [.theme-light_&]:border-gray-200 p-6 shadow-xl [.theme-light_&]:shadow-md">
        <h3 class="text-lg font-semibold text-white [.theme-light_&]:text-gray-900 mb-4">Analyst Performance by Experience</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-400 [.theme-light_&]:text-gray-500 border-b border-white/10 [.theme-light_&]:border-gray-100 text-xs uppercase tracking-wider">
                        <th class="py-3 px-4 font-semibold">Experience Level</th>
                        <th class="py-3 px-4 font-semibold text-right">Avg Rating</th>
                        <th class="py-3 px-4 font-semibold text-right">Total Profit</th>
                        <th class="py-3 px-4 font-semibold text-right">Avg Completion Time</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-white/5 [.theme-light_&]:divide-gray-100">
                    @forelse($analystPerformance as $perf)
                    <tr class="hover:bg-white/5 [.theme-light_&]:hover:bg-gray-50 transition-colors group">
                        <td class="py-3 px-4 text-white [.theme-light_&]:text-gray-900 font-medium">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full 
                                    {{ str_contains($perf['group'], 'Senior') ? 'bg-purple-500' : 
                                       (str_contains($perf['group'], 'Mid') ? 'bg-blue-500' : 
                                       (str_contains($perf['group'], 'Associate') ? 'bg-green-500' : 'bg-gray-500')) }}">
                                </span>
                                {{ $perf['group'] }}
                            </div>
                        </td>
                        <td class="py-3 px-4 text-right text-gray-300 [.theme-light_&]:text-gray-600">
                            <span class="inline-flex items-center gap-1 text-yellow-400 [.theme-light_&]:text-yellow-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                {{ number_format($perf['avg_rating'], 1) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right text-green-400 [.theme-light_&]:text-green-600 font-mono">
                            ${{ number_format($perf['total_profit'], 2) }}
                        </td>
                        <td class="py-3 px-4 text-right text-gray-300 [.theme-light_&]:text-gray-600">
                            {{ $perf['avg_completion_time'] }} days
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-500 italic">No performance data found for this period.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Optional: Visual representation below table -->
        <div class="mt-6 h-64 relative w-full">
             <canvas id="performanceChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Configuration common styles
    const isLight = document.body.classList.contains('theme-light');
    Chart.defaults.color = isLight ? '#475569' : '#94a3b8';
    Chart.defaults.font.family = 'Inter, sans-serif';
    Chart.defaults.scale.grid.color = isLight ? 'rgba(0, 0, 0, 0.05)' : 'rgba(255, 255, 255, 0.05)';

    // 1. Most Profitable Services Chart
    const ctx1 = document.getElementById('profitableServicesChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: {!! $profitableServices['labels'] !!},
            datasets: [{
                label: 'Profit ($)',
                data: {!! $profitableServices['data'] !!},
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: (value) => '$' + value }
                }
            }
        }
    });

    // 2. Profit Segmentation Chart
    const ctx2 = document.getElementById('segmentationChart').getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: {!! json_encode($profitSegmentation['labels']) !!},
            datasets: {!! json_encode($profitSegmentation['datasets']) !!}
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { stacked: true },
                y: { stacked: true, beginAtZero: true }
            }
        }
    });

    // 3. Analyst Performance Mixed Chart (Visualizing the table data)
    const ctx3 = document.getElementById('performanceChart').getContext('2d');
    const perfData = {!! json_encode($analystPerformance) !!};
    
    // Extract arrays for chart
    const groups = perfData.map(d => d.group);
    const ratings = perfData.map(d => d.avg_rating);
    const profits = perfData.map(d => d.total_profit);
    const times = perfData.map(d => d.avg_completion_time);

    new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: groups,
            datasets: [
                {
                    label: 'Avg Rating',
                    data: ratings,
                    type: 'line',
                    borderColor: '#facc15', // Yellow
                    backgroundColor: '#facc15',
                    yAxisID: 'y1',
                    tension: 0.3,
                    borderWidth: 2
                },
                {
                    label: 'Total Profit',
                    data: profits,
                    backgroundColor: 'rgba(16, 185, 129, 0.7)', // Green
                    yAxisID: 'y',
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: { display: true, text: 'Profit ($)' }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    min: 0,
                    max: 5,
                    grid: { drawOnChartArea: false },
                    title: { display: true, text: 'Rating (0-5)' }
                }
            }
        }
    });
</script>
@endsection

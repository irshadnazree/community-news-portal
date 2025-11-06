@extends('layouts.app')

@section('title', 'Social Share Analytics')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold mb-6">Social Share Analytics</h1>

    <!-- Filters -->
    <div class="card bg-base-100 shadow-xl mb-6">
        <div class="card-body">
            <h2 class="card-title mb-4">Filters</h2>
            <form method="GET" action="{{ route('admin.analytics') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="label">
                        <span class="label-text">Start Date</span>
                    </label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="input input-bordered w-full">
                </div>
                <div>
                    <label class="label">
                        <span class="label-text">End Date</span>
                    </label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="input input-bordered w-full">
                </div>
                <div>
                    <label class="label">
                        <span class="label-text">Platform</span>
                    </label>
                    <select name="platform" class="select select-bordered w-full">
                        <option value="all" {{ $platform === 'all' || !$platform ? 'selected' : '' }}>All Platforms</option>
                        <option value="facebook" {{ $platform === 'facebook' ? 'selected' : '' }}>Facebook</option>
                        <option value="twitter" {{ $platform === 'twitter' ? 'selected' : '' }}>X (Twitter)</option>
                        <option value="whatsapp" {{ $platform === 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                        <option value="telegram" {{ $platform === 'telegram' ? 'selected' : '' }}>Telegram</option>
                        <option value="email" {{ $platform === 'email' ? 'selected' : '' }}>Email</option>
                    </select>
                </div>
                <div>
                    <label class="label">
                        <span class="label-text">Page Type</span>
                    </label>
                    <select name="page_type" class="select select-bordered w-full">
                        <option value="all" {{ $pageType === 'all' || !$pageType ? 'selected' : '' }}>All Pages</option>
                        <option value="home" {{ $pageType === 'home' ? 'selected' : '' }}>Home</option>
                        <option value="news" {{ $pageType === 'news' ? 'selected' : '' }}>News Articles</option>
                    </select>
                </div>
                <div class="md:col-span-4">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="{{ route('admin.analytics') }}" class="btn btn-outline ml-2">Clear Filters</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="stat bg-base-200 rounded-lg shadow">
            <div class="stat-title">Total Shares</div>
            <div class="stat-value text-primary">{{ number_format($totalShares) }}</div>
        </div>
        <div class="stat bg-base-200 rounded-lg shadow">
            <div class="stat-title">Platforms</div>
            <div class="stat-value text-secondary">{{ count($sharesByPlatform) }}</div>
        </div>
        <div class="stat bg-base-200 rounded-lg shadow">
            <div class="stat-title">Top Shared Pages</div>
            <div class="stat-value text-accent">{{ $topPages->count() }}</div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Shares by Platform Chart -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title">Shares by Platform</h2>
                <canvas id="platformChart" style="max-height: 300px;"></canvas>
            </div>
        </div>

        <!-- Shares Over Time Chart -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title">Shares Over Time</h2>
                <canvas id="timeChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Shares by Page Type Chart -->
    <div class="card bg-base-100 shadow-xl mb-6">
        <div class="card-body">
            <h2 class="card-title">Shares by Page Type</h2>
            <canvas id="pageTypeChart" style="max-height: 300px;"></canvas>
        </div>
    </div>

    <!-- Top Shared Pages Table -->
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <h2 class="card-title mb-4">Top Shared Pages</h2>
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Page URL</th>
                            <th>Share Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topPages as $page)
                            <tr>
                                <td>
                                    <a href="{{ $page->page_url }}" target="_blank" class="link link-primary">
                                        {{ Str::limit($page->page_url, 80) }}
                                    </a>
                                </td>
                                <td>{{ number_format($page->share_count) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">No data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Platform Chart Data
    const platformData = @json($sharesByPlatform);
    const platformLabels = Object.keys(platformData);
    const platformValues = Object.values(platformData);

    // Time Chart Data
    const timeData = @json($sharesOverTime);
    const timeLabels = Object.keys(timeData);
    const timeValues = Object.values(timeData);

    // Page Type Chart Data
    const pageTypeData = @json($sharesByPageType);
    const pageTypeLabels = Object.keys(pageTypeData);
    const pageTypeValues = Object.values(pageTypeData);

    // Platform Chart (Bar Chart)
    const platformCtx = document.getElementById('platformChart').getContext('2d');
    new Chart(platformCtx, {
        type: 'bar',
        data: {
            labels: platformLabels.map(label => label.charAt(0).toUpperCase() + label.slice(1)),
            datasets: [{
                label: 'Shares',
                data: platformValues,
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(251, 191, 36, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(139, 92, 246, 0.8)'
                ],
                borderColor: [
                    'rgba(59, 130, 246, 1)',
                    'rgba(16, 185, 129, 1)',
                    'rgba(251, 191, 36, 1)',
                    'rgba(239, 68, 68, 1)',
                    'rgba(139, 92, 246, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Time Chart (Line Chart)
    const timeCtx = document.getElementById('timeChart').getContext('2d');
    new Chart(timeCtx, {
        type: 'line',
        data: {
            labels: timeLabels,
            datasets: [{
                label: 'Shares',
                data: timeValues,
                borderColor: 'rgba(59, 130, 246, 1)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Page Type Chart (Pie Chart)
    const pageTypeCtx = document.getElementById('pageTypeChart').getContext('2d');
    new Chart(pageTypeCtx, {
        type: 'pie',
        data: {
            labels: pageTypeLabels.map(label => label ? label.charAt(0).toUpperCase() + label.slice(1) : 'Unknown'),
            datasets: [{
                data: pageTypeValues,
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(156, 163, 175, 0.8)'
                ],
                borderColor: [
                    'rgba(59, 130, 246, 1)',
                    'rgba(16, 185, 129, 1)',
                    'rgba(156, 163, 175, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush
@endsection


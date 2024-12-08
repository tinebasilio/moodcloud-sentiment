@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Analytics Overview')

@section('content')
<div class="p-6 space-y-6 max-w-7xl mx-auto">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-base-200 via-base-200/50 to-transparent rounded-2xl p-8">
        <div class="max-w-2xl">
            <h1 class="text-4xl font-bold mb-4">Welcome to Sentirex</h1>
            <p class="text-lg opacity-90 mb-6">Experience powerful sentiment analysis powered by lexicons to understand sentiments and emotions in text.</p>
            <div class="flex gap-4 flex-wrap">
                <a href="{{ route('analyze') }}" class="btn btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Analysis
                </a>
                <a href="/history" class="btn btn-ghost">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    View History
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="card bg-base-200 shadow-xl">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div class="stat-value text-primary text-3xl">{{ number_format($total_analysis) }}</div>
                    <div class="p-4 bg-primary/10 rounded-lg">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="stat-title text-base">Total Analyses</div>
                <div class="stat-desc">
                    <span class="text-success">↗︎ 12%</span> increase from last month
                </div>
            </div>
        </div>

        <div class="card bg-base-200 shadow-xl">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div class="stat-value text-success text-3xl">{{ $positive_rate }}%</div>
                    <div class="p-4 bg-success/10 rounded-lg">
                        <svg class="w-8 h-8 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="stat-title text-base">Positive Rate</div>
                <div class="stat-desc">
                    <span class="text-success">↗︎ 8%</span> from previous week
                </div>
            </div>
        </div>

        <div class="card bg-base-200 shadow-xl">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div class="stat-value text-info text-3xl">{{ $accuracy }}%</div>
                    <div class="p-4 bg-info/10 rounded-lg">
                        <svg class="w-8 h-8 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="stat-title text-base">Accuracy</div>
                <div class="stat-desc">Based on verified results</div>
            </div>
        </div>

        <div class="card bg-base-200 shadow-xl">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div class="stat-value text-warning text-3xl">1.2s</div>
                    <div class="p-4 bg-warning/10 rounded-lg">
                        <svg class="w-8 h-8 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="stat-title text-base">Avg. Response Time</div>
                <div class="stat-desc">
                    <span class="text-success">↘︎ 0.3s</span> faster than target
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Charts -->
    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Sentiment Trend Chart -->
        <div class="card bg-base-200 shadow-xl">
            <div class="card-body">
                <h2 class="card-title mb-6">Sentiment Trends</h2>
                <div class="w-full h-[300px]" id="sentimentTrendChart"></div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card bg-base-200 shadow-xl">
            <div class="card-body">
                <h2 class="card-title mb-6">Recent Activity</h2>
                <div class="space-y-4">
                    @foreach($recent_analyses as $analysis)
                    <div class="flex items-center gap-4 p-3 bg-base-100 rounded-lg">
                        <div class="flex-none">
                            <div class="avatar placeholder">
                                <div class="bg-{{ $analysis->sentiment_result === 'Positive' ? 'success' : ($analysis->sentiment_result === 'Negative' ? 'error' : 'neutral') }}/10 text-{{ $analysis->sentiment_result === 'Positive' ? 'success' : ($analysis->sentiment_result === 'Negative' ? 'error' : 'neutral') }} rounded-full w-12 h-12">
                                    <span class="text-xl">{{ substr($analysis->sentiment_result, 0, 1) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate">{{ Str::limit($analysis->sentiment_input, 50) }}</p>
                            <p class="text-xs opacity-70">{{ $analysis->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex-none">
                            <div class="badge {{ 
                                $analysis->sentiment_result === 'Positive' ? 'badge-success' : 
                                ($analysis->sentiment_result === 'Negative' ? 'badge-error' : 'badge-neutral') 
                            }}">
                                {{ $analysis->sentiment_result }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="grid md:grid-cols-3 gap-6">
        <div class="card bg-base-200 shadow-xl hover:shadow-2xl transition-all duration-300">
            <div class="card-body">
                <div class="flex justify-center mb-4">
                    <div class="p-3 bg-success/10 rounded-lg">
                        <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="font-bold text-center mb-2">Real-time Analysis</h3>
                <p class="text-center opacity-70">Get instant sentiment analysis results with our advanced Web Application</p>
            </div>
        </div>

        <div class="card bg-base-200 shadow-xl hover:shadow-2xl transition-all duration-300">
            <div class="card-body">
                <div class="flex justify-center mb-4">
                    <div class="p-3 bg-info/10 rounded-lg">
                        <svg class="w-6 h-6 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="font-bold text-center mb-2">Multiple Formats</h3>
                <p class="text-center opacity-70">Support for text, PDF, and document file formats.</p>
            </div>
        </div>

        <div class="card bg-base-200 shadow-xl hover:shadow-2xl transition-all duration-300">
            <div class="card-body">
                <div class="flex justify-center mb-4">
                    <div class="p-3 bg-warning/10 rounded-lg">
                        <svg class="w-6 h-6 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="font-bold text-center mb-2">Detailed Reports</h3>
                <p class="text-center opacity-70">Generate comprehensive analysis reports with visualizations.</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sentimentTrends = @json($sentiment_trends);
    
    const dates = sentimentTrends.map(trend => new Date(trend.date).toLocaleDateString());
    const positiveData = sentimentTrends.map(trend => trend.positive_count);
    const negativeData = sentimentTrends.map(trend => trend.negative_count);
    const neutralData = sentimentTrends.map(trend => trend.neutral_count);

    const options = {
        series: [{
            name: 'Positive',
            data: positiveData
        }, {
            name: 'Negative',
            data: negativeData
        }, {
            name: 'Neutral',
            data: neutralData
        }],
        chart: {
            type: 'area',
            height: 300,
            toolbar: {
                show: false
            },
            background: 'transparent'
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        xaxis: {
            categories: dates,
            labels: {
                style: {
                    colors: '#666'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#666'
                }
            }
        },
        colors: ['#10b981', '#ef4444', '#6B7280'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
                stops: [0, 100]
            }
        },
        grid: {
            borderColor: '#374151',
            strokeDashArray: 4,
            yaxis: {
                lines: {
                    show: true
                }
            }
        },
        legend: {
            labels: {
                colors: '#666'
            }
        },
        theme: {
            mode: document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light'
        }
    };

    const chart = new ApexCharts(document.querySelector("#sentimentTrendChart"), options);
    chart.render();

    // Animate stats on scroll
    const stats = document.querySelectorAll('.stat-value');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'countUp 2s ease-out forwards';
                observer.unobserve(entry.target);
            }
        });
    });

    stats.forEach(stat => observer.observe(stat));
});
</script>
@endpush
@endsection
@extends('layouts.admin.show')

@section('title') {{ __('app.system_health') }} @endsection

@section('content')
<div class="row mt-3 mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1" style="background:none;padding:0;">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="#">{{ __('app.system') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('app.system_health') }}</li>
                    </ol>
                </nav>
                <h4 class="page-title mb-0">{{ __('app.system_health') }}</h4>
            </div>
            <div>
                <span class="badge" style="background:var(--admin-success);color:#fff;font-size:13px;padding:6px 16px;border-radius:20px;">
                    <i class="fas fa-check-circle mr-1"></i> {{ __('app.all_systems_operational') }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-kpi-card">
            <div class="card-body">
                <div class="d-flex align-items-center" style="gap:12px;">
                    <div class="kpi-icon primary"><i class="fas fa-users"></i></div>
                    <div>
                        <div class="kpi-value" style="font-size:24px;">{{ number_format($stats['total_users']) }}</div>
                        <div class="kpi-label">{{ __('app.total_users') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-kpi-card">
            <div class="card-body">
                <div class="d-flex align-items-center" style="gap:12px;">
                    <div class="kpi-icon success"><i class="fas fa-money-bill-wave"></i></div>
                    <div>
                        <div class="kpi-value" style="font-size:24px;">{{ number_format($stats['total_revenue'], 0) }}</div>
                        <div class="kpi-label">{{ __('app.total_revenue') }} (JOD)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-kpi-card">
            <div class="card-body">
                <div class="d-flex align-items-center" style="gap:12px;">
                    <div class="kpi-icon info"><i class="fas fa-sync-alt"></i></div>
                    <div>
                        <div class="kpi-value" style="font-size:24px;">{{ number_format($stats['active_subscriptions']) }}</div>
                        <div class="kpi-label">{{ __('app.active_subscriptions') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-kpi-card">
            <div class="card-body">
                <div class="d-flex align-items-center" style="gap:12px;">
                    <div class="kpi-icon warning"><i class="fas fa-database"></i></div>
                    <div>
                        <div class="kpi-value" style="font-size:24px;">{{ $db_size }}</div>
                        <div class="kpi-label">{{ __('app.database_size') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h5 class="header-title mb-3">{{ __('app.environment_info') }}</h5>
                <table class="table table-sm mb-0">
                    <tbody>
                        <tr><td class="text-muted" style="width:40%;">{{ __('app.php_version') }}</td><td style="font-weight:600;">{{ $php_version }}</td></tr>
                        <tr><td class="text-muted">{{ __('app.laravel_version') }}</td><td style="font-weight:600;">{{ $laravel_version }}</td></tr>
                        <tr><td class="text-muted">{{ __('app.database_size') }}</td><td style="font-weight:600;">{{ $db_size }}</td></tr>
                        <tr><td class="text-muted">{{ __('app.timezone') }}</td><td style="font-weight:600;">{{ config('app.timezone') }}</td></tr>
                        <tr><td class="text-muted">{{ __('app.environment') }}</td><td><span class="badge badge-soft-success">{{ app()->environment() }}</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h5 class="header-title mb-3">{{ __('app.content_overview') }}</h5>
                <table class="table table-sm mb-0">
                    <tbody>
                        <tr><td class="text-muted" style="width:40%;">{{ __('app.news') }}</td><td style="font-weight:600;">{{ number_format($stats['total_news']) }}</td></tr>
                        <tr><td class="text-muted">{{ __('app.photos') }}</td><td style="font-weight:600;">{{ number_format($stats['total_photos']) }}</td></tr>
                        <tr><td class="text-muted">{{ __('app.videos') }}</td><td style="font-weight:600;">{{ number_format($stats['total_videos']) }}</td></tr>
                        <tr><td class="text-muted">{{ __('app.items') }}</td><td style="font-weight:600;">{{ number_format($stats['total_items']) }}</td></tr>
                        <tr><td class="text-muted">{{ __('app.categories') }}</td><td style="font-weight:600;">{{ number_format($stats['total_categories']) }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h5 class="header-title mb-3">{{ __('app.revenue_trend') }}</h5>
                <div id="revenue-trend-chart" class="apex-charts"></div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h5 class="header-title mb-3">{{ __('app.user_growth') }}</h5>
                <div id="user-growth-chart" class="apex-charts"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="header-title mb-3">{{ __('app.today_snapshot') }}</h5>
                <div class="row text-center">
                    <div class="col-md-3">
                        <div style="padding:16px;">
                            <div style="font-size:28px;font-weight:700;color:var(--admin-primary);">{{ $stats['today_payments'] }}</div>
                            <div class="text-muted" style="font-size:13px;">{{ __('app.payments') }} {{ __('app.today') }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div style="padding:16px;">
                            <div style="font-size:28px;font-weight:700;color:var(--admin-success);">{{ number_format($stats['today_revenue'], 0) }}</div>
                            <div class="text-muted" style="font-size:13px;">{{ __('app.revenue') }} {{ __('app.today') }} (JOD)</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div style="padding:16px;">
                            <div style="font-size:28px;font-weight:700;color:var(--admin-info);">{{ $stats['today_users'] }}</div>
                            <div class="text-muted" style="font-size:13px;">{{ __('app.new_users') }} {{ __('app.today') }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div style="padding:16px;">
                            <div style="font-size:28px;font-weight:700;color:var(--admin-danger);">{{ $stats['failed_payments_today'] }}</div>
                            <div class="text-muted" style="font-size:13px;">{{ __('app.failed_payments') }} {{ __('app.today') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('jsCode')
<script>
var revenueData = @json($monthly_revenue);
var userData = @json($monthly_users);

var revChart = new ApexCharts(document.querySelector("#revenue-trend-chart"), {
    chart: { type: 'area', height: 300, fontFamily: 'Inter, sans-serif', toolbar: { show: false } },
    series: [{ name: 'Revenue (JOD)', data: revenueData.map(function(r) { return r.revenue; }) }],
    xaxis: { categories: revenueData.map(function(r) { return r.month; }), labels: { style: { fontSize: '11px' } } },
    yaxis: { labels: { formatter: function(val) { return val >= 1000 ? (val/1000).toFixed(0) + 'K' : val; }, style: { fontSize: '11px' } } },
    colors: ['#13585D'],
    fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05 } },
    stroke: { curve: 'smooth', width: 2 },
    grid: { borderColor: '#f1f3f5', strokeDashArray: 4 },
    dataLabels: { enabled: false },
    tooltip: { y: { formatter: function(val) { return val.toLocaleString() + ' JOD'; } } }
});
revChart.render();

var userChart = new ApexCharts(document.querySelector("#user-growth-chart"), {
    chart: { type: 'bar', height: 300, fontFamily: 'Inter, sans-serif', toolbar: { show: false } },
    series: [{ name: 'New Users', data: userData.map(function(u) { return u.count; }) }],
    xaxis: { categories: userData.map(function(u) { return u.month; }), labels: { style: { fontSize: '11px' } } },
    colors: ['#FECD0F'],
    plotOptions: { bar: { borderRadius: 6, columnWidth: '50%' } },
    grid: { borderColor: '#f1f3f5', strokeDashArray: 4 },
    dataLabels: { enabled: false }
});
userChart.render();
</script>
@endsection

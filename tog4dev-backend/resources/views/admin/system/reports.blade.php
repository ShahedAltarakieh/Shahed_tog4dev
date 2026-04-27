@extends('layouts.admin.show')

@section('title') {{ __('app.reports_center') }} @endsection

@section('content')
<div class="row mt-3 mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1" style="background:none;padding:0;">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="#">{{ __('app.system') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('app.reports_center') }}</li>
                    </ol>
                </nav>
                <h4 class="page-title mb-0">{{ __('app.reports_center') }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-kpi-card">
            <div class="card-body">
                <div class="d-flex align-items-center" style="gap:12px;">
                    <div class="kpi-icon success"><i class="fas fa-money-bill-wave"></i></div>
                    <div>
                        <div class="kpi-value" style="font-size:22px;">{{ number_format($totalRevenue, 0) }}</div>
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
                    <div class="kpi-icon primary"><i class="fas fa-users"></i></div>
                    <div>
                        <div class="kpi-value" style="font-size:22px;">{{ number_format($totalUsers) }}</div>
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
                    <div class="kpi-icon info"><i class="fas fa-receipt"></i></div>
                    <div>
                        <div class="kpi-value" style="font-size:22px;">{{ number_format($totalPayments) }}</div>
                        <div class="kpi-label">{{ __('app.total_transactions') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card dashboard-kpi-card">
            <div class="card-body">
                <div class="d-flex align-items-center" style="gap:12px;">
                    <div class="kpi-icon warning"><i class="fas fa-chart-line"></i></div>
                    <div>
                        <div class="kpi-value" style="font-size:22px;">{{ number_format($avgPayment, 2) }}</div>
                        <div class="kpi-label">{{ __('app.avg_payment') }} (JOD)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h5 class="header-title mb-3">{{ __('app.monthly_overview') }}</h5>
                <div id="monthly-overview-chart" class="apex-charts"></div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h5 class="header-title mb-3">{{ __('app.payment_methods') }}</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>{{ __('app.method') }}</th>
                                <th class="text-center">#</th>
                                <th class="text-right">{{ __('app.amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topPaymentMethods as $method)
                            <tr>
                                <td>
                                    <span class="badge badge-soft-primary">{{ ucfirst($method->method) }}</span>
                                </td>
                                <td class="text-center">{{ number_format($method->count) }}</td>
                                <td class="text-right" style="font-weight:600;">{{ number_format($method->total, 0) }} JOD</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="header-title mb-3">{{ __('app.monthly_breakdown') }}</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('app.month') }}</th>
                                <th class="text-center">{{ __('app.transactions') }}</th>
                                <th class="text-center">{{ __('app.new_users') }}</th>
                                <th class="text-right">{{ __('app.revenue') }} (JOD)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlyData as $row)
                            <tr>
                                <td style="font-weight:500;">{{ $row['month'] }}</td>
                                <td class="text-center">{{ number_format($row['transactions']) }}</td>
                                <td class="text-center">{{ number_format($row['users']) }}</td>
                                <td class="text-right" style="font-weight:600;color:var(--admin-primary);">{{ number_format($row['revenue'], 0) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('jsCode')
<script>
var monthlyData = @json($monthlyData);

var chart = new ApexCharts(document.querySelector("#monthly-overview-chart"), {
    chart: { type: 'line', height: 350, fontFamily: 'Inter, sans-serif', toolbar: { show: false } },
    series: [
        { name: 'Revenue (JOD)', type: 'column', data: monthlyData.map(function(d) { return d.revenue; }) },
        { name: 'Transactions', type: 'line', data: monthlyData.map(function(d) { return d.transactions; }) },
        { name: 'New Users', type: 'line', data: monthlyData.map(function(d) { return d.users; }) }
    ],
    xaxis: { categories: monthlyData.map(function(d) { return d.short; }) },
    yaxis: [
        { title: { text: 'Revenue (JOD)' }, labels: { formatter: function(val) { return val >= 1000 ? (val/1000).toFixed(0) + 'K' : val; } } },
        { opposite: true, title: { text: 'Count' } }
    ],
    colors: ['#13585D', '#FECD0F', '#3b82f6'],
    plotOptions: { bar: { borderRadius: 6, columnWidth: '40%' } },
    stroke: { width: [0, 3, 3], curve: 'smooth' },
    grid: { borderColor: '#f1f3f5', strokeDashArray: 4 },
    dataLabels: { enabled: false },
    legend: { position: 'top', fontFamily: 'Inter, sans-serif' },
    tooltip: { shared: true, intersect: false }
});
chart.render();
</script>
@endsection

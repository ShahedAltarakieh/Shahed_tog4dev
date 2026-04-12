@extends('layouts.admin.show')

@section('title') {{ __('app.home_page') }} @endsection

@section('cssCode')
<style>
    .dashboard-page {
        padding: 8px 0 40px;
    }

    .dashboard-welcome {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .dashboard-welcome h2 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #1a2332;
        margin: 0;
    }

    .dashboard-welcome h2 span {
        color: #13585D;
    }

    .dashboard-welcome .date-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f0f6f6;
        color: #13585D;
        font-size: 0.85rem;
        font-weight: 500;
        padding: 8px 16px;
        border-radius: 10px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    @media (max-width: 1400px) {
        .stats-grid { grid-template-columns: repeat(3, 1fr); }
    }

    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr; }
    }

    .stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px 22px;
        border: 1px solid #e8edf2;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #13585D, #1a8a8f);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(19, 88, 93, 0.12);
    }

    .stat-card:hover::before,
    .stat-card.active::before {
        opacity: 1;
    }

    .stat-card.active {
        border-color: #13585D;
        box-shadow: 0 4px 20px rgba(19, 88, 93, 0.1);
    }

    .stat-card .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .stat-card .stat-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: #6b7a8d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-card .stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
    }

    .stat-card .stat-icon.today { background: #e8f5e9; color: #2e7d32; }
    .stat-card .stat-icon.week { background: #e3f2fd; color: #1565c0; }
    .stat-card .stat-icon.month { background: #fff3e0; color: #e65100; }
    .stat-card .stat-icon.year { background: #f3e5f5; color: #7b1fa2; }
    .stat-card .stat-icon.all { background: #e0f2f1; color: #13585D; }

    .stat-card .stat-value {
        font-size: 1.65rem;
        font-weight: 800;
        color: #1a2332;
        margin-bottom: 8px;
        line-height: 1.2;
    }

    .stat-card .stat-value .currency {
        font-size: 0.85rem;
        font-weight: 600;
        color: #6b7a8d;
        margin-inline-start: 4px;
    }

    .stat-card .stat-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.78rem;
        color: #8896a4;
    }

    .stat-card .stat-change {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .stat-change.up { background: #e8f5e9; color: #2e7d32; }
    .stat-change.down { background: #ffebee; color: #c62828; }

    .stat-card .btn-download-stat {
        background: none;
        border: none;
        color: #b0bec5;
        font-size: 0.85rem;
        padding: 4px;
        cursor: pointer;
        transition: color 0.2s;
        line-height: 1;
    }

    .stat-card .btn-download-stat:hover {
        color: #13585D;
    }

    .custom-range-card {
        background: linear-gradient(135deg, #13585D 0%, #1a7a7f 100%);
        border-radius: 16px;
        padding: 22px 28px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        color: #fff;
    }

    .custom-range-card .range-left {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .custom-range-card .range-label {
        font-weight: 600;
        font-size: 0.95rem;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .custom-range-card .range-label i {
        font-size: 1.1rem;
        opacity: 0.8;
    }

    .custom-range-card #range-datepicker {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        color: #fff;
        border-radius: 10px;
        padding: 8px 14px;
        font-size: 0.88rem;
        width: 220px;
        transition: all 0.2s;
    }

    .custom-range-card #range-datepicker::placeholder {
        color: rgba(255,255,255,0.6);
    }

    .custom-range-card #range-datepicker:focus {
        background: rgba(255,255,255,0.25);
        border-color: rgba(255,255,255,0.5);
        outline: none;
        box-shadow: 0 0 0 3px rgba(255,255,255,0.1);
    }

    .custom-range-card .range-result {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .custom-range-card .range-value {
        font-size: 1.7rem;
        font-weight: 800;
    }

    .custom-range-card .range-value .currency {
        font-size: 0.9rem;
        font-weight: 500;
        opacity: 0.8;
    }

    .custom-range-card .btn-download-range {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.2);
        color: #fff;
        border-radius: 10px;
        padding: 8px 12px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .custom-range-card .btn-download-range:hover {
        background: rgba(255,255,255,0.25);
    }

    .chart-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e8edf2;
        margin-bottom: 20px;
        overflow: hidden;
        transition: box-shadow 0.3s;
    }

    .chart-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }

    .chart-card .chart-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px 0;
    }

    .chart-card .chart-card-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1a2332;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chart-card .chart-card-title .title-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #f0f6f6;
        color: #13585D;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
    }

    .chart-card .btn-download-chart {
        background: #f5f7fa;
        border: 1px solid #e2e8f0;
        color: #6b7a8d;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 0.78rem;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .chart-card .btn-download-chart:hover {
        background: #e8edf2;
        color: #13585D;
    }

    .chart-card .chart-card-body {
        padding: 16px 24px 24px;
    }

    .chart-card .info-note {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        background: #fafbfc;
        border: 1px solid #f0f2f5;
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 16px;
        font-size: 0.8rem;
        color: #6b7a8d;
        line-height: 1.5;
    }

    .chart-card .info-note i {
        color: #13585D;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .charts-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    @media (max-width: 992px) {
        .charts-row { grid-template-columns: 1fr; }
    }

    .influencer-card .table-wrapper {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e8edf2;
    }

    .influencer-card .table {
        margin-bottom: 0;
    }

    .influencer-card .table thead {
        background: #f5f7fa;
    }

    .influencer-card .table thead th {
        border: none;
        padding: 12px 16px;
        font-size: 0.78rem;
        font-weight: 700;
        color: #4a5568;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .influencer-card .table tbody td {
        padding: 12px 16px;
        font-size: 0.85rem;
        color: #2d3748;
        border-color: #f0f2f5;
        vertical-align: middle;
    }

    .influencer-card .table tbody tr:hover {
        background: #f9fafb;
    }

    .influencer-card .table tbody td a {
        color: #13585D;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s;
    }

    .influencer-card .table tbody td a:hover {
        color: #0d3d40;
    }

    .chart-card .nav-tabs {
        border-bottom: 2px solid #f0f2f5;
        padding: 0 24px;
        gap: 4px;
    }

    .chart-card .nav-tabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        padding: 10px 18px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #8896a4;
        border-radius: 0;
        transition: all 0.2s;
    }

    .chart-card .nav-tabs .nav-link:hover {
        color: #13585D;
        background: #f0f6f6;
    }

    .chart-card .nav-tabs .nav-link.active {
        color: #13585D;
        border-bottom-color: #13585D;
        background: transparent;
    }

    .chart-card .tab-content {
        padding: 0 24px 24px;
    }

    .validation-notes {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e8edf2;
        padding: 24px;
        margin-bottom: 20px;
    }

    .validation-notes h5 {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1a2332;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .validation-notes h5 i {
        color: #13585D;
    }

    .note-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .note-list li {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 0.84rem;
        color: #4a5568;
        line-height: 1.5;
        margin-bottom: 6px;
    }

    .note-list li:nth-child(odd) {
        background: #fafbfc;
    }

    .note-list li i {
        flex-shrink: 0;
        margin-top: 3px;
        font-size: 0.7rem;
    }

    .note-list li i.text-info { color: #13585D !important; }

    .note-list li strong {
        color: #1a2332;
    }

    .size-reference {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 12px;
        margin-top: 16px;
    }

    .size-ref-item {
        background: #f5f7fa;
        border-radius: 10px;
        padding: 14px 16px;
        text-align: center;
    }

    .size-ref-item .ref-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #8896a4;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .size-ref-item .ref-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: #13585D;
    }

    .dashboard-section-title {
        font-size: 0.78rem;
        font-weight: 700;
        color: #8896a4;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 16px;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .dashboard-section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e8edf2;
    }
</style>
@endsection

@section('content')

<div class="dashboard-page">

    <div class="dashboard-welcome">
        <h2>{{ __('app.dashboard') }} <span>{{ __('app.home_page') }}</span></h2>
        <div class="date-badge">
            <i class="far fa-calendar-alt"></i>
            {{ \Carbon\Carbon::now()->format('l, d M Y') }}
        </div>
    </div>

    <div class="dashboard-section-title">{{ __('app.payments') }}</div>

    <div class="stats-grid">
        <div class="stat-card {{ ($type == 1) ? 'active' : '' }}" onclick="location.href = '{{ route('dashboard', ['type' => 1, 'start_date' => $list_of_dates['today'], 'end_date' => $list_of_dates['today'] ]) }}';">
            <div class="stat-header">
                <span class="stat-label">{{ __('app.today') }}</span>
                <div class="stat-icon today">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
            <div class="stat-value">
                <span data-plugin="counterup">{{ number_format($payments_today["today"], 0) }}</span>
                <span class="currency">{{ __('app.currency')}}</span>
            </div>
            <div class="stat-footer">
                <span>{{ __('app.yesterday') }}: {{ number_format($payments_today["yesterday"], 0) }}{{ __('app.currency')}}</span>
                <span class="stat-change {{ $payments_today["percentage_change"] >= 0 ? 'up' : 'down' }}">
                    <i class="fa fa-caret-{{ $payments_today["percentage_change"] >= 0 ? 'up' : 'down' }}"></i>
                    {{ $payments_today["percentage_change"] }}%
                </span>
            </div>
            <button class="btn-download-stat btn-download-dashboard" data-type="payments" data-start="{{ $list_of_dates['today'] }}" data-end="{{ $list_of_dates['today'] }}" style="position:absolute;top:16px;right:16px;z-index:2;" onclick="event.stopPropagation();"><i class="fas fa-download"></i></button>
        </div>

        <div class="stat-card {{ ($type == 2) ? 'active' : '' }}" onclick="location.href = '{{ route('dashboard', ['type' => 2, 'start_date' => $list_of_dates['this_week_start'], 'end_date' => $list_of_dates['this_week_end'] ]) }}';">
            <div class="stat-header">
                <span class="stat-label">{{ __('app.this week') }}</span>
                <div class="stat-icon week">
                    <i class="fas fa-calendar-week"></i>
                </div>
            </div>
            <div class="stat-value">
                <span data-plugin="counterup">{{ number_format($payments_week["this_week"], 0) }}</span>
                <span class="currency">{{ __('app.currency')}}</span>
            </div>
            <div class="stat-footer">
                <span>{{ __('app.last week') }}: {{ number_format($payments_week["last_week"], 0) }}{{ __('app.currency')}}</span>
                <span class="stat-change {{ $payments_week["percentage_change"] >= 0 ? 'up' : 'down' }}">
                    <i class="fa fa-caret-{{ $payments_week["percentage_change"] >= 0 ? 'up' : 'down' }}"></i>
                    {{ $payments_week["percentage_change"] }}%
                </span>
            </div>
            <button class="btn-download-stat btn-download-dashboard" data-type="payments" data-start="{{ $list_of_dates['this_week_start'] }}" data-end="{{ $list_of_dates['this_week_end'] }}" style="position:absolute;top:16px;right:16px;z-index:2;" onclick="event.stopPropagation();"><i class="fas fa-download"></i></button>
        </div>

        <div class="stat-card {{ ($type == 4) ? 'active' : '' }}" onclick="location.href = '{{ route('dashboard', ['type' => 4, 'start_date' => $list_of_dates['this_month_start'], 'end_date' => $list_of_dates['this_month_end'] ]) }}';">
            <div class="stat-header">
                <span class="stat-label">{{ __('app.this month') }}</span>
                <div class="stat-icon month">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
            <div class="stat-value">
                <span data-plugin="counterup">{{ number_format($payments_month["this_month"], 0) }}</span>
                <span class="currency">{{ __('app.currency')}}</span>
            </div>
            <div class="stat-footer">
                <span>{{ __('app.last month') }}: {{ number_format($payments_month["last_month"], 0) }}{{ __('app.currency')}}</span>
                <span class="stat-change {{ $payments_month["percentage_change"] >= 0 ? 'up' : 'down' }}">
                    <i class="fa fa-caret-{{ $payments_month["percentage_change"] >= 0 ? 'up' : 'down' }}"></i>
                    {{ $payments_month["percentage_change"] }}%
                </span>
            </div>
            <button class="btn-download-stat btn-download-dashboard" data-type="payments" data-start="{{ $list_of_dates['this_month_start'] }}" data-end="{{ $list_of_dates['this_month_end'] }}" style="position:absolute;top:16px;right:16px;z-index:2;" onclick="event.stopPropagation();"><i class="fas fa-download"></i></button>
        </div>

        <div class="stat-card {{ ($type == 6) ? 'active' : '' }}" onclick="location.href = '{{ route('dashboard', ['type' => 6, 'start_date' => $list_of_dates['this_year_start'], 'end_date' => $list_of_dates['this_year_end'] ]) }}';">
            <div class="stat-header">
                <span class="stat-label">{{ __('app.this year') }}</span>
                <div class="stat-icon year">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div class="stat-value">
                <span data-plugin="counterup">{{ number_format($payments_year["this_year"], 0) }}</span>
                <span class="currency">{{ __('app.currency')}}</span>
            </div>
            <div class="stat-footer">
                <span>{{ __('app.last year') }}: {{ number_format($payments_year["last_year"], 0) }}{{ __('app.currency')}}</span>
                <span class="stat-change {{ $payments_year["percentage_change"] >= 0 ? 'up' : 'down' }}">
                    <i class="fa fa-caret-{{ $payments_year["percentage_change"] >= 0 ? 'up' : 'down' }}"></i>
                    {{ $payments_year["percentage_change"] }}%
                </span>
            </div>
            <button class="btn-download-stat btn-download-dashboard" data-type="payments" data-start="{{ $list_of_dates['this_year_start'] }}" data-end="{{ $list_of_dates['this_year_end'] }}" style="position:absolute;top:16px;right:16px;z-index:2;" onclick="event.stopPropagation();"><i class="fas fa-download"></i></button>
        </div>

        <div class="stat-card {{ ($type == 9) ? 'active' : '' }}" onclick="location.href = '{{ route('dashboard', ['type' => 9, 'start_date' => $firstStartDate, 'end_date' => $lastEndDate ]) }}';">
            <div class="stat-header">
                <span class="stat-label">{{ __('app.all payments') }}</span>
                <div class="stat-icon all">
                    <i class="fas fa-coins"></i>
                </div>
            </div>
            <div class="stat-value">
                <span data-plugin="counterup">{{ number_format($all_payments, 0) }}</span>
                <span class="currency">{{ __('app.currency')}}</span>
            </div>
            <div class="stat-footer">
                <span>&nbsp;</span>
            </div>
            <button class="btn-download-stat btn-download-dashboard" data-type="payments" data-start="{{ $firstStartDate }}" data-end="{{ $lastEndDate }}" style="position:absolute;top:16px;right:16px;z-index:2;" onclick="event.stopPropagation();"><i class="fas fa-download"></i></button>
        </div>
    </div>

    <div class="custom-range-card {{ ($type == -1) ? 'active' : '' }}">
        <div class="range-left">
            <span class="range-label">
                <i class="far fa-calendar-check"></i>
                {{ __('app.custom date') }}
            </span>
            <input type="text" id="range-datepicker" class="form-control not-readonly" placeholder="{{ __('app.from - to') }}">
        </div>
        <div class="range-result">
            <div class="range-value">
                <span data-plugin="counterup">{{ number_format($payments_custom_range, 0) }}</span>
                <span class="currency">{{ __('app.currency')}}</span>
            </div>
            <button class="btn-download-range btn-download-dashboard" data-type="payments" data-start="{{ $startDate }}" data-end="{{ $endDate }}"><i class="fas fa-download"></i></button>
        </div>
    </div>

    <div class="validation-notes">
        <h5><i class="fas fa-clipboard-check"></i> {{ __('app.dashboard') }} — {{ __('app.notes') }}</h5>
        <ul class="note-list">
            <li>
                <i class="fas fa-circle text-info"></i>
                <span><strong>{{ __('app.payments') }}:</strong> {{ __('app.all payments') }} {{ __('app.currency') }} — {{ __('app.today') }}, {{ __('app.this week') }}, {{ __('app.this month') }}, {{ __('app.this year') }}. {{ __('app.click') ?? 'Click' }} {{ __('app.any') ?? 'any' }} {{ __('app.card') ?? 'card' }} {{ __('app.to filter') ?? 'to filter' }}.</span>
            </li>
            <li>
                <i class="fas fa-circle text-info"></i>
                <span><strong>{{ __('app.custom date') }}:</strong> Use the date range picker above to filter all charts and tables to any custom period.</span>
            </li>
            <li>
                <i class="fas fa-circle text-info"></i>
                <span><strong>Export:</strong> Every section has a <i class="fas fa-download" style="color:#13585D"></i> download button to export data as Excel (.xlsx) files.</span>
            </li>
            <li>
                <i class="fas fa-circle text-info"></i>
                <span><strong>Charts:</strong> Hover over any bar or pie slice to see detailed tooltips with amounts, counts, and percentages.</span>
            </li>
            <li>
                <i class="fas fa-circle text-info"></i>
                <span><strong>Influencers:</strong> Click an influencer name to view their detailed payment history and performance breakdown.</span>
            </li>
        </ul>

        <div class="size-reference">
            <div class="size-ref-item">
                <div class="ref-label">{{ __('app.today') }}</div>
                <div class="ref-value">{{ number_format($payments_today["today"], 0) }} {{ __('app.currency') }}</div>
            </div>
            <div class="size-ref-item">
                <div class="ref-label">{{ __('app.this week') }}</div>
                <div class="ref-value">{{ number_format($payments_week["this_week"], 0) }} {{ __('app.currency') }}</div>
            </div>
            <div class="size-ref-item">
                <div class="ref-label">{{ __('app.this month') }}</div>
                <div class="ref-value">{{ number_format($payments_month["this_month"], 0) }} {{ __('app.currency') }}</div>
            </div>
            <div class="size-ref-item">
                <div class="ref-label">{{ __('app.this year') }}</div>
                <div class="ref-value">{{ number_format($payments_year["this_year"], 0) }} {{ __('app.currency') }}</div>
            </div>
            <div class="size-ref-item">
                <div class="ref-label">{{ __('app.all payments') }}</div>
                <div class="ref-value">{{ number_format($all_payments, 0) }} {{ __('app.currency') }}</div>
            </div>
        </div>
    </div>

    <div class="dashboard-section-title">{{ __('app.subscription_status') }} & {{ __('app.payment_methods') }}</div>

    <div class="charts-row">
        <div class="chart-card">
            <div class="chart-card-header">
                <h4 class="chart-card-title">
                    <span class="title-icon"><i class="fas fa-chart-pie"></i></span>
                    {{ __('app.subscription_status') }}
                </h4>
                <button class="btn-download-chart btn-download-dashboard" data-type="subscriptions" data-start="{{ $startDate }}" data-end="{{ $endDate }}">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
            <div class="chart-card-body">
                <div class="info-note">
                    <i class="fas fa-info-circle"></i>
                    <span>Active vs inactive subscription amounts for the selected date range. Each slice shows total JOD value.</span>
                </div>
                <div id="subscription-pie-chart" class="apex-charts"></div>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-card-header">
                <h4 class="chart-card-title">
                    <span class="title-icon"><i class="fas fa-credit-card"></i></span>
                    {{ __('app.payment_methods') }}
                </h4>
                <button class="btn-download-chart btn-download-dashboard" data-type="payment-method" data-start="{{ $startDate }}" data-end="{{ $endDate }}">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
            <div class="chart-card-body">
                <div class="info-note">
                    <i class="fas fa-info-circle"></i>
                    <span>Breakdown of payment methods by total amount (JOD) and unique user count.</span>
                </div>
                <div id="payment-methods-bar-chart" class="apex-charts"></div>
            </div>
        </div>
    </div>

    <div class="dashboard-section-title">{{ __('app.most 10 countries') }}</div>

    <div class="chart-card">
        <div class="chart-card-header">
            <h4 class="chart-card-title">
                <span class="title-icon"><i class="fas fa-globe-americas"></i></span>
                {{ __('app.most 10 countries') }}
            </h4>
            <button class="btn-download-chart btn-download-dashboard" data-type="countries" data-start="{{ $startDate }}" data-end="{{ $endDate }}">
                <i class="fas fa-download"></i> Export
            </button>
        </div>
        <div class="chart-card-body">
            <div class="info-note">
                <i class="fas fa-info-circle"></i>
                <span>Top 10 countries by total donation amount (JOD) with unique donor count. Based on payment user details.</span>
            </div>
            <div id="countries-bar-chart" class="apex-charts"></div>
        </div>
    </div>

    <div class="dashboard-section-title">{{ __('app.individual projects categories') }}</div>

    <div class="chart-card">
        <div class="chart-card-header">
            <h4 class="chart-card-title">
                <span class="title-icon"><i class="fas fa-project-diagram"></i></span>
                {{ __('app.individual projects categories') }}
            </h4>
            <button class="btn-download-chart btn-download-dashboard" data-type="projects" data-start="{{ $startDate }}" data-end="{{ $endDate }}">
                <i class="fas fa-download"></i> Export
            </button>
        </div>
        <div class="chart-card-body">
            <div class="info-note">
                <i class="fas fa-info-circle"></i>
                <span>Individual project categories with number of transactions and total amount collected.</span>
            </div>
            <div id="categories-bar-chart" class="apex-charts"></div>
        </div>
    </div>

    <div class="dashboard-section-title">{{ __('app.crowdfunding projects categories') }}</div>

    <div class="chart-card">
        <div class="chart-card-header">
            <h4 class="chart-card-title">
                <span class="title-icon"><i class="fas fa-hands-helping"></i></span>
                {{ __('app.crowdfunding projects categories') }}
            </h4>
            <button class="btn-download-chart btn-download-dashboard" data-type="projects" data-start="{{ $startDate }}" data-end="{{ $endDate }}">
                <i class="fas fa-download"></i> Export
            </button>
        </div>
        <div class="chart-card-body">
            <div class="info-note">
                <i class="fas fa-info-circle"></i>
                <span>Crowdfunding category breakdown with transaction counts and total collected amounts.</span>
            </div>
            <div id="crowdfunding-categories-bar-chart" class="apex-charts"></div>
        </div>
    </div>

    <div class="dashboard-section-title">{{ __('app.crowdfunding projects') }}</div>

    <div class="chart-card">
        <div class="chart-card-header">
            <h4 class="chart-card-title">
                <span class="title-icon"><i class="fas fa-bullseye"></i></span>
                {{ __('app.crowdfunding projects') }}
            </h4>
            <button class="btn-download-chart btn-download-dashboard" data-type="crowdfunding" data-start="{{ $startDate }}" data-end="{{ $endDate }}">
                <i class="fas fa-download"></i> Export
            </button>
        </div>
        <div class="chart-card-body">
            <div class="info-note">
                <i class="fas fa-info-circle"></i>
                <span>Individual crowdfunding projects by category. Hover each bar to see target, paid, remaining, and closure status. Switch tabs to view different categories.</span>
            </div>
        </div>
        <ul class="nav nav-tabs" id="categoryTabs" role="tablist">
            @foreach($categoryCrowdfundingTargets as $index => $category)
                <li class="nav-item" role="presentation">
                    <button 
                        class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                        id="tab-{{ $category['id'] }}" 
                        data-toggle="tab" 
                        data-target="#content-{{ $category['id'] }}" 
                        type="button" 
                        role="tab" 
                        aria-controls="content-{{ $category['id'] }}" 
                        aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                        {{ $category['title'] }}
                    </button>
                </li>
            @endforeach
        </ul>
        <div class="tab-content" id="categoryTabContent">
            @foreach($categoryCrowdfundingTargets as $index => $category)
                <div 
                    class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" 
                    id="content-{{ $category['id'] }}" 
                    role="tabpanel" 
                    aria-labelledby="tab-{{ $category['id'] }}">
                    <div id="chart-{{ $category['id'] }}" class="apex-chart"></div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="dashboard-section-title">{{ __('app.influencer_name') }}</div>

    <div class="chart-card influencer-card">
        <div class="chart-card-header">
            <h4 class="chart-card-title">
                <span class="title-icon"><i class="fas fa-user-tie"></i></span>
                {{ __('app.influencer_name') }}
            </h4>
            <button class="btn-download-chart btn-download-dashboard" data-type="influencers" data-start="{{ $startDate }}" data-end="{{ $endDate }}">
                <i class="fas fa-download"></i> Export
            </button>
        </div>
        <div class="chart-card-body">
            <div class="info-note">
                <i class="fas fa-info-circle"></i>
                <span>Influencer performance sorted by total amount. Click any name to view detailed payment history. Active/inactive subscription counts include their total JOD values.</span>
            </div>
            <div class="table-wrapper" style="max-height: 500px; overflow-y: auto;">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('app.influencer_name') }}</th>
                            <th>{{ __('app.active_subscriptions') }}</th>
                            <th>{{ __('app.inactive_subscriptions') }}</th>
                            <th>{{ __('app.number_of_transactions') }}</th>
                            <th>{{ __('app.one_time_total') }}</th>
                            <th>{{ __('app.subscription_total') }}</th>
                            <th>{{ __('app.grand_total_payment') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($influencers as $index => $influencer)
                            <tr>
                                <td style="color:#8896a4;font-weight:600;">{{ $index + 1 }}</td>
                                <td>
                                    <a href="{{ route('influencer.payments', $influencer['id']) }}">
                                        {{ $influencer['name'] }}
                                    </a>
                                </td>
                                <td>
                                    {{ $influencer['active_subscriptions'] }}
                                    <span style="color:#8896a4;font-size:0.78rem;">= {{ number_format($influencer['active_subscription_total'], 3) }} JOD</span>
                                </td>
                                <td>
                                    {{ $influencer['inactive_subscriptions'] }}
                                    <span style="color:#8896a4;font-size:0.78rem;">= {{ number_format($influencer['inactive_subscription_total'], 3) }} JOD</span>
                                </td>
                                <td><strong>{{ $influencer['number_of_transactions'] }}</strong></td>
                                <td>{{ number_format($influencer['one_time_total'], 3) }} JOD</td>
                                <td>{{ number_format($influencer['subscription_total'], 3) }} JOD</td>
                                <td><strong style="color:#13585D;">{{ number_format($influencer['total_amount'], 3) }} JOD</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection

@section("jsCode")
<script>
    function formatNumber(value) {
        if (value >= 1_000_000_000) {
            return (value / 1_000_000_000).toFixed(1).replace(/\.0$/, '') + 'B';
        }
        if (value >= 1_000_000) {
            return (value / 1_000_000).toFixed(1).replace(/\.0$/, '') + 'M';
        }
        if (value >= 1_000) {
            return (value / 1_000).toFixed(1).replace(/\.0$/, '') + 'K';
        }
        return value;
    }
</script>
@foreach($categoryCrowdfundingTargets as $index => $category)
<script>

    var transactionsData{{ $category['id'] }} = {!! $category['items']->pluck('total_transactions')->toJson() !!};
    var leftTargetData{{ $category['id'] }} = {!! $category['items']->pluck('left_target')->toJson() !!};
    var createdAtData{{ $category['id'] }} = {!! $category['items']->pluck('created_at')->toJson() !!};
    var targetData{{ $category['id'] }} = {!! $category['items']->pluck('amount')->toJson() !!};
    var isClosedData{{ $category['id'] }} = {!! $category['items']->pluck('is_closed')->toJson() !!};

    var options{{ $category['id'] }} = {
        series: [{
            name: '',
            data: {!! $category['items']->pluck('paid') !!}
        }],
        chart: {
            type: 'bar',
            height: 350,
            toolbar: {
                show: false
            },
            fontFamily: 'inherit'
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '50px',
                borderRadius: 6,
                borderRadiusApplication: 'end'
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
        show: true,
        width: 2,
            colors: ['transparent']
        },
        colors: ['#13585D', '#FECD0F'],
        xaxis: {
            categories: {!! $category['items']->map(function ($item) {
                $words = explode(' ', $item["title"]);
                $chunks = array_chunk($words, 3);
                return array_map(function ($chunk) {
                    return implode(' ', $chunk);
                }, $chunks);
            })->values()->toJson() !!}
        },
        yaxis: {
            title: {
                text: ''
            },
            labels: {
                formatter: function (val) {
                    return formatNumber(val);
                }
            }
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function(value, { dataPointIndex }) {                                        
                    return `
                        Target: ${targetData{{ $category['id'] }}[dataPointIndex]} JOD<br/>
                        Total Paid: ${value} JOD<br/>
                        Transactions: ${transactionsData{{ $category['id'] }}[dataPointIndex]} payments<br/>
                        Is closed: ${isClosedData{{ $category['id'] }}[dataPointIndex]} <br/>
                        Left Target: ${leftTargetData{{ $category['id'] }}[dataPointIndex]} JOD<br/>
                        Created At: ${createdAtData{{ $category['id'] }}[dataPointIndex]}
                    `;
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart-{{ $category['id'] }}"), options{{ $category['id'] }});
    chart.render();
</script>
@endforeach

    <script>
        $("#range-datepicker").flatpickr({
            "locale": "{{ app()->getLocale() }}",
            mode: "range",
            allowInput: true,
            dateFormat: "Y-m-d",
            defaultDate: ["{{ $startDate }}", "{{ $endDate }}"],
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    const startDate = instance.formatDate(selectedDates[0], "Y-m-d");
                    const endDate = instance.formatDate(selectedDates[1], "Y-m-d");
                    window.location.href = `/?type=-1&start_date=${startDate}&end_date=${endDate}`;
                }
            }
        });

        var subscriptionData = @json($subscriptionData);
        var pieOptions = {
            chart: {
                type: 'pie',
                height: 380,
                fontFamily: 'inherit'
            },
            dataLabels: {
                formatter: function (val, opts) {
                    return opts.w.config.series[opts.seriesIndex] + " JOD";
                },
                style: {
                    colors: ['#FFFFFF'],
                    fontSize: '13px',
                    fontWeight: 600
                }
            },
            series: [subscriptionData.activeSubscriptions, subscriptionData.inactiveSubscriptions],
            labels: subscriptionData.subscriptionLabels,
            colors: ['#13585D', '#3ca1a8'],
            legend: {
                position: 'bottom',
                fontSize: '13px'
            }
        };
        var pieChart = new ApexCharts(document.querySelector("#subscription-pie-chart"), pieOptions);
        pieChart.render();

        var options = {
            series: [{
                name: 'Amount',
                data: @json($paymentMethodData)
            },{
                name: 'Users',
                data: @json($paymentMethodUsers)
            }],
            chart: {
                type: 'bar',
                height: 380,
                toolbar: { show: false },
                fontFamily: 'inherit'
            },
            tooltip: {
                shared: true,
                intersect: false,
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 6,
                    borderRadiusApplication: 'end'
                },
            },
            dataLabels: { enabled: false },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            colors: ['#13585D', '#FECD0F'],
            xaxis: {
                categories: @json(
                    collect($paymentMethodCategories)->map(function ($item) {
                        return str_contains($item, ' ') ? explode(' ', $item) : $item;
                    })
                )
            },
            yaxis: {
                title: { text: '' },
                labels: {
                    formatter: function (val) {
                        return formatNumber(val);
                    }
                }
            },
            fill: { opacity: 1 }
        };

        var chart = new ApexCharts(document.querySelector("#payment-methods-bar-chart"), options);
        chart.render();
        
        var options = {
            series: [{
                name: 'Amount',
                data: @json($countryData)
            },{
                name: 'Users',
                data: @json($countryUsers)
            }],
            chart: {
                type: 'bar',
                height: 380,
                toolbar: { show: false },
                fontFamily: 'inherit'
            },
            tooltip: {
                shared: true,
                intersect: false,
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 6,
                    borderRadiusApplication: 'end'
                },
            },
            dataLabels: { enabled: false },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            colors: ['#13585D', '#FECD0F'],
            xaxis: {
                categories: @json(
                    collect($countryCategories)->map(function ($item) {
                        return str_contains($item, ' ') ? explode(' ', $item) : $item;
                    })
                )
            },
            yaxis: {
                title: { text: '' },
                labels: {
                    formatter: function (val) {
                        return formatNumber(val);
                    }
                }
            },
            fill: { opacity: 1 }
        };

        var chart = new ApexCharts(document.querySelector("#countries-bar-chart"), options);
        chart.render();

        var options = {
            series: [{
                name: '# of Transactions',
                data: @json($transactionsCategories)
            },{
                name: 'Total Amount',
                data: @json($totalAmountsCategories)
            }],
            chart: {
                type: 'bar',
                height: 380,
                toolbar: { show: false },
                fontFamily: 'inherit'
            },
            tooltip: {
                shared: true,
                intersect: false,
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '40px',
                    borderRadius: 6,
                    borderRadiusApplication: 'end'
                },
            },
            dataLabels: { enabled: false },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            colors: ['#13585D', '#FECD0F'],
            xaxis: {
                categories: @json($categoriesChart)
            },
            yaxis: {
                title: { text: '' },
                labels: {
                    formatter: function (val) {
                        return formatNumber(val);
                    }
                }
            },
            fill: { opacity: 1 }
        };

        var chart = new ApexCharts(document.querySelector("#categories-bar-chart"), options);
        chart.render();

        var options = {
            series: [{
                name: '# of Transactions',
                data: @json($transactionsCategoriesCrowd)
            },{
                name: 'Total Amount',
                data: @json($totalAmountsCategoriesCrowd)
            }],
            chart: {
                type: 'bar',
                height: 380,
                toolbar: { show: false },
                fontFamily: 'inherit'
            },
            tooltip: {
                shared: true,
                intersect: false,
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '40px',
                    borderRadius: 6,
                    borderRadiusApplication: 'end'
                },
            },
            dataLabels: { enabled: false },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            colors: ['#13585D', '#FECD0F'],
            xaxis: {
                categories: @json($categoriesChartCrowd)
            },
            yaxis: {
                title: { text: '' },
                labels: {
                    formatter: function (val) {
                        return formatNumber(val);
                    }
                }
            },
            fill: { opacity: 1 }
        };

        var chart = new ApexCharts(document.querySelector("#crowdfunding-categories-bar-chart"), options);
        chart.render();
    </script>
@endsection

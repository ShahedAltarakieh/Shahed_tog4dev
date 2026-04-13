@extends('layouts.admin.show')

@section('title') {{ __('app.home_page') }} @endsection

@section('content')

    <div class="row mt-2 mb-2">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:12px;">
                <div>
                    <h4 class="page-title mb-0" style="font-size:20px;font-weight:700;">{{ __('app.dashboard') }}</h4>
                    <p class="text-muted mb-0" style="font-size:13px;margin-top:2px;">{{ __('app.welcome') }}, {{ Auth::user()->username }}</p>
                </div>
                <div class="d-flex align-items-center" style="gap:10px;">
                    <div class="d-flex align-items-center" style="gap:8px; background:#fff; padding:6px 14px; border-radius:var(--admin-radius-sm); border:1px solid var(--admin-gray-200);">
                        <i class="fas fa-calendar-alt" style="color:var(--admin-gray-400);font-size:13px;"></i>
                        <input type="text" id="range-datepicker" class="border-0 bg-transparent not-readonly" style="width: 180px; font-size:13px;" placeholder="{{ __('app.from - to') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-xl-3">
            <a href="{{ route('dashboard', ['type' => 1, 'start_date' => $list_of_dates['today'], 'end_date' => $list_of_dates['today'] ]) }}" class="card dashboard-kpi-card {{ ($type == 1) ? 'active' : '' }}" style="text-decoration:none;display:block;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="kpi-icon primary">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <button class="btn btn-download-dashboard" aria-label="{{ __('app.download') }} {{ __('app.today') }}" data-type="payments" data-start="{{ $list_of_dates['today'] }}" data-end="{{ $list_of_dates['today'] }}" onclick="event.preventDefault();event.stopPropagation();"><i class="fas fa-download"></i></button>
                    </div>
                    <div class="kpi-value"><span data-plugin="counterup">{{ number_format($payments_today["today"], 0) }}</span><small style="font-size:14px;font-weight:500;color:var(--admin-gray-500);"> {{ __('app.currency')}}</small></div>
                    <div class="kpi-label mt-1 mb-2">{{ __('app.today') }}</div>
                    <div class="d-flex justify-content-between align-items-center" style="font-size:13px;">
                        <span class="text-muted">{{ __('app.yesterday') }}: {{ number_format($payments_today["yesterday"], 0) }}{{ __('app.currency')}}</span>
                        <span class="kpi-change {{ $payments_today['percentage_change'] >= 0 ? 'up' : 'down' }}">
                            <i class="fa {{ $payments_today['percentage_change'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                            {{ $payments_today["percentage_change"] }}%
                        </span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-xl-3">
            <a href="{{ route('dashboard', ['type' => 2, 'start_date' => $list_of_dates['this_week_start'], 'end_date' => $list_of_dates['this_week_end'] ]) }}" class="card dashboard-kpi-card {{ ($type == 2) ? 'active' : '' }}" style="text-decoration:none;display:block;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="kpi-icon success">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <button class="btn btn-download-dashboard" aria-label="{{ __('app.download') }} {{ __('app.this week') }}" data-type="payments" data-start="{{ $list_of_dates['this_week_start'] }}" data-end="{{ $list_of_dates['this_week_end'] }}" onclick="event.preventDefault();event.stopPropagation();"><i class="fas fa-download"></i></button>
                    </div>
                    <div class="kpi-value"><span data-plugin="counterup">{{ number_format($payments_week["this_week"], 0) }}</span><small style="font-size:14px;font-weight:500;color:var(--admin-gray-500);"> {{ __('app.currency')}}</small></div>
                    <div class="kpi-label mt-1 mb-2">{{ __('app.this week') }}</div>
                    <div class="d-flex justify-content-between align-items-center" style="font-size:13px;">
                        <span class="text-muted">{{ __('app.last week') }}: {{ number_format($payments_week["last_week"], 0) }}{{ __('app.currency')}}</span>
                        <span class="kpi-change {{ $payments_week['percentage_change'] >= 0 ? 'up' : 'down' }}">
                            <i class="fa {{ $payments_week['percentage_change'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                            {{ $payments_week["percentage_change"] }}%
                        </span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-xl-3">
            <a href="{{ route('dashboard', ['type' => 4, 'start_date' => $list_of_dates['this_month_start'], 'end_date' => $list_of_dates['this_month_end'] ]) }}" class="card dashboard-kpi-card {{ ($type == 4) ? 'active' : '' }}" style="text-decoration:none;display:block;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="kpi-icon warning">
                            <i class="fas fa-coins"></i>
                        </div>
                        <button class="btn btn-download-dashboard" aria-label="{{ __('app.download') }} {{ __('app.this month') }}" data-type="payments" data-start="{{ $list_of_dates['this_month_start'] }}" data-end="{{ $list_of_dates['this_month_end'] }}" onclick="event.preventDefault();event.stopPropagation();"><i class="fas fa-download"></i></button>
                    </div>
                    <div class="kpi-value"><span data-plugin="counterup">{{ number_format($payments_month["this_month"], 0) }}</span><small style="font-size:14px;font-weight:500;color:var(--admin-gray-500);"> {{ __('app.currency')}}</small></div>
                    <div class="kpi-label mt-1 mb-2">{{ __('app.this month') }}</div>
                    <div class="d-flex justify-content-between align-items-center" style="font-size:13px;">
                        <span class="text-muted">{{ __('app.last month') }}: {{ number_format($payments_month["last_month"], 0) }}{{ __('app.currency')}}</span>
                        <span class="kpi-change {{ $payments_month['percentage_change'] >= 0 ? 'up' : 'down' }}">
                            <i class="fa {{ $payments_month['percentage_change'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                            {{ $payments_month["percentage_change"] }}%
                        </span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-xl-3">
            <a href="{{ route('dashboard', ['type' => 6, 'start_date' => $list_of_dates['this_year_start'], 'end_date' => $list_of_dates['this_year_end'] ]) }}" class="card dashboard-kpi-card {{ ($type == 6) ? 'active' : '' }}" style="text-decoration:none;display:block;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="kpi-icon info">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <button class="btn btn-download-dashboard" aria-label="{{ __('app.download') }} {{ __('app.this year') }}" data-type="payments" data-start="{{ $list_of_dates['this_year_start'] }}" data-end="{{ $list_of_dates['this_year_end'] }}" onclick="event.preventDefault();event.stopPropagation();"><i class="fas fa-download"></i></button>
                    </div>
                    <div class="kpi-value"><span data-plugin="counterup">{{ number_format($payments_year["this_year"], 0) }}</span><small style="font-size:14px;font-weight:500;color:var(--admin-gray-500);"> {{ __('app.currency')}}</small></div>
                    <div class="kpi-label mt-1 mb-2">{{ __('app.this year') }}</div>
                    <div class="d-flex justify-content-between align-items-center" style="font-size:13px;">
                        <span class="text-muted">{{ __('app.last year') }}: {{ number_format($payments_year["last_year"], 0) }}{{ __('app.currency')}}</span>
                        <span class="kpi-change {{ $payments_year['percentage_change'] >= 0 ? 'up' : 'down' }}">
                            <i class="fa {{ $payments_year['percentage_change'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                            {{ $payments_year["percentage_change"] }}%
                        </span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-md-6 col-xl-3">
            <a href="{{ route('dashboard', ['type' => 9, 'start_date' => $firstStartDate, 'end_date' => $lastEndDate ]) }}" class="card dashboard-kpi-card {{ ($type == 9) ? 'active' : '' }}" style="text-decoration:none;display:block;">
                <div class="card-body" style="padding:20px 24px !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="kpi-label mb-1">{{ __('app.all payments') }}</div>
                            <div class="kpi-value" style="font-size:24px;"><span data-plugin="counterup">{{ number_format($all_payments, 0) }}</span><small style="font-size:13px;font-weight:500;color:var(--admin-gray-500);"> {{ __('app.currency')}}</small></div>
                        </div>
                        <div class="d-flex align-items-center" style="gap:8px;">
                            <button class="btn btn-download-dashboard" aria-label="{{ __('app.download') }} {{ __('app.all payments') }}" data-type="payments" data-start="{{ $firstStartDate }}" data-end="{{ $lastEndDate }}" onclick="event.preventDefault();event.stopPropagation();"><i class="fas fa-download"></i></button>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-xl-9">
            <div class="card" style="border:1px dashed var(--admin-gray-300) !important;">
                <div class="card-body" style="padding:16px 24px !important;">
                    <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap:12px;">
                        <span class="kpi-label" style="margin-bottom:0;">{{ __('app.custom date') }}</span>
                        <div class="d-flex align-items-center flex-grow-1 justify-content-center" style="gap:16px;">
                            <h3 class="mb-0" style="color:var(--admin-primary);font-weight:700;">
                                <span data-plugin="counterup">{{ number_format($payments_custom_range, 0) }}</span>
                                <small style="font-size:14px;font-weight:500;color:var(--admin-gray-500);">{{ __('app.currency')}}</small>
                            </h3>
                        </div>
                        <button class="btn btn-download-dashboard" data-type="payments" data-start="{{ $startDate }}" data-end="{{ $endDate }}"><i class="fas fa-download"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-kpi-card">
                <div class="card-body" style="padding:16px 20px !important;">
                    <div class="d-flex align-items-center" style="gap:12px;">
                        <div class="kpi-icon primary" style="width:40px;height:40px;font-size:16px;"><i class="fas fa-users"></i></div>
                        <div>
                            <div class="kpi-value" style="font-size:22px;">{{ number_format($totalUsers) }}</div>
                            <div class="kpi-label">{{ __('app.total_users') }}</div>
                        </div>
                    </div>
                    @if($newUsersThisWeek > 0)
                    <div style="margin-top:8px;font-size:12px;color:var(--admin-success);"><i class="fas fa-arrow-up"></i> +{{ $newUsersThisWeek }} {{ __('app.this week') }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-kpi-card">
                <div class="card-body" style="padding:16px 20px !important;">
                    <div class="d-flex align-items-center" style="gap:12px;">
                        <div class="kpi-icon info" style="width:40px;height:40px;font-size:16px;"><i class="fas fa-sync-alt"></i></div>
                        <div>
                            <div class="kpi-value" style="font-size:22px;">{{ number_format($activeSubscriptionsCount) }}</div>
                            <div class="kpi-label">{{ __('app.active_subscriptions') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-kpi-card">
                <div class="card-body" style="padding:16px 20px !important;">
                    <div class="d-flex align-items-center" style="gap:12px;">
                        <div class="kpi-icon success" style="width:40px;height:40px;font-size:16px;"><i class="fas fa-newspaper"></i></div>
                        <div>
                            <div class="kpi-value" style="font-size:22px;">{{ number_format($totalNews) }}</div>
                            <div class="kpi-label">{{ __('app.news_published') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card dashboard-kpi-card">
                <div class="card-body" style="padding:16px 20px !important;">
                    <div class="d-flex align-items-center" style="gap:12px;">
                        <div class="kpi-icon {{ $pendingContacts > 0 ? 'warning' : 'success' }}" style="width:40px;height:40px;font-size:16px;"><i class="fas fa-{{ $pendingContacts > 0 ? 'exclamation-triangle' : 'check-circle' }}"></i></div>
                        <div>
                            <div class="kpi-value" style="font-size:22px;">{{ $pendingContacts }}</div>
                            <div class="kpi-label">{{ __('app.pending_requests') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body" style="padding:20px 24px !important;">
                    <h6 style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:1px;color:var(--admin-gray-400);margin-bottom:16px;">{{ __('app.quick_actions') }}</h6>
                    <div class="quick-actions-grid">
                        <a href="{{ route('news-admin.create') }}" class="quick-action-btn">
                            <i class="fas fa-newspaper"></i>
                            <span>{{ __('app.add new') }} {{ __('app.news') }}</span>
                        </a>
                        <a href="{{ route('gallery-admin.photos.create') }}" class="quick-action-btn">
                            <i class="fas fa-images"></i>
                            <span>{{ __('app.upload_photo') }}</span>
                        </a>
                        <a href="{{ route('gallery-admin.videos.create') }}" class="quick-action-btn">
                            <i class="fas fa-video"></i>
                            <span>{{ __('app.upload_video') }}</span>
                        </a>
                        <a href="{{ route('payments.index') }}" class="quick-action-btn">
                            <i class="fas fa-credit-card"></i>
                            <span>{{ __('app.payments') }}</span>
                        </a>
                        <a href="{{ route('users.index') }}" class="quick-action-btn">
                            <i class="fas fa-users"></i>
                            <span>{{ __('app.users') }}</span>
                        </a>
                        <a href="{{ route('system.reports') }}" class="quick-action-btn">
                            <i class="fas fa-chart-pie"></i>
                            <span>{{ __('app.reports_center') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body" style="padding:20px 24px !important;">
                    <h6 style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:1px;color:var(--admin-gray-400);margin-bottom:16px;">{{ __('app.operations_panel') }}</h6>
                    <div class="operations-list">
                        @if($pendingContacts > 0)
                        <a href="{{ route('contact_us.index', ['type' => 'projects']) }}" class="operation-item warning">
                            <div class="d-flex align-items-center" style="gap:10px;">
                                <i class="fas fa-envelope" style="font-size:14px;"></i>
                                <span>{{ $pendingContacts }} {{ __('app.unread_messages') }}</span>
                            </div>
                            <i class="fas fa-chevron-right" style="font-size:10px;color:var(--admin-gray-400);"></i>
                        </a>
                        @endif
                        @if($failedPaymentsWeek > 0)
                        <a href="{{ route('payments.index') }}" class="operation-item danger">
                            <div class="d-flex align-items-center" style="gap:10px;">
                                <i class="fas fa-exclamation-circle" style="font-size:14px;"></i>
                                <span>{{ $failedPaymentsWeek }} {{ __('app.failed_payments_week') }}</span>
                            </div>
                            <i class="fas fa-chevron-right" style="font-size:10px;color:var(--admin-gray-400);"></i>
                        </a>
                        @endif
                        <a href="{{ route('system.notifications') }}" class="operation-item info">
                            <div class="d-flex align-items-center" style="gap:10px;">
                                <i class="fas fa-bell" style="font-size:14px;"></i>
                                <span>{{ __('app.view_notifications') }}</span>
                            </div>
                            <i class="fas fa-chevron-right" style="font-size:10px;color:var(--admin-gray-400);"></i>
                        </a>
                        <a href="{{ route('system.health') }}" class="operation-item success">
                            <div class="d-flex align-items-center" style="gap:10px;">
                                <i class="fas fa-heartbeat" style="font-size:14px;"></i>
                                <span>{{ __('app.system_health') }}</span>
                            </div>
                            <i class="fas fa-chevron-right" style="font-size:10px;color:var(--admin-gray-400);"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="header-title mb-0">{{ __('app.subscription_status') }}</h5>
                        <button class="btn btn-download-dashboard" aria-label="{{ __('app.download') }} {{ __('app.subscription_status') }}" data-type="subscriptions" data-start="{{ $startDate }}" data-end="{{ $endDate }}"><i class="fas fa-download"></i></button>
                    </div>
                    <div id="subscription-pie-chart" class="apex-charts"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="header-title mb-0">{{ __('app.payment_methods') }}</h5>
                        <button class="btn btn-download-dashboard" aria-label="{{ __('app.download') }} {{ __('app.payment_methods') }}" data-type="payment-method" data-start="{{ $startDate }}" data-end="{{ $endDate }}"><i class="fas fa-download"></i></button>
                    </div>
                    <div id="payment-methods-bar-chart" class="apex-charts"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="header-title mb-0">{{ __('app.most 10 countries') }}</h5>
                        <button class="btn btn-download-dashboard" aria-label="{{ __('app.download') }} {{ __('app.most 10 countries') }}" data-type="countries" data-start="{{ $startDate }}" data-end="{{ $endDate }}"><i class="fas fa-download"></i></button>
                    </div>
                    <div id="countries-bar-chart" class="apex-charts"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="header-title mb-0">{{ __('app.individual projects categories') }}</h5>
                        <button class="btn btn-download-dashboard" data-type="projects" data-start="{{ $startDate }}" data-end="{{ $endDate }}"><i class="fas fa-download"></i></button>
                    </div>
                    <div id="categories-bar-chart" class="apex-charts"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="header-title mb-0">{{ __('app.crowdfunding projects categories') }}</h5>
                        <button class="btn btn-download-dashboard" data-type="projects" data-start="{{ $startDate }}" data-end="{{ $endDate }}"><i class="fas fa-download"></i></button>
                    </div>
                    <div id="crowdfunding-categories-bar-chart" class="apex-charts"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="header-title mb-0">{{ __('app.crowdfunding projects') }}</h5>
                        <button class="btn btn-download-dashboard" data-type="crowdfunding" data-start="{{ $startDate }}" data-end="{{ $endDate }}"><i class="fas fa-download"></i></button>
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
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="header-title mb-0">{{ __('app.influencer_name') }}</h5>
                        <button class="btn btn-download-dashboard" aria-label="{{ __('app.download') }} {{ __('app.influencer_name') }}" data-type="influencers" data-start="{{ $startDate }}" data-end="{{ $endDate }}"><i class="fas fa-download"></i></button>
                    </div>
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm">
                            <thead>
                                <tr>
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
                                @foreach ($influencers as $influencer)
                                    <tr>
                                        <td>
                                            <a href="{{ route('influencer.payments', $influencer['id']) }}" style="color:var(--admin-primary);font-weight:500;">
                                                {{ $influencer['name'] }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge badge-soft-success">{{ $influencer['active_subscriptions'] }}</span>
                                            {{ number_format($influencer['active_subscription_total'], 3) }} JOD
                                        </td>
                                        <td>
                                            <span class="badge badge-soft-danger">{{ $influencer['inactive_subscriptions'] }}</span>
                                            {{ number_format($influencer['inactive_subscription_total'], 3) }} JOD
                                        </td>
                                        <td>{{ $influencer['number_of_transactions'] }}</td>
                                        <td>{{ number_format($influencer['one_time_total'], 3) }} JOD</td>
                                        <td>{{ number_format($influencer['subscription_total'], 3) }} JOD</td>
                                        <td><strong>{{ number_format($influencer['total_amount'], 3) }} JOD</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="header-title mb-0">{{ __('app.recent_activity_feed') }}</h5>
                        <a href="{{ route('system.activity-logs') }}" class="text-primary" style="font-size:13px;">{{ __('app.view_all') }} <i class="fas fa-arrow-right" style="font-size:10px;"></i></a>
                    </div>
                    <div class="activity-timeline" style="max-height:400px;overflow-y:auto;">
                        @foreach($recentPayments as $p)
                        <div class="activity-item">
                            <div class="activity-icon success"><i class="fas fa-credit-card"></i></div>
                            <div class="activity-content">
                                <h6 class="mb-0" style="font-size:13px;font-weight:600;">{{ number_format($p->amount, 2) }} JOD</h6>
                                <p class="text-muted mb-0" style="font-size:12px;">{{ $p->payment_type ?? 'Payment' }} &middot; {{ $p->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                        @foreach($recentUsers as $u)
                        <div class="activity-item">
                            <div class="activity-icon primary"><i class="fas fa-user-plus"></i></div>
                            <div class="activity-content">
                                <h6 class="mb-0" style="font-size:13px;font-weight:600;">{{ $u->first_name }} {{ $u->last_name }}</h6>
                                <p class="text-muted mb-0" style="font-size:12px;">{{ __('app.new_user_registered') }} &middot; {{ $u->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
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

    var chartDefaults = {
        chart: {
            toolbar: { show: false },
            fontFamily: 'Inter, sans-serif'
        },
        colors: ['#13585D', '#FECD0F'],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '50px',
                borderRadius: 8,
                borderRadiusApplication: 'end'
            }
        },
        dataLabels: { enabled: false },
        stroke: { show: true, width: 2, colors: ['transparent'] },
        grid: {
            borderColor: '#f1f3f5',
            strokeDashArray: 4,
            padding: { left: 0, right: 0 }
        },
        yaxis: {
            labels: {
                formatter: function (val) { return formatNumber(val); },
                style: { colors: '#9ca3af', fontSize: '12px' }
            }
        },
        xaxis: {
            labels: { style: { colors: '#6b7280', fontSize: '12px' } }
        },
        tooltip: {
            shared: true,
            intersect: false,
            style: { fontSize: '13px' }
        },
        fill: { opacity: 1 }
    };
</script>
@foreach($categoryCrowdfundingTargets as $index => $category)
<script>
    var transactionsData{{ $category['id'] }} = {!! $category['items']->pluck('total_transactions')->toJson() !!};
    var leftTargetData{{ $category['id'] }} = {!! $category['items']->pluck('left_target')->toJson() !!};
    var createdAtData{{ $category['id'] }} = {!! $category['items']->pluck('created_at')->toJson() !!};
    var targetData{{ $category['id'] }} = {!! $category['items']->pluck('amount')->toJson() !!};
    var isClosedData{{ $category['id'] }} = {!! $category['items']->pluck('is_closed')->toJson() !!};

    var options{{ $category['id'] }} = Object.assign({}, chartDefaults, {
        series: [{
            name: '',
            data: {!! $category['items']->pluck('paid') !!}
        }],
        chart: Object.assign({}, chartDefaults.chart, {
            type: 'bar',
            height: 350
        }),
        xaxis: {
            categories: {!! $category['items']->map(function ($item) {
                $words = explode(' ', $item["title"]);
                $chunks = array_chunk($words, 3);
                return array_map(function ($chunk) {
                    return implode(' ', $chunk);
                }, $chunks);
            })->values()->toJson() !!},
            labels: chartDefaults.xaxis.labels
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
    });

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
                    var baseUrl = '{{ route('dashboard') }}';
                    window.location.href = baseUrl + '?type=-1&start_date=' + startDate + '&end_date=' + endDate;
                }
            }
        });

        var subscriptionData = @json($subscriptionData);
        var pieOptions = {
            chart: {
                type: 'pie',
                height: 350,
                fontFamily: 'Inter, sans-serif'
            },
            dataLabels: {
                formatter: function (val, opts) {
                    return opts.w.config.series[opts.seriesIndex] + " JOD";
                },
                style: { colors: ['#FFFFFF'], fontSize: '13px' }
            },
            series: [subscriptionData.activeSubscriptions, subscriptionData.inactiveSubscriptions],
            labels: subscriptionData.subscriptionLabels,
            colors: ['#13585D', '#3ca1a8'],
            stroke: { width: 2, colors: ['#fff'] },
            legend: {
                position: 'bottom',
                fontFamily: 'Inter, sans-serif',
                fontSize: '13px'
            }
        };
        var pieChart = new ApexCharts(document.querySelector("#subscription-pie-chart"), pieOptions);
        pieChart.render();

        var paymentBarOptions = Object.assign({}, chartDefaults, {
            series: [{
                name: 'Amount',
                data: @json($paymentMethodData)
            },{
                name: 'Users',
                data: @json($paymentMethodUsers)
            }],
            chart: Object.assign({}, chartDefaults.chart, {
                type: 'bar',
                height: 350
            }),
            xaxis: {
                categories: @json(
                    collect($paymentMethodCategories)->map(function ($item) {
                        return str_contains($item, ' ') ? explode(' ', $item) : $item;
                    })
                ),
                labels: chartDefaults.xaxis.labels
            }
        });
        var chart = new ApexCharts(document.querySelector("#payment-methods-bar-chart"), paymentBarOptions);
        chart.render();
        
        var countryBarOptions = Object.assign({}, chartDefaults, {
            series: [{
                name: 'Amount',
                data: @json($countryData)
            },{
                name: 'Users',
                data: @json($countryUsers)
            }],
            chart: Object.assign({}, chartDefaults.chart, {
                type: 'bar',
                height: 350
            }),
            xaxis: {
                categories: @json(
                    collect($countryCategories)->map(function ($item) {
                        return str_contains($item, ' ') ? explode(' ', $item) : $item;
                    })
                ),
                labels: chartDefaults.xaxis.labels
            }
        });
        var chart = new ApexCharts(document.querySelector("#countries-bar-chart"), countryBarOptions);
        chart.render();

        var catBarOptions = Object.assign({}, chartDefaults, {
            series: [{
                name: '# of Transactions',
                data: @json($transactionsCategories)
            },{
                name: 'Total Amount',
                data: @json($totalAmountsCategories)
            }],
            chart: Object.assign({}, chartDefaults.chart, {
                type: 'bar',
                height: 350
            }),
            plotOptions: {
                bar: { horizontal: false, columnWidth: '40px', borderRadius: 8, borderRadiusApplication: 'end' }
            },
            xaxis: {
                categories: @json($categoriesChart),
                labels: chartDefaults.xaxis.labels
            }
        });
        var chart = new ApexCharts(document.querySelector("#categories-bar-chart"), catBarOptions);
        chart.render();

        var crowdBarOptions = Object.assign({}, chartDefaults, {
            series: [{
                name: '# of Transactions',
                data: @json($transactionsCategoriesCrowd)
            },{
                name: 'Total Amount',
                data: @json($totalAmountsCategoriesCrowd)
            }],
            chart: Object.assign({}, chartDefaults.chart, {
                type: 'bar',
                height: 350
            }),
            plotOptions: {
                bar: { horizontal: false, columnWidth: '40px', borderRadius: 8, borderRadiusApplication: 'end' }
            },
            xaxis: {
                categories: @json($categoriesChartCrowd),
                labels: chartDefaults.xaxis.labels
            }
        });
        var chart = new ApexCharts(document.querySelector("#crowdfunding-categories-bar-chart"), crowdBarOptions);
        chart.render();
    </script>
@endsection

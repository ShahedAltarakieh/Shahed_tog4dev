@extends('layouts.admin.show')

@section('title') {{ __('app.home_page') }} @endsection

@section('content')

    <div class="row mt-3">
        <div class="col-10 mb-3 d-none">
                <ul class="nav nav-pills navtab-bg nav-justified">
                    <li class="nav-item">
                        <a href="{{ route('dashboard', ['type' => 1, 'start_date' => $list_of_dates['today'], 'end_date' => $list_of_dates['today'] ]) }}" data-bs-toggle="tab" class="nav-link {{ ($type == 1) ? 'active' : '' }}">
                            {{ __('app.today') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dashboard', ['type' => 8, 'start_date' => $list_of_dates['yesterday'], 'end_date' => $list_of_dates['yesterday'] ]) }}" data-bs-toggle="tab" class="nav-link {{ ($type == 8) ? 'active' : '' }}">
                            {{ __('app.yesterday') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dashboard', ['type' => 2, 'start_date' => $list_of_dates['this_week_start'], 'end_date' => $list_of_dates['this_week_end'] ]) }}" data-bs-toggle="tab" class="nav-link {{ ($type == 2) ? 'active' : '' }}">
                            {{ __('app.this week') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dashboard', ['type' => 3, 'start_date' => $list_of_dates['last_week_start'], 'end_date' => $list_of_dates['last_week_end'] ]) }}" data-bs-toggle="tab" class="nav-link {{ ($type == 3) ? 'active' : '' }}">
                            {{ __('app.last week') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dashboard', ['type' => 4, 'start_date' => $list_of_dates['this_month_start'], 'end_date' => $list_of_dates['this_month_end'] ]) }}" data-bs-toggle="tab" class="nav-link {{ ($type == 4) ? 'active' : '' }}">
                            {{ __('app.this month') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dashboard', ['type' => 5, 'start_date' => $list_of_dates['last_month_start'], 'end_date' => $list_of_dates['last_month_end'] ]) }}" data-bs-toggle="tab" class="nav-link {{ ($type == 5) ? 'active' : '' }}">
                            {{ __('app.last month') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dashboard', ['type' => 6, 'start_date' => $list_of_dates['this_year_start'], 'end_date' => $list_of_dates['this_year_end'] ]) }}" data-bs-toggle="tab" class="nav-link {{ ($type == 6) ? 'active' : '' }}">
                            {{ __('app.this year') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('dashboard', ['type' => 7, 'start_date' => $list_of_dates['last_year_start'], 'end_date' => $list_of_dates['last_year_end'] ]) }}" data-bs-toggle="tab" class="nav-link {{ ($type == 7) ? 'active' : '' }}">
                            {{ __('app.last year') }}
                        </a>
                    </li>
                </ul>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card cursor-pointer {{ ($type == 1) ? 'active' : '' }}" id="tooltip-container" onclick="location.href = '{{ route('dashboard', ['type' => 1, 'start_date' => $list_of_dates['today'], 'end_date' => $list_of_dates['today'] ]) }}';">
                <div class="card-body">
                    <h4 class="mt-0 font-16 d-flex justify-content-between align-items-center">
                        {{ __('app.today') }}
                        <button class="btn btn-light btn-download-dashboard" data-type="payments" data-start="{{ $list_of_dates['today'] }}" data-end="{{ $list_of_dates['today'] }}"><i class="fas fa-download"></i></button>
                    </h4>
                    <h2 class="text-primary my-3 text-center"><span data-plugin="counterup">{{ number_format($payments_today["today"], 0) }}</span>{{ __('app.currency')}}</h2>
                    <p class="text-muted mb-0 d-flex justify-content-between">
                        <span>{{ __('app.yesterday') }}: {{ number_format($payments_today["yesterday"], 0) }}{{ __('app.currency')}} </span>
                        <span>
                            @if($payments_today["percentage_change"] >= 0)
                                <i class="fa fa-caret-up text-success me-1"></i>
                                @else($payments_today["percentage_change"] < 0)
                                <i class="fa fa-caret-down text-danger me-1"></i>
                            @endif
                            {{ $payments_today["percentage_change"] }}%
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card cursor-pointer {{ ($type == 2) ? 'active' : '' }}" id="tooltip-container" onclick="location.href = '{{ route('dashboard', ['type' => 2, 'start_date' => $list_of_dates['this_week_start'], 'end_date' => $list_of_dates['this_week_end'] ]) }}';">
                <div class="card-body">
                    <h4 class="mt-0 font-16 d-flex justify-content-between align-items-center">
                        {{ __('app.this week') }}
                        <button class="btn btn-light btn-download-dashboard" data-type="payments" data-start="{{ $list_of_dates['this_week_start'] }}" data-end="{{ $list_of_dates['this_week_end'] }}"><i class="fas fa-download"></i></button>
                    </h4>
                    <h2 class="text-primary my-3 text-center"><span data-plugin="counterup">{{ number_format($payments_week["this_week"], 0) }}</span>{{ __('app.currency')}}</h2>
                    <p class="text-muted mb-0 d-flex justify-content-between">
                        <span>{{ __('app.last week') }}: {{ number_format($payments_week["last_week"], 0) }}{{ __('app.currency')}} </span>
                        <span>
                            @if($payments_week["percentage_change"] >= 0)
                                <i class="fa fa-caret-up text-success me-1"></i>
                                @else($payments_week["percentage_change"] < 0)
                                <i class="fa fa-caret-down text-danger me-1"></i>
                            @endif
                            {{ $payments_week["percentage_change"] }}%
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card cursor-pointer {{ ($type == 4) ? 'active' : '' }}" id="tooltip-container" onclick="location.href = '{{ route('dashboard', ['type' => 4, 'start_date' => $list_of_dates['this_month_start'], 'end_date' => $list_of_dates['this_month_end'] ]) }}';">
                <div class="card-body">
                    <h4 class="mt-0 font-16 d-flex justify-content-between align-items-center">
                        {{ __('app.this month') }}
                        <button class="btn btn-light btn-download-dashboard" data-type="payments" data-start="{{ $list_of_dates['this_month_start'] }}" data-end="{{ $list_of_dates['this_month_end'] }}"><i class="fas fa-download"></i></button>
                    </h4>
                    <h2 class="text-primary my-3 text-center"><span data-plugin="counterup">{{ number_format($payments_month["this_month"], 0) }}</span>{{ __('app.currency')}}</h2>
                    <p class="text-muted mb-0 d-flex justify-content-between">
                        <span>{{ __('app.last month') }}: {{ number_format($payments_month["last_month"], 0) }}{{ __('app.currency')}} </span>
                        <span>
                            @if($payments_month["percentage_change"] >= 0)
                                <i class="fa fa-caret-up text-success me-1"></i>
                                @else($payments_month["percentage_change"] < 0)
                                <i class="fa fa-caret-down text-danger me-1"></i>
                            @endif
                            {{ $payments_month["percentage_change"] }}%
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card cursor-pointer {{ ($type == 6) ? 'active' : '' }}" id="tooltip-container" onclick="location.href = '{{ route('dashboard', ['type' => 6, 'start_date' => $list_of_dates['this_year_start'], 'end_date' => $list_of_dates['this_year_end'] ]) }}';">
                <div class="card-body">
                    <h4 class="mt-0 font-16 d-flex justify-content-between align-items-center">
                        {{ __('app.this year') }}
                        <button class="btn btn-light btn-download-dashboard" data-type="payments" data-start="{{ $list_of_dates['this_year_start'] }}" data-end="{{ $list_of_dates['this_year_end'] }}"><i class="fas fa-download"></i></button>
                    </h4>
                    <h2 class="text-primary my-3 text-center"><span data-plugin="counterup">{{ number_format($payments_year["this_year"], 0) }}</span>{{ __('app.currency')}}</h2>
                    <p class="text-muted mb-0 d-flex justify-content-between">
                        <span>{{ __('app.last year') }}: {{ number_format($payments_year["last_year"], 0) }}{{ __('app.currency')}} </span>
                        <span>
                            @if($payments_year["percentage_change"] >= 0)
                                <i class="fa fa-caret-up text-success me-1"></i>
                                @else($payments_year["percentage_change"] < 0)
                                <i class="fa fa-caret-down text-danger me-1"></i>
                            @endif
                            {{ $payments_year["percentage_change"] }}%
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card cursor-pointer {{ ($type == 9) ? 'active' : '' }}" id="tooltip-container" onclick="location.href = '{{ route('dashboard', ['type' => 9, 'start_date' => $firstStartDate, 'end_date' => $lastEndDate ]) }}';">
                <div class="card-body">
                    <h4 class="mt-0 font-16 d-flex justify-content-between align-items-center">
                        {{ __('app.all payments') }}
                        <button class="btn btn-light btn-download-dashboard" data-type="payments" data-start="{{ $firstStartDate }}" data-end="{{ $lastEndDate }}"><i class="fas fa-download"></i></button>
                    </h4>
                    <h2 class="text-primary my-0 text-center"><span data-plugin="counterup">{{ number_format($all_payments, 0) }}</span>{{ __('app.currency')}}</h2>
                </div>
            </div>
        </div>


        <div class="col-md-9">
            <div class="card cursor-pointer {{ ($type == -1) ? 'active' : '' }}" id="tooltip-container">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="row w-100">
                        <div class="col-md-6 d-flex align-items-center justify-content-between">
                            <h4 class="mt-0 font-16 d-flex align-items-center mb-0" style="gap:16px">
                                <span class="me-2" style="white-space: nowrap;">{{ __('app.custom date') }}</span>
                                <input type="text" id="range-datepicker" class="form-control not-readonly" style="width: 200px;" placeholder="{{ __('app.from - to') }}">
                            </h4>
                        </div>
                        <div class="col-md-6 d-flex align-items-center justify-content-between">
                            <h2 class="text-primary my-3 text-center">
                                <span data-plugin="counterup">
                                    {{ number_format($payments_custom_range, 0) }}
                                </span>
                                {{ __('app.currency')}}
                            </h2>
                            <button class="btn btn-light btn-download-dashboard" data-type="payments" data-start="{{ $startDate }}" data-end="{{ $endDate }}"><i class="fas fa-download"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <!-- Pie Chart for Subscription Status -->
        <div class="col-xl-6 mt-3">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="header-title mb-0 d-flex justify-content-between align-items-center">
                        {{ __('app.subscription_status') }}
                        <button class="btn btn-light btn-download-dashboard" data-type="subscriptions" data-start="{{ $startDate }}" data-end="{{ $endDate }}"><i class="fas fa-download"></i></button>
                    </h4>
                    <div id="subscription-pie-chart" class="apex-charts pt-3"></div>
                </div>
            </div>
        </div>

        <!-- Bar Chart for Payment Methods -->
        <div class="col-xl-6 mt-3">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="header-title mb-0 d-flex justify-content-between align-items-center">
                        {{ __('app.payment_methods') }}
                        <button class="btn btn-light btn-download-dashboard" data-type="payment-method" data-start="{{ $startDate }}" data-end="{{ $endDate }}"><i class="fas fa-download"></i></button>
                    </h4>
                    <div id="payment-methods-bar-chart" class="apex-charts pt-3"></div>
                </div>
            </div>
        </div>

        <!-- Countries Table -->
        <div class="col-xl-12">
            <div class="card mt-3">
                <div class="card-body">
                    <h4 class="header-title mb-0 d-flex justify-content-between align-items-center">
                        {{ __('app.most 10 countries') }}
                        <button class="btn btn-light btn-download-dashboard" data-type="countries" data-start="{{ $startDate }}" data-end="{{ $endDate }}"><i class="fas fa-download"></i></button>
                    </h4>
                    <div id="countries-bar-chart" class="apex-charts pt-3"></div>
                </div>
            </div>
        </div>

        <!-- Projects Categories Table -->
        <div class="col-xl-12">
            <div class="card mt-3">
                <div class="card-body">
                    <h4 class="header-title mb-0 d-flex justify-content-between align-items-center">
                        {{ __('app.individual projects categories') }}
                        <button class="btn btn-light btn-download-dashboard" data-type="projects" data-start="{{ $startDate }}" data-end="{{ $endDate }}"><i class="fas fa-download"></i></button>
                    </h4>
                    <div id="categories-bar-chart" class="apex-charts pt-3"></div>
                </div>
            </div>
        </div>

        <!-- Crowdfunding Categories Table -->
        <div class="col-xl-12">
            <div class="card mt-3">
                <div class="card-body">
                    <h4 class="header-title mb-0 d-flex justify-content-between align-items-center">
                        {{ __('app.crowdfunding projects categories') }}
                        <button class="btn btn-light btn-download-dashboard" data-type="projects" data-start="{{ $startDate }}" data-end="{{ $endDate }}"><i class="fas fa-download"></i></button>
                    </h4>
                    <div id="crowdfunding-categories-bar-chart" class="apex-charts pt-3"></div>
                </div>
            </div>
        </div>

        <!-- Crowdfunding Categories Table -->
        <div class="col-xl-12">
            <div class="card mt-3">
                <div class="card-body">
                    <h4 class="header-title mb-0 d-flex justify-content-between align-items-center">
                        {{ __('app.crowdfunding projects') }}
                        <button class="btn btn-light btn-download-dashboard" data-type="crowdfunding" data-start="{{ $startDate }}" data-end="{{ $endDate }}"><i class="fas fa-download"></i></button>
                    </h4>
                    <ul class="nav nav-tabs mt-3" id="categoryTabs" role="tablist">
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

        <!-- Influencers Table -->
        <div class="col-xl-12">
            <div class="card mt-3">
                <div class="card-body">
                    <h4 class="header-title d-flex justify-content-between align-items-center mb-3">
                        {{ __('app.influencer_name') }}
                        <button class="btn btn-light btn-download-dashboard" data-type="influencers" data-start="{{ $startDate }}" data-end="{{ $endDate }}"><i class="fas fa-download"></i></button>
                    </h4>
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm table-striped table-bordered">
                            <thead class="bg-light" style="position: sticky;top: 0;">
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
                                            <a href="{{ route('influencer.payments', $influencer['id']) }}" style="color:#13585D">
                                                {{ $influencer['name'] }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $influencer['active_subscriptions'] }} =
                                            {{ number_format($influencer['active_subscription_total'], 3) }} JOD
                                        </td>
                                        <td>
                                            {{ $influencer['inactive_subscriptions'] }} =
                                            {{ number_format($influencer['inactive_subscription_total'], 3) }} JOD
                                        </td>
                                        <td>{{ $influencer['number_of_transactions'] }}</td>
                                        <td>{{ number_format($influencer['one_time_total'], 3) }} JOD</td>
                                        <td>{{ number_format($influencer['subscription_total'], 3) }} JOD</td>
                                        <td>{{ number_format($influencer['total_amount'], 3) }} JOD</td>
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
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '50px',
                borderRadius: 5,
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
                $chunks = array_chunk($words, 3); // Group every 3 words
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
            shared: true, // Show tooltip for the entire column when hovering
            intersect: false, // Show tooltip when hovering over the whole column (not just a segment)
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

        // Pie Chart for Subscription Status
        var subscriptionData = @json($subscriptionData);
        var pieOptions = {
            chart: {
                type: 'pie',
                height: 350
            },
            dataLabels: {
                formatter: function (val, opts) {
                    return opts.w.config.series[opts.seriesIndex] + "JOD"; // Display the actual number
                },
                style: {
                    colors: ['#FFFFFF'] // Makes text color white
                }
            },
            series: [subscriptionData.activeSubscriptions, subscriptionData.inactiveSubscriptions],
            labels: subscriptionData.subscriptionLabels,
            colors: ['#13585D', '#3ca1a8']
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
                height: 350,
                toolbar: {
                    show: false
                }
            },
            tooltip: {
                shared: true, // Show tooltip for the entire column when hovering
                intersect: false, // Show tooltip when hovering over the whole column (not just a segment)
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 5,
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
                categories: @json(
                    collect($paymentMethodCategories)->map(function ($item) {
                        return str_contains($item, ' ') ? explode(' ', $item) : $item;
                    })
                )
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
            }
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
                height: 350,
                toolbar: {
                    show: false
                }
            },
            tooltip: {
                shared: true, // Show tooltip for the entire column when hovering
                intersect: false, // Show tooltip when hovering over the whole column (not just a segment)
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 5,
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
                categories: @json(
                    collect($countryCategories)->map(function ($item) {
                        return str_contains($item, ' ') ? explode(' ', $item) : $item;
                    })
                )
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
            }
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
                height: 350,
                toolbar: {
                    show: false
                }
            },
            tooltip: {
                shared: true, // Show tooltip for the entire column when hovering
                intersect: false, // Show tooltip when hovering over the whole column (not just a segment)
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '40px',
                    borderRadius: 5,
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
                categories: @json($categoriesChart)
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
            }
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
                height: 350,
                toolbar: {
                    show: false
                }
            },
            tooltip: {
                shared: true, // Show tooltip for the entire column when hovering
                intersect: false, // Show tooltip when hovering over the whole column (not just a segment)
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '40px',
                    borderRadius: 5,
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
                categories: @json($categoriesChartCrowd)
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
            }
        };

        var chart = new ApexCharts(document.querySelector("#crowdfunding-categories-bar-chart"), options);
        chart.render();
    </script>
@endsection

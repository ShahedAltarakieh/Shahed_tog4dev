@extends('layouts.admin.show')
@section('title', request()->routeIs('refunds.index') ? __('app.refunds') : __('app.payments'))

@section('content')
@include('includes.admin.header', [
    'label_name' => request()->routeIs('refunds.index') ? __('app.refunds') : __('app.payments')
])

<div class="row mt-3">
    <div class="col-md-6 col-xl-3">
        <div class="card cursor-pointer summary-card card-1 {{ ($chart_type == 1) ? 'active' : '' }}" id="tooltip-container" onclick="updateTable(1, '{{ $list_of_dates['today'] }}', '{{ $list_of_dates['today'] }}' );">
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
        <div class="card cursor-pointer summary-card card-2 {{ ($chart_type == 2) ? 'active' : '' }}" id="tooltip-container" onclick="updateTable(2, '{{ $list_of_dates['this_week_start'] }}', '{{ $list_of_dates['this_week_end'] }}' );">
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
        <div class="card cursor-pointer summary-card card-4 {{ ($chart_type == 4) ? 'active' : '' }}" id="tooltip-container" onclick="updateTable(4, '{{ $list_of_dates['this_month_start'] }}', '{{ $list_of_dates['this_month_end'] }}' );">
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
        <div class="card cursor-pointer summary-card card-6 {{ ($chart_type == 6) ? 'active' : '' }}" id="tooltip-container" onclick="updateTable(6, '{{ $list_of_dates['this_year_start'] }}', '{{ $list_of_dates['this_year_end'] }}' );">
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
        <div class="card cursor-pointer summary-card card-7" id="tooltip-container" onclick="updateTable(7, '{{ $firstStartDate }}', '{{ $lastEndDate }}' );">
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
        <div class="card cursor-pointer summary-card card-8 {{ ($chart_type == -1) ? 'active' : '' }}" id="tooltip-container">
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
                            <span data-plugin="counterup" class="sum_custom_date_payments">
                                {{ number_format($payments_custom_range, 0) }}
                            </span>
                            {{ __('app.currency')}}
                        </h2>
                        <button class="btn btn-light btn-download-dashboard btn-custom-date" data-type="payments" data-start="{{ $startDate }}" data-end="{{ $endDate }}"><i class="fas fa-download"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class='row mt-3'>
    <div class="col-md-12">
        <div class="widget-rounded-circle card-box">
            <div class="row">
                <div class='col-12 table-responsive'>
                    <table id="ajax-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>{{ __('app.id') }}</th>
                                <th>{{ __('app.user') }}</th>
                                <th>{{ __('app.email') }}</th>
                                <th>{{ __('app.phone') }}</th>
                                <th>{{ __('app.status') }}</th>
                                <th>{{ __('app.Cart ID') }}</th>
                                <th>{{ __('app.Influencer Name') }}</th>
                                <th>{{ __('app.total_amount') }}</th>
                                <th>{{ __('app.created at') }}</th>
                                <th>{{ __('app.name on card') }}</th>
                                <th>{{ __('app.bank issuer') }}</th>
                                <th>{{ __('app.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
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
    $(document).ready(function () {
        updateTable();
    });
    
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
                updateTable(8, startDate, endDate)
                $(".btn-custom-date").attr("data-start", startDate);
                $(".btn-custom-date").attr("data-end", endDate);
            }
        }
    });
    function updateTable(type = '', start = '', end = ''){
        
        $(".card.summary-card").removeClass("active");
        $(".card.summary-card.card-" + type).addClass("active");
        $("#ajax-datatable").dataTable().fnDestroy();
        $('#ajax-datatable').DataTable({
            processing: true,
            serverSide: true,
            "ajax": {
                "url": "{{ route('payments.fetch_data') }}",
                "data": {
                	"start_date" : start,
                    "end_date": end,
                    "chart_type": type
                },
                "type": "GET",
                dataSrc: function(response) {                    
                    $(".sum_custom_date_payments").text(response.sum_custom_payments);
                    return response.data;
                }
            },
            order: [[8, 'desc']], // assuming created_at is column index 10
            columns: [
                { data: 'id' },
                { data: 'user_name' },
                { data: 'email' },
                { data: 'phone' },
                { data: 'status' },
                { data: 'cart_id' },
                { data: 'influencer' },
                { data: 'amount' },
                { data: 'created_at' },
                { data: 'name_on_card' },
                { data: 'bank_issuer' },
                { data: 'action', orderable: false, searchable: false },
            ]
        });
    }
</script>
@endsection
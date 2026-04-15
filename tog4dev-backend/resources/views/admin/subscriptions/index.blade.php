@extends('layouts.admin.show')
@section('title') __('app.subscriptions') @endsection

@section('content')
@include('includes.admin.header', ['label_name' => __('app.subscriptions'), "download_button" => route('subscriptions.download', ["active" => $active])])

<div class='row mt-3'>
    <div class="col-md-12">
        <div class="widget-rounded-circle card-box">
            <div class="row">
                <div class='col-12 table-responsive'>
                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>{{ __('app.id') }}</th>
                                <th>{{ __('Cart ID') }}</th>
                                <th>{{ __('app.name') }}</th>
                                <th>{{ __('app.Influencer Name') }}</th>
                                <th>{{ __('app.email') }}</th>
                                <th>{{ __('app.phone') }}</th>
                                <th>{{ __('app.price') }}</th>
                                <th>{{ __('app.next payment') }}</th>
                                <th>{{ __('app.status') }}</th>
                                <th>Apple Payment</th>
                                @if($active == 'active')
                                    <th>{{ __('app.action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subscriptions as $subscription)
                                <tr>
                                    <td>{{ $subscription->id }}</td>
                                    <td>{{ $subscription->payment?->cart_id }}</td>
                                    <td>
                                        {{ $subscription->payment?->userDetails?->first_name ?? $subscription->user?->first_name ?? '-' }}
                                        {{ $subscription->payment?->userDetails?->last_name ?? $subscription->user?->last_name ?? '' }}
                                    </td>
                                    <td>{{ $subscription->payment?->influencer?->name ?? 'Website' }}</td>
                                    <td>{{ $subscription->payment?->userDetails?->email ?? $subscription->user?->email ?? '-' }}</td>
                                    <td>{{ $subscription->payment?->userDetails?->phone ?? $subscription->user?->phone ?? '-' }}</td>
                                    <td>{{ $subscription->price }}{{ __('app.currency') }}</td>
                                    <td>{{ $subscription->end_date }}</td>
                                    <td>
                                        <span class="info-value">
                                            <span class="badge bg-{{ $subscription->status == 'active' ? 'success' : 'danger' }}">
                                                {{ ucfirst($subscription->status) }}
                                            </span>
                                        </span>
                                    </td>
                                    <td>
                                        @if($subscription->payment?->token == "apple")
                                            Apple Payment
                                        @endif
                                    </td>
                                    @if($active == 'active')
                                        <td>
                                            <button class='btn btn-danger btn-unsubscribe' data-toggle="tooltip" data-placement="top"
                                                    title="{{ __('app.cancel_subscription') }}" data-original-title="Tooltip on top"
                                                    data-url="/subscriptions/unsubscribe/{{ $subscription->id }}">
                                                <i class='fas fa-times-circle'></i>
                                            </button>
                                        </td>
                                    @endif
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

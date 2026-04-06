@extends('layouts.admin.show')
@section('title') {{ __('app.Payments') }} @endsection

@section('content')
@include('includes.admin.header', ['label_name' => __('app.payments')])
<div class='row mt-3'>
    <div class="col-md-12">
        <div class="widget-rounded-circle card-box">
        <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">{{ __('Influencers') }} Details</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-title">{{ __('app.name') }}:</span>
                                <span class="info-value">{{ $influencer->name ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-title">{{ __('app.code') }}:</span>
                                <span class="info-value">{{ $influencer->code ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 mt-3">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-title">{{ __('app.expiry_date') }}:</span>
                                <span class="info-value">{{ $influencer->expiry_date ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-title">{{ __('app.number of visit') }}:</span>
                                <span class="info-value">{{ $influencer->visits->count() ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 mt-3">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-title">{{ __('app.total_one_time') }}:</span>
                                <span class="info-value">{{ $oneTimeTotal ?? 0 }}{{ __('app.currency') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-title">{{ __('app.total_subscriptions') }}:</span>
                                <span class="info-value">{{ $subscriptionTotal }}{{ __('app.currency') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 mt-3">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-title">{{ __('app.total_amount') }}:</span>
                                <span class="info-value">{{ $totalAmount }}{{ __('app.currency') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class='col-12 table-responsive'>
                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>{{ __('app.name on card') }}</th>
                                <th>{{ __('app.bank issuer') }}</th>
                                <th>Transaction ID</th>
                                <th>Amount</th>
                                <th>Payment Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>{{ $payment->id }}</td>
                                    <td>{{ $payment->userDetails->first_name }} {{ $payment->userDetails->last_name }}</td>
                                    <td>{{ $payment->name_on_card }}</td>
                                    <td>{{ $payment->bank_issuer }}</td>
                                    <td>{{ $payment->cart_id }}</td>
                                    <td>{{ number_format($payment->amount, 2) }}{{ __('app.currency') }}</td>
                                    <td>{{ $payment->payment_type }}</td>
                                    <td>
                                        <a class="btn btn-primary" href="{{ route('payments.show', $payment->id) }}">
                                            <i class="mdi  mdi-eye-outline"></i>
                                        </a>
                                    </td>
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

@extends('layouts.admin.show')
@section('title') {{ __('app.partners') }} @endsection

@section('content')
@include('includes.admin.header', ['label_name' => __('app.payments')])
<div class='row mt-3'>
    <div class="col-md-12">
        <div class="widget-rounded-circle card-box">
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
                                <th>Status</th>
                                <th>Influencer Name</th>
                                <th>Total</th>
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
                                    <td>{{ $payment->status }}</td>
                                    <td>
                                        {{ $payment->influencer ? $payment->influencer->name : 'Website' }}
                                    </td>
                                    @php
                                        $total = 0;
                                        foreach ($payment->cartItems as $cartItem) {
                                            $total += $cartItem->price;
                                        }
                                    @endphp

                                    <td>{{ $total }}</td>
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

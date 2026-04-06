@extends('layouts.admin.show')
@section('title') {{ __('app.upload sheets') }} @endsection

@section('content')

@include('includes.admin.header', ['label_name' => $excel_sheet->file_name])
<div class='row mt-3'>
    <div class="col-md-12">
        <div class="widget-rounded-circle card-box">
            <div class="row">
                <div class='col-12 table-responsive'>
                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>webID</th>
                                <th>{{ __('app.order id') }}</th>
                                <th>{{ __('app.created_at') }}</th>
                                <th>{{ __('app.status') }}</th>
                                <th>{{ __('app.total') }}</th>
                                <th>{{ __('app.name') }}</th>
                                <th>{{ __('app.email') }}</th>
                                <th>{{ __('app.phone') }}</th>
                                <th>{{ __('app.payment method') }}</th>
                                <th>{{ __('app.cart') }}</th>
                                <th>{{ __('app.language') }}</th>
                                <th>{{ __('app.influencer id') }}</th>
                                <th>{{ __('app.influencer name') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->order_id }}</td>
                                    <td>
                                        @if($item->status == "pending")
                                            Declined
                                        @elseif($item->status == "completed")
                                            Approved
                                        @else
                                            Declined
                                        @endif
                                    </td>
                                    <td>{{ $item->created_order_at }}</td>
                                    <td>{{ $item->total }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->customer_email }}</td>
                                    <td>{{ $item->customer_phone_number }}</td>
                                    <td>{{ $item->payment_method }}</td>
                                    <td>{{ $item->order_items }}</td>
                                    <td>{{ $item->lang }}</td>
                                    <td>{{ $item->inf_id }}</td>
                                    <td>{{ $item->inf_name }}</td>
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

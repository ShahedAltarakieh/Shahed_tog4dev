@extends('layouts.admin.show')
@section('title'){{ __('app.newsletter_subscribers') }}@endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.newsletter_subscribers'), "add_button" => null])
<div class='row mt-3'>
    <div class="col-md-12">
        <div class="widget-rounded-circle card-box">
            <div class="row">
                <div class='col-12 table-responsive'>
                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('app.email') }}</th>
                                <th>{{ __('app.status') }}</th>
                                <th>{{ __('app.created_at') }}</th>
                                <th>{{ __('app.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $subscriber)
                            <tr>
                                <td>{{ $subscriber->id }}</td>
                                <td>{{ $subscriber->email }}</td>
                                <td>
                                    @if ($subscriber->status == 1)
                                    <span class="badge badge-success">{{ __('app.active') }}</span>
                                    @else
                                    <span class="badge badge-danger">{{ __('app.inactive') }}</span>
                                    @endif
                                </td>
                                <td>{{ $subscriber->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>
                                    <button class="btn btn-warning btn-update-status"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="{{ $subscriber->status ? __('app.unsubscribe') : __('app.subscribe') }}"
                                        data-id="{{ $subscriber->id }}"
                                        data-status="{{ $subscriber->status ? 0 : 1 }}">
                                        <i class="fas fa-toggle-{{ $subscriber->status ? 'off' : 'on' }}"></i>
                                    </button>
                                    <button class='btn btn-danger btn-delete'
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="{{ __('app.delete') }}"
                                        data-table="newsletter"
                                        data-id="{{ $subscriber->id }}">
                                        <i class='fas fa-trash-alt'></i>
                                    </button>
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

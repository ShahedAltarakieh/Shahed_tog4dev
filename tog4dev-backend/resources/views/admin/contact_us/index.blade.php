@extends('layouts.admin.show')
@section('title'){{ __('app.contact_us') }}@endsection

@section('content')

@include('includes.admin.header', [
    'label_name' => __('app.contact_us'),
    'show_read' => Route::is('contact_us.showRead')
                    ? route('contact_us.index', ['type' => $type])
                    : route('contact_us.showRead', ['type' => $type])
])
<div class='row mt-3'>
    <div class="col-md-12">
        <div class="widget-rounded-circle card-box">
            <div class="row">
                <div class='col-12 table-responsive'>
                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('app.first_name') }}</th>
                                <th>{{ __('app.last_name') }}</th>
                                <th>{{ __('app.email') }}</th>
                                <th>{{ __('app.country') }}</th>
                                <th>{{ __('app.status') }}</th>
                                <th>{{ __('app.created_at') }}</th>
                                <th>{{ __('app.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $contact)
                            <tr>
                                <td>{{ $contact->id }}</td>
                                <td>{{ $contact->first_name }}</td>
                                <td>{{ $contact->last_name }}</td>
                                <td>{{ $contact->email }}</td>
                                <td>{{ $contact->country }}</td>
                                <td>
                                    @if ($contact->status == 1)
                                    <span class="badge badge-success">{{ __('app.active') }}</span>
                                    @else
                                    <span class="badge badge-danger">{{ __('app.inactive') }}</span>
                                    @endif
                                </td>
                                <td>{{ $contact->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>
                                    <a href="{{ route('contact_us.show', ['type' => $type, 'id' => $contact->id]) }}"
                                        class='btn btn-info'
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="{{ __('app.view') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn btn-warning btn-mark-read"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="{{ __('app.mark_as_read') }}"
                                        data-id="{{ $contact->id }}"
                                        data-type="{{ $type }}"
                                        data-table="{{$type}}/contact-us">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                    <button class='btn btn-danger btn-delete' data-toggle="tooltip" data-placement="top" title="{{ __('app.delete') }}" data-original-title="Tooltip on top" data-table="{{$type}}/contact-us" data-id="{{ $contact->id }}"><i class='fas fa-trash-alt'></i></button>
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

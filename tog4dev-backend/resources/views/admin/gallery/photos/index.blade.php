@extends('layouts.admin.show')
@section('title'){{ __('app.photos') }}@endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.photos'), "add_button" => route('gallery-admin.photos.create')])
<div class='row mt-3'>
    <div class="col-md-12">
        <div class="widget-rounded-circle card-box">
            @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="row">
                <div class='col-12 table-responsive'>
                    <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('app.image') }}</th>
                                <th>{{ __('app.title') }} (AR)</th>
                                <th>{{ __('app.title') }} (EN)</th>
                                <th>{{ __('app.category') }}</th>
                                <th>{{ __('app.status') }}</th>
                                <th>{{ __('app.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td><img src="{{ $item->image }}" style="max-height: 40px"></td>
                                    <td>{{ Str::limit($item->title, 40) }}</td>
                                    <td>{{ Str::limit($item->title_en, 40) }}</td>
                                    <td>{{ $item->category->name ?? '-' }}</td>
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input change_status" {{ ($item->status == 1) ? "checked" : "" }}
                                                   data-table="gallery-management/photos"
                                                   data-status="{{ $item->status }}"
                                                   data-id="{{ $item->id }}"
                                                   id="customSwitchStatus{{ $item->id }}">
                                            <label class="custom-control-label" for="customSwitchStatus{{ $item->id }}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('gallery-admin.photos.show', ['id' => $item->id]) }}"
                                            class='btn btn-secondary' data-toggle="tooltip"
                                            title="{{ __('app.edit') }}">
                                            <i class='fas fa-edit'></i>
                                        </a>
                                        <button class='btn btn-danger btn-delete' data-toggle="tooltip"
                                            title="{{ __('app.delete') }}"
                                            data-table="gallery-management/photos" data-id="{{ $item->id }}">
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

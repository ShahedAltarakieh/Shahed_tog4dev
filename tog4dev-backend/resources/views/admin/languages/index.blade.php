@extends('layouts.admin.show')
@section('title'){{ __('app.languages') }}@endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.languages'), "add_button" => route('languages-admin.create')])
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
                                <th>{{ __('app.code') }}</th>
                                <th>{{ __('app.name') }}</th>
                                <th>{{ __('app.native name') }}</th>
                                <th>{{ __('app.direction') }}</th>
                                <th>{{ __('app.default') }}</th>
                                <th>{{ __('app.position') }}</th>
                                <th>{{ __('app.status') }}</th>
                                <th>{{ __('app.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td><code>{{ $item->code }}</code></td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->native_name }}</td>
                                    <td>{{ strtoupper($item->direction) }}</td>
                                    <td>
                                        @if($item->is_default)
                                            <span class="badge badge-success">{{ __('app.default') }}</span>
                                        @else
                                            <form method="POST" action="{{ route('languages-admin.set_default', ['id' => $item->id]) }}" style="display:inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-secondary">{{ __('app.set as default') }}</button>
                                            </form>
                                        @endif
                                    </td>
                                    <td>{{ $item->position }}</td>
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input change_status" {{ ($item->is_active == 1) ? "checked" : "" }}
                                                   data-table="languages"
                                                   data-status="{{ (int) $item->is_active }}"
                                                   data-id="{{ $item->id }}"
                                                   id="customSwitchStatus{{ $item->id }}"
                                                   {{ $item->is_default ? 'disabled' : '' }}>
                                            <label class="custom-control-label" for="customSwitchStatus{{ $item->id }}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('languages-admin.show', ['id' => $item->id]) }}"
                                            class='btn btn-secondary' data-toggle="tooltip"
                                            title="{{ __('app.edit') }}">
                                            <i class='fas fa-edit'></i>
                                        </a>
                                        @if(!$item->is_default)
                                        <button class='btn btn-danger btn-delete' data-toggle="tooltip"
                                            title="{{ __('app.delete') }}"
                                            data-table="languages" data-id="{{ $item->id }}">
                                            <i class='fas fa-trash-alt'></i>
                                        </button>
                                        @endif
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

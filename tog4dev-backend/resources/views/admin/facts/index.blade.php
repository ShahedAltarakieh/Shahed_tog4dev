@extends('layouts.admin.show')
@section('title') {{ __('app.facts') }} @endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.facts'), "add_button" => route('facts.create', ['type' => $type])])
<div class='row mt-3'>
    <div class="col-md-12">
        <div class="widget-rounded-circle card-box">
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
                                    <td><img src="{{ $item->logo }}" style="max-height: 40px"></td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->title_en }}</td>
                                    <td>{{ $item->category->title }}</td>
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input change_status" {{ ($item->status == 1) ? "checked" : "" }}
                                                   data-table="{{$type}}/facts"
                                                   data-status="{{ $item->status }}"
                                                   data-id="{{ $item->id }}"
                                                   id="customSwitchStatus{{ $item->id }}">
                                            <label class="custom-control-label" for="customSwitchStatus{{ $item->id }}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <a class="btn btn-info"
                                            href="{{ route('facts.show', ['type' => $type, 'fact' => $item->id]) }}">
                                            <i class="mdi mdi-eye-outline"></i>
                                        </a>
                                        <a href="{{ route('facts.edit', ["type" => $type, "fact" => $item->id]) }}"
                                            class='btn btn-secondary' data-toggle="tooltip" data-placement="top"
                                            title="{{ __('app.edit') }}">
                                            <i class='fas fa-edit'></i>
                                        </a>
                                        <button class='btn btn-danger btn-delete' data-toggle="tooltip" data-placement="top"
                                            title="{{ __('app.delete') }}" data-original-title="Tooltip on top"
                                            data-table="{{$type}}/facts" data-id="{{ $item->id }}">
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

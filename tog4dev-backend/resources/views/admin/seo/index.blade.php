@extends('layouts.admin.show')
@section('title') {{ __('app.seo') }} @endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.seo'), "add_button" => route('seo.create')])
    <div class="row">
        <div class="col-12">
            <div class="widget-rounded-circle card-box">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('app.type') }}</th>
                            <th>{{ __('app.image') }}</th>
                            <th>{{ __('app.title') }}</th>
                            <th>{{ __('app.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($seo as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td><img src="{{ $item->image }}" style="max-height: 40px"></td>
                                <td>{{ $item->meta_title }}</td>
                                <td>{{ $item->model_type }}</td>
                                <td>
                                    <a href="{{ route('seo.edit', $item->id) }}"
                                       class='btn btn-secondary' data-toggle="tooltip" data-placement="top"
                                       title="{{ __('app.edit') }}" data-original-title="Tooltip on top"
                                       data-id="{{ $item->id }}"><i class='fas fa-edit'></i></a>
                                    <button class='btn btn-danger btn-delete' data-toggle="tooltip" data-placement="top"
                                            title="{{ __('app.delete') }}" data-original-title="Tooltip on top"
                                            data-table="seo" data-id="{{ $item->id }}">
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
@endsection

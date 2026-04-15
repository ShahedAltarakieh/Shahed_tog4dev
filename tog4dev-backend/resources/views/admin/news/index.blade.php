@extends('layouts.admin.show')
@section('title'){{ __('app.news') }}@endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.news'), "add_button" => route('news-admin.create')])
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
                                <th>{{ __('app.publish date') }}</th>
                                <th>{{ __('app.status') }}</th>
                                <th>{{ __('app.recent') }}</th>
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
                                    <td>{{ $item->published_at ? \Carbon\Carbon::parse($item->published_at)->format('Y-m-d') : '-' }}</td>
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input change_status" {{ ($item->status == 1) ? "checked" : "" }}
                                                   data-table="news-management"
                                                   data-status="{{ $item->status }}"
                                                   data-id="{{ $item->id }}"
                                                   id="customSwitchStatus{{ $item->id }}">
                                            <label class="custom-control-label" for="customSwitchStatus{{ $item->id }}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->created_at && $item->created_at->greaterThanOrEqualTo(now()->subDays(7)))
                                            <span class="badge badge-danger">{{ __('app.new') }}</span>
                                        @else
                                            <span class="badge badge-light">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('news-admin.show', ['id' => $item->id]) }}"
                                            class='btn btn-secondary' data-toggle="tooltip"
                                            title="{{ __('app.edit') }}">
                                            <i class='fas fa-edit'></i>
                                        </a>
                                        <form action="{{ route('news-admin.duplicate', ['id' => $item->id]) }}" method="POST" style="display:inline">
                                            @csrf
                                            <button type="submit" class='btn btn-info' data-toggle="tooltip"
                                                title="{{ __('app.duplicate') }}">
                                                <i class='fas fa-copy'></i>
                                            </button>
                                        </form>
                                        <button class='btn btn-danger btn-delete' data-toggle="tooltip"
                                            title="{{ __('app.delete') }}"
                                            data-table="news-management" data-id="{{ $item->id }}">
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

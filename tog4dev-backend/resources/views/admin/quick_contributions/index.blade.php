@extends('layouts.admin.show')
@section('title') {{ __('app.contributions') }} @endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.contributions'), "add_button" => route('quick-contributions.create')])
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
                            <th>{{ __('app.image') }}</th>
                            <th>{{ __('app.title') }}</th>
                            <th>{{ __('app.type') }}</th>
                            <th>{{ __('app.category') }}</th>
                            <th>{{ __('app.status') }}</th>
                            <th>{{ __('app.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quickContributions as $contribution)
                            <tr>
                                <td>{{ $contribution->id }}</td>
                                <td><img src="{{ $contribution->image }}" style="max-height: 40px"></td>
                                <td>{{ $contribution->title }}</td>
                                <td>
                                    @switch($contribution->type_id)
                                        @case(1)
                                            Home
                                            @break
                                        @case(2)
                                            Project
                                            @break
                                        @case(3)
                                            Crowdfunding
                                            @break
                                    @endswitch
                                </td>
                                <td>{{ $contribution->category->title ?? 'N/A' }}</td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input change_status" {{ ($contribution->status == 1) ? "checked" : "" }}
                                               data-table="quick-contributions"
                                               data-status="{{ $contribution->status }}"
                                               data-id="{{ $contribution->id }}"
                                               id="customSwitchStatus{{ $contribution->id }}">
                                        <label class="custom-control-label" for="customSwitchStatus{{ $contribution->id }}"></label>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('quick-contributions.edit', $contribution->id) }}"
                                       class='btn btn-secondary' data-toggle="tooltip" data-placement="top"
                                       title="{{ __('app.edit') }}" data-original-title="Tooltip on top"
                                       data-id="{{ $contribution->id }}"><i class='fas fa-edit'></i></a>
                                    <button class='btn btn-danger btn-delete' data-toggle="tooltip" data-placement="top"
                                            title="{{ __('app.delete') }}" data-original-title="Tooltip on top"
                                            data-table="quick-contributions" data-id="{{ $contribution->id }}">
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

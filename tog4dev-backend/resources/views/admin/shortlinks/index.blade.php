@extends('layouts.admin.show')
@section('title') {{ __('app.short links') }} @endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.short links'), "add_button" => route('shortlinks.create')])

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
                        <th>{{ __('app.short code') }}</th>
                        <th>{{ __('app.original url') }}</th>
                        <th>{{ __('app.redirect url') }}</th>
                        <th>{{ __('app.created at') }}</th>
                        <th>{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shortlinks as $link)
                        <tr>
                            <td>{{ $link->id }}</td>
                            <td><a href="{{ env('APP_URL_FRONTEND').'s/'.$link->short_code }}" target="_blank" style="text-decoration:underline;color:#0f16fe">{{ $link->short_code }}</a></td>
                            <td><a href="{{ $link->original_url }}" target="_blank" style="text-decoration:underline;color:#0f16fe">{{ __('app.original url') }}</a></td>
                            <td><a href="{{ env('APP_URL_FRONTEND').'s/'.$link->short_code }}" target="_blank" style="text-decoration:underline;color:#0f16fe">{{ env('APP_URL_FRONTEND').'s/'.$link->short_code }}</a></td>
                            <td>{{ $link->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>
                                <a href="{{ route('shortlinks.edit', $link->id) }}"
                                   class='btn btn-secondary' data-toggle="tooltip" data-placement="top"
                                   title="{{ __('app.edit') }}"><i class='fas fa-edit'></i></a>
                                <button class='btn btn-danger btn-delete' data-toggle="tooltip" data-placement="top"
                                        title="{{ __('app.delete') }}" data-original-title="Tooltip on top"
                                        data-table="shortlinks" data-id="{{ $link->id }}">
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

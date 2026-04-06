@extends('layouts.admin.add')
@section('title'){{ __('app.view_details') }} @endsection

@section('content')

    @include('includes.admin.header', ['label_name' => __('app.view_details')])

    <div class="row">
        <div class="col-12">
            <div class="widget-rounded-circle card-box">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>{{ __('app.title') }} (AR):</th>
                            <td>{{ $data->title ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>{{ __('app.title') }} (EN):</th>
                            <td>{{ $data->title_en ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>{{ __('app.description') }} (AR):</th>
                            <td>{{ $data->description ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>{{ __('app.description') }} (EN):</th>
                            <td>{{ $data->description_en ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>{{ __('app.logo') }} (AR):</th>
                            <td>
                                @if($data->image)
                                    <img src="{{ asset($data->image) }}" alt="{{ __('app.logo') }}" class="img-fluid" style="max-width: 200px;">
                                @else
                                    {{ '-' }}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>{{ __('app.logo') }} (EN):</th>
                            <td>
                                @if($data->image_en)
                                    <img src="{{ asset($data->image_en) }}" alt="{{ __('app.logo_en') }}" class="img-fluid" style="max-width: 200px;">
                                @else
                                    {{ '-' }}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>{{ __('app.status') }}:</th>
                            <td>{{ $data->status ? __('app.active') : __('app.inactive') }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('app.created at') }}:</th>
                            <td>{{ $data->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('app.updated at') }}:</th>
                            <td>{{ $data->updated_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-center">
                    <a href="{{ route('sliders.index') }}" class="btn btn-secondary px-4">{{ __('app.back') }}</a>
                </div>
            </div>
        </div>
    </div>

@endsection

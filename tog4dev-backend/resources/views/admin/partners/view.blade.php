@extends('layouts.admin.add')
@section('title'){{ __('app.view_details') }}@endsection

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
                        <th>{{ __('app.name') }} (AR):</th>
                        <td>{{ $partner->title ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.name') }} (EN):</th>
                        <td>{{ $partner->title_en ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.logo') }} (AR):</th>
                        <td>
                            @if($partner->image)
                                <img src="{{ $partner->getImageAttribute() }}" alt="{{ __('app.logo') }}"
                                     class="img-fluid" style="max-width: 200px;">
                            @else
                                {{ '-' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('app.logo') }} (EN):</th>
                        <td>
                            @if($partner->image_en)
                                <img src="{{ $partner->getImageENAttribute() }}" alt="{{ __('app.logo_en') }}"
                                     class="img-fluid" style="max-width: 200px;">
                            @else
                                {{ '-' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('app.category') }}:</th>
                        <td>{{ $partner->category->title ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.status') }}:</th>
                        <td>{{ $partner->status ? __('app.active') : __('app.inactive') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.created at') }}:</th>
                        <td>{{ $partner->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.updated at') }}:</th>
                        <td>{{ $partner->updated_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center">
                <a href="{{ route('partners.index', ['type' => $type]) }}"
                   class="btn btn-secondary px-4">{{ __('app.back') }}</a>
            </div>
        </div>
    </div>
</div>

@endsection

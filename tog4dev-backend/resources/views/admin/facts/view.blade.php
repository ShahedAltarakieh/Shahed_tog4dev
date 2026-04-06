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
                        <th>{{ __('app.title') }} (AR):</th>
                        <td>{{ $fact->title ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.title') }} (EN):</th>
                        <td>{{ $fact->title_en ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.description') }} (AR):</th>
                        <td>{{ $fact->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.description') }} (EN):</th>
                        <td>{{ $fact->description_en ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.logo') }} (AR):</th>
                        <td>
                            @if($fact->logo)
                                <img src="{{ $fact->getLogoAttribute() }}" alt="{{ __('app.logo') }}"
                                     class="img-fluid" style="max-width: 200px;">
                            @else
                                {{ '-' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('app.logo') }} (EN):</th>
                        <td>
                            @if($fact->logo_en)
                                <img src="{{ $fact->getLogoENAttribute() }}" alt="{{ __('app.logo_en') }}"
                                     class="img-fluid" style="max-width: 200px;">
                            @else
                                {{ '-' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('app.category') }}:</th>
                        <td>{{ $fact->category->title ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.status') }}:</th>
                        <td>{{ $fact->status ? __('app.active') : __('app.inactive') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.created at') }}:</th>
                        <td>{{ $fact->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.updated at') }}:</th>
                        <td>{{ $fact->updated_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center">
                <a href="{{ route('facts.index', ['type' => $type]) }}"
                   class="btn btn-secondary px-4">{{ __('app.back') }}</a>
            </div>
        </div>
    </div>
</div>

@endsection

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
                        <td>{{ $story->title ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.title') }} (EN):</th>
                        <td>{{ $story->title_en ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.image') }} (AR):</th>
                        <td>
                            @if($story->image)
                                <img src="{{ $story->getImageAttribute() }}" alt="{{ __('app.image') }}"
                                    class="img-fluid" style="max-width: 200px;">
                            @else
                                {{ '-' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('app.image') }} (EN):</th>
                        <td>
                            @if($story->image_en)
                                <img src="{{ $story->getImageENAttribute() }}" alt="{{ __('app.image_en') }}"
                                    class="img-fluid" style="max-width: 200px;">
                            @else
                                {{ '-' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('app.category') }}:</th>
                        <td>{{ $story->category->title ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.status') }}:</th>
                        <td>{{ $story->status ? __('app.active') : __('app.inactive') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.created at') }}:</th>
                        <td>{{ $story->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.updated at') }}:</th>
                        <td>{{ $story->updated_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center">
                <a href="{{ route('stories.index', ['type' => $type]) }}"
                   class="btn btn-secondary px-4">{{ __('app.back') }}</a>
            </div>
        </div>
    </div>
</div>

@endsection

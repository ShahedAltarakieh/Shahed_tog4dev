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
                        <td>{{ $category->title ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.title') }} (EN):</th>
                        <td>{{ $category->title_en ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.description') }} (AR):</th>
                        <td>{{ $category->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.description') }} (EN):</th>
                        <td>{{ $category->description_en ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.image') }} (AR):</th>
                        <td>
                            @if($category->image)
                                <img src="{{ $category->getImageAttribute() }}" alt="{{ __('app.image') }}"
                                    class="img-fluid" style="max-width: 200px;">
                            @else
                                {{ '-' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('app.image') }} (EN):</th>
                        <td>
                            @if($category->image_en)
                                <img src="{{ $category->getImageENAttribute() }}" alt="{{ __('app.image_en') }}"
                                    class="img-fluid" style="max-width: 200px;">
                            @else
                                {{ '-' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('app.hero_title') }} (AR):</th>
                        <td>{{ $category->hero_title ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.hero_title') }} (EN):</th>
                        <td>{{ $category->hero_title_en ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.hero_description') }} (AR):</th>
                        <td>{{ $category->hero_description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.hero_description') }} (EN):</th>
                        <td>{{ $category->hero_description_en ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.hero') }} (AR):</th>
                        <td>
                            @if($category->hero)
                                <img src="{{ $category->getHeroImageAttribute() }}" alt="{{ __('app.hero_image') }}"
                                    class="img-fluid" style="max-width: 200px;">
                            @else
                                {{ '-' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('app.hero_en') }} (EN):</th>
                        <td>
                            @if($category->hero_en)
                                <img src="{{ $category->getHeroENAttribute() }}" alt="{{ __('app.hero_image_en') }}"
                                    class="img-fluid" style="max-width: 200px;">
                            @else
                                {{ '-' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('app.status') }}:</th>
                        <td>{{ $category->status ? __('app.active') : __('app.inactive') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.created at') }}:</th>
                        <td>{{ $category->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.updated at') }}:</th>
                        <td>{{ $category->updated_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center">
                <a href="{{ route('categories.index', ['type' => $type]) }}"
                    class="btn btn-secondary px-4">{{ __('app.back') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection

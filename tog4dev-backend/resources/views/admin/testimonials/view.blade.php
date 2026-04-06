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
                        <td>{{ $testimonial->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.name') }} (EN):</th>
                        <td>{{ $testimonial->name_en ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.description') }} (AR):</th>
                        <td>{{ $testimonial->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.description') }} (EN):</th>
                        <td>{{ $testimonial->description_en ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.location') }} (AR):</th>
                        <td>{{ $testimonial->location ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>{{ __('app.location') }} (EN):</th>
                        <td>{{ $testimonial->location_en ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.image') }} (AR):</th>
                        <td>
                            @if($testimonial->image)
                                <img src="{{ $testimonial->getImageAttribute() }}" alt="{{ __('app.image') }}"
                                    class="img-fluid" style="max-width: 200px;">
                            @else
                                {{ '-' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('app.image') }} (EN):</th>
                        <td>
                            @if($testimonial->image_en)
                                <img src="{{ $testimonial->getImageENAttribute() }}" alt="{{ __('app.image_en') }}"
                                    class="img-fluid" style="max-width: 200px;">
                            @else
                                {{ '-' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('app.category') }}:</th>
                        <td>{{ $testimonial->category->title ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.status') }}:</th>
                        <td>{{ $testimonial->status ? __('app.active') : __('app.inactive') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.created at') }}:</th>
                        <td>{{ $testimonial->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('app.updated at') }}:</th>
                        <td>{{ $testimonial->updated_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center">
                <a href="{{ route('testimonials.index', ['type' => $type]) }}"
                    class="btn btn-secondary px-4">{{ __('app.back') }}</a>
            </div>
        </div>
    </div>
</div>

@endsection

@extends('layouts.admin.add')
@section('title'){{ __('app.add new') }} @endsection

@section('content')
@include('includes.admin.header', ['label_name' => __('app.add new')])


<div class="col-12 m-2 text-right">
    <button class='btn btn-danger btn-delete' data-toggle="tooltip" data-placement="top" title="{{ __('app.delete') }}" data-original-title="Tooltip on top" data-table="{{$type}}/items/clear-images" data-id="{{ $item->id }}"><i class='fas fa-trash-alt'></i></button>
</div>


<form action="{{ route('items.upload_slider', ['type' => $type, 'item' => $item->id]) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="form-group col-12">
            <input type="file" id="image_item" name="image_item" data-plugins="dropify" data-height="200"
                data-allowed-file-extensions="png jpg jpeg webp" />
            @include('includes.admin.image-upload-notes', [
                'recommendedSize' => '1200 x 800 px',
                'maxSize' => '5 MB',
                'extensions' => 'png,jpg,jpeg,webp'
            ])
        </div>
        <div class="col-12 m-2 text-center">
            <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
        </div>
    </div>
</form>

<div class="col-12">
    <div class="row filterable-content">
        @forelse ($data as $media)
            <div class="col-sm-6 col-xl-3 filter-item all web illustrator">
                <div class="gal-box pb-1">
                    <a href="{{ $media->getUrl() }}" class="image-popup" title="Screenshot-1">
                        <img src="{{ $media->getUrl() }}" class="img-fluid fixed-size-image" alt="work-thumbnail">
                    </a>
                    <button class='btn btn-danger btn-delete d-block mx-auto' data-toggle="tooltip" data-placement="top" title="{{ __('app.delete') }}" data-original-title="Tooltip on top" data-table="{{$type}}/items/clear-single-image/{{ $item->id }}" data-id="{{ $media->id }}"><i class='fas fa-trash-alt'></i> {{ __('app.delete') }}</button>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p>No images available</p>
            </div>
        @endforelse
    </div>
</div>

@endsection

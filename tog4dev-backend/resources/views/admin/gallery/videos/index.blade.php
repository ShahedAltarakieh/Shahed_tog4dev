@extends('layouts.admin.show')
@section('title'){{ __('app.videos') }}@endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.videos'), "add_button" => route('gallery-admin.videos.create')])
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
                                <th>{{ __('app.thumbnail') }}</th>
                                <th>{{ __('app.title') }} (AR)</th>
                                <th>{{ __('app.title') }} (EN)</th>
                                <th>{{ __('app.video url') }}</th>
                                <th>{{ __('app.display for') ?? 'Display For' }}</th>
                                <th>{{ __('app.category') }}</th>
                                <th>{{ __('app.status') }}</th>
                                <th>{{ __('app.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        @if($item->thumbnail)
                                            <img src="{{ $item->thumbnail }}" alt="{{ $item->title }}"
                                                 style="width: 80px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #e2e8f0;" />
                                        @else
                                            <span class="badge badge-light" style="font-size: 11px;">
                                                <i class="fas fa-image text-muted mr-1"></i>{{ __('app.no image') ?? 'No Image' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($item->title, 40) }}</td>
                                    <td>{{ Str::limit($item->title_en, 40) }}</td>
                                    <td>
                                        @if($item->video_url)
                                            <a href="{{ $item->video_url }}" target="_blank" class="text-primary">
                                                {{ Str::limit($item->video_url, 30) }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @php $target = $item->display_target ?? 'both'; @endphp
                                        @if($target === 'mobile')
                                            <span class="badge badge-info"><i class="fas fa-mobile-alt mr-1"></i>{{ __('app.mobile only') ?? 'Mobile' }}</span>
                                        @elseif($target === 'desktop')
                                            <span class="badge badge-secondary"><i class="fas fa-desktop mr-1"></i>{{ __('app.desktop only') ?? 'Desktop' }}</span>
                                        @else
                                            <span class="badge badge-success"><i class="fas fa-globe mr-1"></i>{{ __('app.both') ?? 'Both' }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->category->name ?? '-' }}</td>
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input change_status" {{ ($item->status == 1) ? "checked" : "" }}
                                                   data-table="gallery-management/videos"
                                                   data-status="{{ $item->status }}"
                                                   data-id="{{ $item->id }}"
                                                   id="customSwitchStatus{{ $item->id }}">
                                            <label class="custom-control-label" for="customSwitchStatus{{ $item->id }}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('gallery-admin.videos.show', ['id' => $item->id]) }}"
                                            class='btn btn-secondary' data-toggle="tooltip"
                                            title="{{ __('app.edit') }}">
                                            <i class='fas fa-edit'></i>
                                        </a>
                                        <form action="{{ route('gallery-admin.videos.duplicate', ['id' => $item->id]) }}" method="POST" style="display:inline">
                                            @csrf
                                            <button type="submit" class='btn btn-info' data-toggle="tooltip"
                                                title="{{ __('app.duplicate') }}">
                                                <i class='fas fa-copy'></i>
                                            </button>
                                        </form>
                                        <button class='btn btn-danger btn-delete' data-toggle="tooltip"
                                            title="{{ __('app.delete') }}"
                                            data-table="gallery-management/videos" data-id="{{ $item->id }}">
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

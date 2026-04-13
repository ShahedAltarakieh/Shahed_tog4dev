@extends('layouts.admin.show')

@section('title') {{ __('app.media_library') }} @endsection

@section('content')
<div class="row mt-3 mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1" style="background:none;padding:0;">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="#">{{ __('app.system') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('app.media_library') }}</li>
                    </ol>
                </nav>
                <h4 class="page-title mb-0">{{ __('app.media_library') }}</h4>
            </div>
            <div class="d-flex" style="gap:8px;">
                <a href="{{ route('gallery-admin.photos.create') }}" class="btn btn-sm" style="background:var(--admin-primary);color:#fff;">
                    <i class="fas fa-plus mr-1"></i> {{ __('app.upload_photo') }}
                </a>
                <a href="{{ route('gallery-admin.videos.create') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-video mr-1"></i> {{ __('app.upload_video') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <div class="card dashboard-kpi-card">
            <div class="card-body">
                <div class="d-flex align-items-center" style="gap:12px;">
                    <div class="kpi-icon primary"><i class="fas fa-images"></i></div>
                    <div>
                        <div class="kpi-value" style="font-size:24px;">{{ number_format($totalPhotos) }}</div>
                        <div class="kpi-label">{{ __('app.total_photos') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card dashboard-kpi-card">
            <div class="card-body">
                <div class="d-flex align-items-center" style="gap:12px;">
                    <div class="kpi-icon info"><i class="fas fa-video"></i></div>
                    <div>
                        <div class="kpi-value" style="font-size:24px;">{{ number_format($totalVideos) }}</div>
                        <div class="kpi-label">{{ __('app.total_videos') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs mb-3">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#photosTab">
                            <i class="fas fa-images mr-1"></i> {{ __('app.photos') }} ({{ $totalPhotos }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#videosTab">
                            <i class="fas fa-video mr-1"></i> {{ __('app.videos') }} ({{ $totalVideos }})
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="photosTab">
                        @if($photos->count() > 0)
                        <div class="media-grid">
                            @foreach($photos as $photo)
                            <div class="media-card">
                                <div class="media-thumb">
                                    @if($photo->image)
                                    <img src="{{ $photo->image }}" alt="{{ $photo->title_en ?? '' }}" loading="lazy">
                                    @else
                                    <div class="media-placeholder"><i class="fas fa-image"></i></div>
                                    @endif
                                </div>
                                <div class="media-info">
                                    <div class="media-title">{{ $photo->title_en ?? $photo->title_ar ?? 'Photo #'.$photo->id }}</div>
                                    <div class="media-meta">{{ $photo->created_at ? $photo->created_at->format('M d, Y') : '' }}</div>
                                </div>
                                <div class="media-actions">
                                    <a href="{{ route('gallery-admin.photos.show', $photo->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="empty-state">
                            <div class="empty-state-icon"><i class="fas fa-images"></i></div>
                            <h5>{{ __('app.no_photos') }}</h5>
                            <p class="text-muted">{{ __('app.upload_first_photo') }}</p>
                            <a href="{{ route('gallery-admin.photos.create') }}" class="btn" style="background:var(--admin-primary);color:#fff;">{{ __('app.upload_photo') }}</a>
                        </div>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="videosTab">
                        @if($videos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('app.title') }}</th>
                                        <th>{{ __('app.url') }}</th>
                                        <th>{{ __('app.created at') }}</th>
                                        <th>{{ __('app.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($videos as $video)
                                    <tr>
                                        <td>{{ $video->id }}</td>
                                        <td>{{ $video->title_en ?? $video->title_ar ?? 'Video #'.$video->id }}</td>
                                        <td><a href="{{ $video->url ?? '#' }}" target="_blank" class="text-primary">{{ \Illuminate\Support\Str::limit($video->url ?? '', 40) }}</a></td>
                                        <td>{{ $video->created_at ? $video->created_at->format('M d, Y') : '' }}</td>
                                        <td><a href="{{ route('gallery-admin.videos.show', $video->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="empty-state">
                            <div class="empty-state-icon"><i class="fas fa-video"></i></div>
                            <h5>{{ __('app.no_videos') }}</h5>
                            <p class="text-muted">{{ __('app.upload_first_video') }}</p>
                            <a href="{{ route('gallery-admin.videos.create') }}" class="btn" style="background:var(--admin-primary);color:#fff;">{{ __('app.upload_video') }}</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

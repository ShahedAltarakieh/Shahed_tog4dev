<div class="row mt-3 mb-3">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:12px;">
            <div>
                <nav class="breadcrumb-modern" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1" style="background:transparent;padding:0;margin:0;">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $label_name }}</li>
                    </ol>
                </nav>
                <h4 class="page-title mb-0">
                    {{ $label_name }}
                    @if(isset($type) && !empty($type))
                        <span style="font-weight:400;color:var(--admin-gray-400);"> / </span>
                        <span style="font-weight:500;color:var(--admin-gray-600);">{{ __('app.'.$type) }}</span>
                    @endif
                </h4>
            </div>
            <div class="d-flex align-items-center" style="gap:8px;">
                @if(isset($download_button) && $download_button)
                    <a class="btn btn-primary btn-sm" href="{{ $download_button }}"><i class="fas fa-download mr-1"></i> {{ __('app.download') }}</a>
                @endif
                @if(isset($download_template) && $download_template)
                    <a class="btn btn-success btn-sm" href="{{ $download_template }}"><i class="fas fa-download mr-1"></i> {{ __('app.template sheet') }}</a>
                @endif
                @if(isset($sorting_btn) && $sorting_btn)
                    <a class="btn btn-light btn-sm" href="{{ $sorting_btn }}"><i class="fas fa-sort mr-1"></i> {{ __('app.sorting') }}</a>
                @endif
                
                @if(isset($add_button) && $add_button)
                    <a class="btn btn-primary btn-sm" href="{{ $add_button }}"><i class="fas fa-plus mr-1"></i> {{ __('app.add new') }}</a>
                @elseif(isset($show_read) && $show_read)
                    @if(Route::is('contact_us.showRead'))
                        <a href="{{ route('contact_us.index', ['type' => $type]) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i> {{ __('app.show_unread') }}
                        </a>
                    @else
                        <a href="{{ $show_read }}" class="btn btn-light btn-sm">
                            <i class="fas fa-eye mr-1"></i> {{ __('app.show_read') }}
                        </a>
                    @endif
                @endif
                <a class="btn btn-light btn-sm" onclick="history.back()" href="javascript:void(0)"><i class="fas fa-arrow-left mr-1"></i> {{ __('app.back') }}</a>
            </div>
        </div>
    </div>
</div>

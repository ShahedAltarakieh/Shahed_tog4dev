<div class="row mt-4">
    <div class="col-12">
        <div class="widget-rounded-circle card-box d-flex justify-content-between">
            <h4 class="page-title">
                {{ $label_name }}
                @if(isset($type) && !empty($type))
                    - {{ __('app.'.$type) }}
                @endif
            </h4>
            <div>
                @if(isset($download_button) && $download_button)
                    <a class="btn btn-primary" href="{{ $download_button }}">{{ __('app.download') }}</a>
                @endif
                @if(isset($download_template) && $download_template)
                    <a class="btn btn-success" href="{{ $download_template }}"><i class="fas fa-download"></i> {{ __('app.template sheet') }}</a>
                @endif
                @if(isset($sorting_btn) && $sorting_btn)
                    <a class="btn btn-success" href="{{ $sorting_btn }}">⋮⋮ {{ __('app.sorting') }}</a>
                @endif
                
                @if(isset($add_button) && $add_button)
                    <a class="btn btn-primary" href="{{ $add_button }}">{{ __('app.add new') }}</a>
                @elseif(isset($show_read) && $show_read)
                    @if(Route::is('contact_us.showRead'))
                        <!-- If the current route is 'showRead', show a 'Back to Index' button -->
                        <a href="{{ route('contact_us.index', ['type' => $type]) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('app.show_unread') }}
                        </a>
                    @else
                        <!-- Otherwise, show a 'Show Read' button -->
                        <a href="{{ $show_read }}" class="btn btn-secondary">
                            <i class="fas fa-eye"></i> {{ __('app.show_read') }}
                        </a>
                    @endif
                @endif
                <a class="btn btn-info" onclick="history.back()" href="javascript:void(0)">{{ __('app.back') }}</a>
            </div>
        </div>
    </div>
</div>

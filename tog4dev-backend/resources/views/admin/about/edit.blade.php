@extends('layouts.admin.add')
@section('title'){{ __('app.edit') }} - {{ __('app.about us') }} CMS @endsection

@section('content')

<div class="page-header-box mb-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('about-admin.index') }}" class="page-back-btn"><i class="fa fa-arrow-left"></i></a>
            <h4 class="mb-0">{{ __('app.about us') }} CMS —
                <span class="badge badge-info">{{ strtoupper($page->country_code) }}</span>
                @if($page->status === 'published')
                    <span class="badge badge-success">{{ __('app.published') }} v{{ $page->version }}</span>
                @else
                    <span class="badge badge-warning">{{ __('app.draft') }}</span>
                @endif
            </h4>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('about-admin.preview', $page->id) }}" target="_blank" class="btn btn-outline-info btn-sm">
                <i class="fas fa-eye me-1"></i> {{ __('app.preview') }}
            </a>
            @if($page->status === 'draft')
                <button class="btn btn-success btn-sm btn-publish" data-id="{{ $page->id }}">
                    <i class="fas fa-rocket me-1"></i> {{ __('app.publish') }}
                </button>
            @else
                <button class="btn btn-warning btn-sm btn-unpublish" data-id="{{ $page->id }}">
                    <i class="fas fa-pause me-1"></i> {{ __('app.unpublish') }}
                </button>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card card-box mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-layer-group me-2"></i>{{ __('app.sections') }}</h5>
                <small class="text-muted">{{ __('app.drag to reorder') }}</small>
            </div>
            <div class="card-body p-0">
                <div id="sections-list">
                    @foreach($page->sections->sortBy('sort_order') as $section)
                    <div class="section-row" data-section-id="{{ $section->id }}">
                        <div class="section-header d-flex align-items-center justify-content-between p-3 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <span class="drag-handle" style="cursor:grab;color:#aaa"><i class="fas fa-grip-vertical"></i></span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input toggle-visibility"
                                        id="vis-{{ $section->id }}" data-section-id="{{ $section->id }}"
                                        {{ $section->is_visible ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="vis-{{ $section->id }}"></label>
                                </div>
                                <span class="badge badge-secondary text-uppercase">{{ $section->section_key }}</span>
                                <span class="section-title-preview text-muted small">{{ $section->title ?: $section->title_en ?: '' }}</span>
                            </div>
                            <button class="btn btn-sm btn-outline-primary btn-edit-section" data-toggle="collapse" data-target="#section-body-{{ $section->id }}">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                        </div>
                        <div class="collapse" id="section-body-{{ $section->id }}">
                            <div class="p-3 bg-light">
                                <form class="section-form" data-page-id="{{ $page->id }}" data-section-id="{{ $section->id }}">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="small fw-bold">Title (AR)</label>
                                            <input type="text" name="title" class="form-control form-control-sm" value="{{ $section->title }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="small fw-bold">Title (EN)</label>
                                            <input type="text" name="title_en" class="form-control form-control-sm" value="{{ $section->title_en }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="small fw-bold">Subtitle (AR)</label>
                                            <input type="text" name="subtitle" class="form-control form-control-sm" value="{{ $section->subtitle }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="small fw-bold">Subtitle (EN)</label>
                                            <input type="text" name="subtitle_en" class="form-control form-control-sm" value="{{ $section->subtitle_en }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="small fw-bold">Body (AR)</label>
                                            <textarea name="body" class="form-control form-control-sm" rows="4">{{ $section->body }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="small fw-bold">Body (EN)</label>
                                            <textarea name="body_en" class="form-control form-control-sm" rows="4">{{ $section->body_en }}</textarea>
                                        </div>
                                    </div>

                                    @if(in_array($section->section_key, ['hero']))
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="small fw-bold">Image</label>
                                            <input type="file" name="section_image" class="form-control form-control-sm" accept="image/*">
                                            @if($section->image)
                                                <img src="{{ $section->image }}" class="mt-2 rounded" style="max-height:60px">
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <label class="small fw-bold">Video URL</label>
                                            <input type="text" name="video_url" class="form-control form-control-sm" value="{{ $section->video_url }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label class="small fw-bold">CTA Text (AR)</label>
                                            <input type="text" name="cta_text" class="form-control form-control-sm" value="{{ $section->cta_text }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small fw-bold">CTA Text (EN)</label>
                                            <input type="text" name="cta_text_en" class="form-control form-control-sm" value="{{ $section->cta_text_en }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small fw-bold">CTA Link (AR)</label>
                                            <input type="text" name="cta_link" class="form-control form-control-sm" value="{{ $section->cta_link }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small fw-bold">CTA Link (EN)</label>
                                            <input type="text" name="cta_link_en" class="form-control form-control-sm" value="{{ $section->cta_link_en }}">
                                        </div>
                                    </div>
                                    @endif

                                    @if(in_array($section->section_key, ['partners']))
                                    <div class="form-group mb-3">
                                        <label class="small fw-bold">Layout</label>
                                        <select name="layout" class="form-control form-control-sm">
                                            <option value="grid" {{ $section->layout === 'grid' ? 'selected' : '' }}>Grid</option>
                                            <option value="carousel" {{ $section->layout === 'carousel' ? 'selected' : '' }}>Carousel</option>
                                        </select>
                                    </div>
                                    @endif

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-save me-1"></i> {{ __('app.save') }}
                                        </button>
                                    </div>
                                </form>

                                @if(in_array($section->section_key, ['highlights', 'coreValues', 'founders', 'stats', 'partners', 'beliefs']))
                                <hr>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0"><i class="fas fa-list me-1"></i> Items</h6>
                                    <button class="btn btn-sm btn-outline-success btn-add-item" data-page-id="{{ $page->id }}" data-section-id="{{ $section->id }}" data-section-key="{{ $section->section_key }}">
                                        <i class="fas fa-plus me-1"></i> Add Item
                                    </button>
                                </div>
                                <div class="items-list" data-section-id="{{ $section->id }}">
                                    @foreach($section->items->sortBy('sort_order') as $item)
                                    <div class="item-row border rounded p-2 mb-2 bg-white" data-item-id="{{ $item->id }}">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="item-drag-handle" style="cursor:grab;color:#ccc"><i class="fas fa-grip-vertical"></i></span>
                                                @if($item->image)
                                                    <img src="{{ $item->image }}" class="rounded" style="width:30px;height:30px;object-fit:cover">
                                                @endif
                                                <span class="small">{{ $item->title ?: $item->title_en ?: $item->value ?: 'Item #'.$item->id }}</span>
                                            </div>
                                            <div class="d-flex gap-1">
                                                <button class="btn btn-xs btn-outline-primary btn-edit-item" data-page-id="{{ $page->id }}" data-section-id="{{ $section->id }}" data-item-id="{{ $item->id }}" data-item='@json($item)'>
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-xs btn-outline-danger btn-delete-item" data-page-id="{{ $page->id }}" data-section-id="{{ $section->id }}" data-item-id="{{ $item->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-box mb-4">
            <div class="card-header"><h6 class="mb-0"><i class="fas fa-cog me-2"></i>{{ __('app.settings') }}</h6></div>
            <div class="card-body">
                <form action="{{ route('about-admin.update', $page->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label class="small fw-bold">{{ __('app.country') }}</label>
                        <select name="country_code" class="form-control form-control-sm">
                            <option value="global" {{ $page->country_code === 'global' ? 'selected' : '' }}>🌍 Global</option>
                            <option value="JO" {{ $page->country_code === 'JO' ? 'selected' : '' }}>🇯🇴 Jordan</option>
                            <option value="PS" {{ $page->country_code === 'PS' ? 'selected' : '' }}>🇵🇸 Palestine</option>
                            <option value="SA" {{ $page->country_code === 'SA' ? 'selected' : '' }}>🇸🇦 Saudi Arabia</option>
                            <option value="AE" {{ $page->country_code === 'AE' ? 'selected' : '' }}>🇦🇪 UAE</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="small fw-bold">Meta Title (AR)</label>
                        <input type="text" name="meta_title" class="form-control form-control-sm" value="{{ $page->meta_title }}">
                    </div>
                    <div class="form-group mb-3">
                        <label class="small fw-bold">Meta Title (EN)</label>
                        <input type="text" name="meta_title_en" class="form-control form-control-sm" value="{{ $page->meta_title_en }}">
                    </div>
                    <div class="form-group mb-3">
                        <label class="small fw-bold">Meta Desc (AR)</label>
                        <textarea name="meta_description" class="form-control form-control-sm" rows="2">{{ $page->meta_description }}</textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label class="small fw-bold">Meta Desc (EN)</label>
                        <textarea name="meta_description_en" class="form-control form-control-sm" rows="2">{{ $page->meta_description_en }}</textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label class="small fw-bold">OG Image</label>
                        <input type="file" name="og_image_file" class="form-control form-control-sm" accept="image/*">
                        @if($page->og_image)
                            <img src="{{ $page->og_image }}" class="mt-2 rounded" style="max-height:60px">
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-save me-1"></i> {{ __('app.save') }} {{ __('app.settings') }}
                    </button>
                </form>
            </div>
        </div>

        @if($versions->count() > 0)
        <div class="card card-box mb-4">
            <div class="card-header"><h6 class="mb-0"><i class="fas fa-history me-2"></i>{{ __('app.version history') }}</h6></div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($versions as $v)
                    <div class="list-group-item d-flex justify-content-between align-items-center small">
                        <div>
                            <strong>v{{ $v->version }}</strong> — {{ $v->action }}
                            <br><span class="text-muted">{{ $v->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                        <button class="btn btn-xs btn-outline-warning btn-rollback" data-page-id="{{ $page->id }}" data-version-id="{{ $v->id }}">
                            <i class="fas fa-undo"></i>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="modal fade" id="itemModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Item</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="itemForm" enctype="multipart/form-data">
                    <input type="hidden" id="item-page-id">
                    <input type="hidden" id="item-section-id">
                    <input type="hidden" id="item-id">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="small fw-bold">Title (AR)</label>
                            <input type="text" id="item-title" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold">Title (EN)</label>
                            <input type="text" id="item-title-en" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="small fw-bold">Description (AR)</label>
                            <textarea id="item-description" class="form-control form-control-sm" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold">Description (EN)</label>
                            <textarea id="item-description-en" class="form-control form-control-sm" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="small fw-bold">Value</label>
                            <input type="text" id="item-value" class="form-control form-control-sm" placeholder="+2,000,000">
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold">Label (AR)</label>
                            <input type="text" id="item-label" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="small fw-bold">Label (EN)</label>
                            <input type="text" id="item-label-en" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="small fw-bold">Icon (FontAwesome class)</label>
                            <input type="text" id="item-icon" class="form-control form-control-sm" placeholder="fas fa-heart">
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold">Image</label>
                            <input type="file" id="item-image" class="form-control form-control-sm" accept="image/*">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="small fw-bold">Link (AR)</label>
                            <input type="text" id="item-link" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold">Link (EN)</label>
                            <input type="text" id="item-link-en" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="small fw-bold">Social Links (JSON)</label>
                        <textarea id="item-social-links" class="form-control form-control-sm" rows="2" placeholder='{"linkedin":"url","twitter":"url"}'></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{ __('app.cancel') }}</button>
                <button type="button" class="btn btn-primary btn-sm" id="saveItemBtn">
                    <i class="fas fa-save me-1"></i> {{ __('app.save') }}
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
$(document).ready(function() {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    new Sortable(document.getElementById('sections-list'), {
        handle: '.drag-handle',
        animation: 150,
        onEnd: function() {
            var order = [];
            $('#sections-list .section-row').each(function() {
                order.push($(this).data('section-id'));
            });
            $.post('/about-management/{{ $page->id }}/sections/reorder', {
                _token: csrfToken, order: order
            });
        }
    });

    document.querySelectorAll('.items-list').forEach(function(el) {
        new Sortable(el, {
            handle: '.item-drag-handle',
            animation: 150,
            onEnd: function() {
                var sectionId = el.dataset.sectionId;
                var order = [];
                $(el).find('.item-row').each(function() { order.push($(this).data('item-id')); });
                $.post('/about-management/{{ $page->id }}/sections/' + sectionId + '/items/reorder', {
                    _token: csrfToken, order: order
                });
            }
        });
    });

    $('.section-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var pageId = form.data('page-id');
        var sectionId = form.data('section-id');
        var formData = new FormData(this);
        formData.append('_token', csrfToken);

        $.ajax({
            url: '/about-management/' + pageId + '/sections/' + sectionId,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                if (window.AdminToast) AdminToast.show(res.message || 'Saved', 'success');
                else alert('Saved!');
            },
            error: function() { alert('Error saving section'); }
        });
    });

    $('.toggle-visibility').on('change', function() {
        var sectionId = $(this).data('section-id');
        $.post('/about-management/{{ $page->id }}/sections/' + sectionId + '/toggle', { _token: csrfToken });
    });

    $('.btn-publish').on('click', function() {
        $.post('/about-management/' + $(this).data('id') + '/publish', { _token: csrfToken }, function() {
            location.reload();
        });
    });

    $('.btn-unpublish').on('click', function() {
        $.post('/about-management/' + $(this).data('id') + '/unpublish', { _token: csrfToken }, function() {
            location.reload();
        });
    });

    $('.btn-rollback').on('click', function() {
        var pageId = $(this).data('page-id');
        var versionId = $(this).data('version-id');
        Swal.fire({
            title: 'Rollback to this version?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Rollback'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.post('/about-management/' + pageId + '/rollback/' + versionId, { _token: csrfToken }, function() {
                    location.reload();
                });
            }
        });
    });

    $('.btn-add-item').on('click', function() {
        $('#item-id').val('');
        $('#item-page-id').val($(this).data('page-id'));
        $('#item-section-id').val($(this).data('section-id'));
        $('#itemForm')[0].reset();
        $('#itemModal .modal-title').text('Add Item');
        $('#itemModal').modal('show');
    });

    $(document).on('click', '.btn-edit-item', function() {
        var item = $(this).data('item');
        $('#item-page-id').val($(this).data('page-id'));
        $('#item-section-id').val($(this).data('section-id'));
        $('#item-id').val($(this).data('item-id'));
        $('#item-title').val(item.title || '');
        $('#item-title-en').val(item.title_en || '');
        $('#item-description').val(item.description || '');
        $('#item-description-en').val(item.description_en || '');
        $('#item-value').val(item.value || '');
        $('#item-label').val(item.label || '');
        $('#item-label-en').val(item.label_en || '');
        $('#item-icon').val(item.icon || '');
        $('#item-link').val(item.link || '');
        $('#item-link-en').val(item.link_en || '');
        $('#item-social-links').val(item.social_links ? JSON.stringify(item.social_links) : '');
        $('#itemModal .modal-title').text('Edit Item');
        $('#itemModal').modal('show');
    });

    $('#saveItemBtn').on('click', function() {
        var pageId = $('#item-page-id').val();
        var sectionId = $('#item-section-id').val();
        var itemId = $('#item-id').val();

        var formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('title', $('#item-title').val());
        formData.append('title_en', $('#item-title-en').val());
        formData.append('description', $('#item-description').val());
        formData.append('description_en', $('#item-description-en').val());
        formData.append('value', $('#item-value').val());
        formData.append('label', $('#item-label').val());
        formData.append('label_en', $('#item-label-en').val());
        formData.append('icon', $('#item-icon').val());
        formData.append('link', $('#item-link').val());
        formData.append('link_en', $('#item-link-en').val());
        formData.append('social_links', $('#item-social-links').val() || '{}');

        var imageFile = document.getElementById('item-image').files[0];
        if (imageFile) formData.append('item_image', imageFile);

        var url = itemId
            ? '/about-management/' + pageId + '/sections/' + sectionId + '/items/' + itemId
            : '/about-management/' + pageId + '/sections/' + sectionId + '/items';

        $.ajax({
            url: url, type: 'POST', data: formData,
            processData: false, contentType: false,
            success: function() { $('#itemModal').modal('hide'); location.reload(); },
            error: function() { alert('Error saving item'); }
        });
    });

    $(document).on('click', '.btn-delete-item', function() {
        var btn = $(this);
        Swal.fire({
            title: '{{ __("app.are you sure?") }}', icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33',
            confirmButtonText: '{{ __("app.delete") }}', cancelButtonText: '{{ __("app.cancel") }}'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/about-management/' + btn.data('page-id') + '/sections/' + btn.data('section-id') + '/items/' + btn.data('item-id'),
                    type: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken },
                    success: function() { btn.closest('.item-row').remove(); }
                });
            }
        });
    });
});
</script>
@endsection

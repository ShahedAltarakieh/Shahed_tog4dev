@extends('layouts.admin.add')
@section('title'){{ __('app.edit') }} - {{ __('app.about us') }} CMS @endsection

@section('content')

<style>
.cms-page-header {
    background: linear-gradient(135deg, rgba(19,88,93,0.04), rgba(254,205,15,0.04));
    border-radius: 16px;
    padding: 20px 24px;
    margin-bottom: 24px;
    border: 1px solid rgba(19,88,93,0.08);
}
.cms-page-header h4 { font-weight: 700; font-size: 1.1rem; color: #1a1a1a; }
.cms-back-btn {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: rgba(19,88,93,0.08);
    color: #13585D;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    text-decoration: none;
    border: none;
}
.cms-back-btn:hover { background: #13585D; color: #fff; }
.cms-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 10px;
    font-size: 0.82rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.25s ease;
}
.cms-action-btn.preview { background: rgba(19,88,93,0.08); color: #13585D; text-decoration: none; }
.cms-action-btn.preview:hover { background: rgba(19,88,93,0.15); }
.cms-action-btn.publish { background: rgba(40,167,69,0.1); color: #28a745; }
.cms-action-btn.publish:hover { background: #28a745; color: #fff; }
.cms-action-btn.unpublish { background: rgba(255,193,7,0.1); color: #e6a800; }
.cms-action-btn.unpublish:hover { background: #e6a800; color: #fff; }

.cms-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 600;
}
.cms-status-badge.published { background: rgba(40,167,69,0.1); color: #28a745; }
.cms-status-badge.draft { background: rgba(255,193,7,0.1); color: #e6a800; }
.cms-status-badge .dot {
    width: 6px; height: 6px; border-radius: 50%;
}
.cms-status-badge.published .dot { background: #28a745; }
.cms-status-badge.draft .dot { background: #e6a800; }

.cms-country-tag {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 12px;
    border-radius: 8px;
    font-size: 0.82rem;
    font-weight: 600;
    background: rgba(19,88,93,0.06);
    color: #13585D;
}

.cms-sections-card {
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,0.06);
    overflow: hidden;
}
.cms-sections-card > .card-header {
    background: #fff;
    padding: 16px 20px;
    border-bottom: 1px solid rgba(0,0,0,0.06);
}
.cms-sections-card > .card-header h5 { font-weight: 700; font-size: 1rem; }

.section-row {
    border-bottom: 1px solid rgba(0,0,0,0.04);
    transition: background 0.2s ease;
}
.section-row:last-child { border-bottom: none; }
.section-row:hover { background: rgba(19,88,93,0.01); }

.section-header {
    padding: 14px 20px;
}

.drag-handle {
    cursor: grab;
    color: #ccc;
    transition: color 0.2s;
    font-size: 0.9rem;
    padding: 4px;
}
.drag-handle:hover { color: #13585D; }

.section-key-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.section-key-badge.key-hero { background: rgba(19,88,93,0.1); color: #13585D; }
.section-key-badge.key-intro { background: rgba(99,102,241,0.1); color: #6366f1; }
.section-key-badge.key-highlights { background: rgba(245,158,11,0.1); color: #d97706; }
.section-key-badge.key-statement { background: rgba(16,185,129,0.1); color: #059669; }
.section-key-badge.key-visionMission { background: rgba(139,92,246,0.1); color: #7c3aed; }
.section-key-badge.key-coreValues { background: rgba(236,72,153,0.1); color: #db2777; }
.section-key-badge.key-founders { background: rgba(20,184,166,0.1); color: #0d9488; }
.section-key-badge.key-beliefs { background: rgba(251,146,60,0.1); color: #ea580c; }
.section-key-badge.key-stats { background: rgba(59,130,246,0.1); color: #2563eb; }
.section-key-badge.key-slogan { background: rgba(168,85,247,0.1); color: #9333ea; }
.section-key-badge.key-contact { background: rgba(34,197,94,0.1); color: #16a34a; }
.section-key-badge.key-partners { background: rgba(107,114,128,0.1); color: #4b5563; }

.section-title-preview {
    font-size: 0.85rem;
    color: #888;
    font-weight: 500;
}

.section-edit-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: rgba(19,88,93,0.06);
    color: #13585D;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    transition: all 0.2s ease;
}
.section-edit-btn:hover { background: #13585D; color: #fff; }

.section-form-area {
    padding: 20px;
    background: rgba(19,88,93,0.02);
    border-top: 1px solid rgba(0,0,0,0.04);
}
.section-form-area label {
    font-size: 0.78rem;
    font-weight: 600;
    color: #555;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    margin-bottom: 4px;
}
.section-form-area .form-control {
    border-radius: 8px;
    border: 1px solid rgba(0,0,0,0.08);
    font-size: 0.88rem;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}
.section-form-area .form-control:focus {
    border-color: #13585D;
    box-shadow: 0 0 0 3px rgba(19,88,93,0.08);
}
.section-save-btn {
    background: linear-gradient(135deg, #13585D, #0d4f4f);
    color: #fff;
    border: none;
    padding: 8px 20px;
    border-radius: 10px;
    font-size: 0.82rem;
    font-weight: 600;
    transition: all 0.2s ease;
}
.section-save-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(19,88,93,0.2); }

.items-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 16px 0 12px;
    padding-top: 16px;
    border-top: 1px dashed rgba(0,0,0,0.08);
}
.items-header h6 {
    font-size: 0.85rem;
    font-weight: 700;
    color: #555;
}
.add-item-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 5px 14px;
    border-radius: 8px;
    font-size: 0.78rem;
    font-weight: 600;
    background: rgba(40,167,69,0.08);
    color: #28a745;
    border: none;
    transition: all 0.2s ease;
}
.add-item-btn:hover { background: #28a745; color: #fff; }

.item-row {
    border: 1px solid rgba(0,0,0,0.06);
    border-radius: 10px;
    padding: 10px 14px;
    margin-bottom: 8px;
    background: #fff;
    transition: all 0.2s ease;
}
.item-row:hover {
    border-color: rgba(19,88,93,0.15);
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.item-drag-handle { cursor: grab; color: #ddd; padding: 0 4px; }
.item-drag-handle:hover { color: #13585D; }
.item-thumb {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    object-fit: cover;
}
.item-name {
    font-size: 0.85rem;
    font-weight: 500;
    color: #333;
}
.item-action-btn {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.72rem;
    transition: all 0.2s ease;
}
.item-action-btn.edit { background: rgba(19,88,93,0.06); color: #13585D; }
.item-action-btn.edit:hover { background: #13585D; color: #fff; }
.item-action-btn.delete { background: rgba(220,53,69,0.06); color: #dc3545; }
.item-action-btn.delete:hover { background: #dc3545; color: #fff; }

.cms-sidebar-card {
    border-radius: 16px;
    border: 1px solid rgba(0,0,0,0.06);
    overflow: hidden;
    margin-bottom: 20px;
}
.cms-sidebar-card .card-header {
    background: #fff;
    padding: 14px 18px;
    border-bottom: 1px solid rgba(0,0,0,0.06);
}
.cms-sidebar-card .card-header h6 { font-weight: 700; font-size: 0.9rem; color: #1a1a1a; }
.cms-sidebar-card .card-body { padding: 18px; }
.cms-sidebar-card label {
    font-size: 0.78rem;
    font-weight: 600;
    color: #555;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    margin-bottom: 4px;
}
.cms-sidebar-card .form-control {
    border-radius: 8px;
    border: 1px solid rgba(0,0,0,0.08);
    font-size: 0.88rem;
}
.cms-sidebar-card .form-control:focus {
    border-color: #13585D;
    box-shadow: 0 0 0 3px rgba(19,88,93,0.08);
}
.settings-save-btn {
    background: linear-gradient(135deg, #13585D, #0d4f4f);
    color: #fff;
    border: none;
    padding: 10px 0;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.88rem;
    width: 100%;
    transition: all 0.2s ease;
}
.settings-save-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(19,88,93,0.2); }

.version-list .list-group-item {
    padding: 12px 16px;
    border: none;
    border-bottom: 1px solid rgba(0,0,0,0.04);
    transition: background 0.2s;
}
.version-list .list-group-item:hover { background: rgba(19,88,93,0.02); }
.version-list .list-group-item:last-child { border-bottom: none; }
.version-tag-sm {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    padding: 2px 8px;
    border-radius: 5px;
    font-size: 0.72rem;
    font-weight: 700;
    background: rgba(19,88,93,0.08);
    color: #13585D;
}
.version-action {
    font-size: 0.8rem;
    color: #666;
}
.version-time {
    font-size: 0.72rem;
    color: #aaa;
}
.rollback-btn {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    background: rgba(255,193,7,0.08);
    color: #e6a800;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.72rem;
    transition: all 0.2s ease;
}
.rollback-btn:hover { background: #e6a800; color: #fff; }

.custom-switch-modern .custom-control-input:checked ~ .custom-control-label::before {
    background-color: #13585D;
    border-color: #13585D;
}

.cms-modal .modal-content {
    border-radius: 16px;
    border: none;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
}
.cms-modal .modal-header {
    padding: 18px 24px;
    border-bottom: 1px solid rgba(0,0,0,0.06);
}
.cms-modal .modal-title {
    font-weight: 700;
    font-size: 1rem;
}
.cms-modal .modal-body {
    padding: 24px;
}
.cms-modal .modal-body label {
    font-size: 0.78rem;
    font-weight: 600;
    color: #555;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}
.cms-modal .modal-body .form-control {
    border-radius: 8px;
    border: 1px solid rgba(0,0,0,0.08);
    font-size: 0.88rem;
}
.cms-modal .modal-body .form-control:focus {
    border-color: #13585D;
    box-shadow: 0 0 0 3px rgba(19,88,93,0.08);
}
.cms-modal .modal-footer {
    padding: 14px 24px;
    border-top: 1px solid rgba(0,0,0,0.06);
}
.modal-save-btn {
    background: linear-gradient(135deg, #13585D, #0d4f4f);
    color: #fff;
    border: none;
    padding: 8px 24px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.2s;
}
.modal-save-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(19,88,93,0.2); }
.modal-cancel-btn {
    background: rgba(108,117,125,0.08);
    color: #6c757d;
    border: none;
    padding: 8px 20px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.85rem;
}

.og-preview { max-height: 60px; border-radius: 8px; margin-top: 8px; border: 1px solid rgba(0,0,0,0.06); }
</style>

@php
$sectionIcons = [
    'hero' => 'fas fa-flag',
    'intro' => 'fas fa-book-open',
    'highlights' => 'fas fa-star',
    'statement' => 'fas fa-quote-right',
    'visionMission' => 'fas fa-compass',
    'coreValues' => 'fas fa-gem',
    'founders' => 'fas fa-users',
    'beliefs' => 'fas fa-lightbulb',
    'stats' => 'fas fa-chart-bar',
    'slogan' => 'fas fa-bullhorn',
    'contact' => 'fas fa-envelope',
    'partners' => 'fas fa-handshake',
];
@endphp

<div class="cms-page-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('about-admin.index') }}" class="cms-back-btn"><i class="fa fa-arrow-left"></i></a>
            <div>
                <h4 class="mb-1">
                    {{ __('app.about us') }} CMS
                </h4>
                <div class="d-flex align-items-center gap-2">
                    <span class="cms-country-tag">
                        @php
                            $flags = ['JO'=>'🇯🇴','PS'=>'🇵🇸','SA'=>'🇸🇦','AE'=>'🇦🇪','global'=>'🌍'];
                        @endphp
                        {{ $flags[$page->country_code] ?? '🌐' }}
                        {{ strtoupper($page->country_code) }}
                    </span>
                    @if($page->status === 'published')
                        <span class="cms-status-badge published"><span class="dot"></span> {{ __('app.published') }} v{{ $page->version }}</span>
                    @else
                        <span class="cms-status-badge draft"><span class="dot"></span> {{ __('app.draft') }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="d-flex gap-2">
            @if($page->status === 'draft')
                <button class="cms-action-btn publish btn-publish" data-id="{{ $page->id }}">
                    <i class="fas fa-rocket"></i> {{ __('app.publish') }}
                </button>
            @else
                <button class="cms-action-btn unpublish btn-unpublish" data-id="{{ $page->id }}">
                    <i class="fas fa-pause"></i> {{ __('app.unpublish') }}
                </button>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card cms-sections-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-layer-group me-2" style="color:#13585D"></i>{{ __('app.sections') }}</h5>
                <small style="color:#aaa;font-size:0.78rem"><i class="fas fa-grip-vertical me-1"></i>{{ __('app.drag to reorder') }}</small>
            </div>
            <div class="card-body p-0">
                <div id="sections-list">
                    @foreach($page->sections->sortBy('sort_order') as $section)
                    <div class="section-row" data-section-id="{{ $section->id }}">
                        <div class="section-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <span class="drag-handle"><i class="fas fa-grip-vertical"></i></span>
                                <div class="custom-control custom-switch custom-switch-modern">
                                    <input type="checkbox" class="custom-control-input toggle-visibility"
                                        id="vis-{{ $section->id }}" data-section-id="{{ $section->id }}"
                                        {{ $section->is_visible ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="vis-{{ $section->id }}"></label>
                                </div>
                                <span class="section-key-badge key-{{ $section->section_key }}">
                                    <i class="{{ $sectionIcons[$section->section_key] ?? 'fas fa-puzzle-piece' }}"></i>
                                    {{ $section->section_key }}
                                </span>
                                <span class="section-title-preview">{{ Str::limit($section->title ?: $section->title_en ?: '', 40) }}</span>
                            </div>
                            <button class="section-edit-btn btn-edit-section" data-toggle="collapse" data-target="#section-body-{{ $section->id }}">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                        </div>
                        <div class="collapse" id="section-body-{{ $section->id }}">
                            <div class="section-form-area">
                                <form class="section-form" data-page-id="{{ $page->id }}" data-section-id="{{ $section->id }}">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label>Title (AR)</label>
                                            <input type="text" name="title" class="form-control form-control-sm" value="{{ $section->title }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Title (EN)</label>
                                            <input type="text" name="title_en" class="form-control form-control-sm" value="{{ $section->title_en }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label>Subtitle (AR)</label>
                                            <input type="text" name="subtitle" class="form-control form-control-sm" value="{{ $section->subtitle }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Subtitle (EN)</label>
                                            <input type="text" name="subtitle_en" class="form-control form-control-sm" value="{{ $section->subtitle_en }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label>Body (AR)</label>
                                            <textarea name="body" class="form-control form-control-sm" rows="4">{{ $section->body }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Body (EN)</label>
                                            <textarea name="body_en" class="form-control form-control-sm" rows="4">{{ $section->body_en }}</textarea>
                                        </div>
                                    </div>

                                    @if(in_array($section->section_key, ['hero']))
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label>Image</label>
                                            <input type="file" name="section_image" class="form-control form-control-sm" accept="image/*">
                                            @if($section->image)
                                                <img src="{{ $section->image }}" class="og-preview">
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <label>Video URL</label>
                                            <input type="text" name="video_url" class="form-control form-control-sm" value="{{ $section->video_url }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label>CTA Text (AR)</label>
                                            <input type="text" name="cta_text" class="form-control form-control-sm" value="{{ $section->cta_text }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label>CTA Text (EN)</label>
                                            <input type="text" name="cta_text_en" class="form-control form-control-sm" value="{{ $section->cta_text_en }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label>CTA Link (AR)</label>
                                            <input type="text" name="cta_link" class="form-control form-control-sm" value="{{ $section->cta_link }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label>CTA Link (EN)</label>
                                            <input type="text" name="cta_link_en" class="form-control form-control-sm" value="{{ $section->cta_link_en }}">
                                        </div>
                                    </div>
                                    @endif

                                    @if(in_array($section->section_key, ['partners']))
                                    <div class="form-group mb-3">
                                        <label>Layout</label>
                                        <select name="layout" class="form-control form-control-sm">
                                            <option value="grid" {{ $section->layout === 'grid' ? 'selected' : '' }}>Grid</option>
                                            <option value="carousel" {{ $section->layout === 'carousel' ? 'selected' : '' }}>Carousel</option>
                                        </select>
                                    </div>
                                    @endif

                                    <div class="text-end">
                                        <button type="submit" class="section-save-btn">
                                            <i class="fas fa-save me-1"></i> {{ __('app.save') }}
                                        </button>
                                    </div>
                                </form>

                                @if(in_array($section->section_key, ['highlights', 'coreValues', 'founders', 'stats', 'partners', 'beliefs', 'visionMission', 'contact']))
                                <div class="items-header">
                                    <h6 class="mb-0"><i class="fas fa-th-list me-1" style="color:#13585D"></i> Items ({{ $section->items->count() }})</h6>
                                    <button class="add-item-btn btn-add-item" data-page-id="{{ $page->id }}" data-section-id="{{ $section->id }}" data-section-key="{{ $section->section_key }}">
                                        <i class="fas fa-plus"></i> Add Item
                                    </button>
                                </div>
                                <div class="items-list" data-section-id="{{ $section->id }}">
                                    @foreach($section->items->sortBy('sort_order') as $item)
                                    <div class="item-row" data-item-id="{{ $item->id }}">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="item-drag-handle"><i class="fas fa-grip-vertical"></i></span>
                                                @if($item->image)
                                                    <img src="{{ $item->image }}" class="item-thumb">
                                                @elseif($item->icon)
                                                    <span style="width:32px;height:32px;border-radius:8px;background:rgba(19,88,93,0.06);color:#13585D;display:inline-flex;align-items:center;justify-content:center;font-size:0.8rem"><i class="{{ $item->icon }}"></i></span>
                                                @endif
                                                <span class="item-name">{{ $item->title ?: $item->title_en ?: $item->value ?: 'Item #'.$item->id }}</span>
                                            </div>
                                            <div class="d-flex gap-1">
                                                <button class="item-action-btn edit btn-edit-item" data-page-id="{{ $page->id }}" data-section-id="{{ $section->id }}" data-item-id="{{ $item->id }}" data-item='@json($item)'>
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="item-action-btn delete btn-delete-item" data-page-id="{{ $page->id }}" data-section-id="{{ $section->id }}" data-item-id="{{ $item->id }}">
                                                    <i class="fas fa-trash-alt"></i>
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
        <div class="card cms-sidebar-card">
            <div class="card-header"><h6 class="mb-0"><i class="fas fa-cog me-2" style="color:#13585D"></i>{{ __('app.settings') }}</h6></div>
            <div class="card-body">
                <form action="{{ route('about-admin.update', $page->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label>{{ __('app.country') }}</label>
                        <select name="country_code" class="form-control form-control-sm">
                            <option value="global" {{ $page->country_code === 'global' ? 'selected' : '' }}>🌍 Global</option>
                            <option value="JO" {{ $page->country_code === 'JO' ? 'selected' : '' }}>🇯🇴 Jordan</option>
                            <option value="PS" {{ $page->country_code === 'PS' ? 'selected' : '' }}>🇵🇸 Palestine</option>
                            <option value="SA" {{ $page->country_code === 'SA' ? 'selected' : '' }}>🇸🇦 Saudi Arabia</option>
                            <option value="AE" {{ $page->country_code === 'AE' ? 'selected' : '' }}>🇦🇪 UAE</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Meta Title (AR)</label>
                        <input type="text" name="meta_title" class="form-control form-control-sm" value="{{ $page->meta_title }}">
                    </div>
                    <div class="form-group mb-3">
                        <label>Meta Title (EN)</label>
                        <input type="text" name="meta_title_en" class="form-control form-control-sm" value="{{ $page->meta_title_en }}">
                    </div>
                    <div class="form-group mb-3">
                        <label>Meta Desc (AR)</label>
                        <textarea name="meta_description" class="form-control form-control-sm" rows="2">{{ $page->meta_description }}</textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label>Meta Desc (EN)</label>
                        <textarea name="meta_description_en" class="form-control form-control-sm" rows="2">{{ $page->meta_description_en }}</textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label>OG Image</label>
                        <input type="file" name="og_image_file" class="form-control form-control-sm" accept="image/*">
                        @if($page->og_image)
                            <img src="{{ $page->og_image }}" class="og-preview">
                        @endif
                    </div>

                    <button type="submit" class="settings-save-btn">
                        <i class="fas fa-save me-1"></i> {{ __('app.save') }} {{ __('app.settings') }}
                    </button>
                </form>
            </div>
        </div>

        @if($versions->count() > 0)
        <div class="card cms-sidebar-card">
            <div class="card-header"><h6 class="mb-0"><i class="fas fa-history me-2" style="color:#13585D"></i>{{ __('app.version history') }}</h6></div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush version-list">
                    @foreach($versions as $v)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="version-tag-sm"><i class="fas fa-code-branch"></i> v{{ $v->version }}</span>
                            <span class="version-action ms-2">{{ $v->action }}</span>
                            <br><span class="version-time"><i class="far fa-clock me-1"></i>{{ $v->created_at->diffForHumans() }}</span>
                        </div>
                        <button class="rollback-btn btn-rollback" data-page-id="{{ $page->id }}" data-version-id="{{ $v->id }}" title="Rollback">
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

<div class="modal fade cms-modal" id="itemModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-cube me-2" style="color:#13585D"></i>Item</h5>
                <button type="button" class="close" data-dismiss="modal" style="background:none;border:none;font-size:1.2rem;color:#999"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="itemForm" enctype="multipart/form-data">
                    <input type="hidden" id="item-page-id">
                    <input type="hidden" id="item-section-id">
                    <input type="hidden" id="item-id">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Title (AR)</label>
                            <input type="text" id="item-title" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label>Title (EN)</label>
                            <input type="text" id="item-title-en" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Description (AR)</label>
                            <textarea id="item-description" class="form-control form-control-sm" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label>Description (EN)</label>
                            <textarea id="item-description-en" class="form-control form-control-sm" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Value</label>
                            <input type="text" id="item-value" class="form-control form-control-sm" placeholder="+2,000,000">
                        </div>
                        <div class="col-md-4">
                            <label>Label (AR)</label>
                            <input type="text" id="item-label" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label>Label (EN)</label>
                            <input type="text" id="item-label-en" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label><i class="fas fa-icons me-1" style="color:#13585D"></i> Icon (FontAwesome class)</label>
                            <input type="text" id="item-icon" class="form-control form-control-sm" placeholder="fas fa-heart">
                        </div>
                        <div class="col-md-6">
                            <label><i class="fas fa-image me-1" style="color:#13585D"></i> Image</label>
                            <input type="file" id="item-image" class="form-control form-control-sm" accept="image/*">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Link (AR)</label>
                            <input type="text" id="item-link" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label>Link (EN)</label>
                            <input type="text" id="item-link-en" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label>Social Links (JSON)</label>
                        <textarea id="item-social-links" class="form-control form-control-sm" rows="2" placeholder='{"linkedin":"url","twitter":"url"}'></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-cancel-btn" data-dismiss="modal">{{ __('app.cancel') }}</button>
                <button type="button" class="modal-save-btn" id="saveItemBtn">
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
    var BASE_URL = "{{ url('about-management/' . $page->id) }}";

    new Sortable(document.getElementById('sections-list'), {
        handle: '.drag-handle',
        animation: 200,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        onEnd: function() {
            var order = [];
            $('#sections-list .section-row').each(function() {
                order.push($(this).data('section-id'));
            });
            $.post(BASE_URL + '/sections/reorder', {
                _token: csrfToken, order: order
            });
        }
    });

    document.querySelectorAll('.items-list').forEach(function(el) {
        new Sortable(el, {
            handle: '.item-drag-handle',
            animation: 200,
            onEnd: function() {
                var sectionId = el.dataset.sectionId;
                var order = [];
                $(el).find('.item-row').each(function() { order.push($(this).data('item-id')); });
                $.post(BASE_URL + '/sections/' + sectionId + '/items/reorder', {
                    _token: csrfToken, order: order
                });
            }
        });
    });

    function showToast(msg, type) {
        if (window.Swal) {
            Swal.fire({
                icon: type || 'success', title: msg, toast: true,
                position: 'top-end', timer: 2000, showConfirmButton: false, timerProgressBar: true
            });
        }
    }

    $('.section-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var btn = form.find('.section-save-btn');
        var sectionId = form.data('section-id');
        var formData = new FormData(this);
        formData.append('_token', csrfToken);

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');

        $.ajax({
            url: BASE_URL + '/sections/' + sectionId,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                btn.prop('disabled', false).html('<i class="fas fa-check me-1"></i> Saved!');
                setTimeout(function() {
                    btn.html('<i class="fas fa-save me-1"></i> {{ __("app.save") }}');
                }, 1500);
                showToast((res && res.message) || 'Saved', 'success');
            },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> {{ __("app.save") }}');
                var msg = 'Error saving section';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                showToast(msg, 'error');
            }
        });
    });

    $('.toggle-visibility').on('change', function() {
        var sectionId = $(this).data('section-id');
        $.post(BASE_URL + '/sections/' + sectionId + '/toggle', { _token: csrfToken });
    });

    $('.btn-publish').on('click', function() {
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Publishing...');
        $.post(BASE_URL + '/publish', { _token: csrfToken })
            .done(function() { location.reload(); })
            .fail(function() {
                btn.prop('disabled', false).html('<i class="fas fa-rocket"></i> {{ __("app.publish") }}');
                showToast('Failed to publish', 'error');
            });
    });

    $('.btn-unpublish').on('click', function() {
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Unpublishing...');
        $.post(BASE_URL + '/unpublish', { _token: csrfToken })
            .done(function() { location.reload(); })
            .fail(function() {
                btn.prop('disabled', false).html('<i class="fas fa-pause"></i> {{ __("app.unpublish") }}');
                showToast('Failed to unpublish', 'error');
            });
    });

    $('.btn-rollback').on('click', function() {
        var versionId = $(this).data('version-id');
        Swal.fire({
            title: 'Rollback to this version?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Rollback',
            confirmButtonColor: '#13585D'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.post(BASE_URL + '/rollback/' + versionId, { _token: csrfToken }, function() {
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
        $('#itemModal .modal-title').html('<i class="fas fa-plus-circle me-2" style="color:#28a745"></i>Add Item');
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
        $('#itemModal .modal-title').html('<i class="fas fa-edit me-2" style="color:#13585D"></i>Edit Item');
        $('#itemModal').modal('show');
    });

    $('#saveItemBtn').on('click', function() {
        var btn = $(this);
        var pageId = $('#item-page-id').val();
        var sectionId = $('#item-section-id').val();
        var itemId = $('#item-id').val();

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');

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
            ? BASE_URL + '/sections/' + sectionId + '/items/' + itemId
            : BASE_URL + '/sections/' + sectionId + '/items';

        $.ajax({
            url: url, type: 'POST', data: formData,
            processData: false, contentType: false,
            success: function() { $('#itemModal').modal('hide'); location.reload(); },
            error: function(xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> {{ __("app.save") }}');
                var msg = 'Error saving item';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                showToast(msg, 'error');
            }
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
                    url: BASE_URL + '/sections/' + btn.data('section-id') + '/items/' + btn.data('item-id'),
                    type: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken },
                    success: function() {
                        btn.closest('.item-row').fadeOut(300, function() { $(this).remove(); });
                    }
                });
            }
        });
    });
});
</script>
@endsection

@extends('layouts.admin.show')
@section('title'){{ __('app.about us') }} - CMS @endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.about us') . ' CMS', "add_button" => route('about-admin.create')])

<style>
.about-cms-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}
.about-stat-card {
    background: #fff;
    border-radius: 14px;
    padding: 20px 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    border: 1px solid rgba(0,0,0,0.06);
    transition: all 0.25s ease;
}
.about-stat-card:hover {
    box-shadow: 0 6px 24px rgba(0,0,0,0.06);
    transform: translateY(-2px);
}
.about-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}
.about-stat-icon.pages { background: rgba(19,88,93,0.1); color: #13585D; }
.about-stat-icon.published { background: rgba(40,167,69,0.1); color: #28a745; }
.about-stat-icon.drafts { background: rgba(255,193,7,0.1); color: #e6a800; }
.about-stat-icon.sections { background: rgba(108,117,125,0.1); color: #6c757d; }
.about-stat-value { font-size: 1.5rem; font-weight: 700; color: #1a1a1a; line-height: 1.2; }
.about-stat-label { font-size: 0.78rem; color: #999; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; }

.about-table-card {
    border-radius: 14px;
    border: 1px solid rgba(0,0,0,0.06);
    overflow: hidden;
}
.about-table-card .card-header {
    background: #fff;
    border-bottom: 1px solid rgba(0,0,0,0.06);
    padding: 16px 20px;
}
.about-table-card .card-header h5 {
    font-weight: 700;
    font-size: 1rem;
    color: #1a1a1a;
}
.about-table-card .table th {
    font-size: 0.78rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #999;
    border-top: none;
    padding: 12px 16px;
}
.about-table-card .table td {
    padding: 14px 16px;
    vertical-align: middle;
}
.country-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 8px;
    font-size: 0.82rem;
    font-weight: 600;
    background: rgba(19,88,93,0.06);
    color: #13585D;
}
.country-badge .country-flag { font-size: 1.1rem; }
.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 600;
}
.status-pill.published { background: rgba(40,167,69,0.1); color: #28a745; }
.status-pill.draft { background: rgba(255,193,7,0.1); color: #e6a800; }
.status-pill .status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    display: inline-block;
}
.status-pill.published .status-dot { background: #28a745; }
.status-pill.draft .status-dot { background: #e6a800; }
.version-tag {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    border-radius: 6px;
    font-size: 0.78rem;
    font-weight: 600;
    background: rgba(108,117,125,0.08);
    color: #6c757d;
}
.sections-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 28px;
    height: 28px;
    padding: 0 8px;
    border-radius: 8px;
    font-size: 0.82rem;
    font-weight: 700;
    background: rgba(19,88,93,0.08);
    color: #13585D;
}
.action-btns { display: flex; gap: 6px; }
.action-btns .btn {
    width: 34px;
    height: 34px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-size: 0.82rem;
    transition: all 0.2s ease;
}
.action-btns .btn-edit {
    background: rgba(19,88,93,0.08);
    color: #13585D;
    border: none;
}
.action-btns .btn-edit:hover { background: #13585D; color: #fff; }
.action-btns .btn-del {
    background: rgba(220,53,69,0.08);
    color: #dc3545;
    border: none;
}
.action-btns .btn-del:hover { background: #dc3545; color: #fff; }
.updated-time { font-size: 0.82rem; color: #999; }
</style>

<div class="row mt-3">
    <div class="col-md-12">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" style="border-radius:12px;border:none;background:rgba(40,167,69,0.08);color:#28a745">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        <div class="about-cms-stats">
            <div class="about-stat-card">
                <div class="about-stat-icon pages"><i class="fas fa-file-alt"></i></div>
                <div>
                    <div class="about-stat-value">{{ $pages->count() }}</div>
                    <div class="about-stat-label">{{ __('app.total') }} {{ __('app.pages') }}</div>
                </div>
            </div>
            <div class="about-stat-card">
                <div class="about-stat-icon published"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="about-stat-value">{{ $pages->where('status', 'published')->count() }}</div>
                    <div class="about-stat-label">{{ __('app.published') }}</div>
                </div>
            </div>
            <div class="about-stat-card">
                <div class="about-stat-icon drafts"><i class="fas fa-edit"></i></div>
                <div>
                    <div class="about-stat-value">{{ $pages->where('status', 'draft')->count() }}</div>
                    <div class="about-stat-label">{{ __('app.draft') }}</div>
                </div>
            </div>
            <div class="about-stat-card">
                <div class="about-stat-icon sections"><i class="fas fa-layer-group"></i></div>
                <div>
                    <div class="about-stat-value">{{ $pages->sum('sections_count') }}</div>
                    <div class="about-stat-label">{{ __('app.sections') }}</div>
                </div>
            </div>
        </div>

        <div class="about-table-card card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-globe me-2" style="color:#13585D"></i>{{ __('app.about us') }} {{ __('app.pages') }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('app.country') }}</th>
                                <th>{{ __('app.status') }}</th>
                                <th>{{ __('app.version') }}</th>
                                <th>{{ __('app.sections') }}</th>
                                <th>{{ __('app.last update') }}</th>
                                <th>{{ __('app.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pages as $page)
                            <tr>
                                <td><span style="color:#999;font-weight:600">{{ $page->id }}</span></td>
                                <td>
                                    <span class="country-badge">
                                        @php
                                            $flags = ['JO'=>'🇯🇴','PS'=>'🇵🇸','SA'=>'🇸🇦','AE'=>'🇦🇪','global'=>'🌍'];
                                            $names = ['JO'=>'Jordan','PS'=>'Palestine','SA'=>'Saudi Arabia','AE'=>'UAE','global'=>'Global'];
                                        @endphp
                                        <span class="country-flag">{{ $flags[$page->country_code] ?? '🌐' }}</span>
                                        {{ $names[$page->country_code] ?? strtoupper($page->country_code) }}
                                    </span>
                                </td>
                                <td>
                                    @if($page->status === 'published')
                                        <span class="status-pill published"><span class="status-dot"></span> {{ __('app.published') }}</span>
                                    @else
                                        <span class="status-pill draft"><span class="status-dot"></span> {{ __('app.draft') }}</span>
                                    @endif
                                </td>
                                <td><span class="version-tag"><i class="fas fa-code-branch"></i> v{{ $page->version }}</span></td>
                                <td><span class="sections-count">{{ $page->sections_count }}</span></td>
                                <td><span class="updated-time"><i class="far fa-clock me-1"></i>{{ $page->updated_at->diffForHumans() }}</span></td>
                                <td>
                                    <div class="action-btns">
                                        <a href="{{ route('about-admin.edit', $page->id) }}" class="btn btn-edit" data-toggle="tooltip" title="{{ __('app.edit') }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <button class="btn btn-del btn-delete" data-id="{{ $page->id }}" data-toggle="tooltip" title="{{ __('app.delete') }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
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

@section('scripts')
<script>
$(document).ready(function() {
    $('#datatable').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/{{ app()->getLocale() === "ar" ? "ar" : "en-GB" }}.json' },
        order: [[0, 'desc']]
    });

    $('.btn-delete').on('click', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: '{{ __("app.are you sure?") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: '{{ __("app.delete") }}',
            cancelButtonText: '{{ __("app.cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('about-management') }}/" + id,
                    type: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function() { location.reload(); }
                });
            }
        });
    });
});
</script>
@endsection

@extends('layouts.admin.show')
@section('title'){{ __('app.about us') }} - CMS @endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.about us') . ' CMS', "add_button" => route('about-admin.create')])
<div class='row mt-3'>
    <div class="col-md-12">
        <div class="widget-rounded-circle card-box">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            @endif

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
                            <td>{{ $page->id }}</td>
                            <td>
                                <span class="badge badge-info">{{ strtoupper($page->country_code) }}</span>
                            </td>
                            <td>
                                @if($page->status === 'published')
                                    <span class="badge badge-success">{{ __('app.published') }}</span>
                                @else
                                    <span class="badge badge-warning">{{ __('app.draft') }}</span>
                                @endif
                            </td>
                            <td>v{{ $page->version }}</td>
                            <td>{{ $page->sections_count }}</td>
                            <td>{{ $page->updated_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('about-admin.edit', $page->id) }}" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="{{ __('app.edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $page->id }}" data-toggle="tooltip" title="{{ __('app.delete') }}">
                                    <i class="fas fa-trash"></i>
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
                    url: '/about-management/' + id,
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

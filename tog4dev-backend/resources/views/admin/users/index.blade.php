@extends('layouts.admin.show')
@section('title'){{ __('app.users') }}@endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.users')])
<div class="row mt-3">
    <div class="col-md-12">
        <div class="widget-rounded-circle card-box">
            <div class="row">
                <div class="col-12 table-responsive">
                    <table id="ajax-datatable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>{{ __('app.name') }}</th>
                                <th>{{ __('app.email') }}</th>
                                <th>{{ __('app.organization_name') }}</th>
                                <th>{{ __('app.city') }}</th>
                                <th>{{ __('app.birthday') }}</th>
                                <th>{{ __('app.country') }}</th>
                                <th>{{ __('app.created_at') }}</th>
                                <th>{{ __('app.action') }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('jsCode')
<script>
    $(document).ready(function () {
        $('#ajax-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('users.fetch_data') }}",
                type: "GET",
            },
            order: [[6, 'desc']], // created_at
            columns: [
                { data: 'name' },
                { data: 'email' },
                { data: 'organization_name' },
                { data: 'city' },
                { data: 'birthday' },
                { data: 'country' },
                { data: 'created_at' },
                { data: 'action', orderable: false, searchable: false },
            ]
        });
    });
</script>
@endsection
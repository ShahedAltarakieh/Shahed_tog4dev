@extends('layouts.admin.show')
@section('title'){{ __('app.influencers') }}@endsection

@section('content')

@include('includes.admin.header', [
    'label_name' => __('app.influencers'),
    'add_button' => route('influencers.create')
])

<div class="row">
    <div class="col-12">
        <div class="widget-rounded-circle card-box">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <table id="ajax-datatable" class="table dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('app.name') }}</th>
                        <th>{{ __('app.code') }}</th>
                        <th>{{ __('app.expiry_date') }}</th>
                        <th>{{ __('app.number of visit') }}</th>
                        <th>{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
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
                url: "{{ route('influencers.fetch_data') }}",
                type: "GET",
            },
            order: [[0, 'desc']],
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'code' },
                { data: 'expiry_date' },
                { data: 'visits_count' },
                { data: 'action', orderable: false, searchable: false },
            ]
        });
    });
</script>
@endsection

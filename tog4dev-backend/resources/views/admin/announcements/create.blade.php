@extends('layouts.admin.add')

@section('title') {{ __('app.add_announcement') }} @endsection

@section('content')
@include('includes.admin.header', ['label_name' => __('app.add_announcement')])

<form action="{{ route('announcements.store') }}" method="POST">
    @csrf

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @include('admin.announcements._form', ['announcement' => null, 'newsItems' => $newsItems, 'mode' => 'create'])
</form>
@endsection

@section('jsCode')
@include('admin.announcements._form_js', ['announcement' => null])
@endsection

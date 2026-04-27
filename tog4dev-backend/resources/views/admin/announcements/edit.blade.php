@extends('layouts.admin.add')

@section('title') {{ __('app.edit_announcement') }} @endsection

@section('content')
@include('includes.admin.header', ['label_name' => __('app.edit_announcement')])

<form action="{{ route('announcements.update', $announcement->id) }}" method="POST">
    @csrf
    @method('PUT')

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @include('admin.announcements._form', ['announcement' => $announcement, 'newsItems' => $newsItems, 'mode' => 'edit'])
</form>
@endsection

@section('jsCode')
@include('admin.announcements._form_js', ['announcement' => $announcement])
@endsection

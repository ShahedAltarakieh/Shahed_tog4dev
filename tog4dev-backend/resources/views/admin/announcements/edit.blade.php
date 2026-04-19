@extends('layouts.admin.add')

@section('title') {{ __('app.edit_announcement') }} @endsection

@section('content')
<div class="row mt-3 mb-3">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1" style="background:none;padding:0;">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('announcements.index') }}">{{ __('app.announcements') }}</a></li>
                <li class="breadcrumb-item active">{{ __('app.edit_announcement') }}</li>
            </ol>
        </nav>
        <h4 class="page-title mb-0">{{ __('app.edit_announcement') }}</h4>
    </div>
</div>

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

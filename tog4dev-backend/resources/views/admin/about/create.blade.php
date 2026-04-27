@extends('layouts.admin.add')
@section('title'){{ __('app.create_about_us') }} @endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.create_about_us')])

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card card-box">
            <div class="card-body">
                <form action="{{ route('about-admin.store') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label class="fw-bold">{{ __('app.country') }} <span class="text-danger">*</span></label>
                        <select name="country_code" class="form-control" required>
                            <option value="global">🌍 Global (Default)</option>
                            <option value="JO" selected>🇯🇴 Jordan</option>
                            <option value="PS">🇵🇸 Palestine</option>
                            <option value="SA">🇸🇦 Saudi Arabia</option>
                            <option value="AE">🇦🇪 UAE</option>
                            <option value="US">🇺🇸 USA</option>
                            <option value="GB">🇬🇧 UK</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="fw-bold">Meta Title (AR)</label>
                                <input type="text" name="meta_title" class="form-control" placeholder="عنوان الصفحة">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="fw-bold">Meta Title (EN)</label>
                                <input type="text" name="meta_title_en" class="form-control" placeholder="Page Title">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="fw-bold">Meta Description (AR)</label>
                                <textarea name="meta_description" class="form-control" rows="3" placeholder="وصف الصفحة"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="fw-bold">Meta Description (EN)</label>
                                <textarea name="meta_description_en" class="form-control" rows="3" placeholder="Page Description"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> {{ __('app.create') }}
                        </button>
                        <a href="{{ route('about-admin.index') }}" class="btn btn-light ms-2">{{ __('app.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

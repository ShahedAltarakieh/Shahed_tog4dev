@extends('layouts.admin.add')

@section('title') {{ __('app.contact_info') }} @endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.contact_info')])

<div class="row">
    <div class="col-12">
        <div class="card mb-3" style="border-left:3px solid var(--admin-primary);">
            <div class="card-body" style="padding:14px 18px;">
                <div class="d-flex align-items-center" style="gap:12px;">
                    <div style="width:40px;height:40px;border-radius:10px;background:var(--admin-primary);color:var(--admin-accent);display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;">
                        <i class="fas fa-address-card"></i>
                    </div>
                    <div>
                        <h6 class="mb-0" style="font-weight:600;color:var(--admin-primary);">{{ __('app.contact_info') }}</h6>
                        <small class="text-muted">{{ __('app.contact_info_hint') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .ci-section-header { display:flex; align-items:center; gap:10px; }
    .ci-section-header .ci-icon {
        width:36px; height:36px; border-radius:10px;
        background: rgba(19,88,93,0.08); color:#13585D;
        display:flex; align-items:center; justify-content:center;
        font-size:.95rem; flex-shrink:0;
    }
    .ci-section-header strong { color:#13585D; font-size:1rem; }
    .ci-field { margin-bottom:18px; }
    .ci-field .form-label {
        display:flex; align-items:center; gap:8px;
        font-weight:600; color:#3a4a52;
        margin-bottom:10px;
    }
    .ci-field .form-label i { color:#13585D; width:16px; text-align:center; font-size:.9rem; }
    .ci-field .form-label .lang-tag {
        margin-inline-start:auto; font-size:.7rem; font-weight:500;
        color:#7a8a92; background:#f1f4f5; padding:2px 8px; border-radius:10px;
    }
    .ci-field small.text-muted { display:block; margin-top:6px; }
    .ci-save-bar { position:sticky; bottom:0; background:#fff; padding:14px 0; border-top:1px solid #eef0f2; margin-top:8px; }
</style>

<form action="{{ route('contact-info-admin.update') }}" method="POST">
    @csrf
    @method('PUT')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- ============ Quick Info Cards ============ --}}
    <div class="card mb-3">
        <div class="card-header bg-white">
            <div class="ci-section-header">
                <span class="ci-icon"><i class="fas fa-id-card"></i></span>
                <strong>{{ __('app.quick_cards') }}</strong>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 ci-field">
                    <label class="form-label"><i class="fas fa-mobile-alt"></i> {{ __('app.phone_primary') }}</label>
                    <input type="text" name="phone_primary" class="form-control" value="{{ old('phone_primary', $info->phone_primary) }}" placeholder="+962779400900">
                </div>
                <div class="col-md-6 ci-field">
                    <label class="form-label"><i class="fab fa-whatsapp"></i> {{ __('app.whatsapp_number') }}</label>
                    <input type="text" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $info->whatsapp_number) }}" placeholder="962779400900">
                    <small class="text-muted">{{ __('app.digits_only_no_plus') }}</small>
                </div>

                <div class="col-md-6 ci-field">
                    <label class="form-label"><i class="fas fa-headset"></i> {{ __('app.landline') }}</label>
                    <input type="text" name="landline" class="form-control" value="{{ old('landline', $info->landline) }}">
                </div>
                <div class="col-md-6 ci-field">
                    <label class="form-label"><i class="fas fa-envelope"></i> {{ __('app.email_primary') }}</label>
                    <input type="email" name="email_primary" class="form-control" value="{{ old('email_primary', $info->email_primary) }}">
                </div>

                <div class="col-md-3 ci-field">
                    <label class="form-label"><i class="fas fa-phone-alt"></i> {{ __('app.phone_sub') }} <span class="lang-tag">EN</span></label>
                    <input type="text" name="phone_sub" class="form-control" value="{{ old('phone_sub', $info->phone_sub) }}">
                </div>
                <div class="col-md-3 ci-field">
                    <label class="form-label"><i class="fas fa-phone-alt"></i> {{ __('app.phone_sub') }} <span class="lang-tag">AR</span></label>
                    <input type="text" name="phone_sub_ar" class="form-control" value="{{ old('phone_sub_ar', $info->phone_sub_ar) }}" dir="rtl">
                </div>
                <div class="col-md-3 ci-field">
                    <label class="form-label"><i class="fas fa-headset"></i> {{ __('app.landline_sub') }} <span class="lang-tag">EN</span></label>
                    <input type="text" name="landline_sub" class="form-control" value="{{ old('landline_sub', $info->landline_sub) }}">
                </div>
                <div class="col-md-3 ci-field">
                    <label class="form-label"><i class="fas fa-headset"></i> {{ __('app.landline_sub') }} <span class="lang-tag">AR</span></label>
                    <input type="text" name="landline_sub_ar" class="form-control" value="{{ old('landline_sub_ar', $info->landline_sub_ar) }}" dir="rtl">
                </div>

                <div class="col-md-3 ci-field">
                    <label class="form-label"><i class="far fa-envelope"></i> {{ __('app.email_sub') }} <span class="lang-tag">EN</span></label>
                    <input type="text" name="email_sub" class="form-control" value="{{ old('email_sub', $info->email_sub) }}">
                </div>
                <div class="col-md-3 ci-field">
                    <label class="form-label"><i class="far fa-envelope"></i> {{ __('app.email_sub') }} <span class="lang-tag">AR</span></label>
                    <input type="text" name="email_sub_ar" class="form-control" value="{{ old('email_sub_ar', $info->email_sub_ar) }}" dir="rtl">
                </div>
                <div class="col-md-3 ci-field">
                    <label class="form-label"><i class="fas fa-city"></i> {{ __('app.city_country') }} <span class="lang-tag">EN</span></label>
                    <input type="text" name="city_country" class="form-control" value="{{ old('city_country', $info->city_country) }}">
                </div>
                <div class="col-md-3 ci-field">
                    <label class="form-label"><i class="fas fa-city"></i> {{ __('app.city_country') }} <span class="lang-tag">AR</span></label>
                    <input type="text" name="city_country_ar" class="form-control" value="{{ old('city_country_ar', $info->city_country_ar) }}" dir="rtl">
                </div>

                <div class="col-md-6 ci-field">
                    <label class="form-label"><i class="fas fa-road"></i> {{ __('app.street_short') }} <span class="lang-tag">EN</span></label>
                    <input type="text" name="street_short" class="form-control" value="{{ old('street_short', $info->street_short) }}">
                </div>
                <div class="col-md-6 ci-field">
                    <label class="form-label"><i class="fas fa-road"></i> {{ __('app.street_short') }} <span class="lang-tag">AR</span></label>
                    <input type="text" name="street_short_ar" class="form-control" value="{{ old('street_short_ar', $info->street_short_ar) }}" dir="rtl">
                </div>
            </div>
        </div>
    </div>

    {{-- ============ Office Card ============ --}}
    <div class="card mb-3">
        <div class="card-header bg-white">
            <div class="ci-section-header">
                <span class="ci-icon"><i class="fas fa-building"></i></span>
                <strong>{{ __('app.office_card') }}</strong>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 ci-field">
                    <label class="form-label"><i class="fas fa-briefcase"></i> {{ __('app.company_name') }} <span class="lang-tag">EN</span></label>
                    <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $info->company_name) }}">
                </div>
                <div class="col-md-6 ci-field">
                    <label class="form-label"><i class="fas fa-briefcase"></i> {{ __('app.company_name') }} <span class="lang-tag">AR</span></label>
                    <input type="text" name="company_name_ar" class="form-control" value="{{ old('company_name_ar', $info->company_name_ar) }}" dir="rtl">
                </div>

                <div class="col-md-6 ci-field">
                    <label class="form-label"><i class="fas fa-door-open"></i> {{ __('app.address_line1') }} <span class="lang-tag">EN</span></label>
                    <input type="text" name="address_line1" class="form-control" value="{{ old('address_line1', $info->address_line1) }}">
                </div>
                <div class="col-md-6 ci-field">
                    <label class="form-label"><i class="fas fa-door-open"></i> {{ __('app.address_line1') }} <span class="lang-tag">AR</span></label>
                    <input type="text" name="address_line1_ar" class="form-control" value="{{ old('address_line1_ar', $info->address_line1_ar) }}" dir="rtl">
                </div>

                <div class="col-md-6 ci-field">
                    <label class="form-label"><i class="fas fa-map-signs"></i> {{ __('app.address_line2') }} <span class="lang-tag">EN</span></label>
                    <textarea name="address_line2" rows="2" class="form-control">{{ old('address_line2', $info->address_line2) }}</textarea>
                </div>
                <div class="col-md-6 ci-field">
                    <label class="form-label"><i class="fas fa-map-signs"></i> {{ __('app.address_line2') }} <span class="lang-tag">AR</span></label>
                    <textarea name="address_line2_ar" rows="2" class="form-control" dir="rtl">{{ old('address_line2_ar', $info->address_line2_ar) }}</textarea>
                </div>

                <div class="col-md-6 ci-field">
                    <label class="form-label"><i class="far fa-clock"></i> {{ __('app.working_hours') }} <span class="lang-tag">EN</span></label>
                    <input type="text" name="working_hours" class="form-control" value="{{ old('working_hours', $info->working_hours) }}">
                </div>
                <div class="col-md-6 ci-field">
                    <label class="form-label"><i class="far fa-clock"></i> {{ __('app.working_hours') }} <span class="lang-tag">AR</span></label>
                    <input type="text" name="working_hours_ar" class="form-control" value="{{ old('working_hours_ar', $info->working_hours_ar) }}" dir="rtl">
                </div>
            </div>
        </div>
    </div>

    {{-- ============ Social Links ============ --}}
    <div class="card mb-3">
        <div class="card-header bg-white">
            <div class="ci-section-header">
                <span class="ci-icon"><i class="fas fa-share-alt"></i></span>
                <strong>{{ __('app.social_links') }}</strong>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                @php
                    $socials = (array) ($info->social_links ?? []);
                    $networks = [
                        'facebook'  => 'fab fa-facebook-f',
                        'instagram' => 'fab fa-instagram',
                        'snapchat'  => 'fab fa-snapchat-ghost',
                        'twitter'   => 'fab fa-x-twitter',
                        'linkedin'  => 'fab fa-linkedin-in',
                        'youtube'   => 'fab fa-youtube',
                        'tiktok'    => 'fab fa-tiktok',
                    ];
                @endphp
                @foreach($networks as $net => $icon)
                    <div class="col-md-6 ci-field">
                        <label class="form-label text-capitalize"><i class="{{ $icon }}"></i> {{ $net }}</label>
                        <input type="url" name="social_links[{{ $net }}]" class="form-control" value="{{ old('social_links.'.$net, $socials[$net] ?? '') }}" placeholder="https://...">
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ============ Map ============ --}}
    <div class="card mb-3">
        <div class="card-header bg-white">
            <div class="ci-section-header">
                <span class="ci-icon"><i class="fas fa-map-marked-alt"></i></span>
                <strong>{{ __('app.location_map') }}</strong>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 ci-field">
                    <label class="form-label"><i class="fas fa-link"></i> {{ __('app.map_link') }}</label>
                    <input type="text" name="map_link" class="form-control" value="{{ old('map_link', $info->map_link) }}" placeholder="https://maps.app.goo.gl/...">
                </div>
                <div class="col-md-6 ci-field">
                    <label class="form-label"><i class="fas fa-code"></i> {{ __('app.map_embed_url') }}</label>
                    <input type="text" name="map_embed_url" class="form-control" value="{{ old('map_embed_url', $info->map_embed_url) }}" placeholder="https://www.google.com/maps?q=...&output=embed">
                    <small class="text-muted">{{ __('app.map_embed_hint') }}</small>
                </div>
                <div class="col-md-3 ci-field">
                    <label class="form-label"><i class="fas fa-globe"></i> {{ __('app.map_lat') }}</label>
                    <input type="text" name="map_lat" class="form-control" value="{{ old('map_lat', $info->map_lat) }}">
                </div>
                <div class="col-md-3 ci-field">
                    <label class="form-label"><i class="fas fa-globe"></i> {{ __('app.map_lng') }}</label>
                    <input type="text" name="map_lng" class="form-control" value="{{ old('map_lng', $info->map_lng) }}">
                </div>
            </div>
        </div>
    </div>

    <div class="ci-save-bar text-end">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> {{ __('app.save') }}</button>
    </div>
</form>
@endsection

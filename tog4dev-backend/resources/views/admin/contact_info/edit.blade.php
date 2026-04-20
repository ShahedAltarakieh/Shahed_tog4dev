@extends('layouts.admin.add')

@section('title') {{ __('app.contact_info') }} @endsection

@section('content')
<div class="row mt-3 mb-3">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1" style="background:none;padding:0;">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
                <li class="breadcrumb-item active">{{ __('app.contact_info') }}</li>
            </ol>
        </nav>
        <h4 class="page-title mb-0">{{ __('app.contact_info') }}</h4>
        <p class="text-muted mb-0" style="font-size:.85rem;">{{ __('app.contact_info_hint') }}</p>
    </div>
</div>

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

    {{-- Quick info card subtitles --}}
    <div class="card mb-3">
        <div class="card-header"><strong>{{ __('app.quick_cards') }}</strong></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">{{ __('app.phone_primary') }}</label>
                    <input type="text" name="phone_primary" class="form-control form-control-sm" value="{{ old('phone_primary', $info->phone_primary) }}" placeholder="+962779400900">
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('app.whatsapp_number') }}</label>
                    <input type="text" name="whatsapp_number" class="form-control form-control-sm" value="{{ old('whatsapp_number', $info->whatsapp_number) }}" placeholder="962779400900">
                    <small class="text-muted">{{ __('app.digits_only_no_plus') }}</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('app.landline') }}</label>
                    <input type="text" name="landline" class="form-control form-control-sm" value="{{ old('landline', $info->landline) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('app.email_primary') }}</label>
                    <input type="email" name="email_primary" class="form-control form-control-sm" value="{{ old('email_primary', $info->email_primary) }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">{{ __('app.phone_sub') }} (EN)</label>
                    <input type="text" name="phone_sub" class="form-control form-control-sm" value="{{ old('phone_sub', $info->phone_sub) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('app.phone_sub') }} (AR)</label>
                    <input type="text" name="phone_sub_ar" class="form-control form-control-sm" value="{{ old('phone_sub_ar', $info->phone_sub_ar) }}" dir="rtl">
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('app.landline_sub') }} (EN)</label>
                    <input type="text" name="landline_sub" class="form-control form-control-sm" value="{{ old('landline_sub', $info->landline_sub) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('app.landline_sub') }} (AR)</label>
                    <input type="text" name="landline_sub_ar" class="form-control form-control-sm" value="{{ old('landline_sub_ar', $info->landline_sub_ar) }}" dir="rtl">
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('app.email_sub') }} (EN)</label>
                    <input type="text" name="email_sub" class="form-control form-control-sm" value="{{ old('email_sub', $info->email_sub) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('app.email_sub') }} (AR)</label>
                    <input type="text" name="email_sub_ar" class="form-control form-control-sm" value="{{ old('email_sub_ar', $info->email_sub_ar) }}" dir="rtl">
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('app.city_country') }} (EN)</label>
                    <input type="text" name="city_country" class="form-control form-control-sm" value="{{ old('city_country', $info->city_country) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('app.city_country') }} (AR)</label>
                    <input type="text" name="city_country_ar" class="form-control form-control-sm" value="{{ old('city_country_ar', $info->city_country_ar) }}" dir="rtl">
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('app.street_short') }} (EN)</label>
                    <input type="text" name="street_short" class="form-control form-control-sm" value="{{ old('street_short', $info->street_short) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('app.street_short') }} (AR)</label>
                    <input type="text" name="street_short_ar" class="form-control form-control-sm" value="{{ old('street_short_ar', $info->street_short_ar) }}" dir="rtl">
                </div>
            </div>
        </div>
    </div>

    {{-- Office card --}}
    <div class="card mb-3">
        <div class="card-header"><strong>{{ __('app.office_card') }}</strong></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">{{ __('app.company_name') }} (EN)</label>
                    <input type="text" name="company_name" class="form-control form-control-sm" value="{{ old('company_name', $info->company_name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('app.company_name') }} (AR)</label>
                    <input type="text" name="company_name_ar" class="form-control form-control-sm" value="{{ old('company_name_ar', $info->company_name_ar) }}" dir="rtl">
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('app.address_line1') }} (EN)</label>
                    <input type="text" name="address_line1" class="form-control form-control-sm" value="{{ old('address_line1', $info->address_line1) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('app.address_line1') }} (AR)</label>
                    <input type="text" name="address_line1_ar" class="form-control form-control-sm" value="{{ old('address_line1_ar', $info->address_line1_ar) }}" dir="rtl">
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('app.address_line2') }} (EN)</label>
                    <textarea name="address_line2" rows="2" class="form-control form-control-sm">{{ old('address_line2', $info->address_line2) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('app.address_line2') }} (AR)</label>
                    <textarea name="address_line2_ar" rows="2" class="form-control form-control-sm" dir="rtl">{{ old('address_line2_ar', $info->address_line2_ar) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('app.working_hours') }} (EN)</label>
                    <input type="text" name="working_hours" class="form-control form-control-sm" value="{{ old('working_hours', $info->working_hours) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('app.working_hours') }} (AR)</label>
                    <input type="text" name="working_hours_ar" class="form-control form-control-sm" value="{{ old('working_hours_ar', $info->working_hours_ar) }}" dir="rtl">
                </div>
            </div>
        </div>
    </div>

    {{-- Extra phones / emails --}}
    <div class="card mb-3">
        <div class="card-header"><strong>{{ __('app.extra_contacts') }}</strong></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">{{ __('app.extra_phones') }}</label>
                    <div id="extra-phones-list">
                        @foreach((array) ($info->extra_phones ?? []) as $i => $val)
                            <div class="input-group input-group-sm mb-2">
                                <input type="text" name="extra_phones[]" class="form-control" value="{{ $val }}">
                                <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">&times;</button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addRow('extra-phones-list','extra_phones[]')">+ {{ __('app.add') }}</button>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('app.extra_emails') }}</label>
                    <div id="extra-emails-list">
                        @foreach((array) ($info->extra_emails ?? []) as $i => $val)
                            <div class="input-group input-group-sm mb-2">
                                <input type="email" name="extra_emails[]" class="form-control" value="{{ $val }}">
                                <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">&times;</button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addRow('extra-emails-list','extra_emails[]')">+ {{ __('app.add') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Social links --}}
    <div class="card mb-3">
        <div class="card-header"><strong>{{ __('app.social_links') }}</strong></div>
        <div class="card-body">
            <div class="row g-3">
                @php
                    $socials = (array) ($info->social_links ?? []);
                    $networks = ['facebook','instagram','snapchat','twitter','linkedin','youtube','tiktok'];
                @endphp
                @foreach($networks as $net)
                    <div class="col-md-6">
                        <label class="form-label text-capitalize">{{ $net }}</label>
                        <input type="url" name="social_links[{{ $net }}]" class="form-control form-control-sm" value="{{ old('social_links.'.$net, $socials[$net] ?? '') }}" placeholder="https://...">
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Map --}}
    <div class="card mb-3">
        <div class="card-header"><strong>{{ __('app.location_map') }}</strong></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">{{ __('app.map_link') }}</label>
                    <input type="text" name="map_link" class="form-control form-control-sm" value="{{ old('map_link', $info->map_link) }}" placeholder="https://maps.app.goo.gl/...">
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('app.map_embed_url') }}</label>
                    <input type="text" name="map_embed_url" class="form-control form-control-sm" value="{{ old('map_embed_url', $info->map_embed_url) }}" placeholder="https://www.google.com/maps?q=...&output=embed">
                    <small class="text-muted">{{ __('app.map_embed_hint') }}</small>
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('app.map_lat') }}</label>
                    <input type="text" name="map_lat" class="form-control form-control-sm" value="{{ old('map_lat', $info->map_lat) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('app.map_lng') }}</label>
                    <input type="text" name="map_lng" class="form-control form-control-sm" value="{{ old('map_lng', $info->map_lng) }}">
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mb-4">
        <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
    </div>
</form>
@endsection

@section('jsCode')
<script>
function addRow(listId, name) {
    var wrap = document.createElement('div');
    wrap.className = 'input-group input-group-sm mb-2';
    wrap.innerHTML = '<input type="text" name="' + name + '" class="form-control"><button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">&times;</button>';
    document.getElementById(listId).appendChild(wrap);
}
</script>
@endsection

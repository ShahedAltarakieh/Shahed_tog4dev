@extends('layouts.admin.add')
@section('title'){{ __('app.edit details') }} - {{ __('app.news') }}@endsection

@section('content')
@include('includes.admin.header', ['label_name' => __('app.edit details') . ' - ' . __('app.news')])

<div class="row">
    <div class="col-12">
        <div class="widget-rounded-circle card-box d-flex justify-content-between">
            <form class="w-100" action="{{ route('news-admin.update', ['id' => $data->id]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="ml-3 mb-0">
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                            </div>

                            <div class="form-group col-md-6">
                                <label for="title">{{ __('app.title') }} (AR) <span class="text-danger">*</span></label>
                                <input type="text" id="title" name="title" placeholder="{{ __('app.title') }}"
                                    value="{{ old('title', $data->title) }}" class="form-control" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="title_en">{{ __('app.title') }} (EN)</label>
                                <input type="text" id="title_en" name="title_en" placeholder="{{ __('app.title') }}"
                                    value="{{ old('title_en', $data->title_en) }}" class="form-control">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="excerpt">{{ __('app.short description') }} (AR) <small class="text-muted">— {{ __('app.auto_generated_hint') }}</small></label>
                                <textarea id="excerpt" name="excerpt" placeholder="{{ __('app.auto_generated_from_content') }}"
                                    class="form-control" rows="3">{{ old('excerpt', $data->excerpt) }}</textarea>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="excerpt_en">{{ __('app.short description') }} (EN) <small class="text-muted">— {{ __('app.auto_generated_hint') }}</small></label>
                                <textarea id="excerpt_en" name="excerpt_en" placeholder="{{ __('app.auto_generated_from_content') }}"
                                    class="form-control" rows="3">{{ old('excerpt_en', $data->excerpt_en) }}</textarea>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="body">{{ __('app.full content') }} (AR)</label>
                                <div id="editor-body" style="height: 250px;">{!! old('body', $data->body) !!}</div>
                                <textarea id="body" name="body" style="display:none;">{{ old('body', $data->body) }}</textarea>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="body_en">{{ __('app.full content') }} (EN)</label>
                                <div id="editor-body-en" style="height: 250px;">{!! old('body_en', $data->body_en) !!}</div>
                                <textarea id="body_en" name="body_en" style="display:none;">{{ old('body_en', $data->body_en) }}</textarea>
                            </div>

                            <div class="form-group col-4">
                                <label for="image">{{ __('app.image') }} (Web)</label>
                                <input type="file" id="image" name="image" data-plugins="dropify"
                                    data-default-file="{{ $data->image }}" data-height="200"
                                    data-allowed-file-extensions="png jpg jpeg webp"
                                    accept=".png,.jpg,.jpeg,.webp" />
                                @include('includes.admin.image-upload-notes', [
                                    'recommendedSize' => '1200 x 800 px',
                                    'maxSize' => '5 MB',
                                    'extensions' => 'png,jpg,jpeg,webp'
                                ])
                            </div>
                            <div class="form-group col-4">
                                <label for="image_tablet">{{ __('app.image') }} (Tablet)</label>
                                <input type="file" id="image_tablet" name="image_tablet" data-plugins="dropify"
                                    data-default-file="{{ $data->image_tablet }}" data-height="200"
                                    data-allowed-file-extensions="png jpg jpeg webp"
                                    accept=".png,.jpg,.jpeg,.webp" />
                                @include('includes.admin.image-upload-notes', [
                                    'recommendedSize' => '800 x 600 px',
                                    'maxSize' => '5 MB',
                                    'extensions' => 'png,jpg,jpeg,webp'
                                ])
                            </div>
                            <div class="form-group col-4">
                                <label for="image_mobile">{{ __('app.image') }} (Mobile)</label>
                                <input type="file" id="image_mobile" name="image_mobile" data-plugins="dropify"
                                    data-default-file="{{ $data->image_mobile }}" data-height="200"
                                    data-allowed-file-extensions="png jpg jpeg webp"
                                    accept=".png,.jpg,.jpeg,.webp" />
                                @include('includes.admin.image-upload-notes', [
                                    'recommendedSize' => '600 x 400 px',
                                    'maxSize' => '5 MB',
                                    'extensions' => 'png,jpg,jpeg,webp'
                                ])
                            </div>

                            <div class="form-group col-md-4">
                                <label for="news_category_id">{{ __('app.category') }} <span class="text-danger">*</span></label>
                                <select name="news_category_id" id="news_category_id" class="form-control" required>
                                    <option value="">{{ __('app.choose') }}</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('news_category_id', $data->news_category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }} - {{ $category->name_en }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="published_at">{{ __('app.publish date') }}</label>
                                <input type="date" id="published_at" name="published_at"
                                    value="{{ old('published_at', $data->published_at ? \Carbon\Carbon::parse($data->published_at)->format('Y-m-d') : '') }}" class="form-control">
                            </div>

                            <div class="form-group col-md-2">
                                <label for="position">{{ __('app.position') }}</label>
                                <input type="number" id="position" name="position"
                                    value="{{ old('position', $data->position) }}" class="form-control">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="status" class="d-block">{{ __('app.published') }}</label>
                                <input type="checkbox" data-plugin="switchery" data-color="#1bb99a" name="status" value="1" {{ old('status', $data->status) ? 'checked' : '' }} />
                            </div>


                            <div class="form-group col-md-12 text-center mt-3">
                                <button type="submit" class="btn btn-primary px-5">{{ __('app.save') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('jsCode')
<script>
var toolbarOptions = [
    [{header: [1, 2, 3, 4, 5, 6, false]}],
    ['bold', 'italic', 'underline', 'strike'],
    [{color: []}, {background: []}],
    [{list: 'ordered'}, {list: 'bullet'}],
    [{align: []}],
    ['blockquote'],
    ['link', 'image'],
    ['clean']
];

var quillBody = new Quill('#editor-body', {
    theme: 'snow',
    modules: {toolbar: toolbarOptions},
    placeholder: '{{ __("app.full content") }}'
});

var quillBodyEn = new Quill('#editor-body-en', {
    theme: 'snow',
    modules: {toolbar: toolbarOptions},
    placeholder: '{{ __("app.full content") }}'
});

quillBody.on('text-change', function() {
    document.getElementById('body').value = quillBody.root.innerHTML;
    autoGenerateExcerpt('body', 'excerpt');
});

quillBodyEn.on('text-change', function() {
    document.getElementById('body_en').value = quillBodyEn.root.innerHTML;
    autoGenerateExcerpt('body_en', 'excerpt_en');
});

document.querySelector('form').addEventListener('submit', function() {
    document.getElementById('body').value = quillBody.root.innerHTML;
    document.getElementById('body_en').value = quillBodyEn.root.innerHTML;
});

var excerptHasExisting = document.getElementById('excerpt').value.trim().length > 0;
var excerptEnHasExisting = document.getElementById('excerpt_en').value.trim().length > 0;
if (excerptHasExisting) document.getElementById('excerpt').dataset.manuallyEdited = 'true';
if (excerptEnHasExisting) document.getElementById('excerpt_en').dataset.manuallyEdited = 'true';

function autoGenerateExcerpt(bodyId, excerptId) {
    var excerptField = document.getElementById(excerptId);
    if (excerptField.dataset.manuallyEdited === 'true') return;

    var html = document.getElementById(bodyId).value;
    var tmp = document.createElement('div');
    tmp.innerHTML = html;
    var text = (tmp.textContent || tmp.innerText || '').trim();

    if (!text) { excerptField.value = ''; return; }

    var sentences = text.match(/[^.!?。]+[.!?。]+/g);
    var excerpt = '';
    if (sentences && sentences.length > 0) {
        for (var i = 0; i < sentences.length && excerpt.length < 150; i++) {
            excerpt += sentences[i].trim() + ' ';
        }
        excerpt = excerpt.trim();
    } else {
        excerpt = text;
    }

    if (excerpt.length > 200) {
        excerpt = excerpt.substring(0, 197).replace(/\s+\S*$/, '') + '...';
    } else if (text.length > excerpt.length) {
        excerpt = excerpt.replace(/[.!?。]+$/, '') + '...';
    }

    excerptField.value = excerpt;
}

document.getElementById('excerpt').addEventListener('input', function() {
    this.dataset.manuallyEdited = 'true';
});
document.getElementById('excerpt_en').addEventListener('input', function() {
    this.dataset.manuallyEdited = 'true';
});
</script>
@endsection

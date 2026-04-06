@extends('layouts.admin.add')
@section('title'){{ __('app.edit details') }}@endsection

@section('content')
    @include('includes.admin.header', ['label_name' => __('app.additional_info')])

    <div class="row">
        <div class="col-12">
            <div class="widget-rounded-circle card-box d-flex justify-content-between">
                <form class="w-100" action="{{ route('items.update_additional_info', ['type' => $type, 'item' => $item->id]) }}" method="POST" id="additional_information">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <!-- Handle Errors and Success Messages -->
                        <div class="col-md-12">
                            <div class="form-group">
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
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Project Story -->
                        <div class="form-group col-md-6">
                            <label for="project_story">{{ __('Project Story') }}</label>
                            <textarea class="form-control project_story d-none"  name="project_story">{{ old('project_story', $additionalInfo->project_story) }}</textarea>
                            <div class='w-100' id="project_story" style="height: 300px;">{!! old('project_story', $additionalInfo->project_story) !!}</div>
                        </div>

                        <!-- Project Story (English) -->
                        <div class="form-group col-md-6">
                            <label for="project_story_en">{{ __('Project Story (English)') }}</label>
                            <textarea class="form-control project_story_en d-none"  name="project_story_en">{{ old('project_story_en', $additionalInfo->project_story_en) }}</textarea>
                            <div class='w-100' id="project_story_en" style="height: 300px;">{!! old('project_story_en', $additionalInfo->project_story_en) !!}</div>
                        </div>

                        <!-- Bold Description -->
                        <div class="form-group col-md-6">
                            <label for="bold_description">{{ __('Bold Description') }}</label>
                            <textarea class="form-control bold_description d-none"  name="bold_description">{{ old('bold_description', $additionalInfo->bold_description) }}</textarea>
                            <div class='w-100' id="bold_description" style="height: 300px;">{!! old('bold_description', $additionalInfo->bold_description) !!}</div>
                        </div>

                        <!-- Bold Description (English) -->
                        <div class="form-group col-md-6">
                            <label for="bold_description_en">{{ __('Bold Description (English)') }}</label>
                            <textarea class="form-control bold_description_en d-none"  name="bold_description_en">{{ old('bold_description_en', $additionalInfo->bold_description_en) }}</textarea>
                            <div class='w-100' id="bold_description_en" style="height: 300px;">{!! old('bold_description_en', $additionalInfo->bold_description_en) !!}</div>
                        </div>

                        <!-- Normal Description -->
                        <div class="form-group col-md-6">
                            <label for="normal_description">{{ __('Normal Description') }}</label>
                            <textarea class="form-control normal_description d-none"  name="normal_description">{{ old('normal_description', $additionalInfo->normal_description) }}</textarea>
                            <div class='w-100' id="normal_description" style="height: 300px;">{!! old('normal_description', $additionalInfo->normal_description) !!}</div>
                        </div>

                        <!-- Normal Description (English) -->
                        <div class="form-group col-md-6">
                            <label for="normal_description_en">{{ __('Normal Description (English)') }}</label>
                            <textarea class="form-control normal_description_en d-none"  name="normal_description_en">{{ old('normal_description_en', $additionalInfo->normal_description_en) }}</textarea>
                            <div class='w-100' id="normal_description_en" style="height: 300px;">{!! old('normal_description_en', $additionalInfo->normal_description_en) !!}</div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-primary px-4">{{ __('app.save') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section("jsCode")
<script>
    const project_story = new Quill('#project_story', {
        theme: 'snow'
    });
    const project_story_en = new Quill('#project_story_en', {
        theme: 'snow'
    });
    const bold_description = new Quill('#bold_description', {
        theme: 'snow'
    });
    const bold_description_en = new Quill('#bold_description_en', {
        theme: 'snow'
    });
    const normal_description = new Quill('#normal_description', {
        theme: 'snow'
    });
    const normal_description_en = new Quill('#normal_description_en', {
        theme: 'snow'
    });

    $("#additional_information").on("submit",function() {
        $(".project_story").val($("#project_story").html());
        $(".project_story_en").val($("#project_story_en").html());
        $(".bold_description").val($("#bold_description").html());
        $(".bold_description_en").val($("#bold_description_en").html());
        $(".normal_description").val($("#normal_description").html());
        $(".normal_description_en").val($("#normal_description_en").html());
    })

</script>
@endsection
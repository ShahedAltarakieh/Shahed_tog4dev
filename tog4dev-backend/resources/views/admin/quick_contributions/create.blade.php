@extends('layouts.admin.add')
@section('title') {{ __('app.add new') }} @endsection

@section('content')

@include('includes.admin.header', ['label_name' => __('app.add new')])

<div class="row">
    <div class="col-12">
        <div class="widget-rounded-circle card-box d-flex justify-content-between">
            <form class='w-100' action="{{ route('quick-contributions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class='ml-3 mb-0'>
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

                            <!-- Title (AR) -->
                            <div class="form-group col-md-6">
                                <label for="title">{{ __('app.title') }} (AR)</label>
                                <input type="text" id="title" name="title" placeholder='{{ __('app.title') }} (AR)' value="{{ old('title') }}" class="form-control">
                            </div>

                            <!-- Title (EN) -->
                            <div class="form-group col-md-6">
                                <label for="title_en">{{ __('app.title') }} (EN)</label>
                                <input type="text" id="title_en" name="title_en" placeholder='{{ __('app.title') }} (EN)' value="{{ old('title_en') }}" class="form-control">
                            </div>

                            <!-- Description (AR) -->
                            <div class="form-group col-md-6">
                                <label for="description">{{ __('app.description') }} (AR)</label>
                                <textarea id="description" name="description" rows="4" placeholder='{{ __('app.description') }} (AR)' class="form-control">{{ old('description') }}</textarea>
                            </div>

                            <!-- Description (EN) -->
                            <div class="form-group col-md-6">
                                <label for="description_en">{{ __('app.description') }} (EN)</label>
                                <textarea id="description_en" name="description_en" rows="4" placeholder='{{ __('app.description') }} (EN)' class="form-control">{{ old('description_en') }}</textarea>
                            </div>

                            <!-- beneficiaries message AR -->
                            <div class="form-group col-md-6">
                                <label for="beneficiaries_message">{{ __('app.subscription beneficiaries message') }} (AR)</label>
                                <textarea id="beneficiaries_message" name="beneficiaries_message" placeholder="{{ __('app.subscription beneficiaries message') }}"
                                    class="form-control" rows="4">{{ old('beneficiaries_message') }}</textarea>
                            </div>

                            <!-- beneficiaries message EN -->
                            <div class="form-group col-md-6">
                                <label for="beneficiaries_message_en">{{ __('app.subscription beneficiaries message') }} (EN)</label>
                                <textarea id="beneficiaries_message_en" name="beneficiaries_message_en"
                                    placeholder="{{ __('app.subscription beneficiaries message') }}" class="form-control"
                                    rows="4">{{ old('beneficiaries_message_en') }}</textarea>
                            </div>

                            <!-- invoice description after payment AR -->
                            <div class="form-group col-md-6">
                                <label for="description_after_payment">{{ __('app.invoice description after payment') }} (AR)</label>
                                <textarea id="description_after_payment" name="description_after_payment" placeholder="{{ __('app.invoice description after payment') }}"
                                    class="form-control" rows="4">{{ old('description_after_payment') }}</textarea>
                            </div>

                            <!-- invoice description after payment EN -->
                            <div class="form-group col-md-6">
                                <label for="description_after_payment_en">{{ __('app.invoice description after payment') }} (EN)</label>
                                <textarea id="description_after_payment_en" name="description_after_payment_en"
                                    placeholder="{{ __('app.invoice description after payment') }}" class="form-control"
                                    rows="4">{{ old('description_after_payment_en') }}</textarea>
                            </div>

                            <div class="form-group col-4">
                                <label for="image">{{ __('app.image') }} (Web)</label>
                                <input type="file" id="image" name="image" data-plugins="dropify" data-height="200" data-allowed-file-extensions="png jpg jpeg webp"  />
                            </div>
                            <div class="form-group col-4">
                                <label for="image_tablet">{{ __('app.image') }} (Tablet)</label>
                                <input type="file" id="image_tablet" name="image_tablet" data-plugins="dropify" data-height="200" data-allowed-file-extensions="png jpg jpeg webp"  />
                            </div>
                            <div class="form-group col-4">
                                <label for="image_mobile">{{ __('app.image') }} (Mobile)</label>
                                <input type="file" id="image_mobile" name="image_mobile" data-plugins="dropify" data-height="200" data-allowed-file-extensions="png jpg jpeg webp"  />
                            </div>
                            <div class="form-group col-6 d-none">
                                <label for="image_en">{{ __('app.image') }} (EN)</label>
                                <input type="file" id="image_en" name="image_en" data-plugins="dropify" data-height="200" data-allowed-file-extensions="png jpg jpeg webp"  />
                            </div>

                            <!-- Location AR -->
                            <div class="form-group col-md-6">
                                <label for="location">{{ __('app.location') }} (AR)</label>
                                <input type="text" id="location" name="location" placeholder="{{ __('app.location') }}"
                                       value="{{ old('location') }}" class="form-control">
                            </div>

                            <!-- Location EN -->
                            <div class="form-group col-md-6">
                                <label for="location_en">{{ __('app.location') }} (EN)</label>
                                <input type="text" id="location_en" name="location_en"
                                       placeholder="{{ __('app.location') }}" value="{{ old('location_en') }}"
                                       class="form-control">
                            </div>

                            <!-- Accounting Label -->
                            <div class="form-group col-md-4">
                                <label for="analyticـaccount">{{ __('app.account label') }}</label>
                                <select class="form-control" name="analyticـaccount" id="analyticـaccount">
                                    <option value="">{{ __('app.select') }}</option>
                                    @foreach($analyticـaccounts as $item)
                                        <option value="{{ $item->odoo_id }}">{{ $item->value }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Type -->
                            <div class="form-group col-md-4">
                                <label for="type_id">{{ __('app.type') }}</label>
                                <select class="form-control" name="type_id" id="type_id" required>
                                    <option value="1">Home</option>
                                    <option value="2">Project</option>
                                    <option value="3">Crowdfunding</option>
                                </select>
                            </div>

                            <!-- Category -->
                            <div class="form-group col-md-4">
                                <label for="category_id">{{ __('app.category') }}</label>
                                <select class="form-control" name="category_id" id="category_id">
                                    <option value="">{{ __('app.select category') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" data-type="{{ $category->type }}">{{ $category->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Amount -->
                            <div class="form-group col-md-6 d-none">
                                <label for="target">{{ __('app.amount') }} (JOD)</label>
                                <input type="number" id="target" name="target" placeholder="{{ __('app.amount') }}"
                                       value="{{ old('target') }}" class="form-control">
                            </div>

                            <!-- Amount -->
                            <div class="form-group col-md-6 d-none">
                                <label for="target_usd">{{ __('app.amount') }} (USD)</label>
                                <input type="number" id="target_usd" name="target_usd" placeholder="{{ __('app.amount') }}"
                                       value="{{ old('target_usd') }}" class="form-control">
                            </div>

                            <div class="col-md-12">
                                <label for="price">{{ __('app.prices') }}</label>
                            </div>

                            <div class="col-md-6">
                                <div class="form-row border p-3 mb-2">
                                    <div class="form-group col-md-12 d-flex align-items-center">
                                        <input type="text" id="price" name="price[0]"
                                            placeholder="{{ __('app.price') }} (JOD)" value="{{ old('price.0') }}"
                                            class="form-control mr-2">
                                        <input type="text" id="price" name="price[1]"
                                            placeholder="{{ __('app.price') }} (JOD)" value="{{ old('price.1') }}"
                                            class="form-control mr-2">
                                        <input type="text" id="price" name="price[2]"
                                            placeholder="{{ __('app.price') }} (JOD)" value="{{ old('price.2') }}"
                                            class="form-control mr-2">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-row border p-3 mb-2">
                                    <div class="form-group col-md-12 d-flex align-items-center">
                                        <input type="text" id="price_en" name="price_en[0]"
                                            placeholder="{{ __('app.price') }} (USD)" value="{{ old('price_en.0') }}"
                                            class="form-control mr-2">
                                        <input type="text" id="price_en" name="price_en[1]"
                                            placeholder="{{ __('app.price') }} (USD)" value="{{ old('price_en.1') }}"
                                            class="form-control mr-2">
                                        <input type="text" id="price_en" name="price_en[2]"
                                            placeholder="{{ __('app.price') }} (USD)" value="{{ old('price_en.2') }}"
                                            class="form-control mr-2">
                                    </div>
                                </div>
                            </div>

                            <!-- Amount -->
                            <div class="form-group col-md-2">
                                <label for="single_price">{{ __('app.single_price') }} (JOD)</label>
                                <input type="number" step="any" id="single_price" name="single_price" placeholder="{{ __('app.single_price') }}"
                                        value="{{ old('single_price') }}" class="form-control">
                            </div>

                            <!-- Amount -->
                            <div class="form-group col-md-2">
                                <label for="single_price_usd">{{ __('app.single_price') }} (USD)</label>
                                <input type="number" step="any" id="single_price_usd" name="single_price_usd" placeholder="{{ __('app.single_price') }} (USD)"
                                        value="{{ old('single_price_usd') }}" class="form-control">
                            </div>
                            <!-- Status -->
                            <div class="form-group col-md-12">
                                <label for="status" class="d-block">{{ __('app.status') }}</label>
                                <input type="checkbox" checked data-plugin="switchery" data-color="#1bb99a" name="status" value="1"/>
                            </div>

                            <!-- Save Button -->
                            <div class="form-group col-md-12">
                                <button class='btn btn-primary px-4' type="submit" name="save_and_return" value="save_and_return">{{ __('app.save') }}</button>
                                <button class='btn btn-secondary px-4' type="submit" name="save" value="save">{{ __('app.save & create another') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

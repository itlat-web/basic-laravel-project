@extends('layouts.admin')

@section('content')
    <form action="{{ route('admin.posts.store') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header text-uppercase">
                            <div class="row">
                                <div class="col-lg-6 d-flex align-items-center">
                                    <strong>{{ __('Create Post') }}</strong>
                                </div>
                                <div class="col-lg-6 text-end">
                                    <a href="{{ route('admin.posts.index') }}" class="btn btn-primary">
                                        {{ __('Return to Posts') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <label for="title" class="col-lg-3 col-form-label text-md-end">{{ __('Title') }}<span class="text-danger">*</span></label>
                                <div class="col-lg-6">
                                    <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" autocomplete="off">
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="slug" class="col-lg-3 col-form-label text-md-end">{{ __('Slug') }}<span class="text-danger">*</span></label>
                                <div class="col-lg-6">
                                    <input id="slug" type="text" class="form-control @error('slug') is-invalid @enderror" name="slug" value="{{ old('slug') }}" autocomplete="off">
                                    @error('slug')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="image" class="col-lg-3 col-form-label text-md-end">{{ __('Image') }}<span class="text-danger">*</span></label>
                                <div class="col-lg-6">
                                    <input id="image" type="file" class="form-control @error('image') is-invalid @enderror" name="image" autocomplete="off">
                                    @error('image')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="text" class="col-lg-3 col-form-label text-md-end">{{ __('Text') }}<span class="text-danger">*</span></label>
                                <div class="col-lg-6">
                                    <textarea id="text" class="form-control @error('text') is-invalid @enderror" name="text" autocomplete="off">{{ old('title') }}</textarea>
                                    @error('text')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="active" class="col-lg-3 col-form-label text-md-end">{{ __('Active') }}<span class="text-danger">*</span></label>
                                <div class="col-lg-6">
                                    <select class="form-select @error('active') is-invalid @enderror" autocomplete="off" name="active">
                                        <option value="0" {{ !old('active') ? 'selected' : '' }}>{{ __('No') }}</option>
                                        <option value="1" {{ old('active') ? 'selected' : '' }}>{{ __('Yes') }}</option>
                                    </select>
                                    @error('active')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary text-uppercase">
                                {{ __('Create') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@extends('layouts.admin')

@section('content')
    <form action="{{ route('admin.questions.update', $question) }}" enctype="multipart/form-data" method="POST">
        @csrf
        @method('PATCH')
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header text-uppercase">
                            <div class="row">
                                <div class="col-lg-6 d-flex align-items-center">
                                    <strong>{{ __('Update Question') }}</strong>
                                </div>
                                <div class="col-lg-6 text-end">
                                    <a href="{{ route('admin.questions.index') }}" class="btn btn-primary">
                                        {{ __('Return to Questions') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <label for="email" class="col-lg-3 col-form-label text-md-end">{{ __('Email') }}</label>
                                <div class="col-lg-6">
                                    <input id="email" type="text" class="form-control" name="email" value="{{ $question->email }}" disabled>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="ip" class="col-lg-3 col-form-label text-md-end">{{ __('IP') }}</label>
                                <div class="col-lg-6">
                                    <input id="ip" type="text" class="form-control" name="ip" value="{{ $question->ip }}" disabled>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="question_text" class="col-lg-3 col-form-label text-md-end">{{ __('Question') }}</label>
                                <div class="col-lg-6">
                                    <textarea id="question_text" class="form-control" name="question_text" disabled>{{ $question->question_text }}</textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="verified" class="col-lg-3 col-form-label text-md-end">{{ __('Verified') }}<span class="text-danger">*</span></label>
                                <div class="col-lg-6">
                                    <select class="form-select @error('verified') is-invalid @enderror" autocomplete="off" name="verified">
                                        <option value="0" {{ !old('verified', !$question->verified) ? 'selected' : '' }}>{{ __('No') }}</option>
                                        <option value="1" {{ old('verified', $question->verified) ? 'selected' : '' }}>{{ __('Yes') }}</option>
                                    </select>
                                    @error('verified')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary text-uppercase">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

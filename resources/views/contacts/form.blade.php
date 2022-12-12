@extends('layouts.front')

@section('content')

    <form action="{{ route('contacts.submit') }}" method="POST">
        @csrf
        @method('POST')
        <div class="container">
            <div class="row justify-content-center">

                @if (session()->has('error'))
                    <div class="col-md-8">
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                @if (session()->has('success'))
                    <div class="col-md-8">
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header text-uppercase">
                            <strong>{{ __('Have any questions ?') }}</strong>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <label for="email" class="col-lg-3 col-form-label text-md-end">{{ __('Email') }}<span class="text-danger">*</span></label>
                                <div class="col-lg-6">
                                    <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="off">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="question_text" class="col-lg-3 col-form-label text-md-end">{{ __('Question') }}<span class="text-danger">*</span></label>
                                <div class="col-lg-6">
                                    <textarea id="question_text" class="form-control @error('question_text') is-invalid @enderror" name="question_text">{{ old('question_text') }}</textarea>
                                    @error('question_text')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary text-uppercase">
                                {{ __('Submit') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

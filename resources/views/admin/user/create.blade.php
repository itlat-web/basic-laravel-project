@extends('layouts.admin')

@section('content')
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header text-uppercase">
                            <div class="row">
                                <div class="col-lg-6 d-flex align-items-center">
                                    <strong>{{ __('Create User') }}</strong>
                                </div>
                                <div class="col-lg-6 text-end">
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                                        {{ __('Return to Users') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <label for="name" class="col-lg-3 col-form-label text-md-end">{{ __('Name') }}<span class="text-danger">*</span></label>
                                <div class="col-lg-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="off">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

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
                                <label for="password" class="col-lg-3 col-form-label text-md-end">{{ __('Password') }}<span class="text-danger">*</span></label>
                                <div class="col-lg-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="off">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirmation" class="col-lg-3 col-form-label text-md-end">{{ __('Confirm Password') }}<span class="text-danger">*</span></label>
                                <div class="col-lg-6">
                                    <input id="password-confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" autocomplete="off">
                                    @error('password_confirmation')
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

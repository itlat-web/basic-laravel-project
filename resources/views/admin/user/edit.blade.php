@extends('layouts.admin')

@section('content')
    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @method('PATCH')
        @csrf
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header text-uppercase">
                            <div class="row">
                                <div class="col-lg-6 d-flex align-items-center">
                                    <strong>{{ __('Update User') }}</strong>
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
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" autocomplete="off">
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
                                    <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" autocomplete="off">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="password" class="col-lg-3 col-form-label text-md-end">{{ __('Password') }}</label>
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
                                <label for="password-confirmation" class="col-lg-3 col-form-label text-md-end">{{ __('Confirm Password') }}</label>
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
                                {{ __('Update') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>



    @if ($user->posts->count() > 0)
        <div class="container mt-4">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header text-uppercase">
                            <strong>{{ __('User Posts') }}</strong>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                @foreach($user->posts as $post)
                                    <li class="list-group-item">
                                        <a target="_blank" href="{{ route('admin.posts.edit', $post) }}">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    {{ $post->title }}
                                                </div>
                                                <div class="col-lg-6 text-end">
                                                    @if ($post->active)
                                                        <span class="badge bg-success text-lowercase">{{ __('active') }}</span>
                                                    @else
                                                        <span class="badge bg-danger text-lowercase">{{ __('disabled') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

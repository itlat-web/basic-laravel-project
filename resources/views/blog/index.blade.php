@extends('layouts.front')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="row">
                    @foreach($posts as $post)
                        <div class="col-lg-4 mt-4">
                            <div class="card text-center text-bg-light">
                                <div class="card-header">
                                    {{ $post->user?->name ?? '...' }}
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $post->title }}</h5>

                                    <p class="card-text">{{ Str::of(strip_tags($post->text))->limit(100) }}</p>

                                    <a href="{{ route('blog.show', $post) }}" class="btn btn-primary btn-sm">{{ __('Read More') }}</a>
                                </div>
                                <div class="card-footer text-muted">
                                    {{ $post->created_at }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if ($posts->lastPage() > 1)
                <div class="col-md-8">
                    <hr>
                    {!! $posts->links('vendor.pagination.bootstrap-5') !!}
                </div>
            @endif
        </div>
    </div>
@endsection

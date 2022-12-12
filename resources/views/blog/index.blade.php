@extends('layouts.front')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="row">
                    @foreach($posts as $post)
                        <div class="col-lg-4 mt-4">
                            <div class="card text-center text-bg-light h-100">
                                <img class="card-img-top" src="{{ $post->getImageFullUrl() }}" alt="image">
                                <div class="card-body d-flex align-items-center flex-column">
                                    <h5 class="card-title">{{ $post->title }}</h5>

                                    <p class="card-text mb-auto text-start mb-2">{{ Str::limit(strip_tags($post->text), 100) }}</p>

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

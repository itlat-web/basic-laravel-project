@extends('layouts.admin')

@section('content')

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header text-uppercase">
                            <div class="row">
                                <div class="col-lg-6 d-flex align-items-center">
                                    <strong>{{ __('Posts') }}</strong>
                                </div>
                                <div class="col-lg-6 text-end">
                                    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
                                        {{ __('Create') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($posts->count() > 0)
                                <table class="table table-bordered table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Title') }}</th>
                                            <th>{{ __('Slug') }}</th>
                                            <th>{{ __('Author') }}</th>
                                            <th class="text-center">{{ __('Active') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($posts as $post)
                                            <tr>
                                                <td class="align-middle">{{ $post->title }}</td>
                                                <td class="align-middle">{{ $post->slug }}</td>
                                                <td class="align-middle">{{ $post->user?->name ?? '...' }}</td>
                                                <td class="align-middle text-center">
                                                    @if ($post->active)
                                                        <span class="badge bg-success text-uppercase">{{ __('active') }}</span>
                                                    @else
                                                        <span class="badge bg-danger text-uppercase">{{ __('disabled') }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <a class="btn btn-primary btn-sm" href="{{ route('admin.posts.edit', $post) }}">
                                                            {{ __('Edit') }}
                                                        </a>
                                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false" data-bs-reference="parent">
                                                            <span class="visually-hidden">Toggle</span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="dropdown-item">{{ __('Delete') }}</button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                @if ($posts->lastPage() > 1)
                                    <hr>

                                    <div class="mt-3">
                                        {!! $posts->links('vendor.pagination.bootstrap-5') !!}
                                    </div>
                                @endif
                            @else
                                <div class="text-center text-uppercase">{{ __('Currently there are no posts.') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

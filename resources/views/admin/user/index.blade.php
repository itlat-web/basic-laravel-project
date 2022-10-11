@extends('layouts.admin')

@section('content')

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header text-uppercase">
                            <div class="row">
                                <div class="col-lg-6 d-flex align-items-center">
                                    <strong>{{ __('Users') }}</strong>
                                </div>
                                <div class="col-lg-6 text-end">
                                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                        {{ __('Create') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($users->count() > 0)
                                <table class="table table-bordered table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>{{ __('ID') }}</th>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Email') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                            <tr>
                                                <td class="align-middle">{{ $user->id }}</td>
                                                <td class="align-middle">{{ $user->name }}</td>
                                                <td class="align-middle">{{ $user->email }}</td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <a class="btn btn-primary btn-sm" href="{{ route('admin.users.edit', $user) }}">
                                                            {{ __('Edit') }}
                                                        </a>

                                                        @if (!$currentUser || $user->id !== $currentUser->id)
                                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false" data-bs-reference="parent">
                                                                <span class="visually-hidden">Toggle</span>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button class="dropdown-item">{{ __('Delete') }}</button>
                                                                    </form>
                                                                </li>
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                @if ($users->lastPage() > 1)
                                    <hr>

                                    <div class="mt-3">
                                        {!! $users->links('vendor.pagination.bootstrap-5') !!}
                                    </div>
                                @endif
                            @else
                                <div class="text-center text-uppercase">{{ __('Currently there are no users.') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

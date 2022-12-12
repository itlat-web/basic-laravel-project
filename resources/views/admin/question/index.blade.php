@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-uppercase">
                    <strong>{{ __('Questions') }}</strong>
                </div>
                <div class="card-body">
                    @if ($questions->count() > 0)
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('IP') }}</th>
                                    <th>{{ __('Question') }}</th>
                                    <th class="text-center">{{ __('Verified') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($questions as $question)
                                    <tr>
                                        <td class="align-middle">{{ $question->email }}</td>
                                        <td class="align-middle">{{ $question->ip }}</td>
                                        <td class="align-middle">{{ Str::limit($question->question_text, 10) }}</td>
                                        <td class="align-middle text-center">
                                            @if ($question->verified)
                                                <span class="badge bg-success text-uppercase">{{ __('verified') }}</span>
                                            @else
                                                <span class="badge bg-danger text-uppercase">{{ __('not verified') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a class="btn btn-primary btn-sm" href="{{ route('admin.questions.edit', $question) }}">
                                                    {{ __('Edit') }}
                                                </a>
                                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false" data-bs-reference="parent">
                                                    <span class="visually-hidden">Toggle</span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <form action="{{ route('admin.questions.destroy', $question) }}" method="POST">
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

                        @if ($questions->lastPage() > 1)
                            <hr>

                            <div class="mt-3">
                                {!! $questions->links('vendor.pagination.bootstrap-5') !!}
                            </div>
                        @endif
                    @else
                        <div class="text-center text-uppercase">{{ __('Currently there are no questions.') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

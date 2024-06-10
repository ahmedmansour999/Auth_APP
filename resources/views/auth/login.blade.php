@extends('home')

{{-- style of Register --}}
@section('style', asset('asset/css/auth.css'))

{{-- title of page --}}
@section('title', 'login')

{{-- Content of Page --}}
@section('content')
    <a class="fw-bold text-white float-end m-4 btn btn-outline-warning" href="{{ route('registerView') }}"> Register </a>

    <div class="container loginForm">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Login') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group row">
                                <label for="identifier" class="col-md-4 col-form-label text-md-right">{{ __('Email ') }} /
                                    {{ __('Phone Number') }}</label>
                                <div class="col-md-6">
                                    <input id="identifier" type="text"
                                        class="form-control @error('identifier')
                                        is-invalid @enderror"
                                        name="identifier" value="{{ old('identifier') }}" autofocus>
                                    @error('identifier')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        autocomplete="current-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember"
                                            id="remember"{{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>
                                        <a class="btn btn-link" href="{{ route('password') }}">
                                            {{ __('Forgot Your Password?') }}

                                        </a>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@extends('home')

{{-- style of Register --}}
@section('style', asset('asset/css/auth.css'))

{{-- title of page --}}
@section('title', 'reset password')

{{-- Content of Page --}}
@section('content')
<a class="fw-bold text-white float-end m-4 btn btn-outline-warning" href="{{ route('registerView') }}"> Register </a>

    <div class="container loginForm">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Send Otp') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('verifyOtpPassword' , $user->id ) }}">
                            @csrf
                            @method('post')
                            <div class="form-group row">
                                <label for="otp"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Enter otp Code') }}</label>
                                <div class="col-md-6">
                                    <input id="otp" type="text"
                                        class="form-control @error('otp') is-invalid @enderror" name="otp"
                                        autocomplete="current-otp">
                                    @error('otp')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-warning">
                                        {{ __('Change Password') }}
                                    </button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

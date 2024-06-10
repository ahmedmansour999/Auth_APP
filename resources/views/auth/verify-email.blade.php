@extends('home')

@section('style', asset('asset/css/auth.css'))

@section('title', 'login')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show m-4  " role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    <div class="container loginForm">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Verify Email') }} <a href="{{ route('home') }}"
                            class="skip text-danger float-end"> Skip</a></div>
                    <div class="card-body pb-0 ">
                        <form action="{{ route('verify', $user->id) }}" method="POST">
                            @csrf
                            @method('post')
                            <div class="form-group row">
                                <label for="otp"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Enter otp Code ') }}</label>
                                <div class="col-md-6">
                                    <input id="otp" type="text"
                                        class="form-control @error('otp')
                                        is-invalid @enderror"
                                        name="otp" value="{{ old('otp') }}" autofocus>
                                    @error('otp')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Verify') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                        <form action="{{ route('resendOtp', $user->id) }}" class="text-center my-2"  method="post">
                            @csrf
                            @method('post')
                            <button type="submit" class="btn btn-link mx-5"> Resend Otp Code </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

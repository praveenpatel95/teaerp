@extends('auth.layout.app')
@section('title','Reset')
@section('description','')
@push('headerscript')
    <style>
        .causes-list-box {
            max-width: 400px;
            margin: 0 auto;

            padding: 15px !important;
            padding-top: 10px !important;
            border-color: red !important;
            margin-bottom: 20px
        }
    </style>

@endpush

@section('content')
    <div class="login-content">
        <!-- Login -->
        <div class="lc-block toggled" id="l-login">
            <div class="col-sm-12 ">
                <h3 class="text-capitalize text-center">Reset Password</h3>
                <br>

            </div>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <div class="lcb-form">

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group row">
                        <div class="col-md-12">
                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email" placeholder="Email Address" value="{{ $email ?? old('email') }}"
                                   required readonly
                                   autocomplete="email" autofocus>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   name="password" placeholder="Password" required autocomplete="new-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <input id="password-confirm" type="password" class="form-control"
                                   name="password_confirmation" placeholder="Confirm Password" required
                                   autocomplete="new-password">
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-12 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Reset Password') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

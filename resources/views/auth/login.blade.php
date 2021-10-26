@extends('auth.layout.app')
@section('title','Login')
@section('content')
    <div class="login-content">
        <!-- Login -->
        <div class="lc-block toggled" id="l-login">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="lcb-form">
                    <div class="input-group m-b-20">
                        <span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
                        <div class="fg-line">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                   placeholder="E-mail">
                        </div>
                    </div>
                    <div class="input-group m-b-10">
                        <span class="input-group-addon"><i class="zmdi zmdi-male"></i></span>
                        <div class="fg-line">
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror" name="password" required
                                   placeholder="Password"
                                   autocomplete="current-password">

                        </div>
                        <div class="fg-line">
                            @error('email')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="checkbox">
                        <label>
                            <input class="form-check-input" type="checkbox" name="remember"
                                   id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <i class="input-helper"></i>
                            Keep me signed in
                        </label>
                    </div>

                    <button type="submit" class="btn btn-login btn-success btn-float"><i
                            class="zmdi zmdi-arrow-forward"></i>
                    </button>
                    <br>
                    <p><a href="{{ route('password.request') }}" > <span>Forgot Password</span> <i>?</i></a></p>
                </div>
            </form>


        </div>
    </div>
@endsection


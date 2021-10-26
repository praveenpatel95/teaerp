@extends('auth.layout.app')
@section('title','Reset Password')
@section('content')
    <div class="login-content">
        <!-- Login -->
        <div class="lc-block toggled" id="l-login">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <form class="form-horizontal" role="form" method="POST" action="{{ route('password.email') }}">
                {{ csrf_field() }}
                <div class="lcb-form">
                    <h4>Reset Password</h4>
                    <div class="input-group m-b-20{{ $errors->has('email') ? ' has-error' : '' }}">
                        <span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
                        <div class="fg-line">
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}"
                                   placeholder="Email Address" required autofocus>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="m-l-20">
                            <button type="submit" class="btn btn-primary">
                                Send Password Reset Link
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

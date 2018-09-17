@extends('dashboard.layouts.app-plain')

@section('style')

    <style>

        .login-page .overlay {
            border-bottom: 3px solid #fff;
            position: relative;
            height: 105vh;
            min-height: 105vh;
            background: url(../../images/backgrounds/welcome-cover.jpg) no-repeat center center fixed;
            background-size: cover;
        }

        .login-page .overlay:before {
            position: absolute;
            z-index: 0;
            content: '';
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: .9;
            background: #0e1c3a;
        }
        
        .login-box{
            background:#fff;
        }

    </style>

@endsection

@section('content')
    <div class="login-page">
        <!-- Login Section -->
        <div class="overlay">
            <div class="container-fluid pt-5">
                <div class="row mt-5">
                    <div class="login-box col-lg-4 mx-auto p-5">
                        @include('dashboard/layouts/alerts/default-top-alerts') 
                        <h4 class="font-weight-light mt-4 text-center">Login</h4>
                        <form method="POST" action="{{ route('login') }}" class="pt-4">
                            @csrf
                            <div class="form-group
                                        {{ $errors->has('username') ? ' has-error' : '' }}
                                        {{ $errors->has('email') ? ' has-error' : '' }}
                                        ">
                                <input id="identity" type="text" class="form-control 
                                                    {{ $errors->has('username') ? ' is-invali d' : '' }}
                                                    {{ $errors->has('email') ? ' is-invalid' : '' }}" 
                                                    name="identity" value="{{ old('identity') }}"
                                    placeholder="Email/Username" required autofocus>
                                <i class="mdi mdi-account"></i>
                                @if ($errors->has('username'))
                                <span class="help-block invalid-feedback d-block">
                                    <strong>Username & Password don't match</strong>
                                </span>
                                @endif
                                @if ($errors->has('email'))
                                <span class="help-block invalid-feedback d-block">
                                    <strong>Email & Password don't match</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="Password" required>
                                <i class="mdi mdi-eye"></i>
                                @if ($errors->has('password'))
                                <span class="help-block invalid-feedback d-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember" {{ old( 'remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5">
                                <button type="submit" class="btn btn-lg btn-success w-100">
                                    Login
                                </button>
                            </div>
                            <div class="mt-3 text-center">
                                <a href="{{ route('register') }}">Register</a>
                                <a href="{{ route('password.request') }}">Forgot password?</a>
                            </div>
                        </form>
                    </div>            
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

@endsection

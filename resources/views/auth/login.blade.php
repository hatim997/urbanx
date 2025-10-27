@extends('layouts.authentication.master')
@section('title', 'Login')

@section('css')

@endsection

@section('content')
    <!-- /Left Text -->
    <div class="d-none d-lg-flex col-lg-6 p-0">
        <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
            <img src="{{ asset('assets/img/logo/logo-full.png') }}" alt="auth-login-cover" class="my-5 auth-illustration"
                data-app-light-img="logo/logo-full.png" data-app-dark-img="logo/logo-full.png" />
        </div>
    </div>
    <!-- /Left Text -->

    <!-- Login -->
    <div class="col-12 col-lg-6 d-flex align-items-center justify-content-center min-vh-100 bg-dark" style="background: radial-gradient(50% 50% at 50% 50%, #353535 0%, #000000 100%) !important;">
        <div class="card shadow-lg border-0 rounded-4 p-4 w-100" style="max-width: 420px;">
            <div class="text-center mb-4">
                <h4 class="fw-bold">{{ __('Welcome to') }} {{ \App\Helpers\Helper::getCompanyName() }} 👋</h4>
                <p class="text-muted small mb-0">{{ __('Please sign in to your account and start your adventure') }}</p>
            </div>

            <form id="formLogin" action="{{ route('login.attempt') }}" method="POST">
                @csrf

                <!-- Email / Username -->
                <div class="mb-3">
                    <label for="email_username" class="form-label fw-semibold">{{ __('Email / Username') }} <span
                            class="text-danger">*</span></label>
                    <input type="text" id="email_username" name="email_username"
                        class="form-control form-control-lg @error('email_username') is-invalid @enderror"
                        placeholder="{{ __('Enter your email or username') }}" autofocus required>
                    @error('email_username')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">{{ __('Password') }} <span
                            class="text-danger">*</span></label>
                    <div class="input-group input-group-lg">
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" placeholder="••••••••••••"
                            required>
                        <span class="input-group-text bg-white cursor-pointer">
                            <i class="ti ti-eye-off"></i>
                        </span>
                    </div>
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me / Forgot -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember-me">
                        <label class="form-check-label small" for="remember-me">{{ __('Remember Me') }}</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="small text-primary text-decoration-none">
                            {{ __('Forgot Password?') }}
                        </a>
                    @endif
                </div>

                <!-- Captcha -->
                <div class="mb-4 text-center">
                    @if (config('captcha.version') === 'v3')
                        {!! \App\Helpers\Helper::renderRecaptcha('formLogin', 'register') !!}
                    @elseif(config('captcha.version') === 'v2')
                        <div class="form-field-block">
                            {!! app('captcha')->display() !!}
                            @if ($errors->has('g-recaptcha-response'))
                                <span class="text-danger small">{{ $errors->first('g-recaptcha-response') }}</span>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-primary btn-lg w-100 rounded-3 shadow-sm">
                    {{ __('Sign in') }}
                </button>
            </form>
        </div>
    </div>

    <!-- /Login -->
@endsection

@section('script')
    {!! NoCaptcha::renderJs() !!}
@endsection

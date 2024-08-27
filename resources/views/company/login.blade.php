@extends('layouts.base')

@section("title", __('text.LoginTitleCompany'))

@section('body')

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <div class="page">
        <header class="header">
            <div class="container">

                <div class="header__center">
                    <div class="header__center_vacan">
                        {{ __('text.site_title') }}
                    </div>
                   <div class="header__center_btns">
                        <button onclick="location.href='/company/register'"class="header__center_btn">{{ __('text.RegisterCompany') }}</button>                      
                    </div>                   
                </div>

            </div>
        </header>

        <section class="vacancies">
            <div class="container">
                <div class="login__wrapper">
                   
                <div class="card">
                <div class="card-header">{{ __('text.LoginTitleCompany') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('text.Email') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('text.Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!--<div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('text.RememberMe') }}
                                    </label>
                                </div>
                            </div>
                        </div>-->

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('text.Login') }}
                                </button>
                                <br>

                               <!-- @if (Route::has('password.request'))
                                    <small><a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('text.ForgotYourPassword') }}
                                    </a></small>
                                @endif -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>

                </div>
            </div>
        </section>


    </div>



@endsection

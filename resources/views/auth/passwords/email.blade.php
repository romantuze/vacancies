@extends('layouts.base')

@section('body')
<!-- Styles -->
<link href="{{ asset('css/app.css') }}" rel="stylesheet">

@php
    app()->setLocale('en');
@endphp

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
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('Reset Password') }}</div>

                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf

                                <div class="row mb-3">
                                    <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="dfdfdf form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Send Password Reset Link') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

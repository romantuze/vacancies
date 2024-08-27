@extends('layouts.base')

@section("title", "")

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
                </div>
            </div>
        </header>

        <section class="vacancies">
            <div class="container">
                <div class="login__wrapper">
                   
                <div class="card">
                <div class="card-header">{{ __('text.LoginTitle') }}</div>

                <div class="card-body">

                      @if(session('success'))
                           <h4>{{session('success')}}</h4> 
                      @endif

                    <form method="POST" action="{{ route('candidate_login') }}"> 
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

                       <!-- <div class="row mb-3">
                            <label for="phone" class="col-md-4 col-form-label text-md-end">{{ __('text.Phone') }}</label>
                            <div class="col-md-6">
                                <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus>
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>-->

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('text.Password') }} (Телефон)</label>

                            <div class="col-md-6">
                                <input id="password" type="text" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>                       

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('text.Login') }}
                                </button>                               
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

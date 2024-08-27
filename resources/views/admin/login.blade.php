@extends('layouts.base')

@section("title", __('text.LoginTitleAdmin'))

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
                       
                    </div>
                   
                </div>

            </div>
        </header>

        <section class="vacancies">
            <div class="container">
                <div class="login__wrapper">
                   
                <div class="card">
                <div class="card-header">{{ __('text.LoginTitleAdmin') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin_post_login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="login" class="col-md-4 col-form-label text-md-end">{{ __('text.InputLogin') }}</label>

                            <div class="col-md-6">
                                <input id="login" type="text" class="form-control @error('login') is-invalid @enderror" name="login" value="{{ old('login') }}" required>

                                @error('login')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('text.Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>

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
                                <br>

                              
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

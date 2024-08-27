@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('text.admin_selects_edit') }}</div>

                <div class="card-body">
                    <h1>{{ __('text.currencies') }}</h1>
                    <div id="app">
                        <edit-currencies></edit-currencies>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

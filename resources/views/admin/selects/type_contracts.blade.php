@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('text.admin_selects_edit') }}</div>

                <div class="card-body">
                   
                    <div id="app">
                        <edit-type-contracts></edit-type-contracts>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.base')
@section("title", __('text.LoginTitlePage'))
@section('body')

<div class="page">
<div id="app">
<company-home :user_id="'{{ $user_id }}'" :name="'{{ $name }}'" :balance="'{{ $balance }}'" :currency="'{{ $currency }}'" :logo="'{{ $logo }}'" :status_works="'{{ $status_works }}'"></company-home>
</div> 
</div>

@endsection

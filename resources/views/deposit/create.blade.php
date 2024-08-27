@extends('layouts.base')
@section("title", "Лицевой счет ".$user_id)
@section('body')
<div class="page">
<div id="app">
<create-deposit :company_id="'{{$user_id}}'"></create-deposit>
</div> 
</div> 
@endsection
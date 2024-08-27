@extends('layouts.base')
@section("title", "Список вакансий")
@section('body')

<div class="page">
<div id="app">
<work-list :company_id="'{{$company_id}}'"></work-list>
</div> 
</div>

@endsection

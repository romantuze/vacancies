@extends('layouts.base')
@section("title", "Список кандидатов")
@section('body')

<div class="page">
<div id="app">
<candidate-list :work_id="'{{$work_id}}'" :user_id="'{{$user_id}}'"></candidate-list>
</div> 
</div>

@endsection

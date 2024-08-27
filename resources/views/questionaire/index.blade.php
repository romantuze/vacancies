@extends('layouts.base')
@section("title", "")
@section('body')

<div class="page">

<div id="app">
<candidate-home :user_id="'{{ $user_id }}'" :name="'{{ $name }}'"></candidate-home>
</div> 

</div>

@endsection
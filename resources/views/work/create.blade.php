@extends('layouts.base')
@section("title", __('text.CreateVacancy'))
@section('body')

<div id="app">
<create-vacancy :edit="false" :template="false" :name="'{{$name}}'" :logo="'{{$logo}}'"
 :user_id="'{{$user_id}}'" :work_id="''" :lang="'{{$lang}}'"></create-vacancy>
</div> 

@endsection
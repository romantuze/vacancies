@extends('layouts.base')
@section("title", __('text.EditVacancy'))
@section('body')

<div id="app">
<create-vacancy  :edit="true" :template="false" :name="'{{$name}}'" :logo="'{{$logo}}'" :user_id="'{{$user_id}}'" :work_id="'{{$work_id}}'" :lang="'{{$lang}}'"></create-vacancy>
</div> 

@endsection
@extends('layouts.base')

@section("title", __('text.QuestionnairePage'))

@section('body')

<div id="app">	
<create-question :slug="'{{$slug}}'" :work_id="'{{$work_id}}'" :closed="'{{$closed}}'" :closed_date="'{{$closed_date}}'"  :refer="'{{$refer}}'" :lang="'{{$lang}}'"></create-question>
</div> 

@endsection
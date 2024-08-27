@extends('layouts.base')
@section("title", __('text.CandidateShop'))
@section('body')

<div class="page two">
<div id="app">
<company-shop :work_id="'{{ $work_id }}'" :user_id="'{{ $user_id }}'" :lang="'{{$lang}}'"></company-shop>
</div>
</div>


@endsection

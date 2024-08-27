@extends('layouts.base')
@section("title", "Личный кабинет администратора")
@section('body')


<div class="page">
<div id="app">
<company-list></company-list>
</div>
</div>

<!--
<div class="page">

<div >

    <header class="container header-table"><a href="/home" class="logo"><img loading="lazy" src="/img/logo-table.svg" alt="img"></a><a href="/logout" class="log-off"><span>Выйти из личного кабинета</span><svg class="svg"><use xlink:href="/img/sprite.svg#log-off"></use></svg></a></header>

    <div class="container sort-block">
        <div class="table-tabs"><a href="/admin/companies" class="table-tabs__item"> {{ __('text.admin_companies') }} </a>

            <a href="/admin/vacancies" class="table-tabs__item">{{ __('text.admin_vacancies') }} </a>
<a href="/admin/selects" class="table-tabs__item">{{ __('text.selects') }} </a>
        </div>


    </div>


    <div class="table table-sort-company">
        <div class="container">




</div></div></div> 


</div>-->



@endsection

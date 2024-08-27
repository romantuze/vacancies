@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('text.selects') }}</div>

                <div class="card-body">
                    
                    <p><a href="/admin/cities">{{ __('text.cities') }}</a></p>       

                    <p><a href="/admin/regions">{{ __('text.regions') }}</a></p>     

                    <p><a href="/admin/specializations">{{ __('text.specializations') }}</a></p>

                    <p><a href="/admin/educations">{{ __('text.educations') }}</a></p>

                    <p><a href="/admin/type-educations">{{ __('text.type_educations') }}</a></p>

                    <p><a href="/admin/skills">{{ __('text.skills') }}</a></p>

                    <p><a href="/admin/languages">{{ __('text.languages') }}</a></p>

                    <p><a href="/admin/currencies">{{ __('text.currencies') }}</a></p>

                    <p><a href="/admin/cars">{{ __('text.car') }}</a></p>

                    <p><a href="/admin/car-licences">{{ __('text.car_licence') }}</a></p>
    
                    <p><a href="/admin/language-levels">{{ __('text.language_level') }}</a></p>

                    <p><a href="/admin/employments">{{ __('text.employment') }}</a></p>

                    <p><a href="/admin/degrees">{{ __('text.degree') }}</a></p>

                    <p><a href="/admin/facilitations">{{ __('text.facilitations') }}</a></p>

                    <p><a href="/admin/skill-levels">{{ __('text.skill_level') }}</a></p>

                    <p><a href="/admin/type-contracts">{{ __('text.type_contracts') }}</a></p>

                    <p><a href="/admin/personals">{{ __('text.personals') }}</a></p>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

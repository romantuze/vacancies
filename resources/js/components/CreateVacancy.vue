<template>    
    <div class="page two">
            <section class="description">
                <div class="container">

<div class="description__top">
<h1 class="description__title">
<span v-if="edit">{{ trans("text.EditVacancy") }}</span>
<span v-else>{{ trans("text.CreateVacancy") }}</span>
</h1>
<a href="/instruction" class="instruction_link">
{{ trans("text.InstructionsVacancy") }}
</a>
</div>

<div class="description__info-com">
<div class="description__info-com_logo">
<div v-if="logo">
<img :src="logo" alt="" width="160" height="80" style="width: 160px; height: 80px;"> <!--160 80-->
</div>
<div v-else>
<img src="img/logo.png" alt="">
</div>
</div>
<div class="description__info-com_name">
{{name}}
</div>

<div class="item__radio top">
<div class="item__radio_title">
{{ trans("text.PublishNameCompany") }}  
</div>
<div class="item__radio_wrapper">

<div>
<input type="radio" class="custom-radio" v-model="form.publish_сompany" id="company_yes" value="1">
<label for="company_yes">{{ trans("text.Yes") }}</label>
</div>
<div>
<input type="radio" class="custom-radio" v-model="form.publish_сompany" id="company_no" value="0">
<label for="company_no">{{ trans("text.No") }}</label>
</div>
</div>

</div>
</div>

    <div class="description__wrapper">

                        <div class="item descr1">
                            <div class="item__title">
                                <span>1</span>
                                {{ trans("text.InfoVacancy") }}  <!--Общая информация по вакансии-->
                            </div>
                            <div class="item__inputs">
                                <div class="item__input" >
                                    <label for="name">{{ trans("text.NameVacancy") }}*</label>
                                    <input type="text" 
                                    name="name" 
                                    :placeholder="trans('text.NameVacancyExample')"                              
                                    v-model.trim="form.name" 
                                    @change="v$.form.name.$touch"
                                    :class="{ 'error': v$.form.name.$error }"
                                    >                                  
                                    <span class="invalid-feedback red-text" role="alert" v-if="v$.form.name.$error">
                                        {{ trans("text.FillField") }}
                                    </span>                                    
                                </div>
                                <div class="item__input">
                                    <label for="positions">{{ trans("text.CountVacancyPositions") }}*</label>
                                    <input type="number"
                                     min="1"
                                     name="positions" 
                                     :placeholder="trans('text.ExamplePlaceholder') + ' 6'"
                                     :class="{ 'error': v$.form.positions.$error }"
                                     v-model="form.positions"
                                    >
                                    <span class="invalid-feedback red-text" role="alert" v-if="v$.form.positions.$error">
                                        {{ trans("text.FillField") }}
                                    </span>
                                   
                                </div>
                            </div>
                        </div>

                        <div class="item descr2 descr3">
                            <div class="item__title">
                                <span>2</span>
                                {{ trans("text.LocationPlaceWork") }} <!--Расположение места работы-->
                            </div>

                            <div class="wrap-select wrap-select3">
                                <div class="item__select width558 margin-righttwo">

                                    <Select2
                                    v-model="country"
                                    :options="countries" 
                                    :settings="{  placeholder: trans('text.ChooseCountry') }"
                                    @select="changeCountry($event)" 
                                     />

                                </div>
                                <div class="item__select width558 margin-righttwo">

                                    <Select2 
                                    v-model="region" 
                                    :options="regions"
                                     :settings="{   placeholder: trans('text.ChooseRegion')  }"  
                                     @select="changeRegion($event)" />


                                </div>
                                <div class="item__select width558 margin-righttwo" :class="{ 'error-group': v$.form.city_id.$error }">
                                    <Select2 v-model="form.city_id" :options="cities" :settings="{  placeholder: trans('text.ChooseCity') }"                                   

                                     />
                                    <span class="invalid-feedback red-text" role="alert" v-if="v$.form.city_id.$error">
                                        {{ trans("text.FillField") }}
                                    </span>
                                </div>
                            </div>

                        </div>

                        <div class="item descr2 descr3">
                            <div class="item__title">
                                <span>3</span>
                                {{ trans("text.Specialization") }} <!--Специализация-->
                            </div>
                            <div v-for="(n,i) in form.specializations">
                            <div class="item__selects">
                                <div class="item__select width558 margin-righttwo">

                                    <Select2 
                                    v-model="form.specializations[i].group"
                                    :options="form.specializations[i].list_group_specializations"
                                    :settings="{  placeholder: trans('text.GroupSpecialization')  }"
                                    @select="changeSpecialization($event,i)"/>

                                   
                                </div>
                                <div class="item__select width535" style="margin-top: 0;">

                                    <Select2 
                                    v-model="form.specializations[i].text" 
                                    :options="form.specializations[i].list_specializations"
                                    :settings="{  placeholder: this.trans('text.Specialty') }"                
                                     />            
                                    
                                    <br>
                                    <a class="item__add item__add_two" @click.stop="addSpecialization($event,i)">
                                        {{ trans("text.AddSpecialization") }}
                                    </a>
                                </div>
                            </div>
                            </div>
                            <div class="item__checkbox">
                                <input type="checkbox" class="custom-checkbox" id="one" 
                                v-model="form.is_view_special" 
                                true-value="1"
                                false-value="0"
                                 > 
                                <label for="one">{{ trans("text.OnlySpecialization") }}</label>
                            </div>
                        </div>


                      <div class="item descr3">
                            <div class="item__title">
                                <span>4</span>
                                <div>
                                    {{ trans("text.InfoCandidate") }} <!--Общая информация по кандидату-->
                                    <p class="red-text">{{ trans("text.CandidateWontSeeIt") }}</p>
                                </div>
                            </div>
                            <div class="item__inputs">
                                <div class="item__input">
                                    <label for="name">{{ trans("text.PreferredGender") }}</label>
                                    <div class="item__radio">
                                        <div class="item__radio_wrapper">
                                            <div>
                                                <input class="custom-radio"
                                                 v-model="form.gender" type="radio" id="man"   
                                                       value="1">
                                                <label for="man">{{ trans("text.Male") }}</label>
                                            </div>
                                            <div>
                                                <input class="custom-radio"
                                                 v-model="form.gender" type="radio" id="woman" 
                                                       value="2">
                                                <label for="woman">{{ trans("text.Female") }}</label>
                                            </div>   

                                        </div>
                                    </div>
                                </div>
                                <div class="item__input">
                                    <label for="positions">{{ trans("text.PreferredAge") }}</label>
                                    <div class="item__input-little">
                                        <input type="number" min="1" 
                                        v-model="form.age_start"                                        
                                        :placeholder="trans('text.From')"
                                        :class="{ 'error': v$.form.age_start.$error }"
                                        >                                        
                                        <input type="number" min="1" 
                                        v-model="form.age_end"                                     
                                        :placeholder="trans('text.Before')"  
                                        :class="{ 'error': v$.form.age_end.$error }"
                                         >
                                    </div>
                                </div>
                            </div>

                            <div class="item__selects">
                                <div class="item__select mt-title width558 margin-righttwo"
                                    :class="{ 'error-group': v$.form.education.$error }"
                                >
                                    <span class="item-title">{{ trans("text.EducationLevel") }}*</span>
                                    <Select2 v-model="form.education" :options="educations_list"
                                     :settings="{  placeholder: trans('text.EducationLevel') }" />
                                
                                    <span class="invalid-feedback red-text" 
                                    role="alert" v-if="v$.form.education.$error">
                                    {{ trans("text.FillField") }}
                                    </span>
                                </div>
                                <div class="item__select mt-title width535"                                
                                    :class="{ 'error-group': v$.form.type_education.$error }"
                                >
                                    <Select2 v-model="form.type_education" :options="type_educations_list" 
                                    :settings="{  placeholder: trans('text.TypeEducation') }" />
                                    <span class="invalid-feedback red-text" 
                                    role="alert" v-if="v$.form.type_education.$error">
                                    {{ trans("text.FillField") }}
                                    </span>
                                </div>
 </div>

                            <div class="item__checkbox">
                                <input type="checkbox" class="custom-checkbox" id="set_is_coutch" v-model="form.set_is_coutch"
                                       value="1">
                                <label for="set_is_coutch">{{ trans("text.RemoteConsultant") }}</label>
                            </div>
                        </div>


                        <div class="item descr4">
                            <div class="item__title">
                                <span>5</span>
                                {{ trans("text.Experiences") }} <!--Обязанности-->
                            </div>
                            <span class="invalid-feedback red-text"  role="alert" v-if="v$.form.experiences.$error">
                            {{ trans("text.RequiredExperiences") }}
                            </span>  
                            <div v-for="(n,i) in form.experiences">

                                 <div class="item__selects item__selects-experiences">
                                    <div class="item__input margin-righttwo margin-topnon">
                                    <input class="margin-topnon" type="text"
                                           :placeholder="trans('text.DutyWorkExperience')"
                                           required
                                            v-model="form.experiences[i].text"  
                                            :class="{ 'error': v$.form.experiences.$error }"
                                            @input="experienceInput($event,i)"
                                           >  
                                </div>
                                <div class="item__input ">
                                    <label for="positions">{{ trans("text.YearsExperiences") }}</label>
                                    <div class="item__input-little">
                                        <input type="number" min="1" 
                                               :placeholder="trans('text.ExamplePlaceholder') + ' 7'"
                                               v-model="form.experiences[i].year"   
                                               :class="{ 'error': v$.form.experiences.$error }"
                                               >
                                    </div>
                                </div>
                                <div class="item__select width558 margin-top"
                                :class="{ 'error-group': v$.form.experiences.$error }"
                                >
                                    <Select2 v-model="form.experiences[i].degree" :options="degree_list"  :settings="{  placeholder: trans('text.DegreeExperience') }" />
                                </div>
                                <div class="item__btn-add-remove">
                                    <a class="item__add" @click.stop="addExperience($event,i)">
                                         {{ trans("text.AddExperience") }}
                                    </a>
                                    <a class="item__remove" @click.stop="deleteExperience(i)">
                                         {{ trans("text.Delete") }}
                                    </a>
                                </div>
                                <div class="clear"></div>
                                </div>
                            </div>

                        </div>

            <div class="item descr4">
                            <div class="item__title">
                                <span>6</span>
                                {{ trans("text.PossessionProfessionalSkills") }} <!--Владение профессиональными навыками (по уровню владения)*-->
                            </div>
                            <div v-for="(n,i) in form.skills">
                            <div class="item__selects">                             

                                <div class="item__input  margin-righttwo margin-topnon">
                                    <input class="margin-topnon" type="text" 
                                    :placeholder="trans('text.Skill')+'*'"            
                                    v-model="form.skills[i].text"
                                    @click.stop="popupSkill(i)"                                    
                                    :class="{ 'error': v$.form.skills.$error }"
                                    readonly
                                    >
                                    
                                </div>
                                <div class="item__select width535"
                                :class="{ 'error-group': v$.form.skills.$error }"
                                >
                                    <Select2 v-model="form.skills[i].level" 
                                    :options="skill_levels_list"  
                                    :settings="{  placeholder: trans('text.SkillProficiencyLevel') }" />
                                  
                                </div>
                                <div class="item__select width558 margin-top"
                                :class="{ 'error-group': v$.form.skills.$error }"
                                >
                                     <Select2 v-model="form.skills[i].degree" :options="degree_list"  :settings="{  placeholder: trans('text.DegreeSkillNeed') }" />                        
                               </div>
                                <div class="item__btn-add-remove">
                                    <a class="item__add" @click.stop="addSkill($event,i)">
                                        {{ trans("text.AddSkill") }}
                                    </a>
                                    <a class="item__remove" @click.stop="deleteSkill(i)">
                                        {{ trans("text.Delete") }}
                                    </a>
                                </div>
                            </div>
                            </div>
                            <span class="invalid-feedback red-text" role="alert" v-if="v$.form.skills.$error">
                            {{ trans("text.RequiredProfessionalSkills") }}
                            </span>

                        </div>

                <div class="item descr5">
                            <div class="item__title">
                                <span>7</span>
                                {{ trans("text.GeneralManagerialExperience") }} <!--Стаж общий и управленческий-->
                            </div>
                            <div class="item__selects int">
                                <div class="item__input ">
                                    <label for="positions">{{ trans("text.ExperienceWorkYearAll") }} <!--Требуемый профессиональный стаж в годах (всего)--></label>
                                    <div class="item__input-little">
                                        <input type="number" min="1" 
                                        v-model="form.experience_work_year_all"
                                        :placeholder="trans('text.ExamplePlaceholder') + ' 6'"
                                          :class="{ 'error': v$.form.experience_work_year_all.$error }">       
                                    </div>
                                </div>
                                <div class="item__input ">
                                    <label for="positions">{{ trans("text.ExperienceWorkBaseYearAll") }} <!--Требуемый управленческий стаж в годах (всего)--></label>
                                    <div class="item__input-little">
                                        <input type="number" min="1"
                                         v-model="form.experience_work_base_year_all"
                                         :placeholder="trans('text.ExamplePlaceholder') + ' 8'"                                      
                                               :class="{ 'error': v$.form.experience_work_base_year_all.$error }"
                                               >
                                    </div>
                                </div>
                                <div class="item__input" v-if="form.experience_work_base_year_all > 0">
                                    <label for="positions">{{ trans("text.CountWorkPeople") }} <!--Кол - во сотрудников в прямом подчинении--></label>
                                    <div class="item__input-little">
                                        <input type="number" min="1" 
                                        v-model="form.count_work_people"
                                        :placeholder="trans('text.ExamplePlaceholder') + ' 5'"
                                        :class="{ 'error': v$.form.count_work_people.$error }"       
                                        >
                                    </div>
                                </div>
                                <div class="item__input " v-if="form.experience_work_base_year_all > 0">
                                    <label for="positions">{{ trans("text.CountWorkPeopleAll") }} <!--Кол - во сотрудников в функциональном подчинении--></label>
                                    <div class="item__input-little">
                                        <input type="number" min="1" 
                                        v-model="form.count_work_people_all" 
                                        :placeholder="trans('text.ExamplePlaceholder') + ' 7'"
                                          :class="{ 'error': v$.form.count_work_people_all.$error }"   
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>

 <div class="item descr6">
                            <div class="item__title">
                                <span>8</span>
                                {{ trans("text.ForeignLanguageProficiency") }} <!--Владение иностранным языком-->
                            </div>
                            <div v-for="(n,i) in form.languages">
                            <div class="item__selects no-wrap">
                                <div class="item__select width270 margin-righttwo">
                                     <Select2 v-model="form.languages[i].text" :options="languages_list" 
                                     :settings="{  placeholder: trans('text.Language') }"
                                     @select="selectLanguages($event,i)"
                                     />
                                    
                                </div>
                                <div class="item__select width270 margin-righttwo">
                                    <Select2 v-model="form.languages[i].level" :options="language_levels_list" 
                                    :settings="{  placeholder: trans('text.ProficiencyLevel') }" />
                            
                                </div>
                                <div class="item__select width270">
                                    <Select2 v-model="form.languages[i].degree" :options="degree_list" :settings="{  placeholder: trans('text.DegreeLanguage') }" />
                                
                                </div>
                                <div class="item__btn-add-remove item__btn-add-remove-v2">
                                    <a class="item__add" @click.stop="addLanguage($event,i)">
                                       {{ trans("text.AddLanguage") }}
                                    </a>
                                    <a class="item__remove" @click.stop="deleteLanguage(i)">
                                        {{ trans("text.Delete") }}
                                    </a>
                                </div>
                            </div>
                            </div>
                        </div>


              <div class="item descr6 ">
                            <div class="item__title">
                                <span>9</span>
                                {{ trans("text.HavingDrivingLicense") }} 
                            </div>
                            <div v-for="(n,i) in form.licences">
                            <div class="item__selects no-wrap">
                                <div class="item__select width270 margin-righttwo">
                                    <Select2 v-model="form.licences[i].text" :options="licences_list" 
                                    :settings="{  placeholder: trans('text.CategoryLicences') }"
                                    @select="selectLicences($event,i)"
                                    />                                 
                                </div>
                                <div class="item__select width270">
                                    <Select2 v-model="form.licences[i].degree" :options="degree_list" 
                                    :settings="{  placeholder: trans('text.DegreeLicences') }" />

                                </div>
                                <div class="item__btn-add-remove item__btn-add-remove-v2">
                                    <a class="item__add" @click.stop="addLicences($event,i)">
                                        {{ trans("text.AddLicences") }} 
                                    </a>
                                    <a class="item__remove" @click.stop="deleteLicences(i)">
                                        {{ trans("text.Delete") }}
                                    </a>
                                </div>
                            </div>
                            </div>
                        </div>


                        <div class="item descr7">
                            <div class="item__title">
                                <span>10</span>
                                {{ trans("text.HavingCar") }} <!--Владение собственным автомобилем-->
                            </div>
                            <div class="item__selects no-wrap">
                                <div class="item__select width270 margin-righttwo">
                                    <Select2 v-model="form.type_car[0].text" :options="type_car_list" 
                                    :settings="{  placeholder: trans('text.TypeCar') }"
                                    @select="selectTypeCar($event)"
                                    />
                                
                                </div>
                                <div class="item__select width558">
                                    <Select2 v-model="form.type_car[0].degree" :options="degree_list" :settings="{  placeholder: trans('text.DegreeCar') }" />

                                </div>
                            </div>
                        </div>

                        <div class="item descr8">
                            <div class="item__title">
                                <span>11</span>
                                {{ trans("text.TypeContract") }} <!--Тип договора по вакансии-->
                            </div>
                            <div v-for="(n,i) in form.type_contract">
                            <div class="item__selects no-wrap">
                                <div class="item__select width270"
                                :class="{ 'error-group': v$.form.type_contract.$error }"
                                >
                                    <Select2 v-model="form.type_contract[i].text" :options="type_contract_list" :settings="{  placeholder: trans('text.TypeContract') }" />
                                    <span class="invalid-feedback red-text" 
                                    role="alert" v-if="v$.form.type_contract.$error">
                                    {{ trans("text.FillField") }}
                                    </span>
                                </div>
                                <div class="item__btn-add-remove item__btn-add-remove-v2">
                                    <a class="item__add" @click.stop="addContract($event,i)">
                                        {{ trans("text.AddTypeContract") }}
                                    </a>
                                    <a class="item__remove" @click.stop="deleteContract(i)">
                                        {{ trans("text.Delete") }}
                                    </a>
                                </div>
                            </div>
                             </div>    
                        </div>

                        <div class="item descr9">
                            <div class="item__title">
                                <span>12</span>
                                {{ trans("text.IncomeAccrued") }} <p class="strong">{{ trans("text.Salary") }}</p>
                                <p class="p">{{ trans("text.BeforeTaxes") }}*</p>
                            </div>
                            <div class="item__selects no-wrap">

                                <label class="item__selects-label">{{ trans("text.From") }}</label>

                                <div class="item__input italic width270 margin-righttwo">
                                    <input class="margin-topnon"
                                     type="number"
                                      placeholder="0"
                                      v-model="form.income_start" 
                                    :class="{ 'error': v$.form.income_start.$error }"
                                    @input="incomeInput($event)" 
                                    >
                                    <span class="invalid-feedback red-text" 
                                    role="alert" v-if="v$.form.income_start.$error">
                                    {{ trans("text.MinimumValue") }} 140
                                    </span>

                                </div>

                                <label class="item__selects-label">{{ trans("text.Before") }}</label>

                                <div class="item__input italic width270 margin-righttwo">
                                    <input class="margin-topnon"
                                     type="number"
                                      placeholder="0"
                                      v-model="form.income_end" 
                                    :class="{ 'error': v$.form.income_end.$error }"
                                    @input="incomeInput($event)" 
                                    >
                                    <span class="invalid-feedback red-text" 
                                    role="alert" v-if="v$.form.income_end.$error">
                                    {{ trans("text.MinimumValue") }} 140
                                    </span>
                                </div>    


                                <div class="item__select width270 italic margin-righttwo"
                                :class="{ 'error-group': v$.form.currency.$error }" 
                                >
                                    <span class="currency-label">{{ trans("text.ChoiceIsMadeOnce") }}</span>
                                    <Select2 v-model="form.currency" :options="currency_list" required :settings="{  placeholder: trans('text.CurrencySmall') }" />
                                    <span class="invalid-feedback red-text" 
                                    role="alert" v-if="v$.form.currency.$error">
                                    {{ trans("text.FillField") }}
                                    </span>
                                </div>
                                <div class="item__radio">
                                    <div class="item__radio_title">
                                        {{ trans("text.PublishAmountTotalIncome") }}
                                    </div>
                                    <div class="item__radio_wrapper">
                                        <div>
                                            <input class="custom-radio" v-model="form.publish_income" 
                                            type="radio" id="publish_income_yes" 
                                            
                                            value="1" >
                                            <label for="publish_income_yes">{{ trans("text.Yes") }}</label>
                                        </div>
                                        <div>
                                            <input class="custom-radio" v-model="form.publish_income" 
                                            type="radio" id="publish_income_no" 

                                             value="0">
                                            <label for="publish_income_no">{{ trans("text.No") }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="item descr8">
                            <div class="item__title">
                                <span>13</span>
                                {{ trans("text.EmploymentVacancy") }} <!--Занятость по вакансии-->
                            </div>
                            <div v-for="(n,i) in form.employment">
                            <div class="item__selects no-wrap">
                                <div class="item__select width270">
                                     <Select2 v-model="form.employment[i]" :options="employments_list" :settings="{  placeholder: trans('text.EmploymentVacancy') }" />
                                </div>
                                <div class="item__btn-add-remove item__btn-add-remove-v2">
                                    <a class="item__add" @click.stop="addEmployment($event,i)">
                                        {{ trans("text.AddEmploymentVacancy") }}
                                    </a>
                                    <a class="item__remove" @click.stop="deleteEmployment(i)">
                                        {{ trans("text.Delete") }}
                                    </a>
                                </div>
                            </div>
                            </div>
                        </div>

                        <div class="item descr8">
                            <div class="item__title">
                                <span>14</span>
                                {{ trans("text.WorkTime") }} <!--Режим работы-->
                            </div>
                            <div v-for="(n,i) in form.work_time">
                            <div class="item__selects no-wrap">
                                <div class="item__input italic width270">
                                    <input class="margin-topnon" type="text"
                                    :placeholder="trans('text.WorkTimeExample')"
                                    v-model="form.work_time[i]"
                                    >
                                </div>
                                <div class="item__btn-add-remove item__btn-add-remove-v2">
                                    <a class="item__add" @click.stop="addWorkTime($event,i)">
                                        {{ trans("text.AddWorkTime") }}
                                    </a>
                                    <a class="item__remove" @click.stop="deleteWorkTime(i)">
                                        {{ trans("text.Delete") }}
                                    </a>
                                </div>
                            </div>
                            </div>
                        </div>

                        <div class="item descr8">
                            <div class="item__title">
                                <span>15</span>
                                {{ trans("text.FacilitationVacancy") }}* <!--Льготы и условия работы по вакансии-->
                            </div>

                            <div v-for="(n,i) in form.facilitation">
                            <div class="item__selects no-wrap">
                                <div class="item__select width270">
                                <Select2 v-model="form.facilitation[i]"
                                 :options="facilitation_list" 
                                :settings="{  placeholder: trans('text.FacilitationVacancySmall') }" /> 
                                </div>
                                <div class="item__btn-add-remove item__btn-add-remove-v2">
                                    <a class="item__add" @click.stop="addFacilitation($event,i)">
                                        {{ trans("text.AddFacilitation") }} 
                                    </a>
                                    <a class="item__remove" @click.stop="deleteFacilitation(i)">
                                        {{ trans("text.Delete") }}
                                    </a>
                                </div>
                            </div>
                            </div>
                        </div>


               <div class="item descr8">
                            <div class="item__title">
                                <span>16</span>
                                {{ trans("text.PersonalData") }} <!--Личностные данные-->
                            </div>
                            <div v-for="(n,i) in form.personal">
                            <div class="item__selects no-wrap">
                                <div class="item__select width270 margin-righttwo">
                                    <Select2 :options="form.personal[i].list_category" 
                                     @select="changeCategoryPersonal($event,i)"
                                    :settings="{  placeholder: trans('text.CategoryPersonal') }"
                                    v-model="form.personal[i].text_category"
                                     />
                                    
                                </div>

                                <div class="item__select width270 margin-righttwo">

                                    <Select2 :options="form.personal[i].list_children" 
                                    :settings="{  placeholder: trans('text.PersonalData') }" 
                                     @select="changePersonal($event, i)"
                                     v-model="form.personal[i].text_children"
                                    />
                                
                                </div>

                                <div class="item__input italic width270 margin-leftnon margin-topnon" 
                                v-if="form.personal[i].show_other"
                                 >
                                <input class="margin-topnon" type="text" :placeholder="trans('text.Other')"
                                v-model="form.personal[i].text">
                                  </div>


                                <div class="item__btn-add-remove item__btn-add-remove-v2">
                                    <a class="item__add" @click.stop="addPersonal($event,i)">
                                        {{ trans("text.Add") }} 
                                    </a>
                                    <a class="item__remove" @click.stop="deletePersonal(i)">
                                        {{ trans("text.Delete") }}
                                    </a>
                                </div>
                            </div>
                            </div>
                           
                        </div>


<div class="form__btns" v-if="edit">
    <a class="form__btns_btn blue-border" @click.stop="submitEditHandler(1)">
        {{ trans("text.SaveAndExit") }}
    </a>
    <a href="/home" class="form__btns_btn orange-border">
        {{ trans("text.ExitWithoutSave") }}
    </a>
    <a v class="form__btns_btn blue-bg" @click.stop="submitEditHandler(2)">
        {{ trans("text.TransferWorkButton") }}
    </a>
</div>

<div class="form__btns" v-else>
    <a name="saveEndExit" class="form__btns_btn blue-border" @click.stop="submitSaveHandler(1)">
        {{ trans("text.SaveAndExit") }} 
    </a>
    <a href="/home" name="save" class="form__btns_btn orange-border">
        {{ trans("text.ExitWithoutSave") }} 
    </a>
    <a name="saveEndChangeStatus" class="form__btns_btn blue-bg" @click.stop="submitSaveHandler(2)">
        {{ trans("text.TransferWorkButton") }} 
    </a>    
</div>


</div>              

</div>
</section>
</div>


<transition name="modal">
<popup-modal :lang="lang" v-if="showModalSkill" @closePopup="closePopupSkill">
<template v-slot:header>
<h3>{{ trans('text.PopupProfessionalSkills') }}</h3>
</template>
</popup-modal>
</transition>


</template>
<script>
import Swal from 'sweetalert2';    
import useVuelidate  from "@vuelidate/core";
import { required, email, minLength, maxLength, sameAs } from "@vuelidate/validators";

import Select2 from 'vue3-select2-component';    
import PopupModal from './PopupModal';
import SelectLoad from './mixins/SelectLoad.vue';
import AddInput from './mixins/AddInput.vue';
import PersonalMixin from './mixins/PersonalMixin.vue';
import SpecializationMixin from './mixins/SpecializationMixin.vue';

import ValidationsVacancy from './mixins/ValidationsVacancy.vue';
import ExperienceMixin from './mixins/ExperienceMixin.vue';
import SkillMixin from './mixins/SkillMixin.vue';
import LanguageMixin from './mixins/LanguageMixin.vue';
import CityMixin from './mixins/CityMixin.vue';

export default {
    props: ['name','logo','user_id','work_id','edit','template','lang'],
    setup () {
        return { v$: useVuelidate() }
    },
    mixins: [SelectLoad, AddInput, ValidationsVacancy, PersonalMixin, 
    SpecializationMixin, ExperienceMixin, SkillMixin, LanguageMixin, CityMixin],

    data() {
       return {   

        showModalSkill: false,
        indexSkill: 0,

        country: '', 
        region: '',    

        countries: [],
        regions: [],        
        cities: [],

        list_group_specializations: [],
        educations_list: [],
        type_educations_list: [],

        skill_levels_list: [],

        languages_list: [],
        language_levels_list: [],

        licences_list: [],
 
        type_car_list: [],  

        type_contract_list: [],
        currency_list: [],

        employments_list: [],

        facilitation_list: [],
        personal_list_category: [], 

        degree_list: [],

        status: 1,

        form: {
           
            publish_сompany: 1,
            name: '',
            positions: '',
                     
            city_id: null,
            specializations: [
            {
                id: 0,
                list_group_specializations: [],
                list_specializations: [],
                text: "",
                group: "",
            },
            ],
            is_view_special: 0,

            gender: null,
            age_start: '',
            age_end: '',
            

            education: null,
            type_education: null,

            set_is_coutch: 0,

            experiences: [
            {
                id: 0,
                text: "",
                year: null,
                degree: null,  
                }              
            ],  

            skills: [
                {
                id: 0,
                text: "",
                level: null,
                degree: null,  
                }  
            ],

            experience_work_year_all: null,
            experience_work_base_year_all: null,
            count_work_people: null,
            count_work_people_all: null,

            languages: [
                {
                id: 0,
                text: "",
                level: null,
                degree: null,  
                }  
            ],

            licences: [
             {
                id: 0,
                text: "",
                degree: null,  
             } 
            ],

            type_car: [
             {
                id: 0,
                text: "",
                degree: null,  
             } 
            ], 

            type_contract: [{ id: 0, text: "" }],

            income: '',
            income_start: '',
            income_end: '',

            currency: null,
            publish_income: 1,

            employment: [""],
            work_time: [""],

            facilitation: [""],
            facilitation_other: '',
            
            personal: [
            {
                id: 0,
                list_category: [],
                list_children: [],
                text_category: "",
                text_children: "",
                show_other: false,
                text: "",
            },
            ],
          
        },
    }
    },

    methods: {

        submitEditHandler(work_status) {
            this.v$.$validate(); 

            if (this.form.age_start > this.form.age_end) {
                Swal.fire(this.trans('text.ErrorFieldAge'));
                return false;
            }

            if (this.form.income_start > this.form.income_end) {
                Swal.fire(this.trans('text.ErrorFieldIncome'));
                return false;
            }  

            let personal = this.form.personal;
            if (personal.length > 1) {
                for (let i = 1; i < personal.length; i++) {
                  if (personal[i].text === "") {
                     Swal.fire(trans('text.ErrorFieldPersonal'));
                     return false;
                  }  
                }
            }

            if (!this.v$.$error) { // if ANY fail validation
                   
            } else {
                Swal.fire(this.trans('text.PleaseFillAllFields'));
                return false;
            }


            if (this.work_id > 0) {
            let data = {
                ...this.form,
                user_id: this.user_id,
                status: work_status,
            }

            axios({
              method: 'post',
              url: '/api/works/update/'+this.work_id,
              data: data,
            })
            .then((response) => {                
                if (response.data !== undefined) {
                    if (response.data.status === 1) {     
                        Swal.fire(this.trans('text.VacancyEditedMsg'));            
                        location.href='/home';
                    }
                    if (response.data.status === 0) { 
                        Swal.fire(this.trans('text.Error'));                       
                    }    
                }     
            });

            }
           
        },

        submitSaveHandler(work_status) {  

            this.v$.$validate(); 

            if (this.form.age_start > this.form.age_end) {
                Swal.fire(this.trans('text.ErrorFieldAge'));
                return false;
            }

            if (this.form.income_start > this.form.income_end) {
                Swal.fire(this.trans('text.ErrorFieldIncome'));
                return false;
            }  

            let personal = this.form.personal;
            if (personal.length > 1) {
                for (let i = 1; i < personal.length; i++) {
                  if (personal[i].text === "") {
                     Swal.fire(this.trans('text.ErrorFieldPersonal'));
                     return false;
                  }  
                }
            }

            if (!this.v$.$error) { // if ANY fail validation
                   
            } else {
                   Swal.fire(this.trans('text.PleaseFillAllFields'));
                   return false;
            }           

            let data = {
                ...this.form,
                user_id: this.user_id,
                status: work_status,
            }

            axios({
              method: 'post',
              url: '/api/work/store',
              data: data,
            })
            .then((response) => {                
                if (response.data !== undefined) {
                    if (response.data.status === 1) {   
                        Swal.fire(this.trans('text.VacancySaveMsg'));
                        location.href='/home?status=1';
                    }
                    if (response.data.status === 0) { 
                        Swal.fire(this.trans('text.Error'));         
                    }  
                    if (response.data.status === 2) { 
                        Swal.fire(this.trans('text.VacancyExistsMsg'));                
                    }   
                }     
            });

        },

        loadSaveWork() {
            let id =  this.work_id;
            if (id > 0) {
            axios({
              method: 'get',
              url: '/api/works/edit/'+id,
            })
            .then((response) => {
                if (response.data !== undefined) {

                    let work = response.data.data;
                  
                    if (work.publish_сompany !== undefined) {
                      if (work.publish_сompany === 0) { 
                        this.form.publish_сompany = "0";
                      }
                    }
                  
                    if (work.name !== undefined) {
                        this.form.name = work.name;
                    }
                    
                    if (work.positions !== undefined) {
                        this.form.positions = work.positions;
                    }  

                    if (work.country_id !== undefined) {
                        this.country = work.country_id;
                    }

                    if (work.region_id !== undefined) {
                        this.region = work.region_id;
                        this.loadCities(work.region_id);
                    }

                    if (work.city_id !== undefined) {
                        this.form.city_id = work.city_id;
                    }  

                    if (work.special !== undefined && work.special.length > 0) {
                        this.form.specializations = work.special;
                    }  

                    if (work.is_view_special !== undefined) {
                        this.form.is_view_special = work.is_view_special;
                    }  

                    if (work.gender !== undefined) {
                        this.form.gender = work.gender;
                    }  

                    if (work.age_start !== undefined) {
                        this.form.age_start = work.age_start;
                    }   
                    if (work.age_end !== undefined) {
                        this.form.age_end = work.age_end;
                    }  

                    if (work.education !== undefined) {
                        this.form.education = work.education;
                    }  

                    if (work.type_education !== undefined) {
                        this.form.type_education = work.type_education;
                    }  

                    if (work.set_is_coutch !== undefined) {
                        this.form.set_is_coutch = work.set_is_coutch;
                    }  

                    if (work.experiences !== undefined) {
                        this.form.experiences = work.experiences;
                    }  

                    if (work.skills !== undefined) {
                        this.form.skills = work.skills;
                    }

                    if (work.experience_work_year_all !== undefined) {
                        this.form.experience_work_year_all = work.experience_work_year_all;
                    }  
                    if (work.experience_work_base_year_all !== undefined) {
                        this.form.experience_work_base_year_all = work.experience_work_base_year_all;
                    }  
                    if (work.count_work_people !== undefined) {
                        this.form.count_work_people = work.count_work_people;
                    }  
                    if (work.count_work_people_all !== undefined) {
                        this.form.count_work_people_all = work.count_work_people_all;
                    }  

                    if (work.income_start !== undefined) {
                        this.form.income_start = work.income_start;
                    }  

                    if (work.income_end !== undefined) {
                        this.form.income_end = work.income_end;
                    }  

                    if (work.currency !== undefined) {
                        this.form.currency = work.currency;
                    }  

                    if (work.publish_income !== undefined) {
                        this.form.publish_income = work.publish_income;
                    }  

                    if (work.type_contract !== undefined && work.type_contract.length > 0) {
                        this.form.type_contract = work.type_contract;
                    }  

                    if (work.languages !== undefined && work.languages.length > 0) {
                        this.form.languages = work.languages;
                    }  

                    if (work.licences !== undefined && work.licences.length > 0) {
                        this.form.licences = work.licences;
                    }  

                    if (work.type_car !== undefined && work.type_car.length > 0) {
                        this.form.type_car = work.type_car;
                    }  

                    if (work.employment !== undefined && work.employment.length > 0) {
                        this.form.employment = work.employment;
                    }  

                    if (work.work_time !== undefined && work.work_time.length > 0) {
                        this.form.work_time = work.work_time;
                    }  

                    if (work.facilitation !== undefined && work.facilitation.length > 0) {
                        this.form.facilitation = work.facilitation;
                    }  

                    if (work.personal !== undefined && work.personal.length > 0) {
                        this.form.personal = work.personal;
                    } 

                }       
            });
           }     
        },



        loadTemplateWork() {
            let id =  this.work_id;
            if (id > 0) {
            axios({
              method: 'get',
              url: '/api/works/edit/'+id,
            })
            .then((response) => {
                if (response.data !== undefined) {

                    let work = response.data.data;
                  
                    if (work.publish_сompany !== undefined) {
                      if (work.publish_сompany === 0) { 
                        this.form.publish_сompany = "0";
                      }
                    }
                            
                    if (work.positions !== undefined) {
                        this.form.positions = work.positions;
                    }  

                    if (work.country_id !== undefined) {
                        this.country = work.country_id;
                    }                    

                    if (work.region_id !== undefined) {
                        this.region = work.region_id;
                        this.loadCities(work.region_id);
                    }

                    if (work.city_id !== undefined) {
                        this.form.city_id = work.city_id;
                    }  

                    if (work.special !== undefined && work.special.length > 0) {
                        this.form.specializations = work.special;
                    }  

                    if (work.is_view_special !== undefined) {
                        this.form.is_view_special = work.is_view_special;
                    }  

                    if (work.gender !== undefined) {
                        this.form.gender = work.gender;
                    }  

                    if (work.age_start !== undefined) {
                        this.form.age_start = work.age_start;
                    }   
                    if (work.age_end !== undefined) {
                        this.form.age_end = work.age_end;
                    }  

                    if (work.education !== undefined) {
                        this.form.education = work.education;
                    }  

                    if (work.type_education !== undefined) {
                        this.form.type_education = work.type_education;
                    }  

                    if (work.set_is_coutch !== undefined) {
                        this.form.set_is_coutch = work.set_is_coutch;
                    }  

                    if (work.experiences !== undefined) {
                        this.form.experiences = work.experiences;
                    }  

                    if (work.skills !== undefined) {
                        this.form.skills = work.skills;
                    } 

                    if (work.experience_work_year_all !== undefined) {
                        this.form.experience_work_year_all = work.experience_work_year_all;
                    }  
                    if (work.experience_work_base_year_all !== undefined) {
                        this.form.experience_work_base_year_all = work.experience_work_base_year_all;
                    }  
                    if (work.count_work_people !== undefined) {
                        this.form.count_work_people = work.count_work_people;
                    }  
                   if (work.count_work_people_all !== undefined) {
                        this.form.count_work_people_all = work.count_work_people_all;
                    }  

                    if (work.income_start !== undefined) {
                        this.form.income_start = work.income_start;
                    }  

                    if (work.income_end !== undefined) {
                        this.form.income_end = work.income_end;
                    }                  

                   if (work.currency !== undefined) {
                        this.form.currency = work.currency;
                    }  

                    if (work.publish_income !== undefined) {
                        this.form.publish_income = work.publish_income;
                    }  

                    if (work.type_contract !== undefined && work.type_contract.length > 0) {
                        this.form.type_contract = work.type_contract;
                    }  

                    if (work.languages !== undefined && work.languages.length > 0) {
                        this.form.languages = work.languages;
                    }  

                    if (work.licences !== undefined && work.licences.length > 0) {
                        this.form.licences = work.licences;
                    }  

                    if (work.type_car !== undefined && work.type_car.length > 0) {
                        this.form.type_car = work.type_car;
                    }  

                    if (work.employment !== undefined && work.employment.length > 0) {
                        this.form.employment = work.employment;
                    }  

                    if (work.work_time !== undefined && work.work_time.length > 0) {
                        this.form.work_time = work.work_time;
                    }  

                    if (work.facilitation !== undefined && work.facilitation.length > 0) {
                        this.form.facilitation = work.facilitation;
                    }  

                    if (work.personal !== undefined && work.personal.length > 0) {
                        this.form.personal = work.personal;
                    } 

                }       
            });
           }     
        }


    },

    mounted() {
        if (this.edit === true && this.work_id !== "") {
            this.loadSaveWork();
        }
        if (this.template === true && this.work_id !== "") {
            this.loadTemplateWork();
        }
        this.loadSelects();

        this.loadCurrencies();   
    }
}
</script>
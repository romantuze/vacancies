<template>  
    <div class="page two">
        <section class="questionnaire" v-if="closed">
            <div class="container">
                <div class="questionnaire__top show">
                    <div class="questionnaire__title">
                        {{trans('text.QuestionWelcome')}}
                    </div>
                    <div class="questionnaire__subtitle">
                        <span v-if="work.name">{{ work.name }}</span>
                    </div>
                    <div class="questionnaire__recommendation">
                        <div class="questionnaire__recommendation_title">
                            {{trans('text.AcceptanceJobApplication')}} "{{ work.name }}" {{trans('text.AcceptanceJobApplicationClose')}}.
                        </div>
                        <div class="questionnaire__recommendation_descr" v-if="closed_date">
                            {{ closed_date }}
                        </div>
                    </div>
                    <div class="questionnaire__check">                        
                    </div>
                </div>
            </div>
        </section>

        <section class="questionnaire" v-else>
            <div class="container">
                <div class="questionnaire__top show">
                    <div class="questionnaire__title">
                        {{trans('text.QuestionWelcome')}}
                    </div>
                    <div class="questionnaire__subtitle">
                        <span v-if="work.name">{{ work.name }}</span>
                    </div>
                    <div class="questionnaire__recommendation">
                        <div class="questionnaire__recommendation_title">
                            {{trans('text.ToDetailExperiencePlease')}}
                        </div>
                        <div class="questionnaire__recommendation_descr">
                            <p>{{trans('text.GivePermissionPersonalData')}}</p>
                            <p>{{trans('text.RegisterAnswerQuestionnaire')}}</p>                           
                        </div>
                    </div>
                    <div class="questionnaire__check">
                        <input type="checkbox"
                         class="custom-checkbox"
                         id="check" 
                         v-model="check" 
                         value="1"
                         >
                        <label for="check">{{trans('text.UsePersonalDataForEmployment')}}</label>
                        <a class="questionnaire__check_link" @click.stop="popupPolicy()">
                            {{trans('text.PersonalDataUsagePolicy')}}
                        </a>
                    </div>
                </div>

                <div class="questionnaire__wrapper" :class="{ 'active': check }">

                    <login-question @login-event="loginEventHandler" :work_id="work_id" v-if="login===false"></login-question>

                    <form action="#" class="form" v-if="login">
                        <div class="item quest1 mob  ">
                            <div class="item__title">
                                <span>1</span>
                                {{ trans("text.PersonalDataInfo") }} <!--Личные данные-->
                            </div>
                            <div class="item-flex">
                                <div class="item__inputs flex">
                                    <div class="item__inputs-two">
                                        <div class="item__input width270 item__input-two">
                                            <input type="tel" 
                                            :placeholder="trans('text.CandidatePhone')+'*'"
                                             v-model.trim="form.phone" 
                                              :class="{ 'error': v$.form.phone.$error }"
                                              readonly 
                                            >
                                            <span class="invalid-feedback red-text"
                                              role="alert" v-if="v$.form.phone.$error">
                                                {{ trans("text.FillField") }} 
                                            </span>
                                        </div>
                                        <div class="item__input width270 item__input-two">
                                            <input type="email" 
                                            :placeholder="trans('text.EmailAddress')+'*'"
                                             v-model.trim="form.email" 
                                              :class="{ 'error': v$.form.email.$error }"
                                              readonly 
                                            >
                                            <span class="invalid-feedback red-text" 
                                             role="alert" v-if="v$.form.email.$error">
                                                {{ trans("text.FillField") }} 
                                            </span>
                                        </div>
                                    </div>
                                    <div class="item__inputs-two">
                                        <div class="item__input width270 item__input-two">
                                            <input type="text" name="surname" 
                                            :placeholder="trans('text.Surname')+'*'"
                                            v-model.trim="form.fio_f"
                                             :class="{ 'error': v$.form.fio_f.$error }"   
                                            >
                                            <span class="invalid-feedback red-text" 
                                             role="alert" v-if="v$.form.fio_f.$error">
                                                {{ trans("text.FillField") }} 
                                            </span>
                                        </div>
                                        <div class="item__input width270 item__input-two">
                                            <input type="text" name="names"
                                            :placeholder="trans('text.NameSmall')+'*'"
                                            v-model.trim="form.fio_i" 
                                             :class="{ 'error': v$.form.fio_i.$error }"   
                                            >
                                            <span class="invalid-feedback red-text" 
                                             role="alert" v-if="v$.form.fio_i.$error">
                                                {{ trans("text.FillField") }} 
                                            </span>
                                        </div>
                                        <div class="item__input width270 item__input-two ">
                                            <input type="text" name="names"  
                                            class="margin-bottomnon" 
                                            :placeholder="trans('text.Middlename')"
                                            v-model.trim="form.fio_o"               
                                            >                                           
                                        </div>
                                    </div>
                                </div>
                                <div class="item__selects item__selects-colum">
                                    <div class="item__select_title margin-topminus">
                                        {{ trans("text.WorkPermitQuestion") }}
                                    </div>
                                    <div v-for="(n,i) in form.permit_countries">
                                    <div class="item__select width270 item__select-flex margin-bottom13"
                                    >        
                                        <Select2 
                                        v-model="form.permit_countries[i]" 
                                        :options="question_countries" 
                                        :settings="{  placeholder: trans('text.ChooseCountry') }"
                                         />
                                        <a class="item__add item__add_two"
                                         @click.stop="addCountry($event,i)">
                                            {{ trans("text.AddCountry") }}
                                        </a>
                                    </div>
                                    </div>
                                    <span class="invalid-feedback red-text" 
                                             role="alert" v-if="v$.form.permit_countries.$error">
                                                {{ trans("text.FillField") }} 
                                    </span>
                                    <div class="item__select_title">
                                        {{ trans("text.BirthdayFormat") }} 
                                    </div>
                                    <div class="item__input width270 item__input-two margin-bottom13"> 
                                        <input 
                                        v-mask="'##.##.####'" 
                                        type="text" 
                                        class="margin-topnon date"                                        
                                         v-model="form.birthday"
                                            :placeholder="trans('text.BirthdayFormat')"
                                            :class="{ 'error-group': v$.form.birthday.$error }"
                                            >
                                        <span class="invalid-feedback red-text" 
                                             role="alert" v-if="v$.form.birthday.$error">
                                                {{ trans("text.FillField") }} 
                                            </span>    
                                    </div>
                                    <div class="item__radio">
                                        <div class="item__radio_title">
                                            {{ trans("text.Gender") }} 
                                        </div>
                                        <div class="item__radio_wrapper">
                                            <div>
                                                <input class="custom-radio" type="radio" id="man"
                                                    value="1"
                                                    v-model="form.gender"
                                                    >
                                                <label for="man">{{ trans("text.Male") }}</label>
                                            </div>
                                            <div>
                                                <input class="custom-radio" type="radio" id="woman"
                                                    value="2"
                                                    v-model="form.gender"
                                                    >
                                                <label for="woman">{{ trans("text.Female") }}</label>
                                            </div>
                                            <span class="invalid-feedback red-text" 
                                             role="alert" v-if="v$.form.gender.$error">
                                                {{ trans("text.FillField") }} 
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item quest2 mob ">
                            <div class="item__title">
                                <span>2</span>
                                {{ trans("text.Location") }} <!--Местонахождение-->
                            </div>
                            <div class="item__selects no-wrap">
                                 <div class="item__select width264 margin-righttwo">
                                    <Select2
                                    v-model="country"
                                    :options="countries" 
                                    :settings="{  placeholder: trans('text.ChooseCountry') }"
                                    @select="changeCountry($event)" 
                                     />
                                </div>
                                <div class="item__select width264 margin-righttwo">
                                 <Select2 v-model="region" :options="regions" :settings="{  placeholder: trans('text.ChooseRegion')  }"   @select="changeRegion($event)" />
                                </div>
                                <div class="item__select width264 margin-righttwo" 
                                :class="{ 'error-group': v$.form.city_id.$error }">
                                     <Select2 v-model="form.city_id" :options="cities" 
                                     :settings="{  placeholder: trans('text.ChooseCity') }"  
                                     @select="changeCityCandidate($event)"
                                     />
                                    <span class="invalid-feedback red-text" role="alert" v-if="v$.form.city_id.$error">
                                        {{ trans("text.FillField") }} 
                                    </span>
                                </div>                
                            </div>
                              <div class="item__selects no-wrap">
                              <div class="item__select width547" v-if="show_city_match">
                                        <span>{{city_match_text}}</span>
                                          <Select2 v-model="form.city_match" :options="city_match_list" 
             :settings="{  placeholder: trans('text.ChooseVariant') }"   />                                       
                               </div>  
                               </div>
                        </div>
                        <div class="item descr2 mob descr3 ">
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
                                     :settings="{  placeholder: trans('text.GroupSpecialization')  }" @select="changeSpecialization($event,i)"/> 
                                </div>
                                <div class="item__select width535" style="margin-top: 0;">    
                                   <Select2 v-model="form.specializations[i].text" :options="form.specializations[i].list_specializations"
                                     :settings="{  placeholder: trans('text.Specialty') }"                               
                                     />         
                                     <br>
                                     <a class="item__add item__add_two" @click.stop="addSpecialization($event,i)">
                                        {{ trans("text.AddSpecialization") }} 
                                    </a>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="item quest4 mob " v-if="work.set_is_coutch &&  work.set_is_coutch === 1">
                            <div class="item__title">
                                <span>4</span>
                                {{ trans("text.TransferExperience") }} <!--Передача опыта-->
                            </div>
                            <div class="item__selects">
                                <div class="item__radio">
                                    <div class="item__radio_title">
                                        {{ trans("text.ReadyCouching") }}
                                    </div>
                                    <div class="item__radio_wrapper">
                                        <div>
                                            <input class="custom-radio" 
                                            v-model="form.couch" 
                                            type="radio"
                                             id="yesss"
                                                value="1">
                                            <label for="yesss">{{ trans("text.Yes") }}</label>
                                        </div>
                                        <div>
                                            <input class="custom-radio" v-model="form.couch" 
                                            type="radio"
                                             id="nooo"
                                              value="0">
                                            <label for="nooo">{{ trans("text.No") }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="item__input "   
                                v-if="form.couch === '1'"
                                >
                                    <label for="positions">{{ trans("text.ReadyCouchingHours") }}</label>
                                    <div class="item__input-little">
                                        <input type="number" min="1" v-model="form.couch_hours" 
                                        :placeholder="trans('text.ExamplePlaceholder') + ' 8'"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item quest5 mob">
                            <div class="item__title">
                                <span>5</span>
                                {{ trans("text.Education") }} <!--Образование-->
                            </div>
                             <div v-for="(n,i) in  educationsCount">
                            <div class="item__selects no-wrap">
                                <div class="item__select width270 margin-righttwo" 
                                :class="{ 'error-group': v$.form.education.$error }">
       <Select2 v-model="form.education[i]" :options="educations_list" 
       :settings="{  placeholder: trans('text.EducationLevel') }" 
       />
                                  <span class="invalid-feedback red-text" 
                                             role="alert" v-if="v$.form.education.$error">
                                                {{ trans("text.FillField") }} 
                                            </span>    
                                </div>
                                <div class="item__select width270"
                                :class="{ 'error-group': v$.form.type_education.$error }"
                                >
     <Select2 v-model="form.type_education[i]" :options="type_educations_list" 
     :settings="{  placeholder: trans('text.TypeEducation') }" 
     />
   <span class="invalid-feedback red-text"  role="alert" v-if="v$.form.type_education.$error">
                                                {{ trans("text.FillField") }} 
                                            </span>                      
                                </div>
                            </div>
                            <a class="item__add item__add_two" @click.stop="addEducation($event, i)">
                                {{ trans("text.AddEducation") }}
                            </a>
                            </div>                           
                        </div>

                        <div class="item margin-bottom92 mob quest6">
                            <div class="item__title">
                                <span>6</span>
                                {{ trans("text.ReadyExperiences") }} <!--Готовы ли вы выполнять обязанности-->
                            </div>

                            <div v-for="(experience_item,index) in form.experiences">  

                            <div class="item__radio">
                                <div class="item__radio_title">
                                    {{ experience_item.text }}
                                </div>
                                <div class="item__radio_wrapper">
                                    <div>
                                        <input class="custom-radio"  type="radio" :id="'yes-'+experience_item.id" value="1"
                                        v-model="form.experiences[index].yes_no"
                                        >
                                        <label :for="'yes-'+experience_item.id">{{ trans("text.Yes") }}</label>
                                    </div>
                                    <div>
                                        <input class="custom-radio" type="radio" :id="'no-'+experience_item.id" value="0"
                                       v-model="form.experiences[index].yes_no" 
                                        >
                                        <label :for="'no-'+experience_item.id">{{ trans("text.No") }}</label>
                                    </div> 
                                </div>
                            </div>
                            <div class="item__selects no-wrap">
                                <div class="item__select width726 margin-righttwo">
                                     <Select2  
                                     v-model="form.experiences[index].ready"
                                     :options="experience_ready_list" 
                                    :settings="{  placeholder: trans('text.SpecifyReasonsExperiences') }" />                                                             
                                </div>
                                <div class="item__input"
                                 v-if="form.experiences[index].ready === 'Выполнял такие обязанности ранее' || form.experiences[index].ready === 'Выполнял схожие обязанности ранее' || form.experiences[index].ready === 'Performed such duties earlier' || form.experiences[index].ready === 'Performed similar duties earlier'">
                                    <label for="positions">{{ trans("text.YearsExperiences") }}</label>
                                    <div class="item__input-little">
                                        <input
                                         type="number" 
                                         min="1" 
                                         v-model="form.experiences[index].year" 
                                         :placeholder="trans('text.ExamplePlaceholder') + ' 6'"
                                         >
                                    </div>
                                </div>
                            </div>
                            </div>
                            <span class="invalid-feedback red-text"  role="alert" v-if="v$.form.experiences.$error">
                            {{ trans("text.ErrorExperiences") }}
                            </span>         
                        </div>
                        <div class="item quest7 mob">
                            <div class="item__title">
                                <span>7</span>
                                {{ trans("text.LevelProficiencyProfessionalSkills") }} <!--Уровень владения профессиональными навыками-->
                            </div>
                            <div v-for="(skill_item,index) in work.skills">
                            <div class="item__selects item__selects-center no-wrap">
                                <div class="item__selects_title">
                                    {{skill_item.text}}
                                </div>
                                <div class="item__select margin-leftauto width558"
                                :class="{ 'error-group': v$.form.skills.$error }"
                                >
                                    <Select2 v-model="form.skills[index]" :options="skill_levels_list" required 
                                    :settings="{  placeholder: this.trans('text.AtWhatLevelHaveSkills') }" />
                                    <span class="invalid-feedback red-text" role="alert" v-if="v$.form.skills.$error">
                                        {{ trans("text.FillField") }} 
                                    </span>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="item quest8 mob">
                            <div class="item__title">
                                <span>8</span>
                                {{ trans("text.WorkCompanyTitle") }}  <!--Работа в компаниях (начиная с последней)-->
                            </div>
                             <div v-for="(n,i) in form.work_company">
                            <div class="item-flex">
                                <div class="item__inputs flex">
                                    <div class="item__inputs-two547">
                                        <div class="item__input width547 item__input-two">
                                            <input
                                            type="text"
                                            v-model="form.work_company[i].text" 
                                            :placeholder="trans('text.WorkCompanyName')"                                              
                                            >
                                        </div>
                                        <div class="item__input width547 item__input-two">
                                            <input type="text"  
                                            v-model="form.work_company[i].position"
                                            class="margin-bottomnon"
                                            :placeholder="trans('text.WorkCurrentPosition')"
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div class="item__selects item__selects-colum">
                                    <div class="item__selects-flex">
                                        <div class="item__input ">
                                            <div class="item__input-little">
                                                <input type="number" min="1"  max="100"
                                                v-model="form.work_company[i].year"                                                 
                                                :placeholder="trans('text.WorkYears')"
                                                >
                                                <input type="number" min="1" max="12"
                                                v-model="form.work_company[i].month"
                                                :placeholder="trans('text.WorkMonths')"                                             
                                                 @change="v$.form.work_company.$validate()"
                                                 >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item__btn-add-remove item__btn-add-remove-v4">
                                    <a class="item__add margin-left38" @click.stop="addWorkCompany($event, i)" >
                                        {{ trans("text.WorkAddPlace") }} 
                                    </a>
                                    <a class="item__remove" @click.stop="deleteWorkCompany(i)">
                                        {{ trans("text.Delete") }} 
                                    </a>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <span class="invalid-feedback red-text" role="alert" v-if="v$.form.work_company.$error">
                                    {{ trans('text.FillFieldCorrectly') }} {{ trans('text.WorkCompanyTitle') }}
                            </span>
                        </div>
                        <div class="item quest9 mob">
                            <div class="item__title">
                                <span>9</span>
                                {{ trans("text.WorkExperience") }}  <!--Стаж работы-->
                            </div>
                            <div class="item__inputs item__inputs-colum">
                                <div class="item__inputs_wrapper margin-bottom13">
                                    <div class="item__input_title">
                                        {{ trans("text.SetTotalWorkExperience") }}  <!--Укажите Ваш общий профессиональный стаж в годах-->
                                    </div>
                                    <div class="item__input italic width127">
                                        <input type="number" class="margin-topnon" min="1" 
                                        v-model="form.experience_work_year_all"
                                          :placeholder="trans('text.ExamplePlaceholder') + ' 6'"
                                          :class="{ 'error': v$.form.experience_work_year_all.$error }"  
                                            >
                                    </div>
                                </div>
                                <div class="item__inputs_wrapper margin-bottom13" v-if="work.experience_work_base_year_all &&  work.experience_work_base_year_all > 0">
                                    <div class="item__input_title">
                                        {{ trans("text.SetTotalManagerialExperience") }}  <!--Укажите Ваш общий управленческий стаж в годах-->
                                    </div>
                                    <div class="item__input italic width127">
                                        <input type="number" class="margin-topnon" min="1"
                                         v-model="form.experience_work_base_year_all"
                                            :placeholder="trans('text.ExamplePlaceholder') + ' 8'"
                                             :class="{ 'error': v$.form.experience_work_base_year_all.$error }"  
                                            >
                                    </div>
                                </div>
                                <div class="item__inputs_wrapper margin-bottom13" v-if="form.experience_work_base_year_all > 0">
                                    <div class="item__input_title">
                                        {{ trans("text.SetInDirectSubordination") }}  <!--Укажите максимальное кол-во сотрудников в прямом подчинении-->
                                    </div>
                                    <div class="item__input italic width127">
                                        <input 
                                         type="number"
                                         class="margin-topnon" 
                                         min="1"
                                         v-model="form.count_work_people"
                                         :placeholder="trans('text.ExamplePlaceholder') + ' 8'"
                                         :class="{ 'error': v$.form.count_work_people.$error } "      
                                        >
                                    </div>
                                </div>
                                <div class="item__inputs_wrapper margin-bottom13" v-if="form.experience_work_base_year_all > 0">
                                    <div class="item__input_title">
                                        {{ trans("text.SetInFunctionalSubordination") }}  <!--Укажите максимальное кол-во сотрудников в функциональном
                                        подчинении-->
                                    </div>
                                    <div class="item__input italic width127">
                                        <input
                                        type="number" 
                                        class="margin-topnon" 
                                        min="1"
                                        v-model="form.count_work_people_all"
                                        :placeholder="trans('text.ExamplePlaceholder') + ' 8'"
                                        :class="{ 'error': v$.form.count_work_people_all.$error }"    
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item quest10 mob" v-if="work.languages &&  work.languages.length > 0">
                            <div class="item__title">
                                <span>10</span>
                                {{ trans("text.ForeignLanguageProficiency") }} <!--Владение иностранным языком-->
                            </div>
                             <div v-for="(n,i) in form.languages">
                            <div class="item__selects no-wrap">
                                <div class="item__select width558 margin-righttwo">
                                   <Select2 v-model="form.languages[i].text" :options="languages_list" 
                                   :settings="{  placeholder: trans('text.ChooseLanguage') }" 
                                   @select="selectLanguages($event,i)"
                                   /> 
                                </div>
                                <div class="item__select width270 height44">
                                    <Select2 v-model="form.languages[i].level" :options="language_levels_list" 
                                    :settings="{  placeholder: trans('text.ProficiencyLevel') }" />                           
                                </div>
                                <div class="item__btn-add-remove item__btn-add-remove-v3">
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
                        <div class="items-flex mob quest-items"  v-if="work.licences &&  work.licences.length > 0">
                            <div class="item quest11 no-height">
                                <div class="item__title">
                                    <span>11</span>
                                    {{ trans("text.HavingDrivingLicense") }} <!--Наличие прав управления автомобилем-->
                                </div>

                                <div v-for="(n,i) in form.licences">
                                <div class="item__selects item-flex">
                                    <div class="item__select width270">
                                       <Select2 v-model="form.licences[i].text"
                                        :options="licences_list"
                                        :settings="{  placeholder: trans('text.CategoryLicences') }" />                                  
                                    </div>
                                    <a class="item__add item__add_two width213"  @click.stop="addLicences($event, i)">
                                        {{ trans("text.AddLicences") }} 
                                    </a>
                                </div>
                                </div>

                            </div>
                            <div class="item margin-leftauto quest11" v-if="work.type_car && work.type_car.length > 0">
                                <div class="item__title">
                                    <span>12</span>
                                    {{ trans("text.HavingCar") }} <!--Владение собственным автомобилем-->
                                </div>
                                <div v-for="(n,i) in form.type_car">
                                <div class="item__selects">                                 
                                    <div class="item__select width270">
                                        <Select2 v-model="form.type_car[i]" :options="type_car_list" 
                                        :settings="{  placeholder: trans('text.TypeCar') }" />                                     
                                    </div>              
                                    <a class="item__add item__add_two width213"  @click.stop="addTypeCar($event, i)">
                                        {{ trans("text.AddCar") }}
                                    </a>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="items-flex__wrapper mob">
                            <div class="items-flex quest-item ">
                                <div class="item quest12 no-height">
                                    <div class="item__title title-height">
                                        <span>13</span>
                                        {{ trans("text.TypeContractTitle") }} <!--Тип договора-->
                                        <div  v-if="work.type_contract">
                                        <div class="item__title_field" v-for="work_type_contract_item in work.type_contract">
                                            <!-- Поле для вывода -->
                                            <span>{{work_type_contract_item.text}}</span>
                                        </div>
                                        </div>   
                                    </div>  
                            <div class="item__radio">
                                <div class="item__radio_title">
                                   {{ trans("text.ReadyTypeContract") }}
                                </div>
                                <div class="item__radio_wrapper">
                                    <div>
                                        <input 
                                        class="custom-radio"
                                         v-model="form.type_contract_ok" 
                                         type="radio"
                                         id="type_contract_ok_yes" 
                                         :value="trans('text.Yes')">
                                        <label for="type_contract_ok_yes">{{ trans('text.Yes') }}</label>
                                    </div>
                                    <div>
                                        <input class="custom-radio" 
                                         v-model="form.type_contract_ok" 
                                         type="radio"
                                         id="type_contract_ok_no" 
                                         :value="trans('text.No')">
                                        <label for="type_contract_ok_no">{{ trans('text.No') }}</label>
                                    </div>
                                    <span class="invalid-feedback red-text" 
                                     role="alert" v-if="v$.form.type_contract_ok.$error">
                                        {{ trans("text.FillField") }} 
                                    </span>    
                                </div>
                            </div>
                                </div>
                                <div class="item margin-left19 quest12  no-height" 
                                v-if="work.employment && work.employment.length > 0"
                                >
                                    <div class="item__title title-height">
                                        <span>14</span>
                                        {{ trans("text.EmploymentTitle") }} <!--Занятость-->
                                         <div  v-if="work.employment">
                                        <div class="item__title_field" v-for="work_employment_item in work.employment">
                                            <!-- Поле для вывода -->
                                            <span >{{work_employment_item}}</span>
                                        </div>
                                        </div>                                       
                                    </div>                                   
                                <div class="item__radio">
                                <div class="item__radio_title">
                                   {{ trans('text.ReadyEmployment') }} <!--Устраивает ли Вас указанная занятость?-->
                                </div>
                                <div class="item__radio_wrapper">
                                    <div>
                                        <input 
                                        class="custom-radio"
                                         v-model="form.employment_ok" 
                                         type="radio"
                                         id="employment_ok_yes" 
                                         :value="trans('text.Yes')"
                                         >
                                        <label for="employment_ok_yes">{{ trans('text.Yes') }}</label>
                                    </div>
                                    <div>
                                        <input class="custom-radio" 
                                         v-model="form.employment_ok" 
                                         type="radio"
                                         id="employment_ok_no" 
                                         :value="trans('text.No')"
                                         >
                                        <label for="employment_ok_no">{{ trans('text.No') }}</label>
                                    </div>   
                                     <span class="invalid-feedback red-text" 
                                     role="alert" v-if="v$.form.employment_ok.$error">
                                        {{ trans("text.FillField") }} 
                                    </span>                                   
                                </div>
                                </div>
                                </div>
                            </div>
                            <div class="item quest3"
                            v-if="work.work_time && work.work_time.length > 0"
                            >
                                <div class="item__title title-height">
                                    <span>15</span>
                                    {{ trans("text.WorkTime") }} <!--Режим работы-->
                                    <div  v-if="work.work_time">
                                        <div class="item__title_field" v-for="work_time_item in work.work_time">
                                            <!-- Поле для вывода -->
                                            <span>{{work_time_item}}</span>
                                        </div>
                                        </div> 
                                </div>  
                                <div class="item__radio">
                                <div class="item__radio_title">
                                    {{ trans("text.ReadyWorkTime") }} 
                                </div>
                                <div class="item__radio_wrapper margin-left225">
                                    <div>
                                        <input 
                                        class="custom-radio"
                                         v-model="form.work_time_ok" 
                                         type="radio"
                                         id="work_time_ok_yes" 
                                         :value="trans('text.Yes')"
                                         >
                                        <label for="work_time_ok_yes">{{ trans('text.Yes') }}</label>
                                    </div>
                                    <div>
                                        <input class="custom-radio" 
                                         v-model="form.work_time_ok" 
                                         type="radio"
                                         id="work_time_ok_no" 
                                         :value="trans('text.No')"
                                         >
                                        <label for="work_time_ok_no">{{ trans('text.No') }}</label>
                                    </div>  
                                    <span class="invalid-feedback red-text" 
                                     role="alert" v-if="v$.form.work_time_ok.$error">
                                        {{ trans("text.FillField") }} 
                                    </span>                                     
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="item mob quest13">
                            <div class="item__title title-height">
                                <span>16</span>
                                {{ trans("text.Income") }} <p v-if="work.currency">{{ trans("text.In") }} {{ work.currency }}</p>
                                <div class="item__title_field">
                                    <!-- Поле для вывода --> 
                                    <span v-if="work.publish_income === 1">                                   
                                    {{ work.income_start }}                                    
                                    </span>
                                    <span v-else>"{{ trans("text.ByAgreement") }} "</span>
                                </div>
                            </div>
                            <div class="item__radio" v-if="work.publish_income === 1">
                                <div class="item__radio_title">
                                    {{ trans("text.ReadyIncome") }}
                                </div>
                                <div class="item__radio_wrapper margin-left225">
                                    <div>
                                        <input 
                                        class="custom-radio"
                                        v-model="form.income_ok" 
                                        type="radio"
                                        id="income_ok_yes" 
                                        value="1"
                                        >
                                        <label for="income_ok_yes">{{ trans('text.Yes') }}</label>
                                    </div>
                                    <div>
                                        <input
                                        class="custom-radio" 
                                        v-model="form.income_ok" 
                                        type="radio"
                                        id="income_ok_no" 
                                        value="0"
                                        >
                                        <label for="income_ok_no">{{ trans('text.No') }}</label>
                                    </div>
                                    <span class="invalid-feedback red-text" 
                                     role="alert" v-if="v$.form.income_ok.$error">
                                        {{ trans("text.FillField") }} 
                                    </span>    
                                </div>
                            </div>
                            <div class="item__selects" v-if="form.income_ok === '0' || work.publish_income === 0">
                                <div class="item__inputs_wrapper width709">
                                    <div class="item__input_title">
                                        {{ trans("text.WantIncome") }}
                                    </div>
                                    <div class="item__input italic width146 item-flex">
                                        <input type="number" min="1" class="margin-topnon" 
                                        v-model="form.income_want"
                                        :placeholder="trans('text.ExamplePlaceholder') + ' 80000'"
                                            >
                                        <p v-if="work.currency">{{ work.currency }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item mob quest14">
                            <div class="item__title">
                                <span>17</span>
                                {{ trans("text.PersonalData") }}  <!--Личностные данные-->
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
                                    :settings="{  placeholder: trans('text.PersonalData'), language: 'es' }" 
                                     @select="changePersonal($event, i)"
                                     v-model="form.personal[i].text_children"
                                    />                                
                                </div>
                                <div class="item__input italic width270 margin-leftnon margin-topnon" 
                                v-if="form.personal[i].show_other"
                                 >
                                <input class="margin-topnon" type="text"
                                :placeholder="trans('text.Other')"
                                v-model="form.personal[i].text"
                                >
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
                        <div class="questionnaire__attach mob mob-prevlast">

                            <label for="resume" classs="questionnaire__attach_label">
                            <div class="questionnaire__attach_item">
                               {{ trans("text.ResumeCaps") }} 
                                <span v-if="resume_filename">({{ resume_filename }}) </span>
                                <span v-else>({{ trans("text.FileAttach") }})<br> ({{ trans("text.MaxSize") }}: 4 {{ trans("text.Mb") }}, {{ trans("text.Format") }}: docx, pdf, rtf, txt, jpg, png)</span>                                
                                <div class="questionnaire__attach_item-icon">
                                    <img src="/img/attach_icon.svg" alt="img">
                                    <input id="resume" type="file" class="header__top_logo-input"
                                    @change="onChangeResume($event)">
                                </div>
                                <span class="invalid-feedback red-text" 
                                     role="alert" v-if="v$.resume.$error">
                                        {{ trans("text.FillField") }} 
                                </span> 
                            </div>
                            </label>

                            <label for="photo" classs="questionnaire__attach_label">
                            <div class="questionnaire__attach_item">
                                {{ trans("text.PhotoCaps") }}  
                                <span v-if="photo_filename">({{ photo_filename }}) </span>
                                <span v-else>({{ trans("text.FileAttach") }})<br>  ({{ trans("text.MaxSize") }}: 2 {{ trans("text.Mb") }}, {{ trans("text.Format") }}: jpg, jpeg, png, tiff, bmp)</span>
                                <div class="questionnaire__attach_item-icon">
                                    <img src="/img/attach_iconblue.svg" alt="img">
                                    <input id="photo" type="file" class="header__top_logo-input"
                                    @change="onChangePhoto($event)">
                                </div>
                                <span class="invalid-feedback red-text" 
                                     role="alert" v-if="v$.photo.$error">
                                        {{ trans("text.FillField") }} 
                                </span>                                
                            </div>
                            </label>
                        </div>
                        <div class="form__btns margin-top50 mob mob-last">
                            <a  href="/logout" class="form__btns_btn orange-border" 
                            v-if="form_send === true">
                                {{ trans("text.Exit") }}  
                            </a>

                            <a  href="" class="form__btns_btn orange-border" 
                            v-if="form_send === false">
                                {{ trans("text.ExitWithoutSave") }} 
                            </a>
                            <a class="form__btns_btn blue-bg width330 " 
                            @click.stop="submitSaveHandler"
                             v-if="form_send === false">
                                {{ trans("text.SubmitResponseVacancy") }} 
                            </a>
                        </div>
                    </form>
                </div>
            </div>
           <!-- <div class="mobil-btns">
                <button class="mobil-btns__button btn-one"></button>
                <button class="mobil-btns__button btn-two"></button>
            </div>-->
        </section>
    </div>



<transition name="modal">
<popup-policy :lang="lang" v-if="showModalPolicy" @closePopup="closePopupPolicy">
<template v-slot:header>
<h3>{{ trans("text.PersonalDataUsagePolicy") }}</h3>
</template>
</popup-policy>
</transition>


</template>
<script>
import Swal from 'sweetalert2';
import useVuelidate  from "@vuelidate/core";
import { required, email, minLength, maxLength, sameAs } from "@vuelidate/validators";
import Select2 from 'vue3-select2-component';    

import SelectLoad from './mixins/SelectLoad.vue';
import AddInput from './mixins/AddInput.vue';
import ValidationsQuestion from './mixins/ValidationsQuestion.vue';
import PersonalMixin from './mixins/PersonalMixin.vue';
import SpecializationMixin from './mixins/SpecializationMixin.vue';
import LanguageMixin from './mixins/LanguageMixin.vue';
import CityMixin from './mixins/CityMixin.vue';
import PopupPolicy from './PopupPolicy';

export default { 
      setup () {
        return { v$: useVuelidate() }
      },
    props: ['slug','work_id','closed','closed_date','refer','lang'],
    mixins: [SelectLoad, AddInput,ValidationsQuestion, PersonalMixin, SpecializationMixin, LanguageMixin, CityMixin],
    data() {
       return {  
        showModalPolicy: false,

        count: 1,
       
        form_send: false,

        login: false, 
        check: null,

        error_input_msg: "",
        error_input_msg_email: "",
        error_input_msg_phone: "",

        country: '', 
       
        region: null,    
       
        countries: [],  
        question_countries: [],      
        regions: [],        
        cities: [],

        list_group_specializations: [],
    
        experience_ready_list: [],
            
        city_match_list: [],

        educations_list: [],
        type_educations_list: [],

        educationsCount: [0],
        worksCount: [0],
        languages_list: [],
        language_levels_list: [],

        licences_list: [], 
        type_car_list: [],  

        personal_list_category: [],

        ok_list: [],
        skill_levels_list: [],

        show_city_match: false,

        form: {

        status: 0,
        phone: '',
        email: '',
        fio_f: '',
        fio_i: '',
        fio_o: '',

        permit_countries: [''],

        birthday: null,
        gender: null,

        city_id: null,
        city_match: "",

        specializations: [
        {
        id: 0,
        list_group_specializations: [],
        list_specializations: [],
        text: "",
        group: "",
        },
        ],

        set_is_couch: 0,
        couch_hours: 0,

        education: [],
        type_education: [],

        experiences: [
        {
        id: 0,
        text: "",
        yes_no: "",
        ready: "",
        year: "", 
        },
        ],

        skills: [],

        work_company: [
        {
        id: 0,
        text: "",
        position: "",
        year: null, 
        month: null, 
        }
        ],

        experience_work_year_all: 0,
        experience_work_base_year_all: 0,
        count_work_people: 0,
        count_work_people_all: 0,

        languages: [
        {
        id: 0,
        text: "",
        level: null,
        degree: "",  
        }  
        ],

        licences: [
        {
        id: 0,
        text: "",
        degree: "",  
        }  
        ],

        type_car: [""],
        type_contract_ok: "",
        employment_ok: "",
        work_time_ok: "",

        income: null,
        income_ok: null,
        income_want: null,

        personal: [
        {
            list_category: [],
            list_children: [],
            text_category: "",
            text_children: "",
            show_other: false,
            text: "",
        },
        ],
            
        },

        resume: null,
        resume_filename: null,
        photo_filename: null,
        photo: null,  

        work: {

        languages: [],
        licences: [],
        type_car: [],

        },

        user: {},

        }
    },

    computed: {    
        city_match_text: function() {            
            return this.trans('text.PlaceWorkNotMatch') + " " +  this.work.city_id  + " " +  this.trans('text.PlaceWorkNotMatchWhat');
        },
    },

    methods: {
        popupPolicy() {
            this.showModalPolicy = true;
        },
        closePopupPolicy() {
            this.showModalPolicy = false;
        },
        submitSaveHandler() {
            this.form.status = 1;

            this.v$.$validate(); 

            let personal = this.form.personal;
            if (personal.length > 1) {
                for (let i = 1; i < personal.length; i++) {
                  if (personal[i].text === "") {
                     Swal.fire(this.trans('text.ErrorFieldPersonal'));
                     return false;
                  }  
                }
            }

            let exp = this.form.experiences;            
            for (let j = 0; j < exp.length; j++) {               
                if (exp[j].ready === "Выполнял такие обязанности ранее" || exp[j].ready === "Выполнял схожие обязанности ранее") {  
                  if (exp[j].year === 0 || exp[j].year === "" || exp[j].year === null) {
                     Swal.fire(this.trans('text.ErrorFieldDuty'));
                     return false;
                  }
                } 
            }
            
          
            if (!this.v$.$error) { // if ANY fail validation
                    
            } else {
                   Swal.fire(this.trans('text.QuestionnaireIsNotCompleted'));
                   return false;
            }           

            const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
            }
            
            let data = new FormData();
       
data.append('work_id', this.work_id);

data.append('ref', this.refer);

data.append('status', this.form.status);
data.append('phone', this.form.phone);
data.append('email', this.form.email);
data.append('fio_f', this.form.fio_f);
data.append('fio_i', this.form.fio_i);
data.append('fio_o', this.form.fio_o);


let permit_countries = this.form.permit_countries;
if (permit_countries[0] !== "") {
data.append('countries', JSON.stringify(permit_countries));
}

let birthday = Date.parse(this.form.birthday);
data.append('birthday', this.form.birthday);

data.append('gender', this.form.gender);
data.append('city_id', this.form.city_id);
data.append('city_match', this.form.city_match);

let specializations = this.form.specializations;
if (specializations[0].text !== null) {
specializations = JSON.stringify(specializations);
data.append('specializations', specializations);
}

data.append('set_is_couch', this.form.set_is_couch);
data.append('couch_hours', this.form.couch_hours);

let education = JSON.stringify(this.form.education);
data.append('education', education);

let type_education = JSON.stringify(this.form.type_education);
data.append('type_education', type_education);

let experiences = JSON.stringify(this.form.experiences);
data.append('experiences', experiences);

let skills =  JSON.stringify(this.form.skills);
data.append('skills', skills);

let work_company = JSON.stringify(this.form.work_company);
if (this.form.work_company[0].text !== "") {
data.append('work_company', work_company);
}

data.append('experience_work_year_all', this.form.experience_work_year_all);
data.append('experience_work_base_year_all', this.form.experience_work_base_year_all);
data.append('count_work_people', this.form.count_work_people);
data.append('count_work_people_all', this.form.count_work_people_all);

if (this.form.languages[0].text !== "") {
let languages = JSON.stringify(this.form.languages);
data.append('languages', languages);
}


if (this.form.licences[0].text !== "") {
let licences = JSON.stringify(this.form.licences);
data.append('licences', licences); 
}


if (this.form.type_car[0] !== "") {
let type_car = JSON.stringify(this.form.type_car);
data.append('type_car', type_car); 
}

data.append('type_contract_ok', this.form.type_contract_ok);
data.append('employment_ok', this.form.employment_ok);

data.append('work_time_ok', this.form.work_time_ok);

data.append('income', this.form.income);
data.append('income_ok', this.form.income_ok);
data.append('income_want', this.form.income_want);


let new_personal = [];
for (let i = 0; i < personal.length; i++) {
  if (personal[i].text !== "") {
    new_personal.push(personal[i].text);
  }  
}
data.append('personal', JSON.stringify(new_personal));

            data.append('resume', this.resume);
            data.append('photo', this.photo);

            axios.post('/api/question/store', data, config)
            .then((response) => {                
                if (response.data !== undefined) {                    
                    if (response.data.status === 0) {                       
                        Swal.fire(this.trans('text.DoNotAllowEmployer'));
                        this.form_send = true;
                    }
                    if (response.data.status === 1) {                     
                        Swal.fire(this.trans('text.YouPassedQuestionnaire'));
                        this.form_send = true;            
                    }
                    if (response.data.status === 2) {
                        Swal.fire(this.trans('text.ApplicationAlreadySubmitted'));
                    }
                    if (response.data.status === 3) {
                        Swal.fire(this.trans('text.ErrorFileUpload'));
                    }                    
                }     
            })
            .catch(function (err) {
                Swal.fire(this.trans('text.Error'));               
            });

        },

        loadWork() {
           let id =  this.work_id;
           if (id > 0) {
            axios({
              method: 'get',
              url: '/api/works/'+id,
            })
            .then((response) => {
                if (response.data !== undefined) {
                    let experiences_array = response.data.data.experiences;
                    this.form.experiences = [];
                    for(let i = 0; i < experiences_array.length; i++) {
                        this.form.experiences.push({
                        id: i,
                        text: experiences_array[i].text,
                        yes_no: null,
                        ready: null,
                        year: null, 
                        });
                    }                  
                    this.work = response.data.data;                                  
                }       
            });            
           }
        },     

        onChangeResume(e) {
            this.resume = e.target.files[0];
            this.resume_filename = e.target.files[0].name;
        },

        onChangePhoto(e) {
            this.photo = e.target.files[0];
            this.photo_filename = e.target.files[0].name;
        },

        loginEventHandler(form) {
            this.login = true;
            this.form.email = form.email;
            this.form.phone = form.phone;
        },

        loadExperienceReadyList() {            
            this.experience_ready_list.push(this.trans('text.HaveAppropriateEducation'));
            this.experience_ready_list.push(this.trans('text.PerformedSuchDutiesEarlier'));
            this.experience_ready_list.push(this.trans('text.PerformedSimilarDutiesEarlier'));
            this.experience_ready_list.push(this.trans('text.ReadyPerformSheseDuties'));
        },

        loadCityMatchList() {
            this.city_match_list.push(this.trans('text.ReadyForRemoteWork'));
            this.city_match_list.push(this.trans('text.ReadyToMove'));
            this.city_match_list.push(this.trans('text.ReadyWorkOnShiftBasis'));
            this.city_match_list.push(this.trans('text.LocationDoesNotSuit'));
        },



    },

    mounted() {
        this.loadWork();     
        this.loadSelects();
        this.loadExperienceReadyList();
        this.loadCityMatchList();

    }
}
</script>
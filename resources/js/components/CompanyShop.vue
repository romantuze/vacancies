<template>    
    <div>
        <section class="description store-research">
            <div class="container">
                <div class="description__top">
                    <h1 class="description__title">
                        {{ trans("text.CandidateShop") }} 
                    </h1>
                    
                </div>
                <div class="description__info-com">
                    <div class="description__info-com_logo">
                        <div v-if="company.logo">
                        <div class="logo-image" :style="{ backgroundImage: 'url('+company.logo+')' }"></div>     
                        
                        </div>
                        <div v-else>
                        <img src="/img/logo.png" alt="">
                        </div>
                    </div>
                    <div class="description__info-com_name" v-if="company.name">
                      {{ company.name }} 
                    </div>
                    <div class="description__info-com_name">
                      {{ trans("text.PersonalAccount") }} {{ company.balance }} {{ work.currency }}
                    </div>
                </div>
                <div class="store-research-top">
                    <div class="store-research-top__column">
                        <span class="span-text">{{ trans("text.Vacancy") }} </span>
                        <span class="span-text">{{ trans("text.Location") }}</span>
                        <span class="span-text">{{ trans("text.Candidates") }}</span>
                    </div>
                    <div class="store-research-top__column">
                        <span class="span-text">{{ work.name }}</span>
                        <div class="region">
                            <span class="span-text">{{ work.city_id }}</span>
                        </div>
                        <div class="btn-wrap">
                            <button class="button btn-white btn-new" @click.stop="showNew" v-bind:class="{ active: isNewActive }">{{ trans("text.NewCandidates") }} </button>
                            <button class="button bt-orange btn-viewed" @click.stop="showOpen" v-bind:class="{ active: isOpenActive }">{{ trans("text.ViewedCandidates") }} </button>
                        </div>
                    </div>
                    <div class="store-research-top__column">

                        <span class="span-text">{{ trans("text.NumberVacantPlaces") }} <span>{{ work.positions }}</span></span>
                        <span class="span-text">{{ trans("text.CountCandidates") }} <span>{{ work.count_candidates }}</span></span> 
                        <span class="span-text">{{ trans("text.WaitedCandidates") }}  <span>{{  work.count_candidates_waited }}</span></span>
                        
                    </div>
                    <div class="store-research-top__column">
                        <a href="/home" class="button btn-white btn-mein">{{ trans("text.Home") }} </a>
                        <a class="button btn-archive"  @click="changeStatus(3)" v-if="work.status !== undefined && work.status !== 3">{{ trans("text.AtArchive") }} </a>
                        <a href="/home" class="button bt-blue">{{ trans("text.SearchVacancies") }} </a>
                    </div>
                </div>
                <div class="store-research-sum">
                    <div class="store-research-sum__left">
                        <div>
                            <span>{{ trans("text.SumOpen") }}</span>
                            <span v-if="work.sum_open !==undefined && work.sum_open > 0">
                            {{ work.sum_open }} {{ work.currency }}
                            </span>
                            <span v-else>                                
                                -
                            </span>
                        </div>

                        <div>
                            <span>{{ trans("text.SumReplace") }}</span>
                            <span v-if="work.sum_replace !==undefined && work.sum_replace > 0">
                            {{ work.sum_replace }} {{ work.currency }}
                            </span>
                            <span v-else>                                
                                -
                            </span>
                        </div> 
                        
                    </div>
                    <!--<div class="store-research-sum__right">
                        <p>Осталось до конца проекта</p>
                        <div>
                            <span>12</span>
                            <span>дней</span>
                        </div>
                        <div>
                            <span>6</span>
                            <span>часов</span>
                        </div>
                        <div>
                            <span>32</span>
                            <span>мин</span>
                        </div>
                    </div>-->
                </div>

                <div class="contact-open"><strong>{{ trans("text.ContactDataCandidate") }}</strong> <span>{{ trans("text.OpenContactDataCandidate") }}</span></div>
                <div class="store-research__btn-wrap">
                    <div>
                        <a  class="button btn-white" @click="openCandidate" 
                        v-if="current_candidate.fio_f && current_candidate.opened === 0">{{ trans("text.OpenCandidate") }}</a>
                        <a class="button bt-orange" @click="replaceCandidate" 
                        v-if="current_candidate.fio_f && current_candidate.opened === 0">{{ trans("text.ReplaceCandidate") }}</a>
                    </div>
                    <a href="/document" class="button bt-blue">{{ trans("text.DepositPersonalAccount") }}</a>
                </div>

                <div v-if="current_candidate.fio_f !== undefined">
                <div class="store-research-candidate">
                    <a class="store-research-candidate__link link-prev" @click.stop="prev"></a>
                    <a class="store-research-candidate__link link-next" @click.stop="next"></a>
                    <div class="candidate-item">
                        <div class="candidate-item__left">
                            <div class="candidate-item__img">
                                <div v-if="current_candidate.photo">
                                     <img :src="current_candidate.photo" alt="" width="130"> <!--130-->
                                </div>
                                <div v-else>
                                    <img loading="lazy" src="/img/candidate-item__img2.png" alt="">
                                </div>  
                            </div>
                            <div class="candidate-item__info">
                                <div v-if="current_candidate.fio_f">
                                    <span>{{ trans("text.Surname") }} </span><span>{{current_candidate.fio_f}}</span>
                                </div>
                                <div v-if="current_candidate.fio_i">
                                    <span>{{ trans("text.NameSmall") }} </span><span>{{current_candidate.fio_i}}</span>
                                </div>
                                <div v-if="current_candidate.fio_o">
                                    <span>{{ trans("text.Middlename") }} </span><span>{{current_candidate.fio_o}}</span>
                                </div>
                                <div v-if="current_candidate.phone">
                                    <span>{{ trans("text.Phone") }}</span>
                                    <a :href="'tel:'+current_candidate.phone" v-if="current_candidate.phone !== 'Скрыто' && current_candidate.phone !== 'Hidden'">{{current_candidate.phone}}</a>
                                    <a v-else>{{current_candidate.phone}}</a>
                                </div>
                                <div v-if="current_candidate.email">
                                    <span>{{ trans("text.EmailAddressSmall") }}</span>
                                    <a :href="'mailto:'+current_candidate.email" v-if="current_candidate.email !== 'Скрыто' && current_candidate.phone !== 'Hidden'">{{current_candidate.email}}</a>
                                    <a v-else>{{current_candidate.email}}</a>
                                </div>
                                <div  v-if="current_candidate.age >= 0">
                                    <span>{{ trans("text.AgeSmall") }}  </span><span>{{ current_candidate.age }} </span>
                                </div>
                                <div v-if="current_candidate.birthday">
                                    <span>{{ trans("text.Birthday") }} </span><span>{{current_candidate.birthday}}</span>
                                </div>
                                <div>
                                    <span>{{ trans("text.Gender") }}</span> 
                                    <span v-if="current_candidate.gender === 1">{{ trans("text.Male") }} </span>
                                    <span v-if="current_candidate.gender === 2">{{ trans("text.Female") }} </span>
                                   <span v-if="current_candidate.gender === trans('text.Hide')">{{ trans("text.Hide") }} </span>
                                </div>
                                
                                <div>
                                    <span>{{ trans("text.Resume") }}</span> 
                                    <span v-if="current_candidate.resume"><a :href="current_candidate.resume" target="_blank">{{ trans("text.Download") }}</a></span>
                                    <span v-else>{{ trans("text.Hide") }}</span>
                                </div>
                                <div v-if="current_candidate.country">
                                    <span>{{ trans("text.WorkPermit") }} </span><span>{{current_candidate.country}}</span>
                                </div>
                            </div>
                        </div>            


                        <div class="candidate-item__right">
                            <div>
                                <span>{{ trans("text.FotVacancy") }} <!--ФОТ по вакансии--></span><span><b v-if="work.income > 0">{{work.income_start}}-{{work.income_end}} {{ work.currency }}</b></span>
                            </div>
                            <div>
                                <span>{{ trans("text.IncomeWant") }}</span>
                                <span v-if="current_candidate.income_want > 0"><b>{{current_candidate.income_want}} {{ work.currency }}</b></span>
                                <span v-else><b>-</b></span>
                            </div>
                            <div>
                                <span>{{ trans("text.Region") }} </span><span v-if="current_candidate.region">{{current_candidate.region}}</span>
                            </div>
                            <div>
                                <span>{{ trans("text.City") }} </span><span v-if="current_candidate.city_id">
                                    {{current_candidate.city_id}}</span>
                            </div>
                            <div>
                                <span>{{ trans("text.Specialization") }}</span>
                                 <span v-if="current_candidate.special">
                                     <template v-for="item in current_candidate.special">{{item}}<br></template>
                                </span>
                            </div>
                            <div>
                                <span>{{ trans("text.SpecializationVacancy") }} </span>
                                <span v-if="work.special">
                                    <template v-for="item in work.special">{{item}}<br></template>
                                </span>
                            </div>
                            <div>
                                <span>{{ trans("text.IsViewSpecial") }}</span>
                                <span v-if="work.is_view_special === 1">{{ trans("text.Yes") }}</span>
                                <span v-if="work.is_view_special === 0">{{ trans("text.No") }}</span>
                            </div>
                            <div>
                                <span>{{ trans("text.CommentLocation") }}</span>
                                <span v-if="current_candidate.city_match">
                                    {{current_candidate.city_match}}
                                </span>
                                <span v-else>
                                   -
                                </span>
                            </div>
                            <div>
                                <span>{{ trans("text.ResultQuestions") }}</span>
                                <span v-if="current_candidate.status === 0">{{ trans("text.ResultFailed") }}</span>
                                <span v-else>{{ trans("text.ResultPassed") }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="store-research-form">

                    <div class="form-item">  

                     <div class="form-item__wrapper">

                    <div class="form-item__left grid-block grid-v1">      
                    <h2 class="form-item__top-title">{{ trans("text.CandidateData") }}</h2>
                    </div>

                    <div class="form-item__right grid-block grid-v1">   
                     <h2 class="form-item__top-title">{{ trans("text.RequiredByVacancy") }}</h2>
                    </div> 
                 </div>
                </div>


                    <div class="form-item">                      

                        <div class="form-item__top">
                            <div class="form-item__num">
                                1 
                            </div>
                            <div class="form-item__name">
                                <span>{{ trans("text.Education") }} </span>
                            </div>
                        </div>
                        <div class="form-item__wrapper">
                            <div class="form-item__left grid-block grid-v1">
                                 <input type="text" :value="trans('text.EducationLevel')">
                                 <input type="text" :value="trans('text.TypeEducation')">
                                 <div>
                                <template v-for="item in current_candidate.education">
                                     <input type="text" :value="item">
                                </template>
                                </div>
                                 <div>
                                <template v-for="item in current_candidate.type_education">
                                     <input type="text" :value="item">
                                </template>      
                                </div> 
                            </div>
                            <div class="form-item__right grid-block grid-v1"> 
                                <!--<p>Требуемое по вакансии</p>-->
                                <input type="text" :value="trans('text.EducationLevel')">
                                <input type="text" :value="trans('text.TypeEducation')">
                                <input type="text" :value="work.education">  
                                <input type="text" :value="work.type_education">
                            </div>
                        </div>
                    </div>
                    <div class="form-item">
                        <div class="form-item__top">
                            <div class="form-item__num">
                                2 
                            </div>
                            <div class="form-item__name">
                                <span>{{ trans("text.WorkExperienceYear") }}</span>
                            </div>
                        </div>
                        <div class="form-item__wrapper">
                            <div class="form-item__left grid-block grid-v2">
                                <span>{{ trans("text.TotalWorkExperience") }} </span>
                                <input type="text" placeholder="" :value="current_candidate.experience_work_year_all">
                                <template v-if="current_candidate.experience_work_base_year_all > 0">
                                <span>{{ trans("text.ManagerialWorkExperience") }}</span>
                                <input type="text" placeholder="" :value="current_candidate.experience_work_base_year_all">
                                </template>
                                <template v-if="current_candidate.count_work_people > 0">
                                <input type="text" :value="trans('text.InDirectSubordinationCount')">
                                <input type="text" placeholder="" :value="current_candidate.count_work_people">
                                </template>
                                <template v-if="current_candidate.count_work_people_all > 0">
                                <input type="text" :value="trans('text.InFunctionalSubordinationCount')">
                                <input type="text" placeholder="" :value="current_candidate.count_work_people_all">
                                </template>
                            </div>
                            <div class="form-item__right grid-block grid-v2">
                                <!--<p>Требуемое по вакансии</p>-->
                                <span>{{ trans("text.TotalWorkExperience") }} </span>
                                <input type="text" placeholder="" :value="work.experience_work_year_all">
                                <template v-if="work.experience_work_base_year_all > 0">
                                <span>{{ trans("text.ManagerialWorkExperience") }}</span>
                                <input type="text" placeholder="" :value="work.experience_work_base_year_all">
                                </template>
                                <template v-if="work.count_work_people > 0">
                                <input type="text" :value="trans('text.InDirectSubordination')">
                                <input type="text" placeholder="" :value="work.count_work_people">
                                </template>
                                <template v-if="work.count_work_people_all > 0">
                                <input type="text" :value="trans('text.InFunctionalSubordination')">
                                <input type="text" placeholder="" :value="work.count_work_people_all">
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="form-item">
                        <div class="form-item__top">
                            <div class="form-item__num">
                                3
                            </div>
                            <div class="form-item__name">
                                <span>{{ trans("text.ResponsibilitiesExperience") }}</span>
                            </div>
                        </div>
                        <div class="form-item__wrapper">
                            <div class="form-item__left grid-block grid-v1">
                                <input type="text" :value="trans('text.DutyWorkExperience')">
                                <input type="text" :value="trans('text.ExperienceInYears')">
                                <template v-for="(item, index) in current_candidate.experiences">    
                                    <input type="text" :value="item.text">
                                    <template v-if="item.year"> 
                                       <input type="text" :value="item.year">
                                   </template>
                                   <template v-else> 
                                       <input type="text" value="0">
                                   </template>
                                </template>
                            </div>
                            <div class="form-item__right grid-block grid-v1">
                                <input type="text" :value="trans('text.ExperienceInYears')">
                                <input type="text" :value="trans('text.MandatoryRequirements')">
                                <template v-for="(item, index) in work.experiences">    
                                <input type="text" :value="item.year">
                                <input type="text" :value="degree_list[item.degree-1]">
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="form-item">
                        <div class="form-item__top">
                            <div class="form-item__num">
                                4
                            </div>
                            <div class="form-item__name">
                                <span>{{ trans("text.LevelProficiencyProfessionalSkills") }} <!--Уровень владения профессиональными навыками--></span>
                            </div>
                        </div>
                        <div class="form-item__wrapper">
                            <div class="form-item__left grid-block grid-v1">
                                <input type="text" :value="trans('text.Skill')">
                                <input type="text" :value="trans('text.LevelCandidate')">
                                <template v-for="(item, index) in current_candidate.skills">
                                   <template v-if="work.skills[index].text">
                                    <input type="text" :value="work.skills[index].text">
                                    </template>      
                                    <input type="text" :value="item.level">
                                </template> 
                            </div>
                            <div class="form-item__right grid-block grid-v1">
                                <input type="text" :value="trans('text.ProficiencyLevel')">
                                <input type="text" :value="trans('text.MandatoryRequirements')">                               
                                <template v-for="item in work.skills">
                                     <input type="text" :value="item.level">
                                     <input type="text" :value="degree_list[item.degree-1]">
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="form-item" v-if="work.languages && work.languages.length > 0" >
                        <div class="form-item__top">
                            <div class="form-item__num">
                                5
                            </div>
                            <div class="form-item__name">
                                <span>{{ trans("text.LevelForeignLanguageProficiency") }}</span>
                            </div>
                        </div>
                        <div class="form-item__wrapper">
                            <div class="form-item__left grid-block grid-v1">
                                <input type="text" :value="trans('text.LanguageSmall')">
                                <input type="text" :value="trans('text.ProficiencyLevel')">
                                <template v-for="item in current_candidate.languages">
                                     <input type="text" :value="item.text"> 
                                     <input type="text" :value="item.level">
                                </template> 
                            </div>
                            <div class="form-item__right grid-block grid-v1">
                                <!--<p>Требуемое по вакансии</p>-->
                                <input type="text" :value="trans('text.ProficiencyLevel')">
                                <input type="text" :value="trans('text.MandatoryRequirements')">
                                 <template v-for="item in work.languages">
                                     <input type="text" :value="item.level"> 
                                     <input type="text" :value="degree_list[item.degree-1]">
                                </template>  
                            </div>
                        </div>
                    </div>
                    <div class="form-item" v-if="work.licences && work.licences.length > 0">
                        <div class="form-item__top">
                            <div class="form-item__num">
                                6
                            </div>
                            <div class="form-item__name">
                                <span>{{ trans("text.HavingDrivingLicense") }}</span>
                            </div>
                        </div>
                        <div class="form-item__wrapper">
                            <div class="form-item__left grid-block grid-v4">
                                <input type="text" :value="trans('text.CategoryLicences')">
                                <template v-for="item in current_candidate.licences">
                                     <input type="text" :value="item.text"> 
                                </template>            
                            </div>
                            <div class="form-item__right grid-block grid-v1">
                                <!--<p>Требуемое по вакансии</p>-->
                                <input type="text" :value="trans('text.CategoryLicences')">
                                <input type="text" :value="trans('text.MandatoryRequirements')">
                                <template v-for="item in work.licences">
                                     <input type="text" :value="item.text">
                                     <input type="text" :value="degree_list[item.degree-1]">
                                </template> 
                            </div>
                        </div>
                    </div>
                    <div class="form-item" v-if="work.type_car && work.type_car.length > 0">
                        <div class="form-item__top">
                            <div class="form-item__num">
                                7
                            </div>
                            <div class="form-item__name">
                                <span>{{ trans("text.HavingCar") }} </span>
                            </div>
                        </div>
                        <div class="form-item__wrapper">
                            <div class="form-item__left grid-block grid-v4">
                                <input type="text" :value="trans('text.TypeCar')">
                                <template v-for="item in current_candidate.type_car">
                                     <input type="text" :value="item"> 
                                </template>                               
                            </div>
                            <div class="form-item__right grid-block grid-v1">
                                <!--<p>Требуемое по вакансии</p>-->
                                <input type="text" :value="trans('text.TypeCar')">
                                <input type="text" :value="trans('text.MandatoryRequirements')">
                               <template v-if="work.type_car[0]">
                                 <input type="text" :value="work.type_car[0].text">
                                </template> 
                                <template v-if="work.type_car[0]">     
                                 <input type="text" :value="degree_list[work.type_car[0].degree-1]">
                                </template>                                
                            </div>
                        </div>
                    </div>
                    <div class="form-item__row">
                        <div class="form-item">
                            <div class="form-item__top">
                                <div class="form-item__num">
                                    8
                                </div>
                                <div class="form-item__name">
                                    <span>{{ trans("text.TypeContractTitle") }}</span>
                                </div>
                            </div>

                            <div class="form-item__wrapper">                                
                                <input type="text" :value="trans('text.CandidateAnswer')">
                                <input class="blue" type="text" :value="trans('text.TypeContractTitle')">
                            </div>

                            <div class="form-item__wrapper">
                                <template v-for="item in work.type_contract">    
                                    <input type="text" :value="current_candidate.type_contract" v-if="current_candidate.type_contract">   

                                    <input type="text" :value="item.text" v-if="item.text">                                    
                                </template>
                            </div>   

                             <div class="form-item__wrapper">
                                <input type="text" :value="trans('text.Comment')" class="blue">
                                <input type="text" class="input-comment" v-model="current_candidate.content.type_contract_comment">
                             </div>
                        </div>
                        <div class="form-item" v-if="work.employment && work.employment.length > 0">
                            <div class="form-item__top">
                                <div class="form-item__num">
                                    9
                                </div>
                                <div class="form-item__name">
                                    <span>{{ trans("text.EmploymentTitle") }}</span>
                                </div>
                            </div>

                            <div class="form-item__wrapper">
                                <input type="text" :value="trans('text.CandidateAnswer')">  
                                <input class="blue" type="text" :value="trans('text.EmploymentTitle')"> 
                            </div>
                             <div class="form-item__wrapper">
                                <template v-for="item in work.employment">
                                    <input type="text" :value="current_candidate.employment" v-if="current_candidate.employment">
                                    <input type="text" :value="item">  
                                </template>
                             </div>

                             <div class="form-item__wrapper">
                                <input type="text" :value="trans('text.Comment')" class="blue">
                                <input type="text" class="input-comment" v-model="current_candidate.content.employment_comment">
                             </div>
                        </div>
                        <div class="form-item" v-if="work.work_time && work.work_time.length > 0">
                            <div class="form-item__top">
                                <div class="form-item__num">
                                    10
                                </div>
                                <div class="form-item__name">
                                    <span>{{ trans("text.WorkTime") }}</span>
                                </div>
                            </div>
                            <div class="form-item__wrapper">                              
                                <input type="text" :value="trans('text.CandidateAnswer')">
                                <input class="blue" type="text" :value="trans('text.WorkTime')"> 
                            </div>
                            <div class="form-item__wrapper">
                                <template v-for="item in work.work_time">
                                    <input type="text" :value="current_candidate.work_time" v-if="current_candidate.work_time">
                                    <input type="text" :value="item"> 
                                </template>
                            </div>
                            <div class="form-item__wrapper">
                                <input type="text" :value="trans('text.Comment')" class="blue">
                                <input type="text" class="input-comment" v-model="current_candidate.content.work_time_comment" >
                            </div>                            
                        </div>
                    </div>
                    <div class="form-item">
                        <div class="form-item__top">
                            <div class="form-item__num">
                                11
                            </div>
                            <div class="form-item__name">
                                <span>{{ trans("text.PersonalData") }}</span>
                            </div>
                        </div>
                        <div class="form-item__wrapper">
                            <div class="form-item__left grid-block grid-v4">  
                                <template v-for="item in current_candidate.personal">
                                     <input type="text" :value="item"> 
                                </template>
                            </div>
                            <div class="form-item__right grid-block grid-v4"
                            v-if="work.personal && work.personal.length > 0"
                            >
                                <!--<p>Требуемое по вакансии</p>-->
                                  <template v-for="item in work.personal">
                                     <input type="text" :value="item"> 
                                </template>                         
                            </div>
                        </div>
                    </div>
                </div>

                <div class="expert-comment">
                    <p>{{ trans("text.CommentExpert") }}</p>
                    <textarea class="textarea-comment" v-model="current_candidate.content.expert_comment">                      
                    </textarea>
                </div>
                <div class="store-research__btn-wrap">     
                    <a class="button bt-blue" target="_blank" @click.stop="commentsSaveHandler">{{ trans("text.SaveComment") }}</a>
                </div>


            </div>
            </div>
        </section>
    </div>
</template>
<script>
import Swal from 'sweetalert2';    
import Select2 from 'vue3-select2-component';  
import SelectLoad from './mixins/SelectLoad.vue';
export default {
  props: ['work_id','user_id','lang'],
  mixins: [SelectLoad],
  data() {
       return {            
            isNewActive: false,
            isOpenActive: false,
            count: 1,
            work: {
                name: "",
                balance: 0,  
                count_candidates_waited: 0,
                count_candidates: 0,
                positions: 0,                
            },
            company: {
                name: "",
                logo: null,
            },    
            candidates: [],
            current_candidate_index: 0,

            sort: "",
            sort_list: [
            { id: 1, text: this.trans('text.SortByTitle')},
            { id: 2, text: this.trans('text.SortByDateCreate')},
            ],
            degree_list: [],
        }
    },
    computed: {
        current_candidate: function() {
           if (this.candidates[this.current_candidate_index] !== undefined) {
             return this.candidates[this.current_candidate_index];
           }
           else {
               return { };  
           }
        },
    },   
    methods: {
        commentsSaveHandler() {
            let index = this.current_candidate_index;  
            if (index >=0 && this.candidates[index] !== undefined && this.work.user_id !== undefined) {
                let id = this.candidates[index].id;          
                let expert_comment = "";
                if (this.candidates[index].content.expert_comment !== undefined) {
                    expert_comment = this.candidates[index].content.expert_comment;
                }

                let type_contract_comment = "";
                if (this.candidates[index].content.expert_comment !== undefined) {
                    type_contract_comment = this.candidates[index].content.type_contract_comment;
                }

                let employment_comment = "";
                if (this.candidates[index].content.expert_comment !== undefined) {
                    employment_comment = this.candidates[index].content.employment_comment;
                }

                let work_time_comment = "";  
                if (this.candidates[index].content.expert_comment !== undefined) {
                    work_time_comment = this.candidates[index].content.work_time_comment;
                }

                let params = {
                    questionaire_id: id,              
                    company_id: this.work.user_id,
                    expert_comment: expert_comment,
                    type_contract_comment: type_contract_comment,
                    employment_comment: employment_comment,
                    work_time_comment: work_time_comment,
                  
                };          
                axios.post('/api/question/update_comment', params)
                .then((response) => {
                   if (response.data !== undefined) {
                       Swal.fire(this.trans('text.SavedComments'));
                    }
                });  
            }

        },

        prev() {
          let index = this.current_candidate_index;  
          index = index - 1;

          if (index >=0 && this.candidates[index] !== undefined) {
            this.current_candidate_index = index;
           }  else {
            return false;
           }    
        },

        next() {
          let index = this.current_candidate_index;  
          index++;
          if (this.candidates[index] !== undefined) {
            this.current_candidate_index = index;
           }  else {
            return false;
           }        
        },

        openCandidate() {
            let index = this.current_candidate_index;  
            if (index >=0 && this.candidates[index] !== undefined && this.work.user_id !== undefined) {
                let id = this.candidates[index].id;                
                let params = {
                    questionaire_id: id,
                    work_id: this.work_id,
                };          
                axios.post('/api/question/open', params)
                .then((response) => {   
                    if (response.data !== undefined&& response.data.status !== undefined) {
                        if (response.data.status === 0) {
                            Swal.fire(this.trans('text.InsufficientFundsInPersonalAccount'));                    
                        }
                        if (response.data.status === 1) {
                            Swal.fire(this.trans('text.CandidateDataIsOpen'));
                            this.loadCandidates();   
                            this.loadWork();
                            this.loadCompany(this.work.user_id);
                        }
                    }                                     
                });  
            }
        },

        replaceCandidate() {
             let index = this.current_candidate_index;  
            if (index >=0 && this.candidates[index] !== undefined && this.work.user_id !== undefined) {
                let id = this.candidates[index].id;                
                let params = {
                    questionaire_id: id,
                    work_id: this.work_id,
                };          
                axios.post('/api/question/replace', params)
                .then((response) => {   
                    if (response.data !== undefined&& response.data.status !== undefined) {
                        if (response.data.status === 0) {
                            Swal.fire(this.trans('text.InsufficientFundsInPersonalAccount'));               
                        }
                        if (response.data.status === 1) {
                            Swal.fire(this.trans('text.CandidateReplaced'));
                            this.loadCandidates();
                            this.loadWork();
                            this.loadCompany(this.work.user_id);   
                        }
                    }                                       
                });  
            }
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
                    this.work = response.data.data;
                    if (this.work.user_id > 0) {
                        this.loadCompany(this.work.user_id);
                    }                   
                }       
            });
           }          
        },

        loadCandidates() {
           let id =  this.work_id;
           if (id > 0) {
            axios({
              method: 'get',
              url: '/api/questions/'+id,
            })
            .then((response) => {
                if (response.data !== undefined) {
                     this.candidates = response.data;
                }       
            });
           }      
        },

        loadCompany(company_id) {
           let id =  company_id;
           if (id > 0) {
            axios({
              method: 'get',
              url: '/api/company/'+id,
            })
            .then((response) => {
                if (response.data !== undefined) {
                    this.company = response.data;
                }       
            });
           }
        },

        changeStatus(new_status) {
            let params = {
                work_id: this.work_id,
                new_status: new_status,
                user_id: this.user_id,
            };          
            axios.post('/api/work/status', params)
            .then((response) => {
               if (response.data !== undefined) {
                    location.href='/home?status=3';
                }
            });  
        },

        showNew() {
            this.isNewActive = true;
            this.isOpenActive = false;
           let id =  this.work_id;
           if (id > 0) {
            axios({
              method: 'get',
              url: '/api/questions/'+id,
            })
            .then((response) => {
                if (response.data !== undefined) {
                     this.current_candidate_index = 0;
                     this.candidates = response.data.filter((item) => {
                    return item.opened === 0
                     });
                }       
            });
           } 
        },

        showOpen() {
            this.isNewActive = false;
            this.isOpenActive = true;            
             let id =  this.work_id;
           if (id > 0) {
            axios({
              method: 'get',
              url: '/api/questions/'+id,
            })
            .then((response) => {
                if (response.data !== undefined) {
                     this.current_candidate_index = 0;
                     this.candidates = response.data.filter((item) => {
                        return item.opened === 1
                     });
                }       
            });
           }              
        },

    },
    mounted() {
        this.loadCandidates();
        this.loadWork();
        this.loadShopDegrees();
    }
}
</script>


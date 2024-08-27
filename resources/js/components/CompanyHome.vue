<template>
    <div>
        <header class="header">
            <div class="container">
                <div class="header__top">
                    <div>
                        <upload-logo :user_id="user_id" :logo="logo"></upload-logo>
                    </div>

                    <div class="header__top_name-com">
                        {{ name }}
                    </div>

                    <div class="header__top_name-lk">
                        {{ trans("text.PersonalAccount") }} {{ balance }} {{ currency }}
                    </div>

                    <a href="/logout" class="header__top_exit">
                        {{ trans("text.ExitLk") }}
                    </a>
                </div>

                <div style="display: flex;
                            flex-direction: row-reverse;
                            align-items: flex-start;">
                    <a href="/set-locale/en">
                        <img src="/img/en.png" alt="" style="height: 31px; width: 50px; margin: 0 0 0 20px">
                    </a>
                    <a href="/set-locale/es">
                        <img src="/img/es.jpg" alt="" style="width: 50px"> 
                    </a>
                </div>

                <div class="header__center">
                    <div class="header__center_vacan">
                        {{ trans("text.site_title") }}
                    </div>
                    <div class="header__center_work two activ">
                        <span v-if="status === 1">{{ trans("text.TransferWork") }}</span>
                        <span v-if="status === 2">{{ trans("text.AtWork") }}</span>
                        <span v-if="status === 3">{{ trans("text.Archive") }}</span>
                    </div>

                    <form action="#" class="header__center_form">
                        <div class="header__center_input">
                            <input type="text" v-model="search_input" name="search"
                                :placeholder="trans('text.SearchVacancy')">
                            <div class="header__center_input-icon" @click="searchWorks">
                                <img src="img/icons/input_search-icon.svg" alt="img">
                            </div>
                        </div>
                    </form>
                    <div class="header__center_btns">
                        <button onclick="location.href='/create'"
                            class="header__center_btn">{{ trans("text.CreateANewOne") }}</button>
                        <button onclick="location.href='/oferta'"
                            class="header__center_btn">{{ trans("text.ViewTheOffer") }}</button>
                    </div>
                </div>

                <div class="header__bottom">
                    <div class="header__bottom_select">
                        <Select2 v-model="sort" :options="sort_list"
                            :settings="{  placeholder: trans('text.SortBy') }" />
                    </div>
                    <div class="header__bottom_text">
                        <a href="/document">{{ trans("text.Tariffs") }}</a>
                    </div>
                    <a href="/instruction" class="instruction_link">
                        {{ trans("text.InstructionsVacancy") }}
                    </a>
                </div>
            </div>
        </header>

        <section class="vacancies">
            <div class="container">
                <div class="vacancies__wrapper">
                    <div class="tabs-wrapper vacancies__tab-wrapper" v-if="status === 2">
                        <a class="tab tab-one" @click.stop="showWorks(3)" :class="{ 'tab_active': status === 3 }">
                            <div class="vacancies__tab">
                                <div class="vacancies__tab_text">
                                    {{ trans("text.Archive") }}
                                </div>
                                <div class="vacancies__tab_num">
                                    {{company.counter_3}}
                                </div>
                            </div>
                        </a>

                        <a class="tab tab-two" @click.stop="showWorks(1)" :class="{ 'tab_active': status === 1 }">
                            <div class="vacancies__tab">
                                <div class="vacancies__tab_text">
                                    {{ trans("text.TransferWork") }}
                                </div>
                                <div class="vacancies__tab_num">
                                    {{company.counter_1}}
                                </div>
                            </div>
                        </a>

                        <a class="tab tab-three" @click.stop="showWorks(2)" :class="{ 'tab_active': status === 2 }">
                            <div class="vacancies__tab">
                                <div class="vacancies__tab_text">
                                    {{ trans("text.AtWork") }}
                                </div>
                                <div class="vacancies__tab_num">
                                    {{company.counter_2}}
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="tabs-wrapper vacancies__tab-wrapper" v-if="status === 1">
                        <a class="tab tab-one" @click.stop="showWorks(3)" :class="{ 'tab_active': status === 3 }">
                            <div class="vacancies__tab">
                                <div class="vacancies__tab_text">
                                    {{ trans("text.Archive") }}
                                </div>
                                <div class="vacancies__tab_num">
                                    {{company.counter_3}}
                                </div>
                            </div>
                        </a>

                        <a class="tab tab-three" @click.stop="showWorks(2)" :class="{ 'tab_active': status === 2 }">
                            <div class="vacancies__tab">
                                <div class="vacancies__tab_text">
                                    {{ trans("text.AtWork") }}
                                </div>
                                <div class="vacancies__tab_num">
                                    {{company.counter_2}}
                                </div>
                            </div>
                        </a>

                        <a class="tab tab-two" @click.stop="showWorks(1)" :class="{ 'tab_active': status === 1 }">
                            <div class="vacancies__tab">
                                <div class="vacancies__tab_text">
                                    {{ trans("text.TransferWork") }}
                                </div>
                                <div class="vacancies__tab_num">
                                    {{company.counter_1}}
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="tabs-wrapper vacancies__tab-wrapper" v-if="status === 3">
                        <a class="tab tab-three" @click.stop="showWorks(2)" :class="{ 'tab_active': status === 2 }">
                            <div class="vacancies__tab">
                                <div class="vacancies__tab_text">
                                    {{ trans("text.AtWork") }}
                                </div>
                                <div class="vacancies__tab_num">
                                    {{company.counter_2}}
                                </div>
                            </div>
                        </a>

                        <a class="tab tab-two" @click.stop="showWorks(1)" :class="{ 'tab_active': status === 1 }">
                            <div class="vacancies__tab">
                                <div class="vacancies__tab_text">
                                    {{ trans("text.TransferWork") }}
                                </div>
                                <div class="vacancies__tab_num">
                                    {{company.counter_1}}
                                </div>
                            </div>
                        </a>

                        <a class="tab tab-one" @click.stop="showWorks(3)" :class="{ 'tab_active': status === 3 }">
                            <div class="vacancies__tab">
                                <div class="vacancies__tab_text">
                                    {{ trans("text.Archive") }}
                                </div>
                                <div class="vacancies__tab_num">
                                    {{company.counter_3}}
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="tabs-wrapper">
                        <div id="tab-1" class=" tabs-content vacancies__tabs tabs-content_active">
                            <div class="vacancies__tabs_wrapper">
                                <div class="vacancies__item" v-for="item in search_works" :key="item.id">

                                    <a class="vacancies__item_title" v-if="item.status === 2">
                                        {{item.name}}
                                    </a>

                                    <a :href="'/work/edit/'+item.id" class="vacancies__item_title"
                                        v-if="item.status === 1">
                                        {{item.name}}
                                    </a>

                                    <a class="vacancies__item_title" v-if="item.status === 3">
                                        {{item.name}}
                                    </a>

                                    <div class="vacancies__item_profession">
                                        <div class="vacancies__item_profession-item">
                                            <div class="vacancies__item_profession-title">
                                                {{ trans("text.Action") }}
                                            </div>
                                            <div class="vacancies__item_profession-button blue-bg"
                                                @click="changeSendWork(item.id)" v-if="status===1">
                                                {{ trans("text.TransferWorkButton") }} </div>
                                            <div class="vacancies__item_profession-button  orange-border"
                                                @click="changeStatus(item.id,3)" v-if="status===2">
                                                {{ trans("text.StopArchive") }} </div>
                                            <div class="vacancies__item_profession-button blue-bg"
                                                @click="changeStatus(item.id,2)" v-if="status===3">
                                                {{ trans("text.ReturnToWork") }} </div>
                                        </div>


                                        <div class="vacancies__item_profession-item">
                                            <div class="vacancies__item_profession-title">
                                                {{ trans("text.Status") }}
                                            </div>
                                            <div class="vacancies__item_profession-button blue-bg"
                                                v-if="item.closed === true" @click="changeTimeWork(item.id)">
                                                {{ trans("text.TheSearchPeriodHasExpiredExtend") }}</div>
                                            <div v-else>
                                                <div class="vacancies__item_profession-button blue-bg"
                                                    v-if="item.status === 2 && item.closed === false">
                                                    {{ trans("text.TheSearchIsUnderway") }}</div>
                                                <div class="vacancies__item_profession-button" v-else>
                                                    {{ trans("text.TheDeadlineIsNotSet") }} </div>
                                            </div>
                                        </div>


                                        <div class="vacancies__item_profession-item">
                                            <div class="vacancies__item_profession-title">
                                                {{ trans("text.Expenditure") }}
                                            </div>
                                            <div class="vacancies__item_profession-button">
                                                <div v-if="item.balance > 0">
                                                    {{ item.balance }} {{item.currency}}
                                                </div>
                                                <div v-if="item.balance == 0"> 0 {{item.currency}}
                                                </div>
                                            </div>
                                        </div>


                                        <div class="vacancies__item_profession-item">
                                            <div class="vacancies__item_profession-title">
                                                {{ trans("text.Publication") }}
                                            </div>
                                            <div class="vacancies__item_profession-button" v-if="item.pay_confirm == 1">
                                                <div>
                                                    {{ trans("text.Paid") }}
                                                </div>
                                            </div>
                                            <div class="vacancies__item_profession-button orange-border"
                                                v-if="item.pay_confirm == 0">
                                                <div>
                                                    {{ trans("text.PaymentRequired") }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="vacancies__item_info">

                                        <div class="vacancies__item_info-item">
                                            <div>
                                                <span>{{ trans("text.NumberVacantPlaces") }}
                                                </span><span>{{item.positions}}</span>
                                            </div>
                                        </div>
                                        <div class="vacancies__item_info-item">
                                            <div>
                                                <span>{{ trans("text.Created") }}
                                                </span><span>{{item.date_create}}</span>
                                            </div>
                                        </div>
                                        <div class="vacancies__item_info-item">
                                            <div>
                                                <span>{{ trans("text.PlannedClosure") }}
                                                </span><span>{{item.date_close}}</span>
                                            </div>
                                        </div>
                                        <div class="vacancies__item_info-item">
                                            <div>
                                                <span>{{ trans("text.Location") }} </span><span> {{item.city_id}}</span>
                                            </div>
                                        </div>
                                        <div class="vacancies__item_info-item">
                                            <div>
                                                <span>{{ trans("text.Fot") }}
                                                </span><span>{{item.income_start}}-{{item.income_end}}
                                                    {{item.currency}}</span>
                                            </div>
                                        </div>
                                        <div class="vacancies__item_info-item">
                                            <div>
                                                <span>{{ trans("text.SubmittedByCandidates") }}
                                                </span><span>{{item.count_candidates}}</span>
                                            </div>
                                        </div>
                                        <div class="vacancies__item_info-item">
                                            <div>
                                                <span>{{ trans("text.CandidateViewed") }}
                                                </span><span>{{item.count_candidates_viewed}}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <a :href="'/work/edit/'+item.id" class="vacancies__item_link"
                                        v-if="item.status === 1">
                                        {{ trans("text.EditDraft") }}
                                    </a>

                                    <a :href="'/work/template/'+item.id" class="vacancies__item_link">
                                        {{ trans("text.TemplateCreateNew") }}
                                    </a>

                                    <a :href="'/work/shop/'+item.slug" class="vacancies__item_link"
                                        v-if="item.status !== 1 && item.count_candidates > 0">
                                        {{ trans("text.CandidateShop") }}
                                    </a>
                                    
                                    <a class="vacancies__item_link"
                                        v-if="item.status !== 1 && item.count_candidates === 0"
                                        @click.stop="noCanditates">
                                        {{ trans("text.CandidateShop") }}
                                    </a>

                                    <hr>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
import Swal from 'sweetalert2';  
import Select2 from 'vue3-select2-component';  
export default {
  props: ['name', 'balance', 'currency', 'user_id','logo','status_works'],

  data() {
       return {
            status: 2,
            search_input: "",         
            status_title: "",
            sort: "",
            sort_list: [
            { id: 1, text: this.trans('text.SortByAlph')},
            { id: 2, text: this.trans('text.SortByDateCreate')},
            { id: 3, text: this.trans('text.SortByDateClose') },
            { id: 4, text: this.trans('text.SortByFot') },
            { id: 5, text: this.trans('text.SortByLocation') },            
            ],
            works: [],
            works1: [],
            works2: [],
            works3: [],
            company: {
                counter_1: 0,
                counter_2: 0,
                counter_3: 0,
                counter_4: 0,
                counter_5: 0,
            },
        }
    },
    computed: {
        search_works: function() {
            if (this.search_input !== "") {
                return this.works.filter((item) => {
                    return item.name.toLowerCase().indexOf(this.search_input.toLowerCase()) != -1
                });
            } else {
                return this.sort_works;
            };            
        },
        sort_works: function() {
            let sort = parseInt(this.sort);
            if (sort === 1) {
                let sorted_works = this.works.sort(function(a, b) {
                    if(a.name < b.name) { return -1; }
                    if(a.name > b.name) { return 1; }
                    return 0;
                });
                return sorted_works;
            }

            if (sort === 2) {
                let sorted_works = this.works.sort(function(a, b) {
                    if(a.created < b.created) { return -1; }
                    if(a.created > b.created) { return 1; }
                    return 0;
                });
                return sorted_works;
            }

            if (sort === 3) {
                let sorted_works = this.works.sort(function(a, b) {
                    if(a.date_close < b.date_close) { return -1; }
                    if(a.date_close > b.date_close) { return 1; }
                    return 0;
                });
                return sorted_works;
            }

            if (sort === 4) {
                let sorted_works = this.works.sort(function(a, b) {
                    if(a.income < b.income) { return -1; }
                    if(a.income > b.income) { return 1; }
                    return 0;
                });
                return sorted_works;
            }

            if (sort === 5) {
                let sorted_works = this.works.sort(function(a, b) {
                    if(a.city_id < b.city_id) { return -1; }
                    if(a.city_id > b.city_id) { return 1; }
                    return 0;
                }); 
                return sorted_works;              
            }

            return this.works;
        }
    },
   
    methods: {
        showWorks(status_id) {
            this.status = status_id,
            this.loadWork(status_id);
        },
        loadWorks() {
            this.loadWork(this.status);            
        },
        loadWork(status_id) {
            this.works = [];
            let params = {
                status_id: status_id,
                user_id: this.user_id,
            };          
            axios.post('/api/works', params)
            .then((response) => {
                if (response.data !== undefined) {
                    this.works = response.data; 
                }                       
            });            
        },

        loadCompanyInfo() {
            let company_id = this.user_id;
             axios({
              method: 'get',
              url: '/api/company/'+company_id,
            })
            .then((response) => {             
                    if (response.data.counter_1 !== undefined) {
                        this.company.counter_1 = response.data.counter_1;
                    }
                    if (response.data.counter_2 !== undefined) {
                        this.company.counter_2 = response.data.counter_2;
                    }
                    if (response.data.counter_3 !== undefined) {
                        this.company.counter_3 = response.data.counter_3;
                    }      
                }
            );
        },
        changeStatus(work_id, new_status) {
            let params = {
                work_id: work_id,
                new_status: new_status,
                user_id: this.user_id,
            };          
            axios.post('/api/work/status', params)
            .then((response) => {
                this.loadWorks();    
                this.loadCompanyInfo(); 
            });  
        },
        changeSendWork(work_id) {
            let params = {
                work_id: work_id,
                user_id: this.user_id,
            };          
            axios.post('/api/work/send', params)
            .then((response) => {
                 if (response.data !== undefined) {
                    if (response.data.status === 1) {
                        Swal.fire(this.trans('text.VacancySendWorkMsg'));
                        this.status = 2;
                        this.loadWorks();
                        this.loadCompanyInfo(); 
                    }
                    if (response.data.status === 0) { 
                        Swal.fire(this.trans('text.VacancyPayPublicationMsg'));                     
                    }    
                } 
            });  
        },
        noCanditates() {
            Swal.fire(this.trans('text.NoCandidates'));
        },   
        changeTimeWork(work_id) {

            let params = {
                work_id: work_id,
                user_id: this.user_id,
            };    

            Swal.fire({
            title: this.trans('text.ConfirmExtension'),
            text: this.trans('text.ConfirmExtensionVacancy'),
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: this.trans('text.No'),
            confirmButtonText: this.trans('text.Yes'),
            }).then((result) => {
            if (result.isConfirmed) {
                location.href='/document';
            }
            });  
     

        },
    },
    mounted() {
        this.status = parseInt(this.status_works);
        this.loadWorks();
        this.loadCompanyInfo();
    }
}
</script>


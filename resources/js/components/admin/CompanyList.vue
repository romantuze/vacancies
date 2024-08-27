<template>

<header class="container header-table">
    <a href="/admin/home" class="logo">
        <img loading="lazy" src="/img/logo-table.svg" alt="img">
    </a>

    <a href="/admin-logout" class="log-off">
        <span>Выйти из личного кабинета</span>
        <svg class="svg">
            <use xlink:href="/img/sprite.svg#log-off"></use>
        </svg>
    </a>
</header>

<div class="container sort-block">
  <div class="table-tabs">
    <a href="/admin/companies" class="table-tabs__item active">
      Сортировать по компаниям
    </a>
    <a href="/admin/vacancies" class="table-tabs__item">
      Сортировать по вакансиям
    </a>
  </div>
  <div class="search">
    <input type="text" placeholder="Поиск по наименованию компании" v-model="search_input" @input="performSearch">
    <button class="search__btn"></button>
  </div>
</div>

        <div class="table table-sort-company">
            <div class="container">

                <div  class="table__top table-row" >
                    <div>
                        <span class="item-title left">Лого</span>
                    </div>
                    <div>
                        <span class="item-title left">Наименование</span>
                    </div>
                    <!--<div>
                        <span class="item-title left">Общий счет</span>
                    </div>-->
                    <div>
                        <span class="item-title">Всего <br> вакансий</span>
                    </div>
                    <div>
                        <span class="item-title">Поиск идет</span>
                    </div>
                    <div>
                       <span class="item-title">Ждет подтверждения оплаты публикации
                       </span>
                    </div>
                    <div>
                        <span class="item-title">Черновики</span>
                    </div>
                    <div>
                        <span class="item-title">В архиве</span>
                    </div>
                </div>



                <div v-for="item in search_items" class="table__item table-row" :key="item.id">

                    <div class="table__item--img left">
                        <div v-if="item.logo !== null">

                             <a :href="'/admin/company/'+item.id" class="left">
                                <!--<img loading="lazy" :src="item.logo" alt=""> -->
                                <div class="logo-image-admin" :style="{ backgroundImage: 'url('+item.logo+')' }"></div>     
                            </a>
                        </div>
                        <div v-else>
                            <img loading="lazy" src="/img/logo.png" alt="">
                        </div>
                    </div>

                    <a :href="'/admin/company/'+item.id" class="left">
                        <span class="table__item--text">{{ item.name }} </span><br>
                        <span class="table__item--text">Email: {{ item.email }}</span><br>
                        <span class="table__item--text">Тел: {{ item.phone }} </span>
                    </a>

                    <div>
                        <span class="table__item--text">{{ item.counter_5 }}</span> <!--Всего вакансий-->
                    </div>
                    <div>
                        <span class="table__item--text">{{ item.counter_2 }}</span> <!--Поиск идет-->
                    </div>
                    <div>
                        <span class="table__item--text">{{ item.counter_4 }}</span> <!--Ждет подтверждения оплаты публикации-->
                    </div>
                    <div>
                        <span class="table__item--text">{{ item.counter_1 }}</span> <!--Черновики-->
                    </div>
                    <div>
                        <span class="table__item--text">{{ item.counter_3 }}</span> <!--В архиве-->
                    </div>
                </div>

                <div v-intersection="loadMore" class="observer"></div>

            </div>
        </div>




<div class="container sort-block">
  <div class="table-tabs">
    <a href="/admin/selects" class="table-tabs__item">
     Классификаторы
    </a>
  </div>
</div>


</template>
<script>
export default {
    data() {
       return {
            items: [],
            search_input: "",
            current_offset: 0,
            current_limit: 5,
            loadFlag: true,
        }
    },
    computed: {
        search_items: function() {
            return this.items;  
        }
    },
    mounted() {
        this.loadCompanies(0);
    },
    methods: {
        performSearch() {
            if (this.search_input !== "") {
                this.loadFlag = false;
                this.current_offset = 0;
                this.searchCompany(this.search_input);
            } else {
                this.loadFlag = true;
                this.items = [];
                this.loadCompanies(this.current_offset);
            }
        },

        loadCompanies(offset) {
            let params = {
                limit: this.current_limit,
                offset: offset,   
            };   
           
            axios.post('/api/companies', params)
            .then((response) => {
                if (response.data !== undefined) {
                    let old_items = this.items;
                    let new_items = response.data.map((item)=>{
                       return {
                            id: item.id,
                            name: item.name,
                            phone: item.phone,
                            email: item.email,
                            logo: item.logo,                           
                            counter_1: item.counter_1,
                            counter_2: item.counter_2,
                            counter_3: item.counter_3,
                            counter_4: item.counter_4,
                            counter_5: item.counter_5,
                        }
                    }); 
                    this.items = old_items.concat(new_items);       
                }     
            });
        },

        async loadMore() {
            if(this.loadFlag){
                let offset = this.current_offset + this.current_limit;
                this.current_offset = offset;
                this.loadCompanies(offset);  
            }
        },
        
        searchCompany(query){
            let params = {
                limit: this.current_limit,
                search: query,
                offset: 0,   
            };   
           
            axios.post('/api/companies', params)
            .then((response) => {
                if (response.data !== undefined) {
                    let new_items = response.data.map((item)=>{
                       return {
                            id: item.id,
                            name: item.name,
                            phone: item.phone,
                            email: item.email,
                            logo: item.logo,                           
                            counter_1: item.counter_1,
                            counter_2: item.counter_2,
                            counter_3: item.counter_3,
                            counter_4: item.counter_4,
                            counter_5: item.counter_5,
                        }
                    }); 
                    this.items = new_items;       
                } else {
                    this.items = [];
                }    
            });
        }
    } 
}
</script>
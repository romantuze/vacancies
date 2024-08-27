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
                <a href="/admin/companies" class="table-tabs__item ">
                    Сортировать по компаниям
                </a>
                <a href="/admin/vacancies" class="table-tabs__item active">
                    Сортировать по вакансиям
                </a>
            </div>
            <div class="search">
                <input type="text" placeholder="Поиск по наименованию вакансии" v-model="search_input">
                <button class="search__btn"></button>
            </div>
        </div>

        <div class="table table-sort-vacancies-all">
            <div class="container">
                <div class="table__top table-row">
                    <div>
                        <span class="item-title left">Наименование вакансии</span>
                    </div>
                     <div>
                        <span class="item-title left">Компания</span>
                    </div>
                    <div>
                        <span class="item-title show-list left" @click.stop="loadSortWorks('status')">Статус</span>
                    </div>
                    <div>
                        <span class="item-title">Расход средств</span>
                    </div>
                    <div>
                        <span class="item-title show-list left" @click.stop="loadSortWorks('deposit_text')">Публикация</span>
                    </div>

                    <div>
                        <span class="item-title">К - во <br> кандидатов</span>
                    </div>
                    <div>
                        <span class="item-title show-list left" @click.stop="loadSortWorks('city_id')">Локация</span>

                    </div>
                    <div>
                        <span class="item-title show-list" @click.stop="loadSortWorks('created_at')">Создана дата</span>

                    </div>
                    <div>
                        <span class="item-title show-list left" @click.stop="loadSortWorks('date_close')">Плановое закрытие</span>

                    </div>
                </div>
                <div v-for="item in search_items" class="table__item table-row" :key="item.id">
                    <div class="left">
                        <span class="table__item--text name-chenge--show">
                        
                        <a>
                        № {{ item.id }} {{ item.name }}
                        </a>  

                        <a :href="'/admin/work/'+item.id" v-if="item.status === 2 || item.status === 3">управлять кандидатами</a>

                        <a :href="'/admin/work/export/'+item.id">
                        скачать файл публикации
                        </a>
                        <a :href="'/q/'+item.slug" target="_blank" v-if="item.status === 2">
                        ссылка на опросник
                        </a>
                        <a :href="'/admin/work/delete/'+item.id" target="_blank" onclick="return confirm('Вы действительно хотите удалить вакансию?')"  v-if="item.status === 1">удалить вакансию</a>
                        </span>

                    </div>
                      <div class="left">
                        <span class="table__item--text text-blue">
                        {{item.company_name}}
                    </span>
                    </div>
                    <div class="left">
                        <span class="table__item--text text-blue">
                       <span v-if="item.status == 1">Черновик</span>
                       <span v-if="item.status == 2">В работе</span>
                       <span v-if=" item.status == 3">Архив</span>
                    </span>
                    </div>
                    <div class="left">
                         <span class="table__item--text" style="min-width: 120px;">
                            Расход: {{ item.balance }}<br>
                            <a :href="'/admin/work/deposit/'+item.id">Пополнить счет</a>
                            </span>
                    </div>
                    <div class="left">
                        <span class="table__item--text text-blue">   
                        <span v-if="item.pay_confirm == 1">Оплачена</span><br>
                            <a v-if="item.pay_confirm == 0"
                             @click="payConfirm(item.id, item.name, 1)" 
                            style="cursor: pointer;" 
                            >Подвердить оплату</a>                     
                            <br>
                            <span>Скидка: <input type="text" :value="item.sale" style="width: 40px;" 
                            @change="saveSale($event.target.value, item.id)"></span>  
                        </span>
                    </div>
                    <div>
                        <span class="table__item--text">
                            <a :href="'/admin/work/'+item.id" 
                            v-if="item.count_candidates_approved > 0">{{ item.count_candidates_approved }}</a>
                            <a v-else>0</a>
                        </span>
                    </div>
                    <div>
                        <span class="table__item--text">{{ item.city_id }}</span>
                    </div>
                    <div>
                        <span class="table__item--text">{{ item.date_create }}</span>
                    </div>
                    <div>
                        <span class="table__item--text">{{ item.date_close }}</span>
                    </div>
                </div>

                <div v-intersection="loadMore" class="observer"></div>
               
            </div>
        </div>
</template>
<script>
import Swal from 'sweetalert2';  
export default {
    props: [],
    data() {
       return {
        items: [],
        search_input: "",
        user: {},
        current_offset: 0,
        current_limit: 5,
        sort: "id",
        asc: true,
        }
    },
    computed: {
        search_items: function() {
            if (this.search_input !== "") {
                return this.items.filter((item) => {
                    return item.name.toLowerCase().indexOf(this.search_input.toLowerCase()) != -1
                });
            } else {
                return this.items;
            };     
        }
     },
    mounted() {
         this.loadUser();
         this.loadWorks();            
    },

    methods: {

        payConfirm(id, name, pay_value) {
            let params = {    
                work_id: id,
                pay_value: pay_value,
            };              
            Swal.fire({
            title: 'Подтвердить оплату',
            text: 'Вы действительно хотите подтвердить публикацию вакансии ' + name,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Нет',
            confirmButtonText: 'Да',
            }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                'Подверждено!',
                'Публикация вакансии подтверждена.',
                'success'
                );               
                axios.post('/api/work/pay_confirm', params)
                .then((response) => {
                    if (response.data.msg !== undefined) {                               
                        location.href='';
                    } 
                });
            }
            });          
        },

        saveSale(value, id) {            
            if (value >= 0 && value < 100) {                
            let params = {
                work_id: id,
                value: value,      
            };  
            axios.post('/api/work/sale', params)
            .then((response) => {
                if (response.data.msg !== undefined) {
                    Swal.fire(response.data.msg);                        
                }     
            });
            }
        },

        loadSortWorks(s) {         
            this.current_offset = 0;
            this.sort = s;
            this.items = [];            
            this.asc = !this.asc;
            this.loadWorks();
        },

        async loadWorks() {

            let params = {
                limit: this.current_limit,
                offset: this.current_offset,   
                sort: this.sort,
                asc: this.asc,
            };   

            axios.post('/api/works_admin', params)
            .then((response) => {
                if (response.data !== undefined) {
                    let old_items = [...this.items];
                    let new_items = response.data.map((item)=>{
                       return {
                            id: item.id,
                            name: item.name,
                            company_name: item.company_name,
                            status: item.status,
                            slug: item.slug,
                            city_id: item.city_id,
                           
                            date_close: item.date_close, 
                            count_candidates_approved: item.count_candidates_approved,
                            balance: item.balance,
                            balance_admin: item.balance_admin,
                            deposit_text: item.deposit_text,
                            deposit_in: item.deposit_in,    
                            currency: item.currency,  
                            sale: item.sale,
                            pay_confirm: item.pay_confirm,
                            date_create: item.date_create,
                        }
                    });
                    this.items = old_items.concat(new_items); 
                    old_items = [];  
                    new_items = []; 
                }     
            });
            this.current_offset = this.current_offset + this.current_limit;  
        },

        loadUser() {
            let id =  this.company_id;
           if (id > 0) {
            axios({
              method: 'get',
              url: '/api/users/'+id,
            })
            .then((response) => {
                if (response.data !== undefined) {
                    this.user = response.data;
                }       
            });

           }
        },

        async loadMore() {         
            this.loadWorks();  
        }
      
    } 
}
</script>

<style> 
.observer {
    clear: both;
    width: 10px;    
    height: 1px;
}
</style>
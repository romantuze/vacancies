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
            <div class="company-block company-block-v2">
                <div v-if="work.company_logo !== null">
                    <img loading="lazy" :src="work.company_logo" alt="" width="160">
                </div>
                <div v-else>
                    <img loading="lazy" src="/img/logo.png" alt="" width="160">
                </div>               
               {{ work.company_name }}
            </div>
            <div class="search">
                <input type="text" placeholder="Поиск по имени кандидата" v-model="search_input">
                <button class="search__btn"></button>
            </div>
        </div>

        <div class="container">
            <div class="candidate-block">
                <div class="candidate-block__left">
                    <p>{{work_title}}</p>
                    <span>Работа с кандидатами</span>
                </div>
                <div class="candidate-block__right">
                    <a href="/admin/vacancies" class="candidate-block__btn candidate-block__btn--blue">
                        Вернуться к выбору вакансии
                    </a>
                    <a href="#" class="candidate-block__btn candidate-block__btn--orange">
                        Скачать отклики с сорсинга
                    </a>
                </div>
            </div>
        </div>

        <div class="table table-sort-candidate">
            <div class="container">
                <div class="table__top table-row">
                    <div>
                        <span class="item-title left">Кандидаты</span>
                    </div>
                 
                    <div>
                        <span class="item-title left">Телефон</span>
                    </div>
                    <div>
                        <span class="item-title left">Email</span>
                    </div>
                    
                    <div>
                        <span class="item-title left">Текущий статус</span>
                    </div>
                    <div>
                        <span class="item-title left">Смена статуса</span>
                    </div>
                    <div>
                        <span class="item-title left">Отклик</span>
                    </div>
                </div>

                <div v-for="item in search_items"  class="table__item table-row" :key="item.id">
                    <div class="left">
                        <span class="table__item--text">

                        {{ item.fio_f }}
                        {{ item.fio_i }}
                        {{ item.fio_o }}
                       
                        <a :href="item.resume" target="_blank">
                        посмотреть резюме
                        </a>
                        <a :href="item.photo" target="_blank">
                        посмотреть фото
                        </a>
                        <a :href="'/admin/questionaire/delete/'+item.id" target="_blank" onclick="return confirm('Вы действительно хотите удалить кандидата?')">
                        удалить кандидата
                        </a>
                       
                        </span>
                    </div>
                    
                    <div class="left">
                        <span class="table__item--text"> {{ item.phone }}</span>
                    </div>
                    <div class="left">
                        <span class="table__item--text"> {{ item.email }}</span>
                    </div>
                   
                    <div class="left">
                        <span class="table__item--text text-blue" v-if="item.status_now !== undefined">
                            Статус: {{ status_now_list[item.status_now] }}<br>
                            Результат анкеты: {{ status_result[item.status] }}<br>  
                            Рейтинг анкеты: {{ item.rate }}<br>                          
                            Ключ: {{ item.ref }}
                        </span><br>                      
                        
                    </div>
                    <div class="left">
                         <span class="table__item--text text-blue" 
                         v-if="item.status_now === 0 && item.status === 1">
                            <span @click="changeStatusNow(item.id,1)">Сменить статус на
                            {{status_now_list[1]}}</span>                            
                        </span>
                    </div>
                    <div class="left">
                        <span class="table__item--text">
                            <a :href="'/admin/questionaire/export/'+item.id">
                                <svg class="svg">
                                    <use xlink:href="/img/sprite.svg#response"></use>
                                </svg>
                            </a>                           
                        </span>
                    </div>
                </div>

            </div>
        </div>

</template>
<script>
export default {
    props: ['work_id','user_id'],
    data() {
       return {
        status_result: ["Не прошел", "Прошел"],
        status_opened_list: ["Нет", "Да"],
        status_now_list: [
        "Скачен с сорсинга", //0
        "Представлен Работодателю", //1
        "Отклонен Работодателем",//2
        "Данные приобретены",//3
         ],
        items: [],
        search_input: "",
        user: {},
        work: {},
        work_title: "",
        }
    },
    computed: {
        search_items: function() {
            if (this.search_input !== "") {
                return this.items.filter((item) => {
                    let fio = item.fio_f + " " + item.fio_i + " " + item.fio_o; 
                    return fio.toLowerCase().indexOf(this.search_input.toLowerCase()) !== -1;
                });
            } else {
                return this.items;
            };     
        }
     },
    mounted() {

         this.loadUser();
         this.loadWork();
         this.loadCandidates();
            
    },
    methods: {

        changeStatusNow(questionaire_id, new_status) {
            let params = {
                questionaire_id: questionaire_id,
                new_status: new_status,
                user_id: this.user_id,
            };          
            axios.post('/api/question/status_now', params)
            .then((response) => {
                 this.loadCandidates();
            });  
        },

        loadCandidates() {
           
           let id = this.work_id;
               if (id > 0) {
                axios({
                  method: 'get',
                  url: '/api/admin/questions/'+id,
                })                 
                .then((response) => {
                    if (response.data !== undefined) {
                        this.items = response.data.map((item)=>{
                           return {
                                id: item.id,
                                ref: item.ref,
                                opened: item.opened,
                                fio_f: item.fio_f,
                                fio_i: item.fio_i,
                                fio_o: item.fio_o,                            
                                phone: item.phone,
                                email: item.email,
                                status: item.status,
                                status_now: item.status_now,
                                resume: item.resume,
                                photo: item.photo,
                                rate: item.rate,
                            }
                        });                       
                    }     
                });
             }
   
        },

        loadUser() {
            let id =  this.user_id;
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
                    this.work_title = response.data.data.name;                   
                }       
            });
           }          
        },
      
    } 
}
</script>
<template>
    <header class="container header-table">
    <a href="/admin/home" class="logo">
        <img loading="lazy" src="/img/logo-table.svg" alt="img">
    </a>
    <a href="/logout" class="log-off">
        <span>Выйти из личного кабинета</span>
        <svg class="svg">
            <use xlink:href="/img/sprite.svg#log-off"></use>
        </svg>
    </a>
    </header>
        <div class="container sort-block">
            <div class="table-tabs">
                <a href="/admin/companies" class="table-tabs__item">
                    Сортировать по компаниям
                </a>
                <a href="/admin/vacancies" class="table-tabs__item">
                    Сортировать по вакансиям
                </a>
            </div>            
        </div>
        <div class="table table-sort-vacancies">
            <div class="container">
           
                <h1>Пополнить лицевой счет {{ user.name }}</h1> <br>
                <p><a :href="'/admin/company/'+user.id">Вернуться на страницу компании</a></p><br><br>
                <p>Сумма на лицевом счету: {{ user.balance }} {{ user.currency }}</p><br><br>
                <p>Номер документа: <input type="text" v-model.trim="user.doc_num"></p><br><br>
                <p>Дата документа: <input type="date" v-model="user.doc_date"></p><br><br>
                <p>Сумма по документу: <input type="number" v-model.trim="user.doc_amount">{{ user.currency }}</p><br><br>
                <p>Вносится на депозит:</p><br>

                <form  method="post" @submit.prevent="submitHandler">
                    <input type="number" class="" v-model.trim="amount" required>
                    <button type="submit">Пополнить</button>
                </form>
                <br>
            </div>
        </div>

        <transition name="modal">
            <popup v-if="msg" @closePopup="closePopup" custom-modal-class="success-deposit">
                <template v-slot:header>
                    <h3>Успешное пополнение</h3>
                </template>
                <template v-slot:body>
                    <h3>{{msg}}</h3>
                </template>
            </popup>
        </transition>

        <transition name="modal">
            <popup v-if="err" @closePopup="closePopup" custom-modal-class="error-deposit">
                <template v-slot:header>
                    <h3>Ошибка пополнения</h3>
                </template>
            </popup>
        </transition>
</template>

<script>
export default {
    props: ["company_id"],
    data() {
       return {
            msg: null,
            err: null,
            amount: "",            
            user: {
                balance: 0,
                doc_num: null,
                doc_date: null,
                doc_amount: null,  
            },
        }
    },
    mounted() {
        this.loadUser();
    },
    computed: {
    },
    methods: {

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

        submitHandler() {                 
            this.msg = null;
            if (this.amount > 0 && this.user.doc_amount === this.amount) {
                axios.post('/admin/company/deposit/store', {
                    user_id: this.company_id,
                    amount: this.amount,
                    doc_num: this.user.doc_num,
                    doc_date: this.user.doc_date,
                    doc_amount: this.user.doc_amount,
                })
                .then(response => {  
                    if (response.data.status === 1) {
                        this.msg = `Депозит пополнен на ${this.amount}`;      
                        this.amount = 0;
                        this.loadUser(); 
                    }
                    if (response.data.status === 0) {
                        this.err = "Ошибка";
                    }
                }).catch(error => {
                    console.log(error); 
                }).finally(() => {
                });
            }
        },

        closePopup() {
            this.msg = false;
            this.err = false;
        },
    } 
}
</script>
<template>
    <div>
Test Vacancies
<hr>
{{ vacancies }}


<hr>
{{ response }}
<hr>
<button @click="createVacancy">Create vacancy</button>
<button @click="updateVacancy">Update vacancy</button>


<hr>
Test Questionnaires


<hr>
{{ responseQuestionnaires }}
<hr>
<button @click="createQuestionaire">Create Questionaire</button>
<button @click="updateQuestionare">Update Questionaire</button>
<button @click="deleteQuestionare">Delete Questionaire</button>

<hr>

</div>

</template>
<script>
export default {
   
    data() {
       return {
        vacancies: [],
        response: "",
        responseQuestionnaires: "",      

        }
    },
    computed: {
       
     },
    mounted() {
        
        this.loadVacancies();
    },
    methods: {

        loadVacancies() {

            let params = {
               
            };   
            
        
            axios.get('/api/vacancies?api_token=', params)
            .then((response) => {
                if (response.data !== undefined) {
                    this.vacancies = response.data.map((item)=>{
                       return {
                            id: item.id,
                            name: item.name,
                            company_name: item.company_name,
                            status: item.status,
                            slug: item.slug,
                            city_id: item.city_id,
                            created: item.created,
                            date_close: item.date_close, 
                            count_candidates: item.count_candidates,
                            balance: item.balance,
                            balance_admin: item.balance_admin,
                            deposit_text: item.deposit_text,
                            deposit_in: item.deposit_in,                                   
                        }
                    });   
                }     
            });

        },

        createVacancy() 
        {
            let params = {
                
            user_id: 4,

            status: 1,
           
            publish_сompany: 1,
            name: 'Вакансия с апи 4',
            positions: 2,
                     
            city_id: 4,
            specializations: [
            {
                id: 0,               
                text: "2",
            },
            ],
            is_view_special: 0,

            gender: 1,
            age_start: '',
            age_end: '',
            

            education: "Высшее Бакалавр",
            type_education: "ИТ",

            set_is_coutch: 0,

            experiences: [
            {
                id: 0,
                text: "Обязанность 1",
                year: 1,
                degree: 1,  
                }              
            ],  

            skills: [
                {
                id: 0,
                text: "Организация исследований",
                level: 1,
                degree: 1,  
                }  
            ],

            experience_work_year_all: 1,
            experience_work_base_year_all: 1,
            count_work_people: 0,
            count_work_people_all: 0,

            languages: [
                {
                id: 0,
                text: "Английский",
                level: "А1 Начальный",
                degree: 1,  
                }  
            ],

            licences: [
             {
                id: 0,
                text: "A",
                degree: 1,  
             } 
            ],

            type_car: [
             {
                id: 0,
                text: "Мототехника",
                degree: 1,  
             } 
            ],

            type_contract: [{ id: 0, text: "Трудовой срочный" }],

            income: 3453,
            currency: "RUB",
            publish_income: 1,

            employment: ["Полный день/территория работодателя"],
            work_time: ["9-18"],
            facilitation: ["Возможности удаленной работы"],
                     
            personal: [
            {
                id: 0,
                text: "Организация работы даже в отсутствии полномочий",
            },
            ],
            };  

            axios.post('/api/vacancies?api_token=', params)
            .then((response) => {
                if (response.data !== undefined) {
                    this.response = response.data;     
                }     
            });
        },

        updateVacancy()
        {

            let id = 3;

            let params = {

            user_id: 4,

            status: 1,
           
            publish_сompany: 1,
            name: 'Вакансия с апи 1 обновлена 3',
            positions: 2,
                     
            city_id: 4,
            specializations: [
            {
                id: 0,               
                text: "2",
            },
            ],
            is_view_special: 0,

            gender: 1,
            age_start: '',
            age_end: '',
            

            education: "Высшее Бакалавр",
            type_education: "ИТ",

            set_is_coutch: 0,

            experiences: [
            {
                id: 0,
                text: "Обязанность 1",
                year: 1,
                degree: 1,  
                }              
            ],  

            skills: [
                {
                id: 0,
                text: "Организация исследований",
                level: 1,
                degree: 1,  
                }  
            ],

            experience_work_year_all: 1,
            experience_work_base_year_all: 1,
            count_work_people: 0,
            count_work_people_all: 0,

            languages: [
                {
                id: 0,
                text: "Английский",
                level: "А1 Начальный",
                degree: 1,  
                }  
            ],

            licences: [
             {
                id: 0,
                text: "A",
                degree: 1,  
             } 
            ],

            type_car: [
             {
                id: 0,
                text: "Мототехника",
                degree: 1,  
             } 
            ],

            type_contract: [{ id: 0, text: "Трудовой срочный" }],

            income: 3453,
            currency: "RUB",
            publish_income: 1,

            employment: ["Полный день/территория работодателя"],
            work_time: ["9-18"],
            facilitation: ["Возможности удаленной работы"],
                     
            personal: [
            {
                id: 0,
                /*list_category: [],
                list_children: [],*/
                /*show_other: false,*/
                text: "Организация работы даже в отсутствии полномочий",
            },
            ],

            };  

            axios.put('/api/vacancies/'+id+'?api_token=', params)
            .then((response) => {
                if (response.data !== undefined) {
                    this.response = response.data;     
                }     
            });
        },

        createQuestionaire() {


let params = {
work_id: 1,
opened: 0,
status: 1,
status_now: 0,
rate: 0,
phone: '45674567',
email: 'fghdfgh@dgsdfg.ru',
fio_f: 'Тест',
fio_i: 'Тест',
fio_o: 'Тест',

countries: ['1'],
birthday: "2000-10-10",
gender: 1,

city_id: 15,
city_match: "Готов на удаленную работу",

specializations: [
{
id: 0,
text: "52",
},
],


set_is_couch: 0,
couch_hours: 0,

education: ["Высшее Бакалавр"],
type_education: ["ИТ"],

experiences: [
{
id: 0,
text: "Тест 1",
yes_no: 1,
ready: "Тест",
year: 1, 
},
],


skills: ["Начальный"],

work_company: [
{
id: 0,
text: "Тест",
position: "Тест",
year: 1, 
month: 0, 
}
],


experience_work_year_all: 3,
experience_work_base_year_all: 0,
count_work_people: 0,
count_work_people_all: 0,

languages: [
{
id: 0,
text: "Английский",
level: "А1 Начальный",
}  
],

licences: [
{
id: 0,
text: "A",
}  
],

type_car: ["Мототехника"],
type_contract_ok: "Да",
employment_ok:"Да",
work_time_ok:"Да",

income: null,
income_ok: 1,
income_want: null,

personal: ["Умение распознавать потребности клиента"],
    
resume: "1660989235.docx",
photo: "1660989235.png",

}

          axios.post('/api/questionnaires?api_token=', params)
            .then((response) => {
                if (response.data !== undefined) {
                    this.responseQuestionnaires = response.data;     
                }     
            });
        },

        updateQuestionare() {
            let id = 4;

let params = {
work_id: 1,
opened: 0,
status: 1,
status_now: 0,
rate: 0,
phone: '7685678567',
email: 'ghjfghjfg@dgsdfg.ru',
fio_f: 'Тест',
fio_i: 'Тест 2',
fio_o: 'Тест 3',


countries: ['1'],
birthday: "2000-10-10",
gender: 1,

city_id: 15,
city_match: "Готов на удаленную работу",

specializations: [
{
id: 0,
text: "52",
},
],


set_is_couch: 0,
couch_hours: 0,

education: ["Высшее Бакалавр"],
type_education: ["ИТ"],

experiences: [
{
id: 0,
text: "Тест 1",
yes_no: 1,
ready: "Тест",
year: 1, 
},
],


skills: ["Начальный"],

work_company: [
{
id: 0,
text: "Тест",
position: "Тест",
year: 1, 
month: 0, 
}
],


experience_work_year_all: 3,
experience_work_base_year_all: 0,
count_work_people: 0,
count_work_people_all: 0,

languages: [
{
id: 0,
text: "Английский",
level: "А1 Начальный", 
}  
],

licences: [
{
id: 0,
text: "A",
}  
],

type_car: ["Мототехника"],
type_contract_ok: "Да",
employment_ok:"Да",
work_time_ok:"Да",

income: null,
income_ok: 1,
income_want: null,

personal: ["Умение распознавать потребности клиента"],
    
resume: "1660989235.docx",
photo: "1660989235.png",

}

        axios.put('/api/questionnaires/'+id+'?api_token=', params)
            .then((response) => {
                if (response.data !== undefined) {
                    this.responseQuestionnaires = response.data;     
                }     
            });
        },


        deleteQuestionare() {
            let id = 2;
            axios.delete('/api/questionnaires/'+id+'?api_token=')
            .then((response) => {
                if (response.data !== undefined) {
                    this.responseQuestionnaires = response.data;     
                }     
            }); 
        }

    } 
}
</script>
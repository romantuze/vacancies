<template>
<h2>Группы специализаций</h2>

<ul>
<li v-for="item in group_specializations" :key="item.id">{{item.id}}. {{item.text}}</li>
</ul>

<form method="POST" @submit.prevent="submitGroupHandler">
<input type="text" v-model.trim="new_group_item" required> <button>Добавить</button>
</form>

<hr>
<h2>Специализации</h2>
    
<Select2 v-model="group_specialization_select" :options="group_specializations" :settings="{  placeholder: 'Группа специализаций'  }" @select="changeSpecialization($event)"/>

<ul>
<li v-for="item in specializations" :key="item.id">{{item.id}}. {{item.text}}  <a @click="deleteItem(item.id)">удалить</a> <a @click="dialogItem(item.id, item.text)">редактировать</a></li>
</ul>

<form method="POST" @submit.prevent="submitHandler">
<textarea v-model.trim="new_item" cols="50" rows="15" required></textarea><br>
<button>Добавить</button>
<div>{{ msg }}</div>
</form>

<hr>

<div class="modal-mask" v-if="dialogEdit">
<div class="modal-wrapper">
<div class="modal-container">
<div class="modal-header">
Редактор           
</div>
<div class="modal-body">  
<div> 
<input type="text" v-model="dialogText">
<button class="modal-default-button" @click="editItem">
сохранить
</button>               
</div>
</div>
<div class="modal-footer">                           
<button class="modal-default-button" @click="dialogEdit = false">
отмена
</button>       
</div>
</div>
</div>
</div>

</template>
<script>
import Swal from 'sweetalert2';     
export default {
      data() {
       return {
        msg: '',
        dialogEdit: false,
        dialogText: '',
        dialogId: null,

        new_item: '',   
        new_group_item: '',

        group_specialization_id: null,
        group_specialization_select: null,

        group_specializations: [],
        specializations: [], 

    }
},
 mounted() {
        this.loadGroupSpecializations();
        this.loadSpecializations(0);
},

methods: {
        loadGroupSpecializations() {
            axios({
              method: 'get',
              url: '/api/group-specializations',
            })
            .then((response) => {
                if (response.data !== undefined) {
                    this.group_specializations = response.data; 
                }                       
            });
        },

        loadSpecializations(id) {
            axios({
              method: 'get',
              url: '/api/specializations/'+id,
            })
            .then((response) => {
                if (response.data !== undefined) {
                    this.specializations = response.data;
                }        
            });
        },

        changeSpecialization({id,text}) {
           if (id > 0) {
            this.group_specialization_id = id;
             this.loadSpecializations(id);                
           } 
        },

        submitGroupHandler() {
            this.msg = null;
            if (this.new_group_item !== '') {
            axios.post('/api/group-specializations/store', {
                text: this.new_group_item
            })
            .then(response => {
                  Swal.fire('Добавлено');

                 this.loadGroupSpecializations();
                 this.new_group_item = null;  
            }).catch(error => {
                console.log(error); 
            }).finally(() => {
            });
            }

        },

        storeItem(item) {
             axios.post('/api/specializations/store', {
                group_specialization_id: this.group_specialization_id,
                text: item
            })
            .then(response => {
                Swal.fire('Добавлено ' + item);

                this.loadSpecializations(0);      
                this.group_specialization_select = null;     
            }).catch(error => {
                console.log(error); 
            }).finally(() => {
            });
        },

        submitHandler() {
            this.msg = "загрузка";
            if (this.new_item !== null) {
                let new_item_str = this.new_item;
                let new_item_arr = new_item_str.split(/\r\n|\r|\n/g);
                
                for(let i = 0; i < new_item_arr.length; i++) {
                    setTimeout(()=>{
                        this.storeItem(new_item_arr[i].trim())
                    },1000);                                        
                }
                this.loadSpecializations(this.group_specialization_id);
                this.new_item = null;
            }
            this.msg = null;
        },


        deleteItem(specializationId) {
            if ( confirm('Вы действительно хотите удалить?') ) {
             axios.get('/api/specializations/destroy/'+specializationId)           
            .then(response => {     
                 Swal.fire('Специализация удалена');    
                       
                 this.loadSpecializations(0);      
                 this.group_specialization_select = null;          
            }).catch(error => {
                this.msg = "Ошибка";
                console.log(error); 
            }).finally(() => {

            });   
             }          
        },
        dialogItem(specializationId, specializationText) {
            this.dialogEdit = true;
            this.dialogId = specializationId;
            this.dialogText = specializationText;
        },
        editItem() {
            if (this.dialogText !== '' && this.dialogId !== null) {
            axios.post('/api/specializations/update', {
                text: this.dialogText,
                specialization_id: this.dialogId,
            })
            .then(response => {       
                 Swal.fire('Специализация изменена');     
                 this.loadSpecializations(0);  
                 this.group_specialization_select = null;
            
            }).catch(error => {
   
                 Swal.fire('Ошибка');   
                console.log(error); 
            }).finally(() => {

            });
            }
            this.dialogEdit = false;                            
            this.dialogText = ""; 
            this.dialogId = null;             
        },


    },
 


}
</script>

<style scoped>

.modal-mask {
  position: fixed;
  z-index: 9;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container {
  width: 90%;
  max-width: 400px;
  margin: 0px auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  height: 300px;
}

.modal-header h3 {
  margin-top: 0;
   color: #35A8D4;
  font-weight: 600;
}

.modal-body {
  margin: 20px 0;
}

.modal-default-button {
  display: block;
  margin-top: 1rem;
}

.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.5s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}


</style>
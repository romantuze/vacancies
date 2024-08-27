<template>

<ul>
<li v-for="item in items" :key="item.id">{{item.id}}. {{item.text}}  <a @click="dialogItem(item.id, item.text)">редактировать</a></li>
</ul>
<form method="POST" @submit.prevent="submitAddHandler">
<Select2 :options="items" v-model="category_items" :settings="{ 
     placeholder: 'Категория'  }" @select="changeCategory($event)"/>
<br>
<input type="text" v-model.trim="new_item" required> <button>Добавить</button>
</form>
<div v-if="msg">
{{msg}}
</div>
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
            item_type: 'personals',
            msg: null,
            items: [],
            new_item: '',
            category_id: null,
            category_items: null,
            dialogEdit: false,
            dialogText: '',
            dialogId: null,            
        }
    },
    mounted() {
            this.loadItems();
    },
    methods: {
        changeCategory({id,text}) {
           if (id > 0) {
            this.category_id = id;                          
           } 
        },
        loadItems() {
            axios({
              method: 'get',
              url: '/api/'+this.item_type,
            })
            .then((response) => {
                if (response.data !== undefined) {
                    this.items = response.data.map((item)=>{
                       return {
                            id: item.id,
                            text: item.text,
                        }
                    });        
                }     
            });
        },
        submitAddHandler() {
            this.msg = null;
            if (this.new_item !== '') {
            axios.post('/api/'+this.item_type+'/store', {
                personal_id: this.category_id,
                text: this.new_item
            })
            .then(response => {
                 
                 this.msg = "Добавлено";
                 this.loadItems();
                 this.new_item = null;  
            }).catch(error => {
                console.log(error); 
            }).finally(() => {
            });
            }
        },

        deleteItem(id) {
            this.msg = null;
            if (confirm('Удалить?')) {
                axios.post('/api/'+this.item_type+'/'+id, {
                    _method: 'DELETE'
                })
                .then(response => {
                    this.items = [];
                    this.loadItems();
                }).catch(error => {  
                    console.log(error);                   
                }).finally(() => {                    
                });
            }   
        },

        dialogItem(id,text) {
            this.dialogEdit = true;
            this.dialogId = id;
            this.dialogText = text;
        },

        editItem() {
            if (this.dialogText !== '' && this.dialogId !== null) {
                axios.post('/api/'+this.item_type+'/update', {
                    text: this.dialogText,
                    personal_id: this.dialogId,
                })
                .then(response => {       
                    Swal.fire('Классификатор изменен');     
                    this.loadItems();                    
                
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



    } 
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
<template>
    <h2>Навыки</h2>

<form action="" method="POST" @submit.prevent="submitAddHandler">

<Select2
:options="skill_list" :settings="{  placeholder: 'Категория навыка' }" v-model="category_item_id"/>
<br>
<input type="text" class="" v-model.trim="item_text" required> 
<button name="submit" value="1">Добавить</button>
</form>

<div v-if="msg">
{{msg}}
</div>
<hr>


    <h2>Редактирование</h2>

    <ul>
        <li v-for="item in skill_list" :key="item.id">
            {{ item.text }} 
            <a @click="deleteItem(item.id)" >удалить</a>    <a @click="dialogItem(item.id, item.text)">редактировать</a>
         </li>
    </ul>



<div class="modal-mask" v-if="dialogEdit">
  <div class="modal-wrapper">
    <div class="modal-container">
      <div class="modal-header">
          Редактор           
      </div>
      <div class="modal-body">  
               <div> 
                Старое значениe<br> {{ dialogTextOld }}<br>
                Новое значениe<br>
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
        msg: null,
        category_skill: "",
         
        item_text: null,     
        category_item_id: null, 

        skill_list: [{id: 0, text: "Навык"}],

        dialogEdit: false,
        dialogId: null,
        dialogText: '',
        dialogTextOld: '',

    }
},
 mounted() {  
        this.loadSkills();
    },

methods: {
    loadSkills() {
            axios({
              method: 'get',
              url: '/api/skills',
            })
            .then((response) => {
               if (response.data !== undefined) {
            
                    let arr = response.data;
                    let new_arr = [];

                    for (let i = 0; i < arr.length; i++) {
                       
                        if (arr[i].skill_id === null) {
                            new_arr.push({ id: arr[i].id, text: arr[i].text });
                        }

                        if (arr[i].children_skills !== undefined) {
                            let children_arr =  arr[i].children_skills;                           
                            if(children_arr.length > 0) {
                                for (let j = 0; j < children_arr.length; j++) {
                                   new_arr.push({
                                    id: children_arr[j].id, 
                                    text: "--- " + children_arr[j].text,
                                     });
                                }
                            }
                        } 
                       
                    } 

                    this.skill_list = [...new_arr]; 
                   
                }    
            });
        },

    submitAddHandler() {
        this.msg = null;
        
        if (this.item_text !== null) {
        axios.post('/api/skills/store', {
            skill_id: this.category_item_id,
            text: this.item_text
        })
        .then(response => {             
             
             Swal.fire('Добавлено');      
             this.loadSkills();
             this.item_text = null;         
        }).catch(error => {
            console.log(error); 
        }).finally(() => {
        });
        }
   
    },


        deleteItem(skillId) {
            if ( confirm('Вы действительно хотите удалить?') ) {
             axios.get('/api/skills/destroy/'+skillId)
            .then(response => {            
                
                 Swal.fire('Навык удален');    
                 this.loadSkills();                       
            }).catch(error => {
                
                  Swal.fire('Ошибка'); 
                console.log(error); 
            }).finally(() => {

            });   
            }         
        },
        dialogItem(skillId, skillText) {
            this.dialogEdit = true;
            this.dialogId = skillId;
            this.dialogTextOld = skillText;

        },
        editItem() {
            if (this.dialogText !== '' && this.dialogId !== null) {
            axios.post('/api/skills/update', {
                text: this.dialogText,
                skill_id: this.dialogId,
            })
            .then(response => {            
              
                Swal.fire('Навык изменен'); 
                this.loadSkills();
                        
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
  max-width: 600px;
  margin: 0px auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  height: 400px;
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
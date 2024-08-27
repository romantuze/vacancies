<template>    
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <div class="modal-header">
            <slot name="header">
              {{ trans("text.PossessionProfessionalSkills") }} <!--Владение профессиональными навыками (по уровню владения)*-->
            </slot>
          </div>
          <div class="modal-body">
            <slot name="body">             
                <div class="skill-items">                   
                   <div class="skill-items-wrap">   
                    <h2>{{this.category_skill_title}}</h2>

<div v-if="skill_main">
<div class="skill-column skill-column-1">
<ol style="margin-left: 30px;" class="skill-list-main">
  <li v-for="(skill_item) in skill_list" :key="skill_item.id">
      <strong @click="skillClick($event, skill_item)" class="skill-link-main">{{skill_item.text}}</strong>
  </li>
</ol>
</div>
</div>

<div v-if="skill_second">
<div class="skill-column skill-column-2">
<button class="skill-back" @click="back">
  {{ trans("text.BackButton") }}
</button>
<div>
<ol style="margin-left: 30px;" class="skill-list">
<li v-for="(children_skill_item) in second_skill_list" :key="children_skill_item.id">
    <div v-if="children_skill_item.skills.length > 0">
    <span @click="skillSecondClick($event, children_skill_item)" class="skill-link-parents">{{children_skill_item.text}}</span>
  </div>
 <div v-else>
    <span v-if="children_skill_item.text === 'Указать' || children_skill_item.text === 'указать' || children_skill_item.text === 'Specify' || children_skill_item.text === 'specify'">
      {{ trans("text.OtherSkill") }}
        <input type="text" v-model="other_text">
        <span @click="sendOther">{{ trans("text.SendButton") }}</span>
    </span>
   <span v-else     
    @click="$emit('closePopup',children_skill_item.text)" class="skill-link">{{children_skill_item.text}}</span> 
</div>

</li>
</ol>
</div>
</div>
</div>


<div v-if="skill_third">
<div class="skill-column skill-column-2">
<button class="skill-back" @click="back">
  {{ trans("text.BackButton") }}
</button>
<div>
<ol style="margin-left: 30px;" class="skill-list">
<li v-for="(children_skill_item) in third_skill_list" :key="children_skill_item.id">

    <span v-if="children_skill_item.text === 'Указать' || children_skill_item.text === 'указать' || children_skill_item.text === 'Specify' || children_skill_item.text === 'specify'" >
      {{ trans("text.OtherSkill") }}
     <input type="text" v-model="other_text">
     <span @click="sendOther">{{ trans("text.SendButton") }}</span>
    </span>
   <span v-else    
    @click="skillThirdClick(e,children_skill_item)"  class="skill-link">{{children_skill_item.text}}
    </span> 

</li>
</ol>
</div>
</div>
</div>
               
                     </div>
                </div>
            </slot>
          </div>
          <div class="modal-footer">
            <slot name="footer">                    
              <button class="modal-default-button" @click="$emit('closePopup')">
                {{ trans("text.CancelButton") }} 
              </button>
            </slot>
          </div>
        </div>
      </div>
    </div>
</template>
<script>
import Select2 from 'vue3-select2-component';  
export default {
  props: ['lang'],
  data() {
       return {
          skill_list: [],
          skill_main: true,
          skill_second: false,
          skill_third: false,
          second_skill_list: [],
          third_skill_list: [],
          category_skill_title: "",
          children_skills: [],          
          children_skill_title: '',
          category_skill_list: [],          
          category_skill_id: null,
          show_skill_text: false,
          skill_text: '',
          second_skill_text: '',
          other_text: '',
        }
    },
    methods: {
        sendOther() {
          if (this.other_text !== "") {
            this.$emit('closePopup',this.other_text)
          }          
        },

        skillClick(e, skill_item) {
            this.category_skill_title = e.target.textContent;
            this.skill_main = false;
            this.skill_second = true;
            this.other_text = "";
            this.second_skill_list = skill_item.children_skills.slice();
        },

        skillSecondClick(e,skill_item) {
          this.children_skill_title = skill_item.text;
          this.category_skill_title = e.target.textContent;
          this.skill_main = false;
          this.skill_second = false;
          this.skill_third = true;
          this.other_text = "";
          this.third_skill_list = skill_item.skills.slice();
        },

        skillThirdClick(e,children_skill_item) {
          let title = this.children_skill_title + " " + children_skill_item.text;
          this.$emit('closePopup', title);
        },

        back() {
          this.children_skill_title = "";
          this.category_skill_title ="";
          this.skill_main = true;
          this.skill_second = false;
          this.skill_third = false;  
        },
        
        loadSkills() {
            axios({
              method: 'get',
              url: '/api/skills/'+this.lang,
            })
            .then((response) => {
               if (response.data !== undefined) {
                    this.category_skill_list = response.data; 
                    this.skill_list = response.data; 
                }    
            });
        },
    },
    mounted() {
        this.loadSkills();
    }
}
</script>

<style>
</style>
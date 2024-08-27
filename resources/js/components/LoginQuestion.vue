<template>    
<div class="form-login-box" style="max-width: 270px; margin: 0 auto;">
<form action="#" class="form" v-if="login===false">
<div style="">
<div class="item quest1 mob">
<div class="item__title">
<span>1</span>
{{ trans('text.Login') }}
</div>
<div class="item-flex">
<div class="item__inputs flex">
<div class="item__inputs-two">                                        
<div class="item__input width270 item__input-two">
<input type="email" 
:placeholder="trans('text.EmailAddress')+'*'"
v-model.trim="form.email" 
:class="{ 'error': v$.form.email.$error }"                             
>       
<span class="invalid-feedback red-text" 
role="alert" v-if="v$.form.email.$error">
{{ trans('text.FillFieldCorrectly') }} {{ trans('text.EmailAddress') }}
</span>                                   
</div>
<div class="item__input width270 item__input-two">
<input 
id="phone"
type="tel" 
:placeholder="trans('text.CandidatePhone')+'*'"
v-model="form.phone" 
v-mask="'+############'" 
:class="{ 'error': v$.form.phone.$error }"  
>  
<span class="invalid-feedback red-text"
role="alert" v-if="v$.form.phone.$error">
{{ trans('text.FillFieldCorrectly') }} {{ trans('text.CandidatePhone') }}
</span>                                          
</div>
</div>
</div>
</div>
</div>
<div class="form__btns margin-top50 mob mob-last">
<a class="form__btns_btn blue-border" @click.stop="submitLoginHandler">
{{ trans('text.Enter') }}
</a>                          
</div>
</div>
</form>
</div>
</template>
<script>
import Swal from 'sweetalert2'
import useVuelidate  from "@vuelidate/core";
import { required, email, minLength, maxLength, sameAs } from "@vuelidate/validators";
import Select2 from 'vue3-select2-component';   

export default { 
      setup () {
        return { v$: useVuelidate() }
      },
    props: ['work_id'],
    mixins: [],
    data() {
       return {  
        login: false,    
        form: {
            phone: '',
            email: '',
        }
      }
    },
    methods: {
        submitLoginHandler() {
            this.v$.$validate(); 
            if (!this.v$.$error) {
              let data = {
                  phone: this.form.phone,
                  email: this.form.email,
                  work_id: this.work_id
              }
              axios.post('/api/question/check_user', data)
              .then((response) => {                
                  if (response.data !== undefined) {                    
                      if (response.data.status === 1) {    
                         this.$emit('loginEvent', this.form)
                      } 
                      if (response.data.status === 2) {    
                        Swal.fire(this.trans('text.ApplicationAlreadySubmitted')); 
                      }  
                      if (response.data.status === 0) {    
                         Swal.fire(this.trans('text.Error'));     
                      }                     
                  }     
              })
              .catch(function (err) {
                  Swal.fire(this.trans('text.Error'));               
              });                   
            } else {                 
              return false;
            }   
        },
    },
    validations: {
        form: {
            phone: {
              required,
              minLength: minLength(11),  
            },  
            email: {
              required,  
              email,              
            },
        }
    }
}
</script>
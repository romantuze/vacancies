<script>
import { required, requiredIf, helpers, email, minLength, maxLength, sameAs, between } from "@vuelidate/validators";

export default {

     validations: {
        form: {
            name: {
              required,
              minLength: minLength(3),  
              maxLength: maxLength(200),           
            },  
            positions: {
              required,
              
            },
            city_id: {
                required,
            },

            age_start: {                
                 between: between(1, 100)
            },

            age_end: {                
                 between: between(1, 100)
            },
        
            education: {
                required,
            },
            type_education: {
                required,
            },

            experiences: {
              $each: helpers.forEach({
                text: {
                  required,
                },
                year: {
                  between: between(0, 100)
                }
              })
            },

            skills: {
              $each: helpers.forEach({
                text: {
                  required,
                }
              })
            },  

            experience_work_year_all:  {
                required,
                between: between(0, 100)
            },

            experience_work_base_year_all: {
                between: between(0, 100)
            },

            count_work_people:  {
                required: requiredIf(function() {
                    return this.form.experience_work_base_year_all > 0;
                })
            },
            count_work_people_all: {
                required:  requiredIf(function() {
                    return this.form.experience_work_base_year_all > 0;
                })
            },

            type_contract: {
            $each: helpers.forEach({       
            text: {
              required,     
              }      
            })
            },

            income_start: {
                required,
                between: between(140, 1000000000),
            },
            income_end: {
                required,
                between: between(140, 1000000000),
            },

            currency: {
                required,
            },          
    

        }  
      },

}
</script>
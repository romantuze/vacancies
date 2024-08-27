<script>
import { required, requiredIf, helpers, email, minLength, maxLength, sameAs, between } from "@vuelidate/validators";

export default {

     validations: {

        form: {
            phone: {
              required,
              minLength: minLength(3),  
              maxLength: maxLength(200),           
            },  
            email: {
              required,  
              email,              
            },
            fio_f: {
                required,
            },
            fio_i: {
                required,
            },
            permit_countries: {
                 required,
            },
            birthday: {
                required,
            },
            gender: {
                required
            },
            city_id: {
                required,
            },


            education: {
                required,
            },
            type_education: {
                required,
            },
            skills: {
                required,
            },
            experiences: {
                $each: helpers.forEach({
                yes_no: {
                  required,
                },
                ready: {
                    required,
                }
              })
            },
            
            work_company: {
                $each: helpers.forEach({
                text: {
                  required,
                },
                position: {
                    required,
                },
                year: {
                    required,
                    between: between(0, 100)
                },
                month: {
                    required,
                    between: between(0, 12)
                },
              })
            },

            experience_work_year_all:  {
                required,
            },
            experience_work_base_year_all:  {
                required,
            },
            count_work_people:  {
                required,
            },
            count_work_people_all: {
                required,
            },

            type_contract_ok: {
                required,
            },

            employment_ok: {
                required: requiredIf(function() {
                    return this.work.employment && this.work.employment.length > 0;
                })
            },

            work_time_ok: {
                required: requiredIf(function() {                   
                    return this.work.work_time && this.work.work_time.length > 0;
                })
            },

            income_ok: {
                required: requiredIf(function() {                   
                    return this.work.publish_income === 1;
                })
            },

        },

        resume: {
            required,  
        },
        photo: {
           required,       
        },

      },

}
</script>
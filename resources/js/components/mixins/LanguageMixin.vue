<script>
import Swal from 'sweetalert2';   
export default {
methods: {
         addLanguage(e, i) {
            if (this.form.languages.length > 4) {
                Swal.fire(this.trans('text.MaximumValuesReached'));
                  return false;
            }
            if (this.form.languages[i].text === "") {
                Swal.fire(this.trans('text.PleaseFillAllFields'));
                return false;
            }
            let new_text = this.form.languages[i].text;
            let search_array = this.form.languages.filter((item) => {
            return item.text === new_text;
            });
            if (search_array.length > 1) {                
                Swal.fire(this.trans('text.PleaseFillUniqueFields'));
                return false;
            }
            let new_item = {};
            new_item.id = i + 1;
            new_item.text = "";
            new_item.level = null;
            new_item.degree = null;
            this.form.languages.push(new_item);

        },
        deleteLanguage(i) {
            if (this.form.languages.length > 1) {
                this.form.languages.splice(i, 1);              
            } else {
                this.form.languages[i].text = "";
            }  
        },        
        selectLanguages(e,i) {
            if (this.form.languages[i].level === null) { 
                if (this.lang === 'en') {
                    this.form.languages[i].level = "A1 Beginner"; 
                } else {
                    this.form.languages[i].level = "А1 Начальный"; 
                }                
            }
            if (this.form.languages[i].degree === null) { this.form.languages[i].degree = 2; }
        },
}
}
</script>
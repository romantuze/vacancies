<script>
import Swal from 'sweetalert2';       
export default {
methods: {        
        popupSkill(i) {            
            this.showModalSkill = true;
            this.indexSkill = i;
            if (this.form.skills[i].level === null) {
                if (this.lang = 'en') {
                  this.form.skills[i].level = "Elementary";
                } else {
                  this.form.skills[i].level = "Начальный";
                }   
            }
            if (this.form.skills[i].degree === null) { this.form.skills[i].degree = 2; }
        },
        closePopup() {
            this.showModalSkill = false;
        },
        closePopupSkill(skill) {
            this.showModalSkill = false;
            this.form.skills[this.indexSkill].text = skill;
        },

        addSkill(e,i) {
            if (this.form.skills.length > 4) {
                  Swal.fire(this.trans('text.MaximumValuesReached'));
                  return false;
            }
            if (this.form.skills[i].text === "") {
                Swal.fire(this.trans('text.PleaseFillAllFields'));
                return false;
            }
            let new_text = this.form.skills[i].text;
            let search_array = this.form.skills.filter((item) => {
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
            this.form.skills.push(new_item);

        },
        deleteSkill(i) {
            if (this.form.skills.length > 1) {
                this.form.skills.splice(i, 1);              
            } else {
                this.form.skills[i].text = "";
            }            
        },
}
}
</script>
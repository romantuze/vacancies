<script>
import Swal from 'sweetalert2';   
export default {
methods: {
        experienceInput(e,i) {
            if (this.form.experiences[i].year === null) { this.form.experiences[i].year = 0; }
            if (this.form.experiences[i].degree === null) { this.form.experiences[i].degree = 2; }
        },
        addExperience(e,i) {
            if (this.form.experiences.length > 4) {
                Swal.fire(this.trans('text.MaximumValuesReached'));
                 return false;
            }
            if (this.form.experiences[i].text === "") {
                Swal.fire(this.trans('text.PleaseFillAllFields'));
                return false;
            }
            let new_text = this.form.experiences[i].text;
            let search_array = this.form.experiences.filter((item) => {
                return item.text === new_text;
            });
            if (search_array.length > 1) {
                Swal.fire(this.trans('text.PleaseFillUniqueFields'));
                return false;
            }
            let new_item = {};
            new_item.id = i + 1;
            new_item.text = "";
            new_item.year = null;
            new_item.degree = null;
            this.form.experiences.push(new_item);

        },
        deleteExperience(i) {
            if (this.form.experiences.length > 1) {
                this.form.experiences.splice(i, 1);              
            } else {
                this.form.experiences[i].text = "";
            }
        },
}
}
</script>
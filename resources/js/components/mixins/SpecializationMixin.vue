<script>
import Swal from 'sweetalert2';   
export default {
methods: {
        changeSpecialization({id,text}, i) {
           if (id > 0) {
             this.loadSpecializations(id, i);   
           } 
        },

        initSpecializations() {
            if (this.form.specializations[0].text === "") {
                this.form.specializations[0].id = 0;
                this.form.specializations[0].list_group_specializations = this.list_group_specializations.slice();
                this.form.specializations[0].list_specializations = [];
                this.form.specializations[0].text = "";
                this.form.specializations[0].group = "";
            }
        },

        loadSpecializations(id, i) {
            axios({
              method: 'get',
              url: '/api/specializations/'+this.lang+'/'+id,
            })
            .then((response) => {
                if (response.data !== undefined) {
                    this.form.specializations[i].list_specializations = response.data.map((item)=>{
                        return item.text;  
                    });  
                }     
            });
        },

        addSpecialization(e,i) {
            if (this.form.specializations.length > 4) {
                  Swal.fire(this.trans('text.MaximumValuesReached'));
                  return false;
            }
            if (this.form.specializations[i].text === "") {
                Swal.fire(this.trans('text.PleaseFillAllFields'));
                return false;
            }
            let new_text = this.form.specializations[i].text;
            let search_array = this.form.specializations.filter((item) => {
            return item.text === new_text;
            });
            if (search_array.length > 1) {
                Swal.fire(this.trans('text.PleaseFillUniqueFields'));
                return false;
            }
            let new_item = {};
            new_item.id = i + 1;
            new_item.list_group_specializations = this.list_group_specializations.slice();
            new_item.list_specializations = [];
            new_item.text = "";
            this.form.specializations.push(new_item);
            e.target.style.display = "none";  
        },
}
}
</script>
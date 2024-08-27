<script>
import Swal from 'sweetalert2';     
export default {
methods: {
        initPersonals() {
            for (let i = 0; i < this.form.personal.length; i++) {
                this.form.personal[i].id = i;
                this.form.personal[i].list_category = this.personal_list_category.slice();
                this.form.personal[i].list_children = [];
                if (this.form.personal[i].text === "") {
                  this.form.personal[i].show_other= false;
                  this.form.personal[i].text = "";
                }
            }
        },

        loadPersonalsChildren(id, i) {
            axios({
              method: 'get',
              url: '/api/personal/'+this.lang+'/'+id,
            })
            .then((response) => {
                if (response.data !== undefined) {
                    this.form.personal[i].list_children = response.data.map((item)=>{
                       return item.text
                    });    
                }       
            });
        },

        changeCategoryPersonal({id, text}, i) {
            if (id > 0) {
             this.loadPersonalsChildren(id, i);   
           } 
        },

        changePersonal({id, text}, i) {       
            this.form.personal[i].show_other = false;
            this.form.personal[i].text = text;   
           if (text === "Другое" || text === "Other") {
              this.form.personal[i].text = "";   
              this.form.personal[i].show_other = true;
           } 
        },
        
        addPersonal(e,i) {
            if (this.form.personal.length > 4) {
                Swal.fire(this.trans('text.MaximumValuesReached')); 
                return false;
            }
            if (this.form.personal[i].text === "") {
                Swal.fire(this.trans('text.PleaseFillAllFields')); 
                return false;
            }
            let new_text = this.form.personal[i].text;
            let search_array = this.form.personal.filter((item) => {
            return item.text === new_text;
            });
            if (search_array.length > 1) {
                Swal.fire(this.trans('text.PleaseFillUniqueFields'));
                return false;
            }
            let new_item = {};
            new_item.id = i + 1;
            new_item.list_category = this.personal_list_category.slice();
            new_item.list_children = [];
            new_item.show_other = false;
            new_item.text = "";
            new_item.text_category = "";
            new_item.text_children = "";
            this.form.personal.push(new_item);  
        },
        deletePersonal(i) {
            if (this.form.personal.length > 1) {
                this.form.personal.splice(i, 1);              
            } else {
                this.form.personal[i].text = "";
                this.form.personal[i].text_category = "";
                this.form.personal[i].text_children = "";
            }              
        },
}

}

</script>
<script>
import Swal from 'sweetalert2';    
export default {
methods: {

        incomeInput(e) {
            if (this.form.currency === null) { this.form.currency = "RUB"; }
        },        

        selectLicences(e,i) {
            if (this.form.licences[i].degree === null) { this.form.licences[i].degree = 2; }
        },

        selectTypeCar(e) {
            if (this.form.type_car[0].degree === null) { this.form.type_car[0].degree = 2; }
        },
        
        addEducation(e,i) {           
            let count = this.educationsCount[this.educationsCount.length - 1];
            if((this.form.education[i] == undefined) || (this.form.type_education[i] == undefined)) {
                return false;
            }          
            count++;
            if (count > 4) {
                return false;
            }      
            this.educationsCount.push(count);
            e.target.style.display = "none";
        },

        addWorkCompany(e,i) {

            if (this.form.work_company.length > 4) {    
                Swal.fire(this.trans('text.MaximumValuesReached'));
                return false;
            }
            if (this.form.work_company[i].text === "") {
                Swal.fire(this.trans('text.PleaseFillAllFields'));
                return false;
            }
            if (this.form.work_company[i].position === "") {
                Swal.fire(this.trans('text.PleaseFillAllFields'));
                return false;
            }
            
            if (this.form.work_company[i].month > 12) {
                Swal.fire('Заполните правильно поле месяц');
                return false;
            }
            let new_item = {};
            new_item.id = i + 1;
            new_item.text = "";
            new_item.position = "";
            new_item.year = null; 
            new_item.month = null;  

            this.form.work_company.push(new_item);
            /*e.target.style.display = "none";  */ 

        },

        deleteWorkCompany(i) {
            if (this.form.work_company.length > 1) {
                this.form.work_company.splice(i, 1);              
            } else {
                this.form.work_company[i].text = "";
                this.form.work_company[i].position = "";
                this.form.work_company[i].year = null; 
                this.form.work_company[i].month = null;  
            }  
        },       
   

        addLicences(e,i) {
            if (this.form.licences.length > 4) {
                Swal.fire(this.trans('text.MaximumValuesReached'));
                  return false;
            }
            if (this.form.licences[i].text === "") {
                Swal.fire(this.trans('text.PleaseFillAllFields'));
                return false;
            }
            let new_text = this.form.licences[i].text;
            let search_array = this.form.licences.filter((item) => {
            return item.text === new_text;
            });
            if (search_array.length > 1) {
                Swal.fire(this.trans('text.PleaseFillUniqueFields'));
                return false;
            }
            let new_item = {};
            new_item.id = i + 1;
            new_item.text = "";
            new_item.degree = null;
            this.form.licences.push(new_item);
           /* e.target.style.display = "none";    */
        },
        deleteLicences(i) {
            if (this.form.licences.length > 1) {
                this.form.licences.splice(i, 1);              
            } else {
                this.form.licences[i] = "";
            }              
        },

        addContract(e,i) {
            if (this.form.type_contract.length > 4) {
                Swal.fire(this.trans('text.MaximumValuesReached'));
                  return false;
            }
            if (this.form.type_contract[i].text === "") {
                Swal.fire(this.trans('text.PleaseFillAllFields'));
                return false;
            }
            let new_text = this.form.type_contract[i].text;
            let search_array = this.form.type_contract.filter((item) => {
                return item.text === new_text;
            });
            if (search_array.length > 1) {
                Swal.fire(this.trans('text.PleaseFillUniqueFields'));
                return false;
            }
            let new_item = {};   
            new_item.id = i + 1;
            new_item.text = "";       
            this.form.type_contract.push(new_item);
            /*e.target.style.display = "none";    */
        },
        deleteContract(i) {
            if (this.form.type_contract.length > 1) {
                this.form.type_contract.splice(i, 1);              
            } else {
                this.form.type_contract[i] = "";
            }  
        },
        addEmployment(e,i) {
            if (this.form.employment.length > 4) {
                Swal.fire(this.trans('text.MaximumValuesReached'));
                return false;
            }
            if (this.form.employment[i] === "") {
                Swal.fire(this.trans('text.PleaseFillAllFields'));
                return false;
            }
            let new_text = this.form.employment[i];
            let search_array = this.form.employment.filter((item) => {
            return item === new_text;
            });
            if (search_array.length > 1) {
                Swal.fire(this.trans('text.PleaseFillUniqueFields'));
                return false;
            }
            let new_item = "";          
            this.form.employment.push(new_item);
            /*e.target.style.display = "none"; */   
        },
        deleteEmployment(i) {
            if (this.form.employment.length > 1) {
                this.form.employment.splice(i, 1);              
            } else {
                this.form.employment[i] = "";
            }  
            
        },
        addWorkTime(e,i) {
            if (this.form.work_time.length > 4) {
                Swal.fire(this.trans('text.MaximumValuesReached'));
                  return false;
            }
            if (this.form.work_time[i] === "") {
                Swal.fire(this.trans('text.PleaseFillAllFields'));
                return false;
            }
            let new_text = this.form.work_time[i];
            let search_array = this.form.work_time.filter((item) => {
            return item === new_text;
            });
            if (search_array.length > 1) {
                Swal.fire(this.trans('text.PleaseFillUniqueFields'));
                return false;
            }
            let new_item = "";          
            this.form.work_time.push(new_item);
           /* e.target.style.display = "none";  */  
        },
        deleteWorkTime(i) {
            if (this.form.work_time.length > 1) {
                this.form.work_time.splice(i, 1);              
            } else {
                this.form.work_time[i] = "";
            }              
           
        },
        addFacilitation(e,i) {
            if (this.form.facilitation.length > 4) {
                Swal.fire(this.trans('text.MaximumValuesReached'));
                  return false;
            }
            if (this.form.facilitation[i] === "") {
                Swal.fire(this.trans('text.PleaseFillAllFields'));
                return false;
            }
            let new_text = this.form.facilitation[i];
            let search_array = this.form.facilitation.filter((item) => {
            return item === new_text;
            });
            if (search_array.length > 1) {
                Swal.fire(this.trans('text.PleaseFillUniqueFields'));
                return false;
            }
            let new_item = "";          
            this.form.facilitation.push(new_item);
            /*e.target.style.display = "none"; */ 
        },
        deleteFacilitation(i) {
            if (this.form.facilitation.length > 1) {
                this.form.facilitation.splice(i, 1);              
            } else {
                this.form.facilitation[i] = "";
            }             
        },

        addTypeCar(e,i) {
            if (this.form.type_car.length > 4) {
                Swal.fire(this.trans('text.MaximumValuesReached'));
                  return false;
            }
            if (this.form.type_car[i] === "") {
                Swal.fire(this.trans('text.PleaseFillAllFields'));
                return false;
            }
            let new_text = this.form.type_car[i];
            let search_array = this.form.type_car.filter((item) => {
            return item === new_text;
            });
            if (search_array.length > 1) {
                Swal.fire(this.trans('text.PleaseFillUniqueFields'));
                return false;
            }
            let new_item = "";          
            this.form.type_car.push(new_item);
            /*e.target.style.display = "none"; */ 
        }
 
}
}
</script>

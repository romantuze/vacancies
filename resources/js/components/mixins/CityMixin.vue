<script>
import Swal from 'sweetalert2';   
export default {
methods: {

        addCountry(e,i) {
            if (this.form.permit_countries[i] === "") {
                Swal.fire(this.trans('text.PleaseFillAllFields'));
                return false;
            }
            let new_text = this.form.permit_countries[i];
            let search_array = this.form.permit_countries.filter((item) => {
            return item === new_text;
            });
            if (search_array.length > 1) {
                Swal.fire(this.trans('text.PleaseFillUniqueFields'));
                return false;
            }
            if (this.form.permit_countries.length > 2) {
                Swal.fire(this.trans('text.MaximumValuesReached'));
                return false;
            }
            let new_item = "";          
            this.form.permit_countries.push(new_item);
            e.target.style.display = "none";    
        },

        loadRegions(id) {
            setTimeout(() => {
               //console.log('axios request regions');
            }, 200);

            axios({
              method: 'get',
              url: '/api/countries/'+id+'/regions',
            })
            .then((response) => {
                if (response.data !== undefined) {
                     //console.log('axios request regions done');

                    this.regions = response.data.map((item)=>{
                       return {
                            id: item.id,
                            text: item.text,
                        }
                    });  
                }  
            });
        }, 
        loadCities(id) {
            setTimeout(() => {
               //console.log('axios request cities');
            }, 200);

             axios({
              method: 'get',
              url: '/api/region/'+id+'/cities',
            })
            .then((response) => {
                if (response.data !== undefined) {
                    this.cities = response.data.map((item)=>{
                        return item.text;  
                    });    
                }   
            });
        },      

        changeCountry({id, text}) {
            if (id > 0) {
                this.loadRegions(id);                
            }  
            this.region = null;
            this.form.city_id = null;          
        },

        changeRegion({id, text}) {
            if (id > 0) {
                this.loadCities(id);
            }            
            this.form.city_id = null;                  
        },

        changeCityCandidate({id, text}) {
            if (this.work.city_id !== undefined && this.form.city_id !== undefined) {
                if (this.work.city_id !== text) {
                    this.show_city_match = true;
                } 
                if (this.work.city_id === text) {
                    this.show_city_match = false;
                }               
            }     
        }


}
}
</script>
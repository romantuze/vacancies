<template>

<div v-if="msg">
{{msg}}
</div>

<h2>Страны</h2>

<ul>
    <li v-for="item in countries" :key="item.id">{{ item.text }} </li>
</ul>

<h3>Добавить страну</h3>
<form method="POST" @submit.prevent="submitCountryHandler">
<input type="text" class="" v-model.trim="newCountry" required> <button>Добавить</button>
</form>

<hr>
    <h2>Регионы</h2>

<h3>Добавить регион</h3>
<form method="POST" @submit.prevent="submitRegionHandler">

<Select2 :options="countries" :settings="{  placeholder: 'Страна'  }" v-model="country_id"  @select="changeCountry($event)" />
<br>
<input type="text" class="" v-model.trim="newRegion" required> <button>Добавить</button>
</form>

<hr>
    <h2>Города</h2>

<h3>Добавить город</h3>
<form method="POST" @submit.prevent="submitCityHandler">

<Select2 :options="countries" :settings="{  placeholder: 'Страна'  }" v-model="country_id" @select="changeCountry($event)" /><br>
<Select2 :options="regions" :settings="{  placeholder: 'Регион'  }" v-model="region_id" @select="changeRegion($event)"/><br>

<textarea v-model.trim="newCity" cols="50" rows="15" required></textarea><br>

<button>Добавить</button>
</form>

<div>{{ msg }}</div>

</template>
<script>
import Swal from 'sweetalert2';        
export default {
      data() {
       return {
        country: '', 
        region: '',   
        city: '',       
        newCountry: '',
        country_id: '',
        region_id: '',
        newRegion: '',
        newCity: '',
        countries: [],
        regions: [],        
        cities: [],
        msg: '',
    }
},
 mounted() {
        this.loadCountries();
        this.loadRegions(1);
},

methods: {
    changeCountry({id,text}) {
        if (id > 0) {
            this.country_id = id;   
            this.loadRegions(id);                       
        } 
    },
    changeRegion({id,text}) {
        if (id > 0) {
            this.region_id = id;                          
        }  
    },
    submitCityHandler() {
            this.msg = null;
            if (this.newCity !== '' && this.region_id !== '') {            

            let new_item_str = this.newCity;
            let new_item_arr = new_item_str.split(/\r\n|\r|\n/g);    

            for(let i = 0; i < new_item_arr.length; i++) {
                this.msg = "загрузка";
                    setTimeout(()=>{

                        axios.post('/api/cities/store', {
                            text: new_item_arr[i].trim(),
                            region_id: this.region_id,
                        })
                        .then(response => {
                            this.msg = "Добавлен город "+new_item_arr[i].trim();                                
                        
                        }).catch(error => {
                            console.log(error); 
                        }).finally(() => {
                        });
                        
                    }, 1000);                                        
            }
                     
            this.newCity = ""; 

            }
    },
    submitRegionHandler() {
            this.msg = null;
            if (this.newRegion !== '' && this.country_id !== '') {
            axios.post('/api/regions/store', {
                text: this.newRegion,
                country_id: this.country_id,
            })
            .then(response => {
                Swal.fire("Добавлен регион "+this.newRegion);       
                 this.loadCountries(); 
                 this.loadRegions(this.country_id);                      
                 this.newRegion = ""; 

            }).catch(error => {
                console.log(error); 
            }).finally(() => {
            });
            }
    },    
    submitCountryHandler() {
            this.msg = null;
            if (this.newCountry !== '') {
            axios.post('/api/countries/store', {
                text: this.newCountry
            })
            .then(response => {
                 Swal.fire("Добавлена страна "+this.newCountry);    
                 this.loadCountries();                 
                 this.newCountry = ""; 

            }).catch(error => {
                console.log(error); 
            }).finally(() => {
            });
            }
    },

    loadCountries() {
            axios({
              method: 'get',
              url: '/api/countries/all',
            })
            .then((response) => {
                if (response.data !== undefined) {
                    this.countries = response.data.map((item)=>{
                       return {
                            id: item.id,
                            text: item.text,
                        }
                    });        
                }     
            });
    },

    loadRegions(id) {
            axios({
              method: 'get',
              url: '/api/countries/'+id+'/regions',
            })
            .then((response) => {
                if (response.data !== undefined) {
                    this.regions = response.data.map((item)=>{
                       return {
                            id: item.id,
                            text: item.text,
                        }
                    });  
                }                
            });
    },
    },   
}
</script>
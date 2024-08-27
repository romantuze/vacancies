<template>

<div v-if="msg">
{{msg}}
</div>

<Select2 :options="countries" :settings="{  placeholder: 'Страна'  }" v-model="country_id" @select="changeCountry($event)" /><br>
<Select2 :options="regions" :settings="{  placeholder: 'Регион'  }" v-model="region_id" @select="changeRegion($event)"/><br>

<hr>
<h2>Страны</h2>

<ul>
<li v-for="item in countries" :key="item.id">{{ item.text }} 
<a @click="deleteItemCountry(item.id)">удалить</a> <a @click="dialogItemCountry(item.id, item.text)">редактировать</a></li>
</ul>

<hr>
<h2>Регионы</h2>

<ul>
<li v-for="item in regions" :key="item.id">{{ item.text }} <a @click="deleteItemRegion(item.id)">удалить</a> <a @click="dialogItemRegion(item.id, item.text)">редактировать</a></li>
</ul>

<hr>
<h2>Города</h2>

<ul>
<li v-for="item in cities" :key="item.id">{{ item.text }} <a @click="deleteItemCity(item.id)">удалить</a> <a @click="dialogItemCity(item.id, item.text)">редактировать</a></li>
</ul>


<div class="modal-mask" v-if="dialogEditCountry">
  <div class="modal-wrapper">
    <div class="modal-container">
      <div class="modal-header">
          Редактор           
      </div>
      <div class="modal-body">  
               <div> 
                <input type="text" v-model="dialogTextCountry">
                <button class="modal-default-button" @click="editItemCountry">
                  сохранить
                </button>               
                 </div>
      </div>
      <div class="modal-footer">                           
          <button class="modal-default-button" @click="dialogEditCountry = false">
            отмена
          </button>       
      </div>
    </div>
  </div>
</div>

<div class="modal-mask" v-if="dialogEditRegion">
  <div class="modal-wrapper">
    <div class="modal-container">
      <div class="modal-header">
          Редактор           
      </div>
      <div class="modal-body">  
               <div> 
                <input type="text" v-model="dialogTextRegion">
                <button class="modal-default-button" @click="editItemRegion">
                  сохранить
                </button>               
                 </div>
      </div>
      <div class="modal-footer">                       
          <button class="modal-default-button" @click="dialogEditRegion = false">
            отмена
          </button> 
      </div>
    </div>
  </div>
</div>

<div class="modal-mask" v-if="dialogEditCity">
  <div class="modal-wrapper">
    <div class="modal-container">
      <div class="modal-header">
          Редактор           
      </div>
      <div class="modal-body">  
               <div> 
                <input type="text" v-model="dialogTextCity">
                <button class="modal-default-button" @click="editItemCity">
                  сохранить
                </button>               
                 </div>
      </div>
      <div class="modal-footer">                            
          <button class="modal-default-button" @click="dialogEditCity = false">
            отмена
          </button>        
      </div>
    </div>
  </div>
</div>
</template>
<script>
import Swal from 'sweetalert2';     
export default {
      data() {
       return {

        dialogEditCountry: false,
        dialogTextCountry: "",
        dialogIdCountry: null,

        dialogEditRegion: false,
        dialogTextRegion: "",
        dialogIdRegion: null,

        dialogEditCity: false,
        dialogTextCity: "",
        dialogIdCity: null,

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

    }
},
 mounted() {
        this.loadCountries();
        this.loadRegions(0);
    },

methods: {
    dialogItemCountry(countryId, countryText) {
        this.dialogEditCountry = true;
        this.dialogIdCountry = countryId;
        this.dialogTextCountry = countryText;
    },

    dialogItemRegion(regionId, regionText) {
        this.dialogEditRegion = true;
        this.dialogIdRegion = regionId;
        this.dialogTextRegion = regionText;
    },

    dialogItemCity(cityId, cityText) {
        this.dialogEditCity = true;
        this.dialogIdCity = cityId;
        this.dialogTextCity = cityText;
    },


    deleteItemCountry(countryId) {
      if ( confirm('Вы действительно хотите удалить?') ) {
        axios.get('/api/countries/destroy/'+countryId)
        .then(response => {  
             Swal.fire(response.data.msg);              
             
             this.loadCountries();   
             this.country_id = '';
             this.region_id = '';                         
        }).catch(error => {
            Swal.fire('Ошибка'); 
            console.log(error); 
        }).finally(() => {

        });
      }
    },

    deleteItemRegion(regionId) {
      if ( confirm('Вы действительно хотите удалить?') ) {
         axios.get('/api/regions/destroy/'+regionId)
        .then(response => {    
             Swal.fire(response.data.msg);            
             
             this.loadCountries();
              this.loadRegions(0);
             this.country_id = '';
             this.region_id = '';                           
        }).catch(error => {
           Swal.fire('Ошибка'); 
            console.log(error); 
        }).finally(() => {

        });
      }
    },

    deleteItemCity(cityId) {
      if ( confirm('Вы действительно хотите удалить?') ) {
         axios.get('/api/cities/destroy/'+cityId)
        .then(response => {    
              Swal.fire(response.data.msg);              
             
             this.loadCountries(); 
             this.loadCities(0);  
             this.country_id = '';
             this.region_id = '';                          
        }).catch(error => {
            Swal.fire('Ошибка'); 
            console.log(error); 
        }).finally(() => {

        });
      }
    },


    editItemCountry() {
        if (this.dialogTextCountry !== '' && this.dialogIdCountry !== null) {
        axios.post('/api/countries/update', {
            text: this.dialogTextCountry,
            country_id: this.dialogIdCountry,
        })
        .then(response => {
            Swal.fire("Страна изменена " + this.dialogTextCountry);             
            
             this.loadCountries();  
             this.country_id = '';
             this.region_id = '';                            
        }).catch(error => {
            Swal.fire('Ошибка'); 
            console.log(error); 
        }).finally(() => {

        });
        }
        this.dialogEditCountry = false;                            
        this.dialogTextCountry = ""; 
        this.dialogIdCountry = null; 
    },


    editItemRegion() {
        if (this.dialogTextRegion !== '' && this.dialogIdRegion !== null) {
        axios.post('/api/regions/update', {
            text: this.dialogTextRegion,
            region_id: this.dialogIdRegion,
        })
        .then(response => { 
              Swal.fire("Регион изменен " + this.dialogTextRegion);                 
                 
             this.loadRegions(0);
             this.country_id = '';
             this.region_id = '';                   
        }).catch(error => {
           Swal.fire('Ошибка'); 
            console.log(error); 
        }).finally(() => {

        });
        }
        this.dialogEditRegion = false;                            
        this.dialogTextRegion = ""; 
        this.dialogIdRegion = null; 
    },

    editItemCity() {
        if (this.dialogTextCity !== '' && this.dialogIdCity !== null) {
        axios.post('/api/cities/update', {
            text: this.dialogTextCity,
            city_id: this.dialogIdCity,
        })
        .then(response => {            
            
             Swal.fire("Город изменен " + this.dialogTextCity);             
             this.loadCities(0);  
             this.country_id = '';
             this.region_id = '';          
        }).catch(error => {
            Swal.fire('Ошибка'); 
            console.log(error); 
        }).finally(() => {

        });
        }
        this.dialogEditCity = false;                            
        this.dialogTextCity = ""; 
        this.dialogIdCity = null; 
    },


    changeCountry({id,text}) {
        if (id > 0) {
            this.country_id = id;   
            this.loadRegions(id);                       
        } 
    },
    changeRegion({id,text}) {
        if (id > 0) {
            this.region_id = id;   
            this.loadCities(id);                       
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


    loadCities(id) {
          axios({
              method: 'get',
              url: '/api/region/'+id+'/cities',
            })
            .then((response) => {
                if (response.data !== undefined) {
                    this.cities = response.data.map((item)=>{
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

<style scoped>
.modal-mask {
  position: fixed;
  z-index: 9;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container {
  width: 90%;
  max-width: 400px;
  margin: 0px auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  height: 300px;
}

.modal-header h3 {
  margin-top: 0;
   color: #35A8D4;
  font-weight: 600;
}

.modal-body {
  margin: 20px 0;
}

.modal-default-button {
  display: block;
  margin-top: 1rem;
}

.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.5s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}
</style>
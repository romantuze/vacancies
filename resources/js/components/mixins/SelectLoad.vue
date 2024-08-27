<script>
export default {
methods: {

        loadSelects() {
           axios({
              method: 'get',
              url: '/api/selects/'+this.lang,
            })
            .then((response) => {
                if (response.data !== undefined) {

                    this.degree_list = response.data.degree.map((item)=>{
                       //return item.text
                       return {
                                id: item.id,
                                text: item.text,
                            }
                    }); 

                    if (response.data.country !== undefined) {
                        this.countries = response.data.country.map((item)=>{
                           return {
                                id: item.id,
                                text: item.text,
                            }
                        });   
                        this.question_countries = response.data.country.map((item)=>{
                           return  item.text
                        });        
                    }    

                    if (response.data.group_specialization !== undefined) {
                      this.list_group_specializations = response.data.group_specialization.map((item)=>{
                       return {
                            id: item.id,
                            text: item.text,
                        }
                      });         
                      this.initSpecializations();
                    }                   

                    this.type_car_list = response.data.car.map((item)=>{
                       return item.text
                    }); 

                    this.licences_list = response.data.car_licence.map((item)=>{
                       return item.text 
                    }); 

                    this.employments_list = response.data.employment.map((item)=>{
                       return item.text
                    });

                    this.facilitation_list = response.data.facilitation.map((item)=>{
                        return item.text
                    });

                    /*this.currency_list = response.data.currency.map((item)=>{
                        return item.text
                    });*/

                    this.type_contract_list = response.data.type_contract.map((item)=>{
                        return item.text
                    });
                    this.educations_list = response.data.education.map((item)=>{
                        return item.text
                    });

                    this.type_educations_list = response.data.type_education.map((item)=>{
                        return item.text
                    });

                    this.skill_levels_list = response.data.skill_level.map((item)=>{
                       return item.text;                        
                    }); 

                    this.languages_list = response.data.language.map((item)=>{
                       return item.text 
                    });

                    this.language_levels_list = response.data.language_level.map((item)=>{
                       return item.text 
                    });

                    if (response.data.personal !== undefined) {
                        this.personal_list_category = response.data.personal;
                        this.initPersonals();
                    }    
                }       
            });
        },

        loadCurrencies() {
            axios({
              method: 'get',
              url: '/api/currencies/'+this.user_id,
            })
            .then((response) => {
                if (response.data !== undefined) {
                    this.currency_list = response.data;
                }       
            });
        },

        loadShopDegrees() {
            axios({
              method: 'get',
              url: '/api/degrees/'+this.lang,
            })
            .then((response) => {
                if (response.data !== undefined) {
                    this.degree_list = response.data.map((item)=>{
                       return  item.text
                    });
                }       
            });
        },

       /* loadCars() {
            setTimeout(() => {
               //console.log('axios request cars');
            }, 100);
            axios({
              method: 'get',
              url: '/api/cars',
            })
            .then((response) => {
                if (response.data !== undefined) {
                    this.type_car_list = response.data.map((item)=>{
                       return item.text 
                    });    
                }       
            });
        },*/


        /*loadCarLicences() {
            setTimeout(() => {
               //console.log('axios request licences');
            }, 200);
            axios({
              method: 'get',
              url: '/api/car-licences',
            })
            .then((response) => {
                if (response.data !== undefined) {
                    this.licences_list = response.data.map((item)=>{
                        return item.text 
                    });    
                }       
            });
        },*/

        /*loadEmployments() {
            setTimeout(() => {
               //console.log('axios request employments');
            }, 100);
            axios({
              method: 'get',
              url: '/api/employments',
            })
            .then((response) => {
                if (response.data !== undefined) {
                    this.employments_list = response.data.map((item)=>{
                       return item.text
                    });
                }       
            });
        },*/



        /*loadFacilitations() {
            setTimeout(() => {
               //console.log('axios request facilitations');
            }, 100);

            axios({
              method: 'get',
              url: '/api/facilitations',
            })
            .then((response) => {
                if (response.data !== undefined) {
                    this.facilitation_list = response.data.map((item)=>{
                        return item.text
                    });
                }       
            });
        },*/


        /*loadTypeContracts() {
            setTimeout(() => {
               //console.log('axios request contracts');
            }, 100);

            axios({
              method: 'get',
              url: '/api/type-contracts',
            })
            .then((response) => {
                if (response.data !== undefined) {
                    this.type_contract_list = response.data.map((item)=>{
                        return item.text
                    });
                }       
            });
        },*/

        /*loadEducations() {
            setTimeout(() => {
               //console.log('axios request educations');
            }, 200);

            axios({
              method: 'get',
              url: '/api/educations',
            })
            .then((response) => {
                if (response.data !== undefined) {
                    this.educations_list = response.data.map((item)=>{
                        return item.text
                    });
                }       
            });
        },*/

        /*loadTypeEducations() {
            setTimeout(() => {
               //console.log('axios request type educations');
            }, 100);

            axios({
              method: 'get',
              url: '/api/type-educations',
            })
            .then((response) => {
                if (response.data !== undefined) {
                    this.type_educations_list = response.data.map((item)=>{
                        return item.text
                    });
                }       
            });
        },*/

        /*loadSkillLevels() {
            setTimeout(() => {
               //console.log('axios request skill levels');
            }, 200);
            
            axios({
              method: 'get',
              url: '/api/skill-levels',
            })
            .then((response) => {
                if (response.data !== undefined) {
                    this.skill_levels_list = response.data.map((item)=>{
                       return item.text;                        
                    }); 
                }       
            });
        },*/

}
}
</script>

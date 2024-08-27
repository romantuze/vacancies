<template>
<ul>
<li v-for="item in items" :key="item.id">{{item.id}}. {{item.text}}</li>
</ul>
<form method="POST" @submit.prevent="submitAddHandler">
<input type="text" class="" v-model.trim="new_item" required> <button>Добавить</button>
</form>
<div v-if="msg">
{{msg}}
</div>
<hr>
</template>
<script>
export default {
    data() {
       return {
        item_type: 'degrees',
        msg: null,
        items: [],
        new_item: '',
        }
    },
    mounted() {
            this.loadItems();
    },
    methods: {
        loadItems() {
            axios({
              method: 'get',
              url: '/api/'+this.item_type,
            })
            .then((response) => {
                if (response.data !== undefined) {
                    this.items = response.data.map((item)=>{
                       return {
                            id: item.id,
                            text: item.text,
                        }
                    });        
                }     
            });
        },
        submitAddHandler() {
            this.msg = null;
            if (this.new_item !== '') {
            axios.post('/api/'+this.item_type+'/store', {
                text: this.new_item
            })
            .then(response => {
                 console.log(response.data);
                 this.msg = "Добавлено";
                 this.loadItems();
                 this.new_item = null;  
            }).catch(error => {
                console.log(error); 
            }).finally(() => {
            });
            }
        },
    } 
}
</script>
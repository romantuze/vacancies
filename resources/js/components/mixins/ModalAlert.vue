<script>
export default {
      data() {
       return {
            modal: {
                show: false,
                title: "Test",    
            },
        }
    },
    methods: {
        modalAlertOpen(title) {
           this.modal.title = title;
           this.modal.show = true;
        },
        modalAlertClose() {
            this.modal.show = false;
        },
    }
}
</script>
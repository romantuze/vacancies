<template>    
    <div class="modal-mask">
        <div class="modal-wrapper">
            <div :class="['modal-container', customModalClass]">
                <div class="modal-header">
                    <slot name="header"> 
                    </slot>
                </div>
                <div class="modal-body">
                    <slot name="body">
                    </slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer">
                        <button class="modal-button" @click="$emit('closePopup')">
                           Закрыть
                        </button>
                    </slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        customModalClass: String // Receive the custom class as a prop
    }
}
</script>

<style>
</style>
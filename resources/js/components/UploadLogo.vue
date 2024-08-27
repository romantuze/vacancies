<template>
    <div>
        <div class="img-input tooltip-wrap">
            <div v-if="logo">
                <label for="header__top_logo-id">
                    <div class="logo-image" :style="{ backgroundImage: 'url('+logo+')' }"></div>
                </label>
            </div>
            <div class="header__top_logo" v-else>
                <div class="header__top_logo-title">
                    {{ trans("text.LogoCompany") }}
                </div>
                <label for="header__top_logo-id">
                    <a class="header__top_logo-link">
                        {{ trans("text.UploadLogoFile") }}
                    </a>
                </label>
            </div>
            <div class="tooltip">
                <span class="text-medium"> {{ trans("text.UploadLogoFile") }}</span>
                <div class="text text-sm">
                    <ul>
                        <li>{{ trans('text.image-format') }}</li> <!-- Изображения формата .png, .jpg, .jpeg -->
                        <li>{{ trans('text.max-image-size-2') }}</li> <!-- Максимальный размер 2 МБ -->
                    </ul>
                </div>
            </div>
            <input id="header__top_logo-id" type="file" accept=".png,.jpg,.jpeg" class="header__top_logo-input" @change="onChange($event)">
        </div>
    </div>
</template>
<script>
import Swal from 'sweetalert2'; 
export default {
    props: ['user_id', 'logo'],
    data() {
        return {       
            name: '',
            file: '',
            success: ''
        }
    },
    methods: {
        onChange(e) {
            this.file = e.target.files[0];
            this.formSubmit();
        },
        formSubmit() {
            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }
            let data = new FormData();
            data.append('user_id', this.user_id);
            data.append('file', this.file);
            axios.post('/upload/image', data, config)
                .then(function (response) {                       
                    if (response.data !== undefined) {
                        Swal.fire(response.data.success);
                        location.href='/home';
                    }
                })
                .catch(function (error) {
                    Swal.fire(trans("text.ErrorUploadLogo"));
                });
        }
    },
}
</script>

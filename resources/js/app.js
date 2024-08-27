require('./bootstrap');


import { createApp } from 'vue'

import CurrentVersion from './components/admin/CurrentVersion.vue';
import CreateVacancy from './components/CreateVacancy.vue';
import CreateQuestion from './components/CreateQuestion.vue';
import LoginQuestion from './components/LoginQuestion.vue';
import CompanyHome from './components/CompanyHome.vue';
import CompanyShop from './components/CompanyShop.vue';
import CompanyList from './components/admin/CompanyList.vue';
import WorkList from './components/admin/WorkList.vue';
import VacancyList from './components/admin/VacancyList.vue';
import CandidateList from './components/admin/CandidateList.vue';
import TestApi from './components/admin/TestApi.vue';
import EditCities from './components/admin/selects/EditCities.vue';
import EditRegions from './components/admin/selects/EditRegions.vue';
import EditEducations from './components/admin/selects/EditEducations.vue';
import EditTypeEducations from './components/admin/selects/EditTypeEducations.vue';
import EditSpecializations from './components/admin/selects/EditSpecializations.vue';
import EditSkills from './components/admin/selects/EditSkills.vue';
import EditLanguages from './components/admin/selects/EditLanguages.vue';
import EditCars from './components/admin/selects/EditCars.vue';
import EditCarLicences from './components/admin/selects/EditCarLicences.vue';
import EditLanguageLevels from './components/admin/selects/EditLanguageLevels.vue';
import EditEmployments from './components/admin/selects/EditEmployments.vue';
import EditDegrees from './components/admin/selects/EditDegrees.vue';
import EditFacilitations from './components/admin/selects/EditFacilitations.vue';
import EditSkillLevels from './components/admin/selects/EditSkillLevels.vue';
import EditTypeContracts from './components/admin/selects/EditTypeContracts.vue';
import EditCurrencies from './components/admin/selects/EditCurrencies.vue';
import EditPersonals from './components/admin/selects/EditPersonals.vue';
import CreateDeposit from './components/admin/CreateDeposit.vue';
import UploadLogo from './components/UploadLogo.vue';
import PopupModal from './components/PopupModal.vue';
import Popup from './components/Popup.vue';
import PopupPolicy from './components/PopupPolicy.vue';


import Select2 from 'vue3-select2-component';
import VueTheMask from 'vue-the-mask'

import VIntersection from './components/directives/VIntersection.js'


const token = document.head.querySelector('meta[name="csrf-token"]');
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;



const app = createApp({});

app.use(VueTheMask)

app.directive('intersection', VIntersection);

app.component('current-version', CurrentVersion);
app.component('create-vacancy', CreateVacancy);
app.component('create-question', CreateQuestion);
app.component('login-question', LoginQuestion);
app.component('company-home', CompanyHome);
app.component('test-api', TestApi);
app.component('company-shop', CompanyShop);
app.component('company-list', CompanyList);
app.component('work-list', WorkList);
app.component('vacancy-list', VacancyList);
app.component('candidate-list', CandidateList);
app.component('Select2', Select2);
app.component('upload-logo', UploadLogo);
app.component('edit-cities', EditCities);
app.component('edit-regions', EditRegions);
app.component('edit-educations', EditEducations);
app.component('edit-type-educations', EditTypeEducations);
app.component('edit-specializations', EditSpecializations);
app.component('edit-skills', EditSkills);
app.component('edit-languages', EditLanguages);
app.component('edit-cars', EditCars);
app.component('edit-car-licences', EditCarLicences);
app.component('edit-language-levels', EditLanguageLevels);
app.component('edit-employments', EditEmployments);
app.component('edit-degrees', EditDegrees);
app.component('edit-skill-levels', EditSkillLevels);
app.component('edit-type-contracts', EditTypeContracts);
app.component('edit-facilitations', EditFacilitations);
app.component('edit-currencies', EditCurrencies);
app.component('edit-personals', EditPersonals);
app.component('create-deposit', CreateDeposit);
app.component('popup', Popup);
app.component('popup-modal', PopupModal);
app.component('popup-policy', PopupPolicy);



//lang
app.config.globalProperties.trans = string => _.get(window.i18n, string);


// mount the app to the DOM
app.mount('#app');





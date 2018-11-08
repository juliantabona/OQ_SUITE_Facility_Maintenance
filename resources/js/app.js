
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

//  For our custom routes
import VueRouter from 'vue-router';
import router from './routes.js';
//  For bootstrap
import BootstrapVue from 'bootstrap-vue';
//  For toast notifications
import Snotify from 'vue-snotify';

import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.min.css';

//  For authentication handling
import auth from './auth.js';

window.Vue = require('vue');

//  For our custom routes
Vue.use(VueRouter);
//  Use Bootstrap & Tooltips
Vue.use(BootstrapVue);
//  For toast notifications
Vue.use(Snotify);

//  For Pagination
Vue.component('pagination', require('laravel-vue-pagination'));

//  Global event manager, to emit changes/updates
//  such as when user has logged in e.g) auth.js
window.Event = new Vue;

//  For authentication handling
window.auth = auth;

//  Imports Moment.js for use by vue components in formatting dates
Vue.use(require('vue-moment'));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 /**
 * DASHBOARD LAYOUT COMPONENTS
 */
Vue.component('main-dashboard', require('./components/layout/main.vue'));
Vue.component('top-main-menu', require('./components/layout/top-menu/main.vue'));
Vue.component('left-main-menu', require('./components/layout/left-menu/main.vue'));
Vue.component('settings-widget', require('./components/layout/sidebar/settings-widget.vue'));
Vue.component('todo-widget', require('./components/layout/sidebar/todo-widget.vue'));
Vue.component('app-footer', require('./components/layout/footer/main.vue'));

 /**
 * JOBCARD COMPONENTS
 */
Vue.component('jobcard-body', require('./components/dashboard/jobcard/show/body.vue'));

 /**
 * COMPANY COMPONENTS
 */
Vue.component('company-side-widget', require('./components/dashboard/company/sideWidget.vue'));
Vue.component('contractor-list-widget', require('./components/dashboard/company/contractorListWidget.vue'));

 /**
 * DOCUMENT COMPONENTS
 */
Vue.component('document-list', require('./components/dashboard/document/documentList.vue'));
Vue.component('document-uploader', require('./components/dashboard/document/upload.vue'));

 /**
 * RECENT ACTIVITY COMPONENTS
 */
Vue.component('recent-activity-widget', require('./components/dashboard/widgets/recentActivityWidget.vue'));

 /**
 * LOADER COMPONENTS
 */
Vue.component('loader', require('./components/loaderComponent.vue'));

 /**
 * DASHBOARD MODALS
 */
Vue.component('create-company-modal', require('./components/modal/createCompany.vue'));
Vue.component('edit-company-modal', require('./components/modal/editCompany.vue'));

Vue.component('vue-ctk-date-time-picker', VueCtkDateTimePicker);

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue')
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue')
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue')
);

const app = new Vue({
    el: '#app',
    //  For our custom routes
    router
});

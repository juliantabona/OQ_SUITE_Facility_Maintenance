
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

//Imports Moment.js for use by vue components in formatting dates
Vue.use(require('vue-moment'));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('loader', require('./components/loaderComponent.vue'));
Vue.component('jobcard-body', require('./components/jobcard/body.vue'));
Vue.component('document-list', require('./components/document/documentList.vue'));
Vue.component('document-uploader', require('./components/upload.vue'));
Vue.component('company-side-widget', require('./components/company/sideWidget.vue'));
Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app'
});

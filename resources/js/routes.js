import VueRouter from 'vue-router';

let routes = [
    {
        path: '/login', name: 'login',
        component: require('./components/auth/Login.vue')
        
    },
    {
        path: '/register', name: 'register',
        component: require('./components/auth/Register.vue')
    },
    {
        //  Dashboard overview
        path: '/dashboard', name: 'dashboard',
        component: require('./components/dashboard/overview.vue')
    },
    {
        //  Get all jobcards
        path: '/jobcards', name: 'jobcards',
        component: require('./components/dashboard/jobcard/list2.vue'),
    },
    {
        //  Show one jobcard
        path: '/jobcards/:id', name: 'show-jobcard',
        component: require('./components/dashboard/jobcard/main.vue'),
        props: true
    },
    {
        //  Create jobcard
        path: '/jobcards/create', name: 'create-jobcard',
        component: require('./components/dashboard/jobcard/create.vue'),
    }
];

export default new VueRouter({
    //mode: 'history',
    routes
});
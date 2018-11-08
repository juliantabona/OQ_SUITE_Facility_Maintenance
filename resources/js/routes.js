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
        path: '/activate-account', name: 'activate-account',
        component: require('./components/auth/ActivateAccount.vue')
    },
    {
        //  Dashboard overview
        path: '/dashboard', name: 'dashboard',
        component: require('./components/dashboard/overview.vue')
    },
    {
        //  Get all jobcards
        path: '/jobcards', name: 'jobcards',
        component: require('./components/dashboard/jobcard/list/main.vue'),
    },
    {
        //  Create jobcard
        path: '/jobcards/create', name: 'create-jobcard',
        component: require('./components/dashboard/jobcard/create/main.vue'),
    },
    {
        //  Show one jobcard
        path: '/jobcards/:id', name: 'show-jobcard',
        component: require('./components/dashboard/jobcard/show/main.vue'),
        props: true
    },
    {
        //  Get all clients
        path: '/clients', name: 'clients',
        component: require('./components/dashboard/company/client/list/main.vue'),
    },
    {
        //  Get all contractors
        path: '/contractors', name: 'contractors',
        component: require('./components/dashboard/company/contractor/list/main.vue'),
    },
    {
        //  Get all contractors
        path: '/staff', name: 'staff',
        component: require('./components/dashboard/user/staff/list/main.vue'),
    },
    {
        //  Get all recent activities
        path: '/recentactivities', name: 'recentactivities',
        component: require('./components/dashboard/recentActivity/list/main.vue'),
    },
];

export default new VueRouter({
    //mode: 'history',
    routes
});
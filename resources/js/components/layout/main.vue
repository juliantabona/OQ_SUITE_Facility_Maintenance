<style>
    /* Enter and leave animations can use different */
    /* durations and timing functions.              */
    .slide-fade-enter-active {
        transition: all 0.8s ease;
    }
    .slide-fade-leave-active {
        transition: all 0.5s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }
    .slide-fade-enter, .slide-fade-leave-to{
        transform: translateX(-50%);
        opacity: 0;
    }

    .slide-fade2-enter-active {
        transition: all 1.5s ease;
    }
    .slide-fade2-leave-active {
        transition: all 0.5s ease-in;
    }
    .slide-fade2-enter, .slide-fade2-leave-to{
        opacity: 0;
    }

    .component-fade-enter-active, .component-fade-leave-active {
    transition: all 1s ease;
    }
    .component-fade-enter, .component-fade-leave-to
    /* .component-fade-leave-active for <2.1.8 */ {
    opacity: 0;
    display: none;
    }
</style>

<template>
    <main>
        <div class="container-scroller p-0" v-if="noLayout.indexOf($route.name) == -1">
            <div class="container-fluid page-body-wrapper">
                <div class="row row-offcanvas row-offcanvas-right">
                    <!-- Right sidebar with instant settings & todo list widgets-->
                    <settings-widget></settings-widget>
                    <todo-widget></todo-widget> 
                    <!-- Left sidebar with navigation menus -->
                    <left-main-menu></left-main-menu>
                    <!-- Dashboard content -->
                    <div class="content-wrapper p-0" style="min-height: 2956.38px;">
                        <!-- Top menu with logo, profile and message icons -->
                        <top-main-menu></top-main-menu>
                        <div class="pr-3 pl-3">
                            <alert></alert>
                            <vue-snotify></vue-snotify>
                            <!-- Dashboard content -->
                            <!-- Put Profile, Jobcards, Staff e.t.c resource content here -->
                            <!-- Only authenticated users can access this content -->
                            <transition name="component-fade">
                                <router-view class="mt-3"></router-view>
                            </transition>
                        </div>
                    </div>
                    <!-- partial:partials/_footer.html -->
                    <app-footer></app-footer>
                </div>
            </div>
        </div>
        <!-- Put Login, Register, Password Reset, e.t.c content here -->
        <!-- Usually guests who are not loggedIn can access this content -->
        <transition name="slide-fade">
            <router-view v-if="noLayout.indexOf($route.name) > -1"></router-view>
        </transition>
    </main>
</template>
<script>
    export default {
        data() {
            return {
                noLayout: ['login', 'register', 'activate-account', 'activate-account-token'],
                authenticated: auth.check(),
                user: auth.user
            };
        },
        methods: {
            logout() {
                //  Log user out
                auth.logout();

                console.log('go to login page...');
                //  Navigate to the login page
                this.$router.push({ name: 'login' })
            }
        },
        mounted() {
            Event.$on('userLoggedIn', () => {
                this.authenticated = true;
                this.user = auth.user;
                console.log('userLoggedIn...');
            });

            Event.$on('userLoggedOut', () => {
                this.authenticated = false;
                this.user = null;
                console.log('userLoggedOut...');
            });

            Event.$on('performLogout', () => {
                console.log('logging out...');
                this.logout();
            });
        },
    }
</script>
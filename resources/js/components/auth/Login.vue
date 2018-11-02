<style>
    body{
        overflow:hidden;
    }

    .slider {
        height: 100%;
        text-align:center;
    }

    .slider article {
        margin: 50px 0 15px;
        padding: 0 22px;
        box-sizing: border-box;
        display: inline-block;
        width: 100%;
        z-index:1;
    }

    .slider h2 {
        font-size: 36px;
        color: #fff;
        text-transform: capitalize;
        font-family: proximaNova_semibold,Arial,Helvetica;
        margin-top: 9px;
    }

    .slider p {
        color:#fff;
    }

    .slider img {
        display: block;
        margin: 0 auto;
    }

    .slider-box{
        height: 100vh;
    }

    .slick-dots {
        bottom: 60px;
    }

    .slick-dots li button:before{
        display:none;
    }

    .slick-dots li.slick-active button {
        background-color: #fff!important;
    }

    .slick-dots li button {
        background-color: #c5c5c5!important;
        border-radius: 100%;
        width: 7px!important;
        height: 7px!important;
    }



    .login-form {
        background: #ffffff none repeat scroll 0 0;
        border-radius: 2px;
        margin: 10px auto;
        padding: 50px 100px;
    }

    .login-form h5 {
        text-align: left; 
        position:relative;
    }

    .login-form h5:before {
        content: "";
        position: absolute;
        width: 65%;
        left: 70px;
        top: 13px;
        border-top: 1px solid #ccc;
    }

    .login-form .form-control {
        background: #ffffff none repeat scroll 0 0;
        border: 1px solid #bbbbbb;
        border-radius: 3px;
        font-size: 14px;
        height: 40px;
        line-height: 50px;
    }

    .login-form .form-group {
        margin-bottom:10px;
    }
    .login-form{ text-align:center;}

    .login-form  .btn.btn-primary {
        background: #f0ad4e none repeat scroll 0 0;
        border-color: #f0ad4e;
        color: #ffffff;
        font-size: 14px;
        width: 100%;
        height: 50px;
        line-height: 50px;
        padding: 0;
    }

    .botto-text {
        color: #ffffff;
        font-size: 14px;
        margin: auto;
    }

    .help-block{
        text-align:left;
    }

    .login-form button[type="button"] {
        border-radius:3px;
        border-color: #ffb400 !important;
        background-color: #ffb400 !important;
    }

    .promo-box{
        background: #e2e2e2;
        padding: 30px 20px 20px;
        color: #5a5a5a;
    }
</style>

<template>
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-6">
                <slick ref="slick" :options="slickOptions" class="slider">
                    <a href="#">
                        <div class="slider-box pl-3 pr-3" style="background:#121058;">
                            <article flex="" class="flex">
                                <h2>Better Management Anytime - Anywhere On Your Phone</h2>
                                <p>Start managing your jobs, clients, workers and more with our Mobile App. And better yet, we support all IPhone, Android and Windows devices</p>
                            </article>
                            <div class="flex-100 layout-align-center-center layout-row" layout="row" layout-align="center center">
                                <img src="/images/backgrounds/something.png" alt="">
                            </div>
                            <div class="flex-100 layout-align-center-center layout-row" layout="row" layout-align="center center">
                                <button class="btn btn-outline-white mt-5 mb-5" type="button">
                                    Download APP
                                </button>
                            </div>
                        </div>
                    </a>
                    
                    <a href="#">
                        <div class="slider-box pl-3 pr-3" style="background:#060e49;">
                            <article flex="" class="flex">
                                <h2>Get Customer Feedback<br>On The Job</h2>
                                <p>Job management has just got even easier! Now you can send your customers login details to track job progress while also giving valuable feeback!</p>
                            </article>
                            <div class="flex-100 layout-align-center-center layout-row" layout="row" layout-align="center center">
                                <img src="/images/backgrounds/something2.png" alt="">
                            </div>
                            <div class="flex-100 layout-align-center-center layout-row" layout="row" layout-align="center center">
                                <button class="btn btn-outline-white mt-5 mb-5" type="button">
                                    Read More
                                </button>
                            </div>
                        </div>
                    </a>
                </slick>
            </div>

            <div class="col-6 pl-5 pr-5">
                <!-- Login Form -->
                <div class="login-form">
                    <img src="/images/assets/logo/OQ-INFINITE-B-150X84.gif" alt="logo" class="mb-4">
                    <h5>Login</h5>
                    <form id="Login" ng-submit="vm.login()" autocomplete="off" novalidate="">
                        <div :class="'form-group'+(loginErrors.email ? ' has-error' : '')
                                                    +(loginErrors.username ? ' has-error' : '')">
                            <input type="email" name="identity" v-model="identity" :class="'form-control' 
                                    +(loginErrors.email ? ' is-invalid' : '')
                                    +(loginErrors.username ? ' is-invalid' : '')" 
                                    placeholder="Email/Username" autofocus>
                            <span v-if="loginErrors.email" class="help-block invalid-feedback d-block">
                                <strong>
                                    {{ loginErrors.email[0] }}
                                </strong>
                            </span>
                            <span v-if="loginErrors.username" class="help-block invalid-feedback d-block">
                                <strong>
                                    {{ loginErrors.username[0] }}
                                </strong>
                            </span>
                        </div>
                        <div :class="'form-group'+(loginErrors.password ? ' has-error' : '')">
                            <input type="password" name="password" v-model="password" :class="'form-control' 
                                    +(loginErrors.password ? ' is-invalid' : '')" 
                                    placeholder="Password">
                            <span v-if="loginErrors.password" class="help-block invalid-feedback d-block">
                                <strong>{{ loginErrors.password[0] }}</strong>
                            </span>
                        </div>
                        <div v-show="isLoggingIn" class="mt-4">
                            <img src="/images/assets/icons/star_loader.svg" alt="Loader" style=" width: 40px;">Logging in...
                        </div>
                        <button v-show="!isLoggingIn" type="button" @click="login" class="btn btn-primary mt-2">Login</button>
                        <div class="mt-4">
                            <a href="#" class="mr-3">Forgot password?</a>
                            <a href="#">Create Account?</a>
                        </div>
                    </form>
                    <div class="promo-box mt-5" flex="">
                        <b>Terms And Conditions: </b>
                        <p>You will get <b>20% OFF</b> on your next 4 invoices</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import Slick from 'vue-slick';

export default {

    components: { Slick },

    data() {
        return {
            identity: '',
            password: '',
            loginErrors: [],
            isLoggingIn:false,
            slickOptions: {
                dots: true,
                speed: 1000,
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 3000,
                fade: true,
            },
        };
    },

    // All slick methods can be used too, example here
    methods: {
        login() {
            const self = this;
            //  Start loader
            self.isLoggingIn = true

            let loginData = {
                identity: this.identity,
                password: this.password
            };
            axios.post('/api/login', loginData)
                .then(({data}) => {
                    console.log(data);
                    let response = data;
                    let token = response.data.auth.access_token;
                    let user = response.data.user;

                    //  Stop loader
                    self.isLoggingIn = false;

                    //  Save token and user
                    //  Include token in all further axios api calls
                    auth.login(token, user);

                    //  Navigate to the dashboard
                    self.$router.push('/dashboard');
                })         
                .catch(({response}) => { 
                    console.error(response);
                    //  Stop loader
                    self.isLoggingIn = false;     
                    //  Grab errors              
                    self.loginErrors = response.data.errors;
                });
        }
    },
}
</script>
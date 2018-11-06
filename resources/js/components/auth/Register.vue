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



    .register-form {
        background: #ffffff none repeat scroll 0 0;
        border-radius: 2px;
        margin: 10px auto;
        padding: 50px 100px;
    }

    .register-form h5 {
        text-align: left; 
        position:relative;
    }

    .register-form h5:before {
        content: "";
        position: absolute;
        width: 65%;
        left: 70px;
        top: 13px;
        border-top: 1px solid #ccc;
    }

    .register-form .form-control {
        background: #ffffff none repeat scroll 0 0;
        border: 1px solid #bbbbbb;
        border-radius: 3px;
        font-size: 14px;
        height: 40px;
        line-height: 50px;
    }

    .register-form .form-group {
        margin-bottom:10px;
    }
    .register-form{ text-align:center;}

    .register-form  .btn.btn-primary {
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

    .register-form button[type="button"] {
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
                <!-- register Form -->
                <div class="register-form">
                    <img src="/images/assets/logo/OQ-INFINITE-B-150X84.gif" alt="logo" class="mb-4">
                    <h5>Register</h5>
                    <form id="register" autocomplete="off" novalidate="">
                        <div :class="'form-group'+(registerErrors.first_name ? ' has-error' : '')
                                                    +(registerErrors.first_name ? ' has-error' : '')">
                            <input type="text" name="first_name" v-model="first_name" :class="'form-control' 
                                    +(registerErrors.first_name ? ' is-invalid' : '')" 
                                    placeholder="First Name" autofocus>
                            <span v-if="registerErrors.first_name" class="help-block invalid-feedback d-block">
                                <strong>
                                    {{ registerErrors.first_name[0] }}
                                </strong>
                            </span>
                        </div>
                        <div :class="'form-group'+(registerErrors.last_name ? ' has-error' : '')
                                                    +(registerErrors.last_name ? ' has-error' : '')">
                            <input type="text" name="last_name" v-model="last_name" :class="'form-control' 
                                    +(registerErrors.last_name ? ' is-invalid' : '')" 
                                    placeholder="Last Name" autofocus>
                            <span v-if="registerErrors.last_name" class="help-block invalid-feedback d-block">
                                <strong>
                                    {{ registerErrors.last_name[0] }}
                                </strong>
                            </span>
                        </div>
                        <div :class="'form-group'+(registerErrors.username ? ' has-error' : '')">
                            <input type="text" name="username" v-model="username" :class="'form-control' 
                                    +(registerErrors.username ? ' is-invalid' : '')" 
                                    placeholder="Username" autofocus>
                            <span v-if="registerErrors.username" class="help-block invalid-feedback d-block">
                                <strong>
                                    {{ registerErrors.username[0] }}
                                </strong>
                            </span>
                        </div>
                        <div :class="'form-group'+(registerErrors.email ? ' has-error' : '')">
                            <input type="email" name="email" v-model="email" :class="'form-control' 
                                    +(registerErrors.email ? ' is-invalid' : '')" 
                                    placeholder="Email" autofocus>
                            <span v-if="registerErrors.email" class="help-block invalid-feedback d-block">
                                <strong>
                                    {{ registerErrors.email[0] }}
                                </strong>
                            </span>
                        </div>
                        <div :class="'form-group'+(registerErrors.password ? ' has-error' : '')">
                            <input type="password" name="password" v-model="password" :class="'form-control' 
                                    +(registerErrors.password ? ' is-invalid' : '')" 
                                    placeholder="Password">
                            <span v-if="registerErrors.password" class="help-block invalid-feedback d-block">
                                <strong>{{ registerErrors.password[0] }}</strong>
                            </span>
                        </div>
                        <div :class="'form-group'+(registerErrors.password_confirmation ? ' has-error' : '')">
                            <input type="password" name="password_confirmation" v-model="password_confirmation" :class="'form-control' 
                                    +(registerErrors.password_confirmation ? ' is-invalid' : '')" 
                                    placeholder="Password Confirmation">
                            <span v-if="registerErrors.password_confirmation" class="help-block invalid-feedback d-block">
                                <strong>{{ registerErrors.password_confirmation[0] }}</strong>
                            </span>
                        </div>
                        <hr>
                        <div :class="'form-group'+(registerErrors.company_name ? ' has-error' : '')
                                                    +(registerErrors.company_name ? ' has-error' : '')">
                            <input type="text" name="company_name" v-model="company_name" :class="'form-control' 
                                    +(registerErrors.company_name ? ' is-invalid' : '')" 
                                    placeholder="Company Name" autofocus>
                            <span v-if="registerErrors.company_name" class="help-block invalid-feedback d-block">
                                <strong>
                                    {{ registerErrors.company_name[0] }}
                                </strong>
                            </span>
                        </div>
                        <div :class="'form-group'+(registerErrors.company_branch_name ? ' has-error' : '')
                                                    +(registerErrors.company_branch_name ? ' has-error' : '')">
                            <input type="text" name="company_branch_name" v-model="company_branch_name" :class="'form-control' 
                                    +(registerErrors.company_branch_name ? ' is-invalid' : '')" 
                                    placeholder="Branch Name" autofocus>
                            <span v-if="registerErrors.company_branch_name" class="help-block invalid-feedback d-block">
                                <strong>
                                    {{ registerErrors.company_branch_name[0] }}
                                </strong>
                            </span>
                        </div>
                        <div :class="'form-group'+(registerErrors.company_destination ? ' has-error' : '')
                                                    +(registerErrors.company_destination ? ' has-error' : '')">
                            <input type="text" name="company_destination" v-model="company_destination" :class="'form-control' 
                                    +(registerErrors.company_destination ? ' is-invalid' : '')" 
                                    placeholder="Company Location" autofocus>
                            <span v-if="registerErrors.company_destination" class="help-block invalid-feedback d-block">
                                <strong>
                                    {{ registerErrors.company_destination[0] }}
                                </strong>
                            </span>
                        </div>
                        <div v-show="isRegistering" class="mt-4">
                            <img src="/images/assets/icons/star_loader.svg" alt="Loader" style=" width: 40px;">Registering...
                        </div>
                        <button v-show="!isRegistering" type="button" @click="register" class="btn btn-primary mt-2">Register</button>
                        <div class="mt-4">
                            Have an account? <router-link :to="{ name: 'login'}">Login</router-link>
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
            first_name: '',
            last_name: '',
            username: '',
            email: '',
            password: '',
            password_confirmation: '',
            company_name: '',
            company_branch_name: '',
            company_destination: '',
            registerErrors: [],
            isRegistering:false,
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
        register() {
            const self = this;
            //  Start loader
            self.isRegistering = true

            let registerData = {
                first_name: this.first_name,
                last_name: this.last_name,
                username: this.username,
                email: this.email,
                password: this.password,
                password_confirmation: this.password_confirmation,
                company_name: this.company_name,
                company_branch_name: this.company_branch_name,
                company_destination: this.company_destination,
            };
            axios.post('/api/register', registerData)
                .then(({data}) => {
                    console.log(data);
                    let response = data;
                    let token = response.auth.access_token;
                    let user = response.user;

                    //  Stop loader
                    self.isRegistering = false;

                    //  Save token and user
                    //  Include token in all further axios api calls
                    console.log(token);
                    console.log(user);
                    auth.login(token, user);

                    //  Navigate to the dashboard
                    //self.$router.push('/dashboard');
                    self.$router.push('/activate-account');
                })         
                .catch(({response}) => { 
                    console.error(response);

                    //  Stop loader
                    self.isRegistering = false;     
                    
                    if(response){
                        //  Grab errors              
                        self.registerErrors = response.data.errors;
                    }
                });
        }
    },
}
</script>
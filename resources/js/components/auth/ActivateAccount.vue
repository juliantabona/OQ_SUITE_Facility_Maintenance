<style>

    .activate-page .overlay {
        border-bottom: 3px solid #fff;
        position: relative;
        min-height: 120vh;
        
        background-size: cover;
    }

    .activate-page .overlay:before {
        position: absolute;
        z-index: 0;
        content: '';
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: .9;
        background: #0e1c3a;
    }
    
    .activate-box{
        position: relative;
        background: #fff;
        overflow: hidden;
        min-height: 400px;
    }

    .mail{
        margin: auto;
        max-height: 200px;
        display: block;
    }


</style>
<template>
    <div class="activate-page">
        <!-- activate Section -->
        <div class="overlay">
            <div class="container-fluid pt-2">
                <div class="row mt-5">
                    <div class="activate-box col-lg-6 mx-auto p-5">
                        <img class="mail" src="/images/assets/icons/closed-envelope.png">
                        <div v-if="verification.msg">
                            <hr>
                            <h4 :class="'text-center text-'+verification.type">{{ verification.msg }}</h4>
                            <hr>
                        </div> 
                        <div v-if = "!isVerifying">
                            <h2 class="font-weight-light mt-4 text-center">Activate Account</h2>
                            <p style="text-align:center;">Visit your email to activate your account!</p>
                            <button v-if = "!isSendingEmail" @click="resendActivationEmail" type="button" class="btn btn-success d-block m-auto">Resend Activation</button>
                            <loader v-bind:isLoading="isSendingEmail" v-bind:msg="'Sending account activation...'"></loader>
                        </div>
                        <loader v-bind:isLoading="isVerifying" v-bind:msg="'Verifying account...'"></loader>
                    </div>            
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            token: null,
            email: null,
            isSendingEmail: false,
            isVerifying: false,
            verification: {
                msg: '',
                type: '',
            }
        };
    },
    created () {
        // Verify the token if provided
        console.log('verification details');

        if(this.$route.query.email){
            this.email = this.$route.query.email;
        }

        if(this.$route.query.token){
            this.token = this.$route.query.token;
        }
        
        this.verifyToken();
    },
    watch: {
        // call again the method if the route changes
        '$route': 'verifyToken'
    },
    // All slick methods can be used too, example here
    methods: {
        verifyToken () {

            const self = this;
            
            if(self.token != undefined){

                //  Start loader
                self.isVerifying = true;

                axios.post('/api/register/activate-account?email='+self.email+'&&token='+self.token)
                    .then(({data}) => {
                        console.log(data);

                        //  Stop loader
                        self.isVerifying = false;

                        if(data.message == 'Activation token not provided'){
                            self.verification.msg = 'Activation token not provided';
                            self.verification.type = 'danger';
                        }

                        if(data.message == 'Account does not exist'){
                            self.verification.msg = 'Account does not exist';
                            self.verification.type = 'danger';
                        }

                        if(data.message == 'Incorrent Token'){
                            self.verification.msg = 'Incorrent Token. Account not verified';
                            self.verification.type = 'danger';
                        }

                        //  Navigate to the login page
                        //  With a query to indicate that login was successful

                        self.$router.push({ name: 'login', 
                            query: { 
                                activated: data.user.username
                            }
                        });
                    

                    }).catch(({response}) => { 
                        console.error(response); 

                        //  Stop loader
                        self.isVerifying = false;   
                            
                        if(response){
                            //  Grab errors              
                            self.errors = response.data.errors;
                        }
                    });
            }
        },
        resendActivationEmail() {
            const self = this;
            //  Start loader
            self.isSendingEmail = true;

            let activationData = {
                user_id: this.id
            };
            
            axios.post('/api/register/resend-activation', activationData)
                .then(({data}) => {
                    console.log(data);
                    let response = data;
                    let token = response.auth.access_token;
                    let user = response.user;

                    //  Stop loader
                    self.isSendingEmail = false;

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
                    if(response){
                        console.error(response.data);
                        //  Stop loader
                        self.isSendingEmail = false;     
                        //  Grab errors              
                        self.registerErrors = response.data.errors;
                    }
                });
        }
    },
}
</script>
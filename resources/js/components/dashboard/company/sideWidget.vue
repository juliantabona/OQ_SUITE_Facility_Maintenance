<template>
    <div class="col-12 col-md-4 col-lg-4 grid-margin stretch-card">
        <div class="card card-hoverable">
            <div class="card-body p-3 pt-4">
                <loader v-bind:isLoading="isLoading"></loader>
                <div v-if="company && !isLoading" class="row">
                    <div class="col-12">
                        <div class="bg-primary p-2 text-white">
                            <i class="float-left icon-emotsmile icon-sm icons ml-3 mr-2"></i>
                            <h6 class="card-title mb-0 ml-2 text-white">Client Details</h6>
                        </div>
                        <div class="mt-3 ml-3 reference-details">
                            <div v-if="company.logo_url" class="lightgallery">
                                <a :href="company.logo_url">
                                    <img class="company-logo img-thumbnail mb-2 p-2 rounded rounded-circle w-50" 
                                        :src="company.logo_url" />
                                </a>
                            </div>
                            <span class="lower-font">
                                <b>Client Name: </b>{{ company.name ? company.name:'____' }}<br/>
                                <b>City/Town: </b>{{ company.city ? company.city:'____' }}<br/>
                                <b>Address: </b>{{ company.address ? company.address:'____' }}
                            </span>
                            <br/>
                            <span class="lower-font">
                                <b>Phone: </b>
                                {{ company.phone_ext ? '+'+company.phone_ext+'-':'___-' }}
                                {{ company.phone_num ? company.phone_num:'____' }}
                            </span>
                            <span class="lower-font mb-3">
                                <b>Email: </b>{{ company.city ? company.email:'____' }}
                            </span>
                            <span class="lower-font clearfix mb-3">
                                <button type="button" @click="removeClient()" class="btn btn-link float-right mr-1">
                                    <i class="icon-trash mr-0"></i> 
                                    Remove
                                </button>
                                <button type="button" @click="showEditModal = true" class="btn btn-link float-right mr-1"><i class="icon-pencil mr-0"></i> Edit</button>   
                                <button type="button" @click="showCreateModal = true" class="btn btn-link float-right mr-1"><i class="icon-refresh mr-0"></i> Change Client</button>   
                                <button type="button" class="btn btn-link float-right mr-1"><i class="icon-eye mr-0"></i> View</button>   
                            </span> 
                        </div>
                    </div>
                    <div v-if="contacts.length" class="col-12 mb-2">
                        <div class="bg-primary p-2 text-white">
                            <i class="float-left icon-user icon-sm icons ml-3 mr-2"></i>
                            <h6 class="card-title mb-0 ml-2 text-white d-inline">Contact Details ({{ contacts.length }})</h6>
                            <a href="#" style="font-size:  12px;" class="float-right mr-1 mt-1 text-white"><i class="icon-eye"></i> View All</a>
                        </div>
                        <div v-for="contact in contacts" :key="contact.id" class="mt-1 ml-2 reference-details">
                            <div class=" d-flex align-items-center border-bottom p-2">
                                <a class="p-0 m-0">
                                    <img class="img-sm rounded-circle" src="http://127.0.0.1:8000/images/profile_placeholder.svg" alt="">
                                </a>
                                <div class="wrapper w-100 ml-3">
                                    <p class="pt-2 mb-2" style="font-size:  12px;">
                                        <a href="#" class="mr-1">{{ contact.first_name ? contact.first_name:'____' }} {{ contact.last_name ? contact.last_name:'____' }}</a>
                                    </p>
                                    <div>
                                        <div v-if="contact.position" class="d-inline mr-2" v-b-tooltip.hover :title="(contact.position ? contact.position:'____')">
                                            <i class="icon-info text-dark"></i>
                                        </div>
                                        <div v-if="contact.phone_num" class="d-inline mr-2" v-b-tooltip.hover :title="(contact.phone_ext ? '+'+contact.phone_ext+'-':'___-') + (contact.phone_num ? contact.phone_num:'____')">
                                            <i class="icon-phone text-dark"></i>
                                        </div>
                                        <div v-if="contact.email" class="d-inline" v-b-tooltip.hover :title="(contact.email) ? contact.email:'____'">
                                            <i class="icon-envelope text-dark"></i>
                                        </div>
                                        <button type="button" class="btn btn-link float-right mr-1"><i class="icon-trash"></i> Remove</button>
                                        <button type="button" class="btn btn-link float-right mr-1"><i class="icon-pencil"></i> Edit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div v-b-tooltip.hover title="Add another contact/reference working at this company or organisation" >
                            <button type="button" class="animated-strips btn btn-success float-right pt-3 pb-3 pl-4 pr-4 w-100" data-toggle="modal" data-target="#add-reference-modal">                                            
                                <i class="icon-sm icon-user icons"></i>
                                <span class="mt-4">Add Contact</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div v-if="!company && !isLoading" class="col-12">
                    <div v-b-tooltip.hover title="Add a company or organisation corresponding to this jobcard"
                         v-b-modal.addClientModal>
                        <button @click="showCreateModal = true" 
                                type="button" class="btn btn-success p-5 w-100 animated-strips">                                            
                            <i class="d-block icon-sm icon-emotsmile icons" style="font-size: 25px;"></i>
                            <span class="d-block mt-4">Add Client</span>
                        </button>
                    </div>
                </div>
                <create-company-modal v-if="showCreateModal"       
                        :jobcard-id="jobcardId" 
                        :company-relation="'client'"
                        @companyCreated="updateWidgetDetails"
                        @hidden="destroyCreateModal">
                </create-company-modal>
                <edit-company-modal v-if="showEditModal"       
                        :jobcard-id="jobcardId" 
                        :company-relation="'client'"
                        :current-company="company"
                        @companyUpdated="updateWidgetDetails"
                        @hidden="destroyEditModal">
                </edit-company-modal>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['companyId', 'jobcardId'],
        data(){
            return {
                showCreateModal: false,
                showEditModal: false,
                company: null,
                contacts: [],
                isLoading: false
            }
        },
        watch: {
            companyId: function(newVal){
                this.fetchData();
            }
        },
        methods: {
            destroyCreateModal: function(){
                this.showCreateModal = false;
            },
            destroyEditModal: function(){
                this.showEditModal = false;
            },
            updateWidgetDetails: function(company){
                this.company = company;
            },
            fetchData: function(){
                const self = this;

                //  Start loader
                self.isLoading = true
                
                if(self.companyId){
                    
                    axios.get('/api/companies/'+this.companyId+'?contacts=true'
                    ).then(response =>{

                        //  Stop loader
                        self.isLoading = false;

                        self.company = response.data;
                        console.log(response.data);
                        
                        if(response.data.contact_directory){         
                            console.log(self.contacts);
                            self.contacts = response.data.contact_directory;
                        }
                        
                    }).catch(function (error) {

                        //  Stop loader
                        self.isLoading = false;

                        console.log(error);
                    });

                }else{
                    self.isLoading = false;
                }
            },
            removeClient: function(){
                const yesAction = (toast) => {
                    //  Remove client
                    this.removeClientFromJobcard();
                    //  Remove toast
                    this.$snotify.remove(toast.id); 
                }

                const noAction = (toast) => {
                    //  Remove toast
                    this.$snotify.remove(toast.id); 
                }

                this.$snotify.confirm('Are you sure you want to remove this client?', '', 
                {
                    timeout: 5000,
                    showProgressBar: true,
                    closeOnClick: false,
                    pauseOnHover: true,
                    buttons: [
                        {text: 'Yes', action: yesAction, bold: true },
                        {text: 'No', action: noAction },
                    ]
                });
            },
            removeClientFromJobcard: function(){
                const self = this;

                //  Start loader
                self.isLoading = true
                
                if(self.companyId){
                    
                    axios.post('/api/jobcards/'+this.jobcardId+'/removeclient'
                    ).then(response =>{

                        //  Stop loader
                        self.isLoading = false;
    
                        self.company = response.data;
                        self.$snotify.success('Client removed!');
                        console.log(response.data);
                        
                    }).catch(function (error) {

                        //  Stop loader
                        self.isLoading = false;

                        console.log(error);
                    });

                }else{
                    self.isLoading = false;
                }
            }
        },
        created(){
            this.fetchData();
        }
    }
</script>


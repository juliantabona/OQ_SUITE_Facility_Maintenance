<template>
    <div class="col-12 grid-margin stretch-card">
        <div class="card card-hoverable">
            <div class="card-body p-3 pt-4">
                <div class="row">
                    <loader v-bind:isLoading="isLoading"></loader>
                    <div v-if="contractorsList.length && !isLoading" class="col-12 clearfix">
                        <h4 class="card-title mb-3 mt-4 ml-2">Potential Contractors ({{ contractorsList.length }})</h4>
                        <div class="table-responsive table-hover">
                            <table class="table mt-3 border-top">
                                <thead>
                                    <tr>
                                        <td>Choose</td>
                                        <th>Logo</th>
                                        <th>Company Name</th>
                                        <th style="min-width: 18%">Tel</th>
                                        <th style="min-width: 18%">Email</th>
                                        <th>Submitted On</th>
                                        <th class="d-sm-none d-md-table-cell">Price</th>
                                        <th class="d-sm-none d-md-table-cell">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(contractor , index) in contractorsList" :key="contractor.id" class="clickable-row show-contractor-modal-btn">
                                        <td>
                                            <input v-if="contractor.selected" class="icheck" type="checkbox" name="selected_contractor" checked>
                                            <input v-if="contractor.selected" class="icheck" type="checkbox" name="selected_contractor">
                                        </td>
                                        <td>
                                            <div v-if="contractor.logo_url" class="lightgallery">
                                                <a :href="contractor.logo_url">
                                                    <img class="company-logo img-thumbnail mb-2 p-2 rounded rounded-circle w-50" 
                                                        :src="contractor.logo_url" />
                                                </a>
                                            </div>
                                        </td>
                                        <td class="company-name">{{ contractor.name ? contractor.name:'___' }}</td>
                                        <td class="company-phone">
                                            {{ contractor.phone_ext ? '+'+contractor.phone_ext+'-':'___-' }}
                                            {{ contractor.phone_num ? contractor.phone_num:'____' }}
                                        </td>
                                        <td class="company-email">{{ contractor.email ? contractor.email:'____' }}</td>
                                        <td class="company-created_at">{{ contractor.pivot.created_at | moment("DD MMM YYYY") }}</td>
                                        <td class="company-price">{{ contractor.pivot.amount ? contractor.pivot.amount:'____' }}</td>
                                        <td>
                                            <div>
                                                <button type="button" @click="removeContractor(contractor.id, index)" class="btn-link float-right mr-1">
                                                    <i class="icon-trash"></i> 
                                                    Remove
                                                </button>
                                                <button type="button" @click="openEditModal(contractor, index)" class="btn-link float-right mr-1">
                                                    <i class="icon-pencil"></i> Edit
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-12 mt-2">
                                <nav v-if="contractorsListPagination.length" class="float-right">
                                    <div>
                                        <small> Showing {{contractorsListPagination.from}} - {{contractorsListPagination.to}} of {{contractorsListPagination.total}} entries.</small>
                                    </div>
                                    <div>
                                        <pagination :data="contractorsListPagination" @pagination-change-page="fetchData"></pagination>
                                    </div>
                                </nav>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="float-right" v-b-tooltip.hover title="Add a contractor aligned with this job. You can add more than one">
                                    <button @click="showCreateModal = true"
                                        type="button" class="animated-strips btn btn-success float-right pt-3 pb-3 pl-4 pr-4">                                         
                                        <i class="icon-briefcase icon-sm icons"></i>
                                        <span class="mt-4">Add Contractor</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div v-if="!contractorsList.length && !isLoading" class="col-4 offset-4">
                        <div v-b-tooltip.hover title="Add a contractor aligned with this job. You can add more than one">
                            <button @click="showCreateModal = true"
                                    type="button" class="btn btn-success p-5 w-100 animated-strips">                                         
                                <i class="d-block icon-briefcase icon-md icons" style="font-size: 25px;"></i>
                                <span class="d-block mt-4">Add Contractor</span>
                            </button>
                        </div>
                    </div>
                    <create-company-modal v-if="showCreateModal"       
                                          :jobcard-id="jobcardId" 
                                          :company-relation="'contractor'"
                                          @companyCreated="updateWidgetDetailsOnCreated"
                                          @hidden="destroyCreateModal">
                    </create-company-modal>
                    <edit-company-modal v-if="showEditModal"       
                            :jobcard-id="jobcardId" 
                            :company-relation="'contractor'"
                            :current-company="companyToEdit"
                            @companyUpdated="updateWidgetDetailsOnUpdated"
                            @hidden="destroyEditModal">
                    </edit-company-modal>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['jobcardId'],
        data () {
            return {
                index: null,
                showCreateModal: false,
                showEditModal: false,
                companyToEdit:null,
                contractorsList: [],
                contractorsListPagination:[],
                isLoading: false
            }
        },
        created () {
            // fetch the data when the view is created and the data is
            // already being observed
            this.fetchData();
        },
        methods: {
            openEditModal: function(contractor, index){
                this.companyToEdit = contractor;
                this.index = index;
                this.showEditModal = true;
            },
            removeContractor: function(contractor_id, index){
                const yesAction = (toast) => {
                    //  Remove contractor
                    this.index = index;
                    this.removeContractorFromJobcard(contractor_id);
                    //  Remove toast
                    this.$snotify.remove(toast.id); 
                }

                const noAction = (toast) => {
                    //  Remove toast
                    this.$snotify.remove(toast.id); 
                }

                this.$snotify.confirm('Are you sure you want to remove this contractor', '', 
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
            destroyCreateModal: function(){
                this.showCreateModal = false;
            },
            destroyEditModal: function(){
                this.showEditModal = false;
            },
            updateWidgetDetailsOnCreated: function(company){
                this.contractorsList.push(company);
            },
            updateWidgetDetailsOnUpdated: function(company){
                console.log('add to list');
                Vue.set(this.contractorsList, this.index, company);
                console.log('added to list!');
            },
            fetchData (page=1) {
                const self = this;

                //  Start loader
                self.isLoading = true
                
                /*  Specify other related model data to collect, just separate by comma (,) and extend with a period (.)
                *  E.g) selectedContractors.branches will bring all selected contractors and their company branches
                */
                let connections = '';

                /*  Specify limit for pagination
                */
                let limit = 3;

                axios.get('/api/jobcards/'+self.jobcardId+'/contractors?connections='+connections+'&&page='+page)
                    .then(({data}) => {
                        console.log(data);
                        if(data.data){
                            //  Get the jobcard
                            self.contractorsList = data.data;
                            self.contractorsListPagination = data;
                        }
                        //  Stop loader
                        self.isLoading = false;
                        
                    }).catch(({response}) => { 
                        console.log('stage 2 !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!');
                        //  Stop loader
                        self.isLoading = false;   
                          
                        if(response){
                            console.error(response); 
                        }
                    });
            },
            removeContractorFromJobcard: function(contractor_id){
                const self = this;

                //  Start loader
                self.isLoading = true;
                
                if(contractor_id){
                    
                    axios.post('/api/jobcards/'+this.jobcardId+'/'+contractor_id+'/remove'
                    ).then(response =>{

                        //  Stop loader
                        self.isLoading = false;
                        self.contractorsList.splice(this.index, 1);
                        self.$snotify.success('Contractor removed!');
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
        }
    }
</script>

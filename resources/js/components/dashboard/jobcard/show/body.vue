<style>

    .modal-content-max-height {
        overflow-y: auto;
        overflow-x: hidden;
        max-height: 320px;
        padding: 20px 15px;
    }

    .process_notice {
        color: #ffab00;
        font-size: 25px;
        position: absolute;
        top: -13px;
        right: 15px;
    }

    .process_notice > a{
        position: absolute;
        font-size: 14px;
        top: 8px;
        right: 0;
    }

    .process_notice > i {
        top: 0;
        right: 68px;
        position: absolute;
        animation: beat 0.5s infinite alternate;
    }

</style>

<template>
    <div class="card card-hoverable">
        <loader v-bind:isLoading="isLoading"></loader>
        <div v-if="!isLoading" class="card-body p-3 pt-4">
            <div class="row">
                <div class="col-12">
                    5 days left
                    <span class="process_notice">
                        <i class="icon-exclamation icons"></i>
                        <a href="#" class="text-warning">Authorize</a>
                    </span>
                    <select>
                        <option value="Open">Open</option>
                        <option value="Pending">Pending</option>
                        <option value="Closed">Closed</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <h3 class="card-title mb-3 mt-4 border-bottom pb-3">{{ jobcard.title }}</h3>
                            <b>Description: </b>
                            <p class="mt-2">{{ jobcard.description }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <span class="lower-font mr-4">
                                <b>Start Date: </b>{{ jobcard.start_date | moment("H:mmA, DD MMM YYYY") }}
                            </span>
                        </div>        
                        <div class="col-6">    
                            <span class="lower-font">
                                <b>End Date: </b>{{ jobcard.end_date | moment("H:mmA, DD MMM YYYY") }}
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <span v-b-tooltip.hover :title="jobcard.category.description" class="lower-font mr-4">
                                <b>Catergory: </b>{{ jobcard.category.name }}
                            </span>
                        </div>
                        <div class="col-6">
                            <span v-b-tooltip.hover :title="jobcard.costcenter.description" class="lower-font">
                                <b>Cost Center: </b>{{ jobcard.costcenter.name }}
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6" v-if="creator">
                            <span class="lower-font">
                                <b>Created By: </b>
                                <a href="#">{{ creator.first_name+' '+creator.last_name }}</a>
                            </span>
                        </div>
                        <div class="col-6">
                            <span class="lower-font">
                                <b>Assigned: </b>
                                <a href="/staff/1">Tumisang Mogotsi</a> 
                                <a href="/jobcards/1/views/1"></a>
                            </span>
                        </div>
                    </div>
                    <document-list 
                        v-bind:model-id="id" model-type="jobcard" 
                        v-bind:storage-location="'jobcard_images'" v-bind:group-type="'jobcard'" >
                    </document-list>
                </div>
            </div>

        </div>
        <div v-if="!isLoading" class="card-footer">
            <div class="row">
                <div class="col-12">
                    <div class="form-group mt-0">
                        <a class="btn btn-primary mr-2 float-right" href="#" target="_blank">
                            <i class="icon-cloud-download icons"></i>
                            Download Jobcard
                        </a>
                        <a href="#" class="btn btn-primary mr-2 float-right">
                            Send Jobcard
                            <i class="icon-paper-plane icons"></i>
                        </a>
                    </div>                                
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['id'],
        data () {
            return {
                isLoading: false,
                jobcard: null,
                errors: null
            }
        },
        created () {
            // fetch the data when the view is created and the data is
            // already being observed
            this.fetchData();
            console.log('fetching only jobcard information...');
        },
        watch: {
            // call again the method if the route changes
            '$route': 'fetchData'
        },
        computed: {
            // a computed getter
            creator: function () {
                return this.jobcard.createdby;
            }
        },
        methods: {
            fetchData () {
                const self = this;

                //  Start loader
                self.isLoading = true
                
                /*  Specify other related model data to collect, just separate by comma (,) and extend with a period (.)
                *  E.g) selectedContractors.branches will bring all selected contractors and their company branches
                */
                let connections = 'client,contractorsList,category,priority,costcenter,createdby';

                /*  Specify limit for pagination
                */
                let limit = 3;

                axios.get('/api/jobcards/'+self.id+'?connections='+connections)
                    .then(({data}) => {
                        console.log(data);
                        
                        //  Get the jobcard
                        self.jobcard = data;

                        //  Stop loader
                        self.isLoading = false;

                        //  Emit to update other components
                        self.$emit('jobcardFound', data);
                        
                    }).catch(({response}) => { 
                        //  Stop loader
                        self.isLoading = false;   
                          
                        if(response){
                            console.error(response); 
                            //  Grab errors              
                            self.errors = response.data.errors;
                        }
                    });
            }
        }
    }
</script>

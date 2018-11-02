<template>
    <div class="row">
        <loader v-bind:isLoading="isLoading"></loader>
        <div v-if="!isLoading" class="col-12">
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
                    <span v-b-tooltip.hover title="jobcard.category.description" class="lower-font mr-4">
                        <b>Catergory: </b>{{ jobcard.category.name }}
                    </span>
                </div>
                <div class="col-6">
                    <span v-b-tooltip.hover title="jobcard.costcenter.description" class="lower-font">
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
            <document-list v-bind:documents="jobcard.documents" v-bind:model-id="jobcard.id" model-type="jobcard"></document-list>
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
                paginationData: {},
                error: null
            }
        },
        created () {
            // fetch the data when the view is created and the data is
            // already being observed
            this.fetchData()
            console.log('fetching only one jobcard...');
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
            fetchData (page = 1) {
                const self = this;

                //  Start loader
                self.isLoading = true
                
                /*  Specify other related model data to collect, just separate by comma (,) and extend with a period (.)
                *  E.g) selectedContractors.branches will bring all selected contractors and their company branches
                */
                let connections = 'category,priority,costcenter,createdby,documents,recentactivities';

                /*  Specify limit for pagination
                */
                let limit = 3;

                axios.get('/api/jobcards/'+self.id+'?connections='+connections)
                    .then(({data}) => {
                        console.log(data);
                        
                        //  Get the jobcard
                        self.jobcard = data.data;
                        self.paginationData = data;

                        //  Stop loader
                        self.isLoading = false;
                        
                    }).catch(({response}) => { 
                        console.error(response);
                        //  Stop loader
                        self.isLoading = false;     
                        //  Grab errors              
                        self.loginErrors = response.data.errors;
                    });
            }
        }
    }
</script>

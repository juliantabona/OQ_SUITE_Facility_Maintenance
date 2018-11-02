<template>
    <div class="row">
        <div class="col-lg-12 d-flex flex-column">
            <div class="row flex-grow">
                <div class="col-12 col-md-12 col-lg-12 grid-margin stretch-card">
                    <div class="card card-hoverable">
                        <div class="card-body">
                            <div v-show="isLoading" class="mt-4">
                                <img src="/images/assets/icons/star_loader.svg" alt="Loader" style=" width: 40px;">Getting jobcards...
                            </div>
                            <div v-if="jobcards && !isLoading">
                                <i class="float-left icon-flag icon-sm icons ml-3"></i>
                                <h6 class="card-title float-left mb-0 ml-2">All Jobcards</h6>
                                <div class="d-flex table-responsive">
                                    <div class="btn-group ml-auto mr-2 border-0">
                                    <input type="text" class="form-control" placeholder="Search Here">
                                    <button class="btn btn-sm btn-primary"><i class="icon-magnifier icons"></i> Search</button>
                                    </div>
                                    <div class="btn-group">
                                    <button type="button" class="btn btn-light"><i class="mdi mdi-printer"></i></button>
                                    <button type="button" class="btn btn-light"><i class="mdi mdi-dots-vertical"></i></button>
                                    </div>
                                </div>
                                <div class="table-responsive table-hover">
                                    <table class="table mt-3 border-top">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">ID</th>
                                                <th style="width: 28%">Customer</th>
                                                <th style="width: 15%">Start Date</th>
                                                <th style="width: 15%">End Date</th>
                                                <th style="width: 18%" class="d-sm-none d-md-table-cell">Contractor</th>
                                                <th style="width: 14%">Due</th>
                                                <th class="d-sm-none d-md-table-cell">Priority</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="jobcard in jobcards" :key="jobcard.id" class='clickable-row' :data-href="'/jobcards/'+jobcard.id">
                                                <td class="d-none d-md-table-cell">{{ jobcard.id }}</td>   
                                                <td v-b-tooltip.hover :title="jobcard.description">{{ jobcard.title ? jobcard.title:'____' }}</td>
                                                <td v-b-tooltip.hover :title="jobcard.start_date | moment('H:mmA, DD MMM YYYY')">{{ jobcard.start_date | moment("DD MMM YYYY") }}</td>
                                                <td v-b-tooltip.hover :title="jobcard.end_date | moment('H:mmA, DD MMM YYYY')">{{ jobcard.end_date | moment("DD MMM YYYY") }}</td>
                                                
                                                <td v-b-tooltip.hover :title="jobcard.selected_contractors.length ? jobcard.selected_contractors[0].description : '____'" class="d-none d-md-table-cell">
                                                    {{  jobcard.selected_contractors.length ? jobcard.selected_contractors[0].name : '____'}}    
                                                </td>      
                                                <td class="d-none d-md-table-cell">3 days</td>                  
                                                <td v-b-tooltip.hover :title="jobcard.priority ? jobcard.priority.description : '____'" class="d-none d-md-table-cell">
                                                    {{  jobcard.priority ? jobcard.priority.name : '____'}}    
                                                </td>                                             
                                                <td>
                                                    <div class="badge badge-success badge-fw">
                                                        Closed
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex align-items-center justify-content-between flex-column flex-sm-row mt-4">
                                    <p class="mb-3 ml-3 mb-sm-0"><strong>{{ paginationData.total }}</strong> {{ jobcards.total == 1 ? ' result': '  results' }} found</p>
                                    <nav>
                                       <pagination :data="paginationData" @pagination-change-page="fetchData"></pagination>
                                    </nav>
                                </div>
                            </div>
                            <div v-if="!jobcards && !isLoading">
                                <h6 class="card-title float-left mb-0 ml-2">No Jobcards</h6>
                                <div class="col-4 offset-4">
                                    <div v-b-tooltip.hover title="Create a new jobcard">
                                        <a href="#" class="btn btn-success p-5 w-100 animated-strips">                                            
                                            <i class="d-block icon-sm icon-flag icons" style="font-size: 25px;"></i>
                                            <span class="d-block mt-4">Create Jobcard</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
    data () {
        return {
            isLoading: false,
            jobcards: null,
            paginationData: {},
            error: null
        }
    },
    created () {
        // fetch the data when the view is created and the data is
        // already being observed
        this.fetchData()
    },
    methods: {
        fetchData (page = 1) {
            const self = this;

            //  Start loader
            self.isLoading = true
            
            /*  Specify other related model data to collect, just separate by comma (,) and extend with a period (.)
             *  E.g) selectedContractors.branches will bring all selected contractors and their company branches
             */
            let connections = 'selectedContractors,priority';

            /*  Specify limit for pagination
             */
            let limit = 3;

            axios.get('/api/jobcards?connections='+connections+'&&limit='+limit+'&&page='+page)
                .then(({data}) => {
                    console.log(data);
                    
                    //  Get the jobcards
                    self.jobcards = data.data;
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

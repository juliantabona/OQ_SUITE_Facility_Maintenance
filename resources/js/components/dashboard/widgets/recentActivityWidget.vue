<template>
    <div class="row">
        <div class="col-lg-12 d-flex flex-column">
            <div class="row flex-grow">
                <div class="col-12 col-md-12 col-lg-12 grid-margin stretch-card">
                    <div class="card card-hoverable">
                        <div class="card-body">
                            <div v-show="isLoading" class="mt-4">
                                <img src="/images/assets/icons/star_loader.svg" alt="Loader" style=" width: 40px;">Getting activities...
                            </div>
                            <div v-if="activities && !isLoading">
                                <i class="float-left icon-flag icon-sm icons ml-3"></i>
                                <h6 class="card-title float-left mb-0 ml-2">All Activities</h6>
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
                                                <th style="width:10%;">Resource</th>
                                                <th style="width:15%;">Activity Type</th>
                                                <th style="width:45%;">Activity Details</th>
                                                <th style="width:15%;">Created At</th>
                                                <th style="width:15%;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="activity in activities" :key="activity.id" class='clickable-row' :data-href="'/activities/'+activity.id">
                                                <td>{{ nameActivity(activity) }}</td>  
                                                <td>{{ capitalizeFirstLetter(activity.type) }}</td>  
                                                <td v-html="describeActivity(activity)"></td>  
                                                <td v-b-tooltip.hover :title="activity.created_at | moment('H:mmA, DD MMM YYYY')">{{ activity.created_at | moment("DD MMM YYYY") }}</td>                                           
                                                <td>
                                                    <div class="badge badge-success badge-fw">
                                                        View
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex align-items-center justify-content-between flex-column flex-sm-row mt-4">
                                    <div class="row flex-grow">
                                        <div class="col-4">
                                            <p class="mb-3 ml-3 mb-sm-0"><strong>{{ paginationData.total }}</strong> {{ activities.total == 1 ? ' result': '  results' }} found</p>
                                        </div>
                                        <div class="col-8">
                                            <nav>
                                                <pagination :data="paginationData" @pagination-change-page="fetchData"></pagination>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-if="!activities && !isLoading">
                                <h6 class="card-title float-left mb-0 ml-2">No Activities</h6>
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
        data(){
            return {
                activities: [],
                paginationData: [],
                isLoading: false
            }
        },
        methods: {
            nameActivity(activity){
                //  Current resources name
                var resourceName = activity.trackable_type;
                //  Common resource names
                var resources = ['user', 'company', 'companybranch', 'jobcard', 'document'];
                //  Names we want to replace with
                var names = ['User', 'Company', 'Company Branch', 'Jobcard', 'Document'];

                //  For the activity locate the index location of its name 
                var position = resources.indexOf(resourceName);
                
                //  If the name exists lets replace with our prefered naming
                if(position != undefined){
                    return names[position];
                //  Otherwise return the original name
                }else{
                    return resourceName;
                }
            },
            describeActivity(activity){

                let creator = ' <a href="#">'+activity.created_by.first_name+' '+activity.created_by.last_name+'</a>';

                //  User type of activity
                if(activity.trackable_type == 'user'){
                    if(activity.type == 'created'){
                        return 'Account created for <a href="#" v-b-tooltip.hover :title="Sample tooltip">'+
                                    activity.detail.user.first_name+' '+activity.detail.user.last_name+
                                '</a> by' + creator;
                    }
                }

                //  Company type of activity
                if(activity.trackable_type == 'company'){
                    if(activity.type == 'created'){
                        return 'Company created,  <a href="#" v-b-tooltip.hover :title="Sample tooltip">'+
                                    activity.detail.company.name+
                                '</a> by' + creator;
                    }
                }

                //  Company type of activity
                if(activity.trackable_type == 'companybranch'){
                    if(activity.type == 'created'){
                        let location = activity.detail.branch.destination;
                        location = location ? ' located in ' + location : '';

                        return 'Company branch created and named <a href="#" v-b-tooltip.hover :title="Sample tooltip">'+
                                    '"'+activity.detail.branch.name+'"'+ 
                                '</a> by' + location + creator;
                    }
                }

                //  Activity type of activity
                if(activity.trackable_type == 'jobcard'){
                    if(activity.type == 'created'){
                        return 'Jobcard reference #'+activity.trackable_id+' - <a href="#" v-b-tooltip.hover :title="Sample tooltip">'+
                                    activity.detail.jobcard.title+ 
                                '</a> created by' + creator;
                    }
                }

                //  Activity type of activity
                if(activity.trackable_type == 'document'){
                    if(activity.type == 'jobcard uploaded'){
                        return 'Document ref #'+activity.trackable_id+' also named - <a href="#">'+
                                    activity.detail.document.name+ 
                                '</a> uploaded to Jobcard by' + creator;
                    }
                }
            },
            capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            },
            fetchData (page=1) {
                const self = this;

                //  Start loader
                self.isLoading = true
                
                /*  Specify other related model data to collect, just separate by comma (,) and extend with a period (.)
                *  E.g) selectedContractors.branches will bring all selected contractors and their company branches
                */
                let connections = 'createdBy';

                /*  Specify limit for pagination
                */
                let limit = 3;

                axios.get('/api/recentactivities?connections='+connections+'&page='+page)
                 .then(({data}) => {
                    console.log(data);
                    
                    //  Get the activities
                    self.activities = data.data;
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
        },
        created(){
            this.fetchData();
        }
    }
</script>


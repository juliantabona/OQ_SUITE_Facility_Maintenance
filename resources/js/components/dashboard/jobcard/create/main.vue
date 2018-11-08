<style>
    .ctk-date-time-picker .field.has-value .field-label{
        display:none !important;
    }

    .ctk-date-time-picker .field.has-value .field-input,
    .ctk-date-time-picker .field .field-input {
        padding-top: 0px !important;
        font-size: 12px !important;
    }

</style>

<template>
    <div class="row">
        <div class="col-lg-12 d-flex flex-column">
            <div class="row flex-grow">
                <div class="col-12 col-md-10 col-lg-9 grid-margin offset-md-1 stretch-card mb-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group mt-0">
                                <h3 class="float-left">Create Jobcard</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-10 col-lg-9 grid-margin offset-md-1 stretch-card">
                    <div class="card card-hoverable">
                        <div class="card-body p-3 pt-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="title">Title</label>
                                                <div :class="'form-group'+(errors.title ? ' has-error' : '')" 
                                                     v-b-tooltip.hover :title="'Jobcard short title/heading'">
                                                    <input type="text" name="title" v-model="title" :class="'form-control' 
                                                            +(errors.title ? ' is-invalid' : '')" 
                                                            placeholder="Enter jobcard title..." autofocus>
                                                    <span v-if="errors.title" class="help-block invalid-feedback d-block">
                                                        <strong>
                                                            {{ errors.title[0] }}
                                                        </strong>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <div :class="'form-group'+(errors.description ? ' has-error' : '')" 
                                                     v-b-tooltip.hover :title="'Jobcard detailed description of work to be done'">
                                                    <textarea type="text" name="description" v-model="description" :class="'form-control' 
                                                            +(errors.description ? ' is-invalid' : '')" 
                                                            placeholder="Enter jobcard description..." autofocus>
                                                    </textarea>
                                                    <span v-if="errors.description" class="help-block invalid-feedback d-block">
                                                        <strong>
                                                            {{ errors.description[0] }}
                                                        </strong>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <p class="m-0 mb-1">Start Date</p>
                                                <div class="input-group date datepicker p-0" 
                                                        v-b-tooltip.hover :title="'Date expected to start work'">
                                                    <vue-ctk-date-time-picker 
                                                        v-model="start_date" 
                                                        color="#ffb400"
                                                        format="YYYY-MM-DD HH:mm:ss">
                                                        </vue-ctk-date-time-picker>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <p class="m-0 mb-1">End Date</p>
                                                <div class="input-group date datepicker p-0" 
                                                        v-b-tooltip.hover :title="'Date expected to end work'">
                                                        <vue-ctk-date-time-picker 
                                                            v-model="end_date" 
                                                            color="#ffb400"
                                                            format="YYYY-MM-DD HH:mm:ss"></vue-ctk-date-time-picker>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="priority">Priority:</label>
                                                <div class="input-group mb-2">
                                                    <select v-model="priority" v-b-tooltip.hover :title="'Priority/Urgency of this work'"
                                                            id="priority" :class="'form-control'+(errors.priorities ? ' has-error' : '')+' custom-select'" name="priority">
                                                            <option v-for="priority in priorities" :key="priority.id" :value="priority.id">
                                                                {{ priority.name }}
                                                            </option>
                                                    </select>
                                                    {{ priority }}
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text p-0">
                                                            <button type="button" class="select-option-creation-btn btn btn-success pl-2 pr-1"
                                                                    v-b-tooltip.hover :title="'Add a new priority'">
                                                                <i class="icon-plus icons m-0"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <span v-if="errors.priorities" class="help-block invalid-feedback d-block">
                                                        <strong>
                                                            {{ errors.priorities[0] }}
                                                        </strong>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                    <label for="cost_center" class="m-0 p-0 w-100">Cost Center</label>
                                                <div class="input-group mb-2">
                                                    <select v-model="costcenter" v-b-tooltip.hover :title="'Departments/Facilities being costed doing this work'"
                                                            id="cost_center" :class="'form-control'+(errors.costcenters ? ' has-error' : '')+' custom-select'" name="cost_center">
                                                        <option v-for="costcenter in costcenters" :key="costcenter.id" :value="costcenter.id">
                                                            {{ costcenter.name }}
                                                        </option>
                                                    </select>
                                                    {{ costcenter }}
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text p-0">
                                                            <button type="button" class="select-option-creation-btn btn btn-success pl-2 pr-1"
                                                                    v-b-tooltip.hover :title="'Add a new priority'">
                                                                <i class="icon-plus icons m-0"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <span v-if="errors.costcenters" class="help-block invalid-feedback d-block">
                                                        <strong>
                                                            {{ errors.costcenters[0] }}
                                                        </strong>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="category" class="m-0 p-0 w-100">Job Category</label>
                                                <div class="input-group mb-2">
                                                    <select v-model="category" v-b-tooltip.hover :title="'Category that this job belongs to'"
                                                            id="category" :class="'form-control'+(errors.categories ? ' has-error' : '')+' custom-select'" name="category">
                                                        <option v-for="category in categories" :key="category.id" :value="category.id">
                                                            {{ category.name }}
                                                        </option>
                                                    </select>
                                                    {{ category }}
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text p-0">
                                                            <button type="button" class="select-option-creation-btn btn btn-success pl-2 pr-1"
                                                                    v-b-tooltip.hover :title="'Add a new priority'">
                                                                <i class="icon-plus icons m-0"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <span v-if="errors.categories" class="help-block invalid-feedback d-block">
                                                        <strong>
                                                            {{ errors.categories[0] }}
                                                        </strong>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="branch" class="m-0 p-0 w-100">Company Branch</label>
                                                <div class="input-group mb-2">
                                                    <select v-model="branch" v-b-tooltip.hover :title="'Company branch/destination to do this work'"
                                                            id="branch" :class="'form-control'+(errors.branches ? ' has-error' : '')+' custom-select'" name="branch">
                                                            <option v-for="branch in branches" :key="branch.id" :value="branch.id">
                                                                {{ branch.name }} ({{ branch.destination }})
                                                            </option>
                                                    </select>
                                                    {{ branch }}
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text p-0">
                                                            <button type="button" class="select-option-creation-btn btn btn-success pl-2 pr-1"
                                                                    v-b-tooltip.hover :title="'Add a new priority'">
                                                                <i class="icon-plus icons m-0"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <span v-if="errors.branches" class="help-block invalid-feedback d-block">
                                                        <strong>
                                                            {{ errors.branches[0] }}
                                                        </strong>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="assigned">Assigned to:</label>
                                                <div class="input-group mb-2">
                                                    <input v-b-tooltip.hover :title="'Person assigned to monitor this work'"
                                                            type="text" id="assigned" name="assigned" placeholder="Search for assignee..." 
                                                            :class="'form-control'+(errors.assigned ? ' has-error' : '')">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text p-0">
                                                            <button type="button" class="btn btn-success pl-2 pr-1" 
                                                                    v-b-tooltip.hover :title="'Add a new assignee'">
                                                                <i class="icon-plus icons m-0"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <span v-if="errors.assigned" class="help-block invalid-feedback d-block">
                                                        <strong>
                                                            {{ errors.assigned[0] }}
                                                        </strong>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div v-show="isCreating" class="mt-4">
                                                <img src="/images/assets/icons/star_loader.svg" alt="Loader" style=" width: 40px;">Creating jobcard...
                                            </div>
                                            <button v-show="!isCreating" type="button" @click="createJobcard" class="btn btn-success float-right pb-3 pl-5 pr-5 pt-3 ml-2">
                                                Create Jobcard
                                            </button>
                                        </div>
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
        props: ['id'],
        data () {
            return {
                title: '',
                description: '',
                start_date: '',
                end_date: '',
                priority: '',
                category: '',
                costcenter: '',
                branch:'',

                company: null,
                branches: [],
                categories: [],
                priorities: [],
                costcenters: [],

                errors: [],
                isLoading: false,
                isCreating:false
            }
        },
        created () {
            this.fetchData();
        },
        methods: {
            fetchData () {
                const self = this;

                //  Start loader
                self.isLoading = true
                
                /*  Specify other related model data to collect, just separate by comma (,) and extend with a period (.)
                *  E.g) selectedContractors.branches will bring all selected contractors and their company branches
                */
                let connections = 'categories,priorities,costcenters,branches';

                /*  Specify limit for pagination
                */
                let limit = 3;

                axios.get('/api/companies/1?connections='+connections)
                    .then(({data}) => {
                        console.log(data);
                        
                        //  Get the jobcard
                        self.company = data;
                        self.branches = data.branches;
                        self.categories = data.categories;
                        self.priorities = data.priorities;
                        self.costcenters = data.costcenters;

                        //  Stop loader
                        self.isLoading = false;
                        
                    }).catch(({response}) => { 
                        //  Stop loader
                        self.isLoading = false;   
                          
                        if(response){
                            console.error(response); 
                            //  Grab errors              
                            self.errors = response.data.errors;
                        }
                    });
            },
            createJobcard () {
                const self = this;

                //  Start loader
                self.isCreating = true
                
                /*  Specify other related model data to collect, just separate by comma (,) and extend with a period (.)
                *  E.g) selectedContractors.branches will bring all selected contractors and their company branches
                */
                let connections = 'client,contractorsList,category,priority,costcenter,createdby';

                /*  Specify limit for pagination
                */
                let limit = 3;

                let jobcardData = {
                    jobcard_title: this.title,
                    jobcard_description: this.description,
                    jobcard_start_date: this.start_date,
                    jobcard_end_date: this.end_date,
                    jobcard_priority_id: this.priority,
                    jobcard_category_id: this.category,
                    jobcard_cost_center_id: this.costcenter,
                    jobcard_company_branch_id: this.branch
                };

                axios.post('/api/jobcards', jobcardData)
                    .then(({data}) => {
                        console.log(data);
                        
                        //  Get the jobcard
                        self.jobcard = data;

                        //  Stop loader
                        self.isCreating = false;

                        //  Navigate to the jobcard
                        self.$router.push({ name: 'show-jobcard', params: { id: data.id }})
                        
                    }).catch(({response}) => { 
                        //  Stop loader
                        self.isCreating = false;   
                          
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

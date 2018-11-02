<style>

    .filterable .card {
        background: #fff;
        margin-bottom: 16px;
        border-radius: 2px;
    }

    .filterable .card-heading {
        padding: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .filterable .card-title {
        font-size: 16px;
        line-height: 24px;
    }

    .filterable .card-body {
        padding: 8px;
    }

    .filterable .card-footer {
        padding: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .filterable select {
        font-size: 12px;
        background: #fafafa;
        border: none;
        border-bottom: 1px solid rgba(0, 0, 0, 0.01);
        border-radius: 0px;
        box-shadow: inset 0 1px 1px 0 rgba(0, 0, 0, 0.1);
    }

    .filterable .form-control-sm {
        line-height: 12px;
        font-size: 12px;
        background: #fafafa;
        border: 1px solid #dcdcdc;
        border-bottom: 1px solid rgba(0, 0, 0, 0.01);
        border-radius: 2px;
        -webkit-box-shadow: inset 0 1px 1px 0 rgba(0, 0, 0, 0.1);
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.1);
        padding: 8px 8px 4px;
    }

    .filterable .filter-item {
        margin-bottom: 5px;
        display: flex;
    }
    .filterable .filter-column {
        padding-right: 15px;
        width: 25%;
    }
    .filterable .filter-operator {
        padding-right: 15px;
        width: 20%;
    }
    .filterable .filter-query_1 {
        width: 24%;
        padding-right: 15px;
    }
    .filterable .filter-full {
        width: 48%;
        padding-right: 15px;
    }

    .filterable .filter-query_2 {
        width: 24%;
        padding-right: 15px;
    }
    .filterable .filter-remove {
        width: 2%;
        text-align: right;
    }

    .filterable .filterable-export {
        margin-bottom: 15px;
        justify-content: space-between;
    }

</style>

<template>
    <div class="filterable">
        <div v-show="isLoading" class="card mt-4">
            <div class="card-body">
                <img src="/images/assets/icons/star_loader.svg" alt="Loader" style=" width: 40px;">Getting jobcards...
            </div>
        </div>
        <div v-show="!isLoading" class="card pt-4 pl-3 pr-3 pb-0">
            <div class="card-body">
                <div class="d-flex table-responsive">
                    <div class="d-flex table-responsive">
                        <i class="float-left icon-flag icon-sm icons ml-3"></i>
                        <h6 class="card-title float-left mb-0 ml-2">All Jobcards</h6>
                        <div class="btn-group ml-auto mr-2 border-0">
                            <input type="text" class="form-control" placeholder="Search Here">
                            <button class="btn btn-sm btn-primary"><i class="icon-magnifier icons"></i> Search</button>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-light"><i class="mdi mdi-printer"></i></button>
                            <button type="button" class="btn btn-light" @click="toggleAdvancedFilter"><i class="mdi mdi-filter"></i></button>
                            <button type="button" class="btn btn-light"><i class="mdi mdi-dots-vertical"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-show="!isLoading && advancedFilterShow" class="card pt-4 pl-3 pr-3 pb-0">
            <div class="card-body">
                <div class="row">
                    <div class="filterable-export col-5">
                        <span>Jobcards match</span> 
                        <select class="form-control-sm d-inline">
                            <option value="and">All</option> 
                            <option value="or">Any</option>
                        </select> 
                        <span>of the following:</span>
                    </div> 
                    <div class="col-7">
                        <label for="orderStatement" class="col-form-label float-left mr-2">
                            Order by:
                        </label>
                        <div class="float-left form-group mr-4">
                            <select :disabled="isLoading" @input="updateOrderColumn" 
                                    class="float-left form-control-sm" style="width: auto !important;">
                                    <option v-for="column in orderables"
                                        :value="column.name"
                                        :selected="column && column.name == query.order_column">
                                            {{column.title}}
                                    </option>
                            </select>
                            <strong @click="updateOrderDirection" class="btn btn-sm btn-light float-left">
                                <span v-if="query.order_direction === 'asc'">&uarr;</span>
                                <span v-else>&darr;</span>
                            </strong>
                        </div>
                        <button class="btn btn-sm btn-primary float-right mr-3" @click="applyFilter">Apply Filter</button>
                        <button class="btn btn-sm btn-secondary float-right mr-2" @click="resetFilter"
                            v-if="this.appliedFilters.length > 0">
                            Reset
                        </button>
                        <button class="btn btn-sm float-right mr-2" @click="addFilter">+</button>
                    </div>
                </div>

                <div class="filter">
                    <div class="filter-item" v-for="(f, i) in filterCandidates">
                        <div class="filter-column pr-1">
                            <div class="form-group mb-0">
                                <select class="form-control-sm w-100" @input="selectColumn(f, i, $event)">
                                    <option value="">Select a filter</option>
                                    <optgroup v-for="group in filterGroups" :label="group.name">
                                        <option v-for="x in group.filters"
                                            :value="JSON.stringify(x)"
                                            :selected="f.column && x.name === f.column.name">
                                                {{x.title}}
                                            </option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="filter-operator" v-if="f.column">
                            <div class="form-group">
                                <select class="form-control-sm w-100" @input="selectOperator(f, i, $event)">
                                    <option v-for="y in fetchOperators(f)"
                                        :value="JSON.stringify(y)"
                                        :selected="f.operator && y.name === f.operator.name">
                                            {{y.title}}
                                        </option>
                                </select>
                            </div>
                        </div>
                        <template v-if="f.column && f.operator">
                            <div class="filter-full" v-if="f.operator.component === 'single'">
                                <input type="text" class="form-control-sm w-100" v-model="f.query_1">
                            </div>
                            <template  v-if="f.operator.component === 'double'">
                                <div class="filter-query_1">
                                    <input type="text" class="form-control-sm w-100" v-model="f.query_1">
                                </div>
                                <div class="filter-query_2">
                                    <input type="text" class="form-control-sm w-100" v-model="f.query_2">
                                </div>
                            </template>
                            <template v-if="f.operator.component === 'datetime_1'">
                                <div class="filter-query_1">
                                    <input type="text" class="form-control-sm w-100" v-model="f.query_1">
                                </div>
                                <div class="filter-query_2">
                                    <select class="form-control-sm w-100" v-model="f.query_2">
                                        <option>hours</option>
                                        <option>days</option>
                                        <option>months</option>
                                        <option>years</option>
                                    </select>
                                </div>
                            </template>
                            <template v-if="f.operator.component === 'datetime_2'">
                                <div class="filter-query_2">
                                    <select class="form-control-sm w-100" v-model="f.query_1">
                                        <option value="yesterday">yesterday</option>
                                        <option value="today">today</option>
                                        <option value="tomorrow">tomorrow</option>
                                        <option value="last_month">last month</option>
                                        <option value="this_month">this month</option>
                                        <option value="next_month">next month</option>
                                        <option value="last_year">last year</option>
                                        <option value="this_year">this year</option>
                                        <option value="next_year">next year</option>
                                    </select>
                                </div>
                            </template>
                        </template>
                        <div class="filter-remove" v-if="f">
                            <button @click="removeFilter(f, i)" class="btn btn-sm btn-danger">x</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">  
                            <button @click="exportToCSV()" class="btn btn-sm btn-primary float-right mr-3">Export</button> 
                        </div>
                    </div> 
                </div>
            </div>
        </div>
        <div class="card card-hoverable" v-show="collection.data && collection.data.length && !isLoading">
            <div class="card-body">
                <div class="table-responsive table-hover">
                    <table class="table pt-2">
                        <slot name="thead"></slot>
                        <tbody>
                            <slot v-for="item in collection.data"
                                  :item="item">
                            </slot>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer border-bottom border-right border-left">
                <div>
                    <select v-model="query.limit" :disabled="isLoading" @change="updateLimit" class="form-control-sm bg-primary text-white mr-2">
                        <option>2</option>
                        <option>10</option>
                        <option>20</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                    <small> Showing {{collection.from}} - {{collection.to}} of {{collection.total}} entries.</small>
                </div>
                <div>
                    <pagination :data="collection" @pagination-change-page="fetch"></pagination>
                </div>
            </div>
             
        </div>
        <div class="card bg-warning p-2" v-show="!collection.data.length && !isLoading">
            <div class="card-body">
                No records found
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import axios from 'axios'

    export default {
        props: {
            url: String,
            filterGroups: Array,
            orderables: Array
        },
        data() {
            return {
                isLoading: true,
                appliedFilters: [],
                filterCandidates: [],
                advancedFilterShow: false,
                query: {
                    order_column: 'created_at',
                    order_direction: 'desc',
                    filter_match: 'and',
                    limit: 10,
                    page: 1
                },
                collection: {
                    data: []
                }
            }
        },
        computed: {
            fetchOperators() {
                return (f) => {
                    return this.availableOperators().filter((operator) => {
                        if(f.column && operator.parent.includes(f.column.type)) {
                            return operator
                        }
                    })
                }
            },
        },
        mounted() {
            this.fetch()
            this.addFilter()
        },
        methods: {
            toggleAdvancedFilter(){
                return this.advancedFilterShow = !this.advancedFilterShow;
            },
            updateOrderDirection() {
                if(this.query.order_direction === 'desc') {
                    this.query.order_direction = 'asc'
                } else {
                    this.query.order_direction = 'desc'
                }
                this.applyChange()
            },
            updateOrderColumn(e) {
                const value = e.target.value
                Vue.set(this.query, 'order_column', value)
                this.applyChange()
            },
            exportToCSV() {
                // next video
            },
            resetFilter() {
                this.appliedFilters.splice(0)
                this.filterCandidates.splice(0)
                this.addFilter()
                this.query.page = 1
                this.applyChange()
            },
            applyFilter() {
                Vue.set(this.$data, 'appliedFilters',
                    JSON.parse(JSON.stringify(this.filterCandidates))
                )
                this.query.page = 1;
                this.applyChange()
            },
            removeFilter(f, i) {
                this.filterCandidates.splice(i, 1)
            },
            selectOperator(f, i, e) {
                let value = e.target.value
                if(value.length === 0) {
                    Vue.set(this.filterCandidates[i], 'operator', value)
                    return
                }

                let obj = JSON.parse(value)

                Vue.set(this.filterCandidates[i], 'operator', obj)

                this.filterCandidates[i].query_1 = null
                this.filterCandidates[i].query_2 = null

                // set default query

                switch(obj.name) {
                    case 'in_the_past':
                    case 'in_the_next':
                        this.filterCandidates[i].query_1 = 28
                        this.filterCandidates[i].query_2 = 'days'
                        break;
                    case 'in_the_peroid':
                        this.filterCandidates[i].query_1 = 'today'
                        break;
                }
            },
            selectColumn(f, i, e) {
                let value = e.target.value
                if(value.length === 0) {
                    Vue.set(this.filterCandidates[i], 'column', value)
                    return
                }

                let obj = JSON.parse(value)

                Vue.set(this.filterCandidates[i], 'column', obj)

                // set default operator: todo
                switch(obj.type) {
                    case 'numeric':
                        this.filterCandidates[i].operator = this.availableOperators()[4]
                        this.filterCandidates[i].query_1 = null
                        this.filterCandidates[i].query_2 = null
                        break;
                    case 'string':
                        this.filterCandidates[i].operator = this.availableOperators()[6]
                        this.filterCandidates[i].query_1 = null
                        this.filterCandidates[i].query_2 = null
                        break;
                    case 'datetime':
                        this.filterCandidates[i].operator = this.availableOperators()[9]
                        this.filterCandidates[i].query_1 = 28
                        this.filterCandidates[i].query_2 = 'days'
                        break;
                    case 'counter':
                        this.filterCandidates[i].operator = this.availableOperators()[14]
                        this.filterCandidates[i].query_1 = null
                        this.filterCandidates[i].query_2 = null
                        break;
                }
            },
            addFilter() {
                this.filterCandidates.push({
                    column: '',
                    operator: '',
                    query_1: null,
                    query_2: null
                })
            },
            applyChange() {
                this.fetch()
            },
            updateLimit() {
                this.query.page = 1
                this.applyChange()
            },
            getFilters() {
                const f = {}

                this.appliedFilters.forEach((filter, i) => {
                    f[`f[${i}][column]`] = filter.column.name
                    f[`f[${i}][operator]`] = filter.operator.name
                    f[`f[${i}][query_1]`] = filter.query_1
                    f[`f[${i}][query_2]`] = filter.query_2
                })

                return f
            },
            fetch (page = 1) {
                this.query.page = page;

                this.isLoading = true
                const filters = this.getFilters()

                const params = {
                    ...filters,
                    ...this.query
                }

                axios.get(this.url, {params: params})
                    .then((res) => {
                        if(!res.data.data.length){
                            console.log('No jobcards found.');
                            this.collection.data = [];
                        }else{
                            this.collection = res.data;
                            this.query.page = res.data.current_page
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                        this.collection.data = [];
                    })
                    .finally(() => {
                        this.isLoading = false;
                    })
            },
            availableOperators() {
                return [
                    {title: 'equal to', name: 'equal_to', parent: ['numeric', 'string'], component: 'single'},
                    {title: 'not equal to', name: 'not_equal_to', parent: ['numeric', 'string'], component: 'single'},
                    {title: 'less than', name: 'less_than', parent: ['numeric'], component: 'single'},
                    {title: 'greater than', name: 'greater_than', parent: ['numeric'], component: 'single'},

                    {title: 'between', name: 'between', parent: ['numeric'], component: 'double'},
                    {title: 'not between', name: 'not_between', parent: ['numeric'], component: 'double'},

                    {title: 'contains', name: 'contains', parent: ['string'], component: 'single'},
                    {title: 'starts with', name: 'starts_with', parent: ['string'], component: 'single'},
                    {title: 'ends with', name: 'ends_with', parent: ['string'], component: 'single'},

                    {title: 'in the past', name: 'in_the_past', parent: ['datetime'], component: 'datetime_1'},
                    {title: 'in the next', name: 'in_the_next', parent: ['datetime'], component: 'datetime_1'},
                    {title: 'in the peroid', name: 'in_the_peroid', parent: ['datetime'], component: 'datetime_2'},

                    {title: 'equal to', name: 'equal_to_count', parent: ['counter'], component: 'single'},
                    {title: 'not equal to', name: 'not_equal_to_count', parent: ['counter'], component: 'single'},
                    {title: 'less than', name: 'less_than_count', parent: ['counter'], component: 'single'},
                    {title: 'greater than', name: 'greater_than_count', parent: ['counter'], component: 'single'},
                ]
            }
        }
    }
</script>

<template>
    <div class="row">
        <div class="col-12">
            <loader v-bind:isLoaded="isLoaded"></loader>
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
                    <span data-toggle="tooltip" data-placement="top" 
                        :title="jobcard.category.description" class="lower-font mr-4">
                        <b>Catergory: </b>{{ jobcard.category.name }}
                    </span>
                </div>
                <div class="col-6">
                    <span class="lower-font" data-toggle="tooltip" data-placement="top" 
                        :title="jobcard.cost_center.description">
                        <b>Cost Center: </b>{{ jobcard.cost_center.name }}
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-6" v-if="createdBy">
                    <span class="lower-font">
                        <b>Created By: </b>
                        <a href="#">{{ createdBy.first_name+' '+createdBy.last_name }}</a>
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
            <div class="row" v-if="jobcard.priority">
                <div class="col-12">
                    <span class="lower-font mt-3 d-block">
                        <b>Priority: </b>
                        <div data-toggle="tooltip" data-placement="top" 
                            :title="jobcard.priority.description"
                            class="badge badge-success" 
                            :style="'background:'+jobcard.priority.color_code+';'">
                            {{ jobcard.priority.name }}
                        </div>
                    </span>
                </div>
            </div>
            <document-list v-bind:documents="jobcard.documents" v-bind:model-id="jobcard.id" model-type="jobcard"></document-list>
        </div>
    </div>
</template>

<script>
    export default {
        props:['jobcard', 'createdBy'],
        data: function () {
                return {
                    isLoaded: false
                }
        },
        created(){
            this.isLoaded = true;
        }
    }
</script>

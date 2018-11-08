<template>
    <div class="container">
        <div class="content">
            <filterable v-bind="filterable" :resource-name="'activity'">
                <template slot="header-title">
                    <i class="icon-graph mr-2"></i>
                    Recent Activities
                </template>
                <thead slot="thead">
                    <tr>
                        <th style="width:10%;">Resource</th>
                        <th style="width:15%;">Activity Type</th>
                        <th style="width:45%;">Activity Details</th>
                        <th style="width:15%;">Created At</th>
                        <th style="width:15%;">Action</th>
                    </tr>
                </thead>
                <tr slot-scope="{item}" @click="navigateTo(item.id)">
                    <td>{{ nameActivity(item) }}</td>  
                    <td>{{ capitalizeFirstLetter(item.type) }}</td>  
                    <td v-html="describeActivity(item)"></td>  
                    <td v-b-tooltip.hover :title="item.created_at | moment('H:mmA, DD MMM YYYY')">{{ item.created_at | moment("DD MMM YYYY") }}</td>                                           
                    <td>
                        <div class="badge badge-success badge-fw">
                            View
                        </div>
                    </td>
                </tr>
            </filterable>
        </div>
    </div>
</template>
<script type="text/javascript">
    import Filterable from '../../../layout/Filterable.vue'
    export default {
        components: { Filterable },
        data() {
            return {
                filterable: {
                    url: '/api/recentactivities?connections=createdBy',
                    orderables: [
                        {title: 'Id', name: 'id'},
                        {title: 'Type', name: 'type'},
                        {title: 'Created At', name: 'created_at'},
                    ],
                    filterGroups: [
                        {
                            name: 'Recent Activity',
                            filters: [
                                {title: 'Id', name: 'id', type: 'numeric'},
                                {title: 'Type', name: 'type', type: 'string'},
                                {title: 'Created At', name: 'created_at', type: 'datetime'}
                            ]
                        }
                    ]
                }
            }
        },
        methods: {
            navigateTo(id) {
                this.$router.push({ name: 'show-jobcard', params: { id: id } });
            },
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
            }
        }
    }
</script>

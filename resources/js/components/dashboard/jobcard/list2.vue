<template>
    <div class="container">
        <div class="content">
            <filterable v-bind="filterable">
                <thead slot="thead">
                    <tr>
                        <th style="width: 5%">ID</th>
                        <th style="width: 28%">Title</th>
                        <th style="width: 15%">Start Date</th>
                        <th style="width: 15%">End Date</th>
                        <th style="width: 18%" class="d-sm-none d-md-table-cell">Contractor</th>
                        <th style="width: 14%">Due</th>
                        <th class="d-sm-none d-md-table-cell">Priority</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tr slot-scope="{item}" @click="navigateTo(item.id)">
                    <td class="d-none d-md-table-cell">{{ item.id }}</td>   
                    <td v-b-tooltip.hover :title="item.description">{{ item.title ? item.title:'____' }}</td>
                    <td v-b-tooltip.hover :title="item.start_date | moment('H:mmA, DD MMM YYYY')">{{ item.start_date | moment("DD MMM YYYY") }}</td>
                    <td v-b-tooltip.hover :title="item.end_date | moment('H:mmA, DD MMM YYYY')">{{ item.end_date | moment("DD MMM YYYY") }}</td>
                    
                    <td v-b-tooltip.hover :title="item.selected_contractors.length ? item.selected_contractors[0].description : '____'" class="d-none d-md-table-cell" data-container="body">
                        {{  item.selected_contractors.length ? item.selected_contractors[0].name : '____'}}    
                    </td>      
                    <td class="d-none d-md-table-cell">3 days</td>                  
                    <td v-b-tooltip.hover :title="item.priority ? item.priority.description : '____'" class="d-none d-md-table-cell" data-container="body">
                        {{  item.priority ? item.priority.name : '____'}}    
                    </td>                                             
                    <td>
                        <div class="badge badge-success badge-fw">
                            Closed
                        </div>
                    </td>
                </tr>
            </filterable>
        </div>
    </div>
</template>
<script type="text/javascript">
    import Filterable from '../../layout/Filterable.vue'
    export default {
        components: { Filterable },
        data() {
            return {
                filterable: {
                    url: '/api/jobcards?connections=selectedContractors,priority',
                    orderables: [
                        {title: 'Id', name: 'id'},
                        {title: 'Title', name: 'title'},
                        {title: 'Start Date', name: 'start_date'},
                        {title: 'End Date', name: 'end_date'},
                        {title: 'Created At', name: 'created_at'},
                    ],
                    filterGroups: [
                        {
                            name: 'Jobcard',
                            filters: [
                                {title: 'Id', name: 'id', type: 'numeric'},
                                {title: 'Title', name: 'title', type: 'string'},
                                {title: 'Description', name: 'description', type: 'string'},
                                {title: 'Start Date', name: 'start_date', type: 'datetime'},
                                {title: 'End Date', name: 'end_date', type: 'datetime'},
                                {title: 'Created At', name: 'created_at', type: 'datetime'},
                            ]
                        },
                        {
                            name: 'Documents',
                            filters: [
                                {title: 'Document Count', name: 'documents.count', type: 'counter'},
                                {title: 'Document Id', name: 'documents.id', type: 'numeric'},
                                {title: 'Document Name', name: 'documents.name', type: 'string'},
                                {title: 'Document Type', name: 'documents.type', type: 'string'},
                                {title: 'Document Mime', name: 'documents.mime', type: 'string'},
                                {title: 'Document Size', name: 'documents.size', type: 'numeric'},
                                {title: 'Document Link', name: 'documents.url', type: 'string'},
                                {title: 'Document Created At', name: 'documents.created_at', type: 'datetime'},
                            ]
                        },
                        {
                            name: 'Client',
                            filters: [
                                {title: 'Client Id', name: 'client.id', type: 'numeric'},
                                {title: 'Client Name', name: 'client.name', type: 'string'},
                                {title: 'Client City', name: 'client.city', type: 'string'},
                                {title: 'Client State Or Region', name: 'client.state_or_region', type: 'string'},
                                {title: 'Client Address', name: 'client.address', type: 'string'},
                                {title: 'Client Industry', name: 'client.industry', type: 'string'},
                                {title: 'Client Type', name: 'client.type', type: 'string'},
                                {title: 'Client Website Link', name: 'client.website_link', type: 'string'},
                                {title: 'Client Phone ext', name: 'client.phone_ext', type: 'numeric'},
                                {title: 'Client Phone Number', name: 'client.phone_num', type: 'numeric'},
                                {title: 'Client Email', name: 'client.email', type: 'string'},
                                {title: 'Client Created At', name: 'client.created_at', type: 'datetime'},
                            ]
                        },
                        {
                            name: 'With Contractors',
                            filters: [
                                {title: 'Contractor Count', name: 'contractorsList.count', type: 'counter'},
                                {title: 'Contractor Id', name: 'contractorsList.id', type: 'numeric'},
                                {title: 'Contractor Name', name: 'contractorsList.name', type: 'string'},
                                {title: 'Contractor City', name: 'contractorsList.city', type: 'string'},
                                {title: 'Contractor State Or Region', name: 'contractorsList.state_or_region', type: 'string'},
                                {title: 'Contractor Address', name: 'contractorsList.address', type: 'string'},
                                {title: 'Contractor Industry', name: 'contractorsList.industry', type: 'string'},
                                {title: 'Contractor Type', name: 'contractorsList.type', type: 'string'},
                                {title: 'Contractor Website Link', name: 'contractorsList.website_link', type: 'string'},
                                {title: 'Contractor Phone ext', name: 'contractorsList.phone_ext', type: 'numeric'},
                                {title: 'Contractor Phone Number', name: 'contractorsList.phone_num', type: 'numeric'},
                                {title: 'Contractor Email', name: 'contractorsList.email', type: 'string'},
                                {title: 'Contractor Created At', name: 'contractorsList.created_at', type: 'datetime'},
                            ]
                        }
                    ]
                }
            }
        },
        methods: {
            navigateTo(id) {
                this.$router.push({ name: 'show-jobcard', params: { id: id } });
            }
        }
    }
</script>

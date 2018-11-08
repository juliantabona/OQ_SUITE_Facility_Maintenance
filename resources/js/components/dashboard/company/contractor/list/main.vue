<template>
    <div class="container">
        <div class="content">
            <filterable v-bind="filterable" :resource-name="'contractors'">
                <template slot="header-title">
                    <i class="icon-briefcase mr-2"></i>
                    Contractors
                </template>
                <thead slot="thead">
                    <tr>
                        <th style="width: 5%">Logo</th>
                        <th style="width: 15%">Name</th>
                        <th style="width: 15%">City</th>
                        <th style="width: 15%">Industry</th>
                        <th style="width: 15%">Email</th>
                        <th style="width: 18%">Phone</th>
                    </tr>
                </thead>
                <tr slot-scope="{item}" @click="navigateTo(item.id)">
                    <td class="d-none d-md-table-cell">                            
                        <div v-if="item.logo_url" class="lightgallery">
                            <a :href="item.logo_url">
                                <img class="company-logo img-thumbnail mb-2 p-2 rounded rounded-circle w-50" 
                                    :src="item.logo_url" />
                            </a>
                        </div>
                    </td>  
                    <td class="d-none d-md-table-cell">{{ item.name ? item.name:'____' }}</td>  
                    <td class="d-none d-md-table-cell">{{ item.city ? item.city:'____' }}</td>  
                    <td class="d-none d-md-table-cell">{{ item.industry }}</td>  
                    <td class="d-none d-md-table-cell">{{ item.email }}</td>  
                    <td class="d-none d-md-table-cell">
                        {{ item.phone_ext ? '+'+item.phone_ext+'-':'___-' }}
                        {{ item.phone_num ? item.phone_num:'____' }}
                    </td>  

                </tr>
            </filterable>
        </div>
    </div>
</template>
<script type="text/javascript">
    import Filterable from '../../../../layout/Filterable.vue'
    export default {
        components: { Filterable },
        data() {
            return {
                filterable: {
                    url: '/api/contractors?id='+auth.user.company_branch_id,
                    orderables: [
                        {title: 'Id', name: 'id'},
                        {title: 'Name', name: 'name'},
                        {title: 'Description', name: 'description'},
                        {title: 'City', name: 'city'},
                        {title: 'State Or Region', name: 'state_or_region'},
                        {title: 'Address', name: 'address'},
                        {title: 'Industry', name: 'city'},
                        {title: 'Website Link', name: 'website_link'},
                        {title: 'Phone Ext', name: 'city'},
                        {title: 'Phone Num', name: 'city'},
                        {title: 'Email', name: 'email'},
                        {title: 'Created At', name: 'created_at'},
                    ],
                    filterGroups: [
                        {
                            name: 'Jobcard',
                            filters: [
                                {title: 'Id', name: 'id', type: 'numeric'},
                                {title: 'Name', name: 'name', type: 'string'},
                                {title: 'City', name: 'city', type: 'string'},
                                {title: 'State Or Region', name: 'state_or_region', type: 'string'},
                                {title: 'Address', name: 'address', type: 'string'},
                                {title: 'Industry', name: 'industry', type: 'string'},
                                {title: 'Website Link', name: 'website_link', type: 'string'},
                                {title: 'Phone Ext', name: 'phone_ext', type: 'numeric'},
                                {title: 'Phone Num', name: 'phone_num', type: 'numeric'},
                                {title: 'Email', name: 'email', type: 'string'},
                                {title: 'Created At', name: 'created_at', type: 'datetime'},
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

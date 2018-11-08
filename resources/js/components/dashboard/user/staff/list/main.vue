<template>
    <div class="container">
        <div class="content">
            <filterable v-bind="filterable" :resource-name="'staff'">
                    <template slot="header-title">
                        <i class="icon-user mr-2"></i>
                        Staff Members
                    </template>
                <thead slot="thead">
                    <tr>
                        <th style="width: 5%">Avatar</th>
                        <th style="width: 15%">First Name</th>
                        <th style="width: 15%">Last Name</th>
                        <th style="width: 15%">Gender</th>
                        <th style="width: 15%">Email</th>
                        <th style="width: 18%">Phone</th>
                    </tr>
                </thead>
                <tr slot-scope="{item}" @click="navigateTo(item.id)">
                    <td class="d-none d-md-table-cell">                            
                        <div v-if="item.avatar" class="lightgallery">
                            <a :href="item.avatar">
                                <img class="img-thumbnail" 
                                    :src="item.avatar" />
                            </a>
                        </div>
                    </td>  
                    <td class="d-none d-md-table-cell">{{ item.first_name ? item.first_name:'____' }}</td> 
                    <td class="d-none d-md-table-cell">{{ item.last_name ? item.last_name:'____' }}</td>  
                    <td class="d-none d-md-table-cell">{{ item.gender ? item.gender:'____' }}</td>  
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
                    url: '/api/staff?id='+auth.user.company_branch_id,
                    orderables: [
                        {title: 'Id', name: 'id'},
                        {title: 'First Name', name: 'first_name'},
                        {title: 'Last Name', name: 'last_name'},
                        {title: 'Gender', name: 'gender'},
                        {title: 'Date Of Birth', name: 'date_of_birth'},
                        {title: 'Biography', name: 'bio'},
                        {title: 'Address', name: 'address'},
                        {title: 'Phone Ext', name: 'phone_ext'},
                        {title: 'Phone Num', name: 'phone_num'},
                        {title: 'Email', name: 'email'},
                        {title: 'Additional Email', name: 'additional_email'},
                        {title: 'Position', name: 'position'},
                        {title: 'Country', name: 'country'},
                        {title: 'City', name: 'city'},
                        {title: 'Accessibility', name: 'accessibility'},
                        {title: 'Created At', name: 'created_at'},
                    ],
                    filterGroups: [
                        {
                            name: 'Staff',
                            filters: [
                                {title: 'Id', name: 'id', type: 'numeric'},
                                {title: 'First Name', name: 'first_name', type: 'string'},
                                {title: 'Last Name', name: 'last_name', type: 'string'},
                                {title: 'Gender', name: 'gender', type: 'string'},
                                {title: 'Date Of Birth', name: 'date_of_birth', type: 'datetime'},
                                {title: 'Biography', name: 'bio', type: 'string'},
                                {title: 'Address', name: 'address', type: 'string'},
                                {title: 'Phone Ext', name: 'phone_ext', type: 'numeric'},
                                {title: 'Phone Num', name: 'phone_num', type: 'numeric'},
                                {title: 'Email', name: 'email', type: 'string'},
                                {title: 'Additional Email', name: 'additional_email', type: 'string'},
                                {title: 'Position', name: 'position', type: 'string'},
                                {title: 'Country', name: 'country', type: 'string'},
                                {title: 'City', name: 'city', type: 'string'},
                                {title: 'Accessibility', name: 'accessibility', type: 'string'},
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

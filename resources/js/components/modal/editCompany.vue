<template>
    <b-modal  v-model="modalShow"
              :ref="'edit'+companyKind+'Modal'" 
              :title="'Edit '+companyKind" 
              :ok-title="'Save Changes'" 
              @ok="updateCompany"
              @hidden="$emit('hidden')">

        <loader v-bind:isLoading="isLoading" v-bind:msg="'Updating '+companyKind+'...'"></loader>
         
        <div v-if="!isLoading" class="row p-0">
            <div class="col-12">
                <h4>Company Details*</h4>
                <div :class="'form-group'+(errors.company_name ? ' has-error' : '')">
                    <input type="text" placeholder="Enter company name..." class="form-control" 
                            autocomplete="off" v-model="company_name">      
                </div>
                <span v-if="errors.company_name" class="help-block invalid-feedback d-block">
                    <strong>
                        {{ errors.company_name[0] }}
                    </strong>
                </span>
            </div>

            <div class="col-12">
                <div :class="'form-group'+(errors.company_city ? ' has-error' : '')">
                    <input type="text" placeholder="Enter company city..." class="form-control" 
                            autocomplete="off" v-model="company_city">      
                </div>
                <span v-if="errors.company_city" class="help-block invalid-feedback d-block">
                    <strong>
                        {{ errors.company_city[0] }}
                    </strong>
                </span>
            </div>

            <div class="col-12">
                <div :class="'form-group'+(errors.company_state_or_region ? ' has-error' : '')">
                    <input type="text" placeholder="Enter company state/region..." class="form-control" 
                            autocomplete="off" v-model="company_state_or_region">      
                </div>
                <span v-if="errors.company_state_or_region" class="help-block invalid-feedback d-block">
                    <strong>
                        {{ errors.company_state_or_region[0] }}
                    </strong>
                </span>
            </div>

            <div class="col-12">
                <div :class="'form-group'+(errors.company_address ? ' has-error' : '')">
                    <input type="text" placeholder="Enter company address..." class="form-control" 
                            autocomplete="off" v-model="company_address">      
                </div>
                <span v-if="errors.company_address" class="help-block invalid-feedback d-block">
                    <strong>
                        {{ errors.company_address[0] }}
                    </strong>
                </span>
            </div>

            <div class="col-12">
                <h4>Contact Details*</h4>
                <div :class="'form-group'+(errors.company_email ? ' has-error' : '')">
                    <input type="email" placeholder="Enter company email address" class="form-control" 
                            autocomplete="off" v-model="company_email">      
                </div>
                <span v-if="errors.company_email" class="help-block invalid-feedback d-block">
                    <strong>
                        {{ errors.company_email[0] }}
                    </strong>
                </span>
            </div>

            <div class="col-12">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">+</span>
                    </div>
                    
                    <input type="text" class="form-control" placeholder="Country code..." autocomplete="off">
                    <input type="text" class="form-control" placeholder="Phone number..." autocomplete="off">
                    
                    <span v-if="errors.company_phone_ext" class="help-block invalid-feedback d-block">
                        <strong>
                            {{ errors.company_phone_ext[0] }}
                        </strong>
                    </span>
                    <span v-if="errors.company_phone_num" class="help-block invalid-feedback d-block">
                        <strong>
                            {{ errors.company_phone_num[0] }}
                        </strong>
                    </span>
                </div>
            </div>

            <div v-if="companyRelation == 'contractor'" class="col-12">
                <h4 class="mt-3">Quotation Details*</h4>
                <div :class="'form-group'+(errors.company_total_price ? ' has-error' : '')">
                    <input type="text" placeholder="Enter total price..." class="form-control" 
                            autocomplete="off" v-model="company_total_price">      
                </div>
                <span v-if="errors.company_total_price" class="help-block invalid-feedback d-block">
                    <strong>
                        {{ errors.company_total_price[0] }}
                    </strong>
                </span>
            </div>
        </div>

    </b-modal>
</template>

<script>
    export default {
        props:['jobcardId', 'companyRelation', 'currentCompany'],
        data(){
            return {
                errors: [],
                isLoading: false,
                modalShow: false,

                company_name: this.currentCompany.name,
                company_description: this.currentCompany.description,
                company_city: this.currentCompany.city,
                company_state_or_region: this.currentCompany.state_or_region,
                company_address: this.currentCompany.address,
                company_industry: this.currentCompany.industry,
                company_type: this.currentCompany.type,
                company_website_link: this.currentCompany.website_link,
                company_profile_doc_url: this.currentCompany.profile_doc_url,
                company_phone_num: this.currentCompany.phone_num,
                company_phone_ext: this.currentCompany.phone_ext,
                company_email: this.currentCompany.email,

                company_total_price: this.getContractorAmount()
            }
        },
        computed: {
            companyKind: function(){
                //  Capitalize first letter
                return this.companyRelation.charAt(0).toUpperCase() + this.companyRelation.slice(1)
            }
        },
        created(){
            //  Hide all tooltips
            this.$root.$emit('bv::hide::tooltip');
            //  Immediately show the modal
            this.modalShow = true;
        },
        methods: {
            getContractorAmount: function(){
                if(this.currentCompany.pivot){
                    return this.currentCompany.pivot.amount;
                }else{
                    return null;
                }
            },
            updateCompany: function(e){
                e.preventDefault();
                this.isLoading = true;
                //this.validate();
                this.runUpdate();
            },
            validate: function(){

            },
            runUpdate: function(){
                const self = this;
                //  Start loader
                self.isLoading = true

                let companyData = {
                    //  Company Details
                    company_name: this.company_name,
                    company_description: this.company_description,
                    company_city: this.company_city,
                    company_state_or_region: this.company_state_or_region,
                    company_address: this.company_address,
                    company_industry: this.company_industry,
                    company_type: this.company_type,
                    company_website_link: this.company_website_link,
                    company_profile_doc_url: this.company_profile_doc_url,
                    //company_phone_num: this.company_phone_num,
                    //company_phone_ext: this.company_phone_ext,
                    company_email: this.company_email,
                    
                    //  Details to assign to jobcard
                    company_relation: this.companyRelation,  //  e.g) client, contractor, e.t.c
                    jobcard_id: this.jobcardId,

                    //  Extra details for contractors
                    company_total_price: this.company_total_price
                };
                console.log('send update data');
                console.log(companyData);

                axios.put('/api/companies/'+this.currentCompany.id, companyData)
                    .then(({data}) => {
                        console.log('company updated');
                        console.log(data);

                        //  Get the company
                        let company = data;

                        //  Stop loader
                        self.isLoading = false;

                        //  Emit to update other components
                        self.$emit('companyUpdated', company);

                        this.$snotify.success(this.companyKind+' updated!');

                        //  Immediately hide the modal
                        this.modalShow = false;
                        
                    })
                    .catch(({response}) => { 
                        //  Stop loader
                        self.isLoading = false;   
                          
                        if(response){
                            console.error(response);
                            //  Grab errors              
                            self.errors = response.errors;
                        }
                    });
            }
        }
    }
</script>

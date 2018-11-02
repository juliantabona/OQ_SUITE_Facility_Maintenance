<template>
    <div class="row">
        <div class="col-12">
            <loader v-bind:isLoading="isLoading" v-bind:msg="'Getting documents...'"></loader>
            <div class="row" v-if="docs && !isLoading">
                <div class="col-12">
                    <div class="table-responsive table-hover">
                        <table class="table mt-3 border-top">
                            <thead>
                                <tr>
                                    <th>Image/File</th>
                                    <th style="max-width: 100px; ">Name</th>
                                    <th>Type</th>
                                    <th>Size</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="document in docs" :key="document.id" class="clickable-row">
                                    <td>
                                        <div class="lightgallery mt-3">
                                            <a :href="document.url">
                                                <img :src="document.url" />
                                            </a>
                                        </div>
                                    </td>
                                    <td>{{ document.name  }}</td>
                                    <td>{{ document.mime }}</td>
                                    <td>{{ getFileSizeWithUnits(document.size) }}</td>
                                    <td><span class="remove-file">Delete</span></td>
                                </tr>
                                <tr v-if="docs.length != 0"
                                    class="clickable-row">
                                    <td></td>
                                    <td></td>
                                    <td>TOTAL SIZE</td>
                                    <td>{{ getFileTotalSizeWithUnits() }}</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="d-flex align-items-center justify-content-between flex-column flex-sm-row mt-4">
                            <p class="mb-3 ml-3 mb-sm-0"><strong>{{ paginationData.total }}</strong> {{ docs.total == 1 ? ' result': '  results' }} found</p>
                            <nav>
                                <pagination :data="paginationData" @pagination-change-page="fetchData"></pagination>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <document-uploader
            v-if="!isLoading && storageLocation && groupType"
            v-bind:model-id="modelId" v-bind:model-type="modelType"  
            v-bind:storage-location="storageLocation" v-bind:group-type="groupType" 
            v-on:uploaded="updateDocuments">
        </document-uploader>
    </div>
</template>

<script>
    export default {
        props:{
            modelId: [Number, String], 
            modelType: String,
            storageLocation: {
                default: null
            },
            groupType: {
                default: null
            }
        },
        data: function () {
                return {
                    docs: [],
                    paginationData: {},
                    isLoading: false,
                    errors: null
                }
        },
        created () {
            // fetch the data when the view is created and the data is
            // already being observed
            console.log('fetching documents...');
            this.fetchData()
        },
        methods: {

            getFileSizeWithUnits: function(bytes){
                
                var fileSize = 0;

                if (bytes >= 1073741824) {
                    fileSize = (bytes / 1073741824).toFixed(2)+' GB';
                }else if (bytes >= 1048576) {
                    fileSize = (bytes / 1048576).toFixed(2)+' MB';
                }else if (bytes >= 1024) {
                    fileSize = (bytes / 1024).toFixed(2)+' KB';
                }else if (bytes > 1) {
                    fileSize = bytes+' bytes';
                }else if (bytes == 1) {
                    fileSize = bytes+' byte';
                }else {
                    fileSize = '0 bytes';
                }

                return fileSize;
            },

            getFileTotalSizeWithUnits: function(){
                var total = 0;
                for( var i = 0; i < this.docs.length; i++ ){
                    total += this.docs[i].size;
                }

                return this.getFileSizeWithUnits(total);
            },

            updateDocuments: function(data){
                console.log(this.docs);
                console.log('before');
                this.docs.push.apply(this.docs, data);
                console.log('after');
                console.log(this.docs);
            },

            fetchData (page = 1) {
                const self = this;

                //  Start loader
                self.isLoading = true
                
                /*  Specify other related model data to collect, just separate by comma (,) and extend with a period (.)
                *  E.g) selectedContractors.branches will bring all selected contractors and their company branches
                */
                let connections = '';

                /*  Specify limit for pagination
                */
                let limit = 5;
                
                // Get only documents where the documentable_id = modelId && documentable_type = modelType
                
                let query = 'f[0][column]=documentable_id'+
                            '&f[0][operator]=equal_to'+
                            '&f[0][query_1]='+this.modelId+
                            '&f[1][column]=documentable_type'+
                            '&f[1][operator]=equal_to'+
                            '&f[1][query_1]='+this.modelType;

                axios.get('/api/documents?page='+page+'&&limit='+limit+'&&'+query)
                    .then(({data}) => {
                        console.log(data);
                        
                        //  Get the documents
                        self.docs = data.data;
                        self.paginationData = data;

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
            }

        }
    }
</script>

<template>
    <div class="row">
        <div class="col-12">
            <loader v-bind:isLoaded="isLoaded"></loader>
            <div class="row" v-if="docs">
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
                                <tr v-for="document in docs"
                                    class="clickable-row">
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
                    </div>
                </div>
            </div>
        </div>
        <upload-test v-bind:model-id="modelId" v-bind:model-type="modelType"  v-on:uploaded="updateDocuments"></upload-test>
    </div>
</template>

<script>
    export default {
        props:['documents', 'modelId', 'modelType'],
        data: function () {
                return {
                    docs: this.documents,
                    isLoaded: false
                }
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
            }

        },
        created(){
            this.isLoaded = true;
        }
    }
</script>

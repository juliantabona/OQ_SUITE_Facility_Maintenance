<style>
  input[type="file"]{
    position: absolute;
    top: -500px;
  }
  span.remove-file{
    color: red;
    cursor: pointer;
    float: right;
  }
  progress{
    width: 100%;
    margin-top: 20px;
  }
</style>

<template>
    <div class="col-12 pt-2 mt-2 pb-2 bg-secondary">
        <div class="row">
            <div class="col-12">
                <label v-show="files.length != 0">
                    <span class="ml-3 mt-3 d-block">Uploads:</span>
                    <input type="file" id="files" ref="files" accept="image/*" multiple v-on:change="handleFilesUpload()"/>
                </label>
                <div v-if="files.length != 0" class="table-responsive table-hover">
                    <table class="table mt-3 border-top">
                        <thead>
                            <tr>
                                <th style="min-width: 100px">Image/File</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Size</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(file, key) in files" :key="'file_'+key" class="clickable-row">
                                <td>
                                    <div class="lightgallery mt-3">
                                        <img class="preview" v-bind:ref="'image'+parseInt( key )"/>
                                    </div>
                                </td>
                                <td>{{ file.name }}</td>
                                <td>{{ file.type }}</td>
                                <td>{{ getFileSizeWithUnits(file.size) }}</td>
                                <td><span class="remove-file" v-on:click="removeFile( key )">Remove</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div :class="(files.length == 0 ? 'col-4 offset-8': 'col-4 offset-4')">
                <div dv-b-tooltip.hover title="Add Image/File" >
                    <button v-on:click="addFiles()" type="button" :class="(files.length == 0 ? 'animated-strips btn-success ': 'btn-default ') +'btn float-right mt-2 pt-2 pb-2 pl-4 pr-4 w-100'">                                            
                        <i class="icon-sm icon-doc icons"></i>
                        <span class="mt-4">Add Image/File</span>
                    </button>
                </div>
            </div>
            <div v-if="files.length != 0" :class="(files.length == 0 ? 'col-4 offset-8': 'col-4')">
                <div v-b-tooltip.hover title="Add Image/File" >
                    <button v-on:click="submitFiles()" type="button" class="animated-strips btn btn-success float-right mt-2 pt-2 pb-2 pl-4 pr-4 w-100">                                            
                        <span class="mt-4">Upload</span>
                    </button>
                </div>
            </div>
            <div v-if="files.length != 0" class="col-12">
                <span v-if="isUploading">Uploading...</span>
                <progress v-if="files" max="100" :value.prop="uploadPercentage"></progress>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    props:['modelId', 'modelType', 'storageLocation', 'groupType'],
    /*
      Defines the data used by the component
    */
    data(){
      return {
        files: [],
        uploadPercentage: 0,
        isUploading:false
      }
    },

    /*
      Defines the method used by the component
    */
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

      /*
        Adds a file
      */
      addFiles(){
        this.$refs.files.click();
      },

      removeFile( key ){
        this.files.splice( key, 1 );
      },

      /*
        Submits files to the server
      */
      submitFiles(){
        
        const self = this;

        this.isUploading = true; 

        /*
          Initialize the form data
        */
        let formData = new FormData();

        /*
          Iteate over any file sent over appending the files
          to the form data.
        */
        for( var i = 0; i < this.files.length; i++ ){
          let file = this.files[i];

          formData.append('files[' + i + ']', file);
        }

        /*
          Make the request to the POST /documents URL
        */
        axios.post( '/api/documents?id='+this.modelId+'&&model='+this.modelType+'&&location='+this.storageLocation+'&&type='+this.groupType,
          formData,
          {
            headers: {
                'Content-Type': 'multipart/form-data'
            },
            onUploadProgress: function( progressEvent ) {
                this.uploadPercentage = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ) );
                this.$forceUpdate();
            }.bind(this)
          }
        ).then(function(response){
          console.log('uploaded');
          console.log(response.data);
          self.isUploading = false;
          self.$emit('uploaded', response.data);

          let docCount = self.files.length;
          let docMsg = (docCount == 1) ? docCount+' document added' : docCount+' documents added';
          
          self.$snotify.success(docMsg);

          self.files = [];
        })
        .catch(function(error){
          console.log(error);
          self.isUploading = false;
          self.files = [];
        });
      },

      /*
        Handles the uploading of files
      */
      handleFilesUpload(){
        /*
          Get the uploaded files from the input.
        */
        let uploadedFiles = this.$refs.files.files;

        /*
          Adds the uploaded file to the files array
        */
        for( var i = 0; i < uploadedFiles.length; i++ ){
          this.files.push( uploadedFiles[i] );
        }

        /*
          Generate image previews for the uploaded files
        */
        this.getImagePreviews();
      },

      /*
        Gets the preview image for the file.
      */
      getImagePreviews(){
        /*
          Iterate over all of the files and generate an image preview for each one.
        */
        for( let i = 0; i < this.files.length; i++ ){
          /*
            Ensure the file is an image file
          */
          if ( /\.(jpe?g|png|gif)$/i.test( this.files[i].name ) ) {
            /*
              Create a new FileReader object
            */
            let reader = new FileReader();

            /*
              Add an event listener for when the file has been loaded
              to update the src on the file preview.
            */
            reader.addEventListener("load", function(){
              this.$refs['image'+parseInt( i )][0].src = reader.result;
            }.bind(this), false);

            /*
              Read the data for the file in through the reader. When it has
              been loaded, we listen to the event propagated and set the image
              src to what was loaded from the reader.
            */
            reader.readAsDataURL( this.files[i] );
          }
        }
      }
    }
  }
</script>
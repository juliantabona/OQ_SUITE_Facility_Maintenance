<style>

    .jobcard-container .card {
        border: 1px solid #dbe3e6;
    }

    .lower-font {
        font-size: 14px;
    }

    .reference-details span {
        padding-top: 3px;
        display: block;
    }

    .company-logo{
        width: auto !important;
        max-width: 80px;
        height: 80px !important;
    }

    .modal-content-max-height {
        overflow-y: auto;
        overflow-x: hidden;
        max-height: 320px;
        padding: 20px 15px;
    }

</style>

<template>
    <div class="row jobcard-container">
        <button type="button" @click="goBack" class="btn btn-primary mt-3 ml-2 mb-2">
            <i class="icon-arrow-left icons"></i>
            Go Back
        </button>
        <a href="/jobcards/1/viewers" class="btn btn-inverse-light mt-3 ml-2 mb-2">
            <i class="icon-eye icons"></i>
            4 viewer(s)
        </a>

        <div class="col-lg-12 d-flex flex-column">
            <div class="row flex-grow">
                <div class="col-12 col-md-8 col-lg-8 grid-margin stretch-card">
                    <jobcard-body :id="id" @jobcardFound="catchJobcard"></jobcard-body>
                </div>
                <company-side-widget :company-id="clientId" :jobcard-id="id"></company-side-widget>
                <contractor-list-widget :jobcard-id="id"></contractor-list-widget>
                <recent-activity-widget></recent-activity-widget>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['id'],
        data() {
            return {
                jobcard: null
            }
        },
        computed: {
            clientId: {
                get: function() {
                    return this.jobcard != null ? this.jobcard.client_id : null;
                },
                set: function(updatedClientId) {
                    return updatedClientId;
                }
            }
        },
        methods: {
            catchJobcard(jobcard){
                this.jobcard = jobcard;
                this.clientId = jobcard.client_id;
                console.log('updated client id');
                console.log(this.clientId);
            },

            goBack(){
                this.$router.go(-1);
            }
        }
    }
</script>

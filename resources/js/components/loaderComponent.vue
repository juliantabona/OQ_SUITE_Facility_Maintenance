<template>

    <div v-if="isLoading">
        <div class="row">
            <div class="col-12">
                <img src="/images/assets/icons/star_loader.svg" alt="Loader" style=" width: 40px;">
                {{ msg ? msg : 'Loading...' }}
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        props:['isLoading', 'msg'],
    }
</script>
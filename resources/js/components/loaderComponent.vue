<template>

    <div v-if="!isLoaded">
        <div class="row mt-4">
            <div class="col-12">
                <span>Loading...</span>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        props:['isLoaded'],
    }
</script>
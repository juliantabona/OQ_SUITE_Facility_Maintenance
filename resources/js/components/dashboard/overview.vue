<style>

</style>

<template>
    <div>
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-6">
                    Welcome, {{ user.first_name }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import Slick from 'vue-slick';

export default {

    components: { Slick },

    data() {
        return {
            user: auth.user
        };
    }
}
</script>
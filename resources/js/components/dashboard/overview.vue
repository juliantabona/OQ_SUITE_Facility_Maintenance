<style>

</style>

<template>
    <div>
        <div class="container-fluid p-0">
            <div class="row mb-2">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            Welcome, {{ user.first_name }}
                        </div>
                    </div>
                </div>
            </div>

            <recent-activity-widget></recent-activity-widget>
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
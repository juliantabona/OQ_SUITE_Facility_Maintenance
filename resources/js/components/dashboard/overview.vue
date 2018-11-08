<style>
    .overview-link,
    .overview-link:hover,
    .overview-link:active{
        color: #000 !important;
        text-decoration: none !important;
    }
</style>

<template>
    <div>
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="row overview-summary">
                        <div class="col-md-12 col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body p-3">
                                    <h6 class="card-title mb-0">Overview</h6>
                                </div>
                            </div>
                        </div>
                        <div class="summary-card col-md-6 col-lg-3 grid-margin stretch-card">
                            <div class="card card-clickable">
                                <div class="card-body">
                                    <router-link :to="{ name: 'jobcards'}" class="overview-link">
                                        <div class="d-flex align-items-center justify-content-md-center">
                                            <i class="icon-social-dropbox icons icon-lg"></i>
                                            <div class="ml-3">
                                                <p class="mb-0">Requests</p>
                                                <h6>215</h6>
                                            </div>
                                        </div>
                                    </router-link>
                                </div>
                            </div>
                        </div>
                        <div class="summary-card col-md-6 col-lg-3 grid-margin stretch-card">
                            <div class="card card-clickable">
                                <div class="card-body">
                                    <router-link :to="{ name: 'jobcards'}" class="overview-link">
                                        <div class="d-flex align-items-center justify-content-md-center">
                                            <i class="icon-lg icon-layers icons"></i>
                                            <div class="ml-3">
                                                <p class="mb-0">Authorized</p>
                                                <h6>187</h6>
                                            </div>
                                        </div>
                                    </router-link>
                                </div>
                            </div>
                        </div>
                        <div class="summary-card col-md-6 col-lg-3 grid-margin stretch-card">
                            <div class="card card-clickable">
                                <div class="card-body">
                                    <router-link :to="{ name: 'jobcards'}" class="overview-link">
                                        <div class="d-flex align-items-center justify-content-md-center">
                                            <i class="icon-lg icon-eye icons"></i>
                                            <div class="ml-3">
                                                <p class="mb-0">Currently Open</p>
                                                <h6>126</h6>
                                            </div>
                                        </div>
                                    </router-link>
                                </div>
                            </div>
                        </div>
                        <div class="summary-card col-md-6 col-lg-3 grid-margin stretch-card">
                            <div class="card card-clickable">
                                <div class="card-body">
                                    <router-link :to="{ name: 'jobcards'}" class="overview-link">
                                        <div class="d-flex align-items-center justify-content-md-center">
                                            <i class="icon-lg icon-eyeglass icons"></i>
                                            <div class="ml-3">
                                                <p class="mb-0">Inspection</p>
                                                <h6>107</h6>
                                            </div>
                                        </div>
                                    </router-link>
                                </div>
                            </div>
                        </div>
                        <div class="summary-card col-md-6 col-lg-3 grid-margin stretch-card">
                            <div class="card card-clickable">
                                <div class="card-body">
                                    <router-link :to="{ name: 'jobcards'}" class="overview-link">
                                        <div class="d-flex align-items-center justify-content-md-center">
                                            <i class="icon-lg icon-like icons"></i>
                                            <div class="ml-3">
                                                <p class="mb-0">Completed</p>
                                                <h6>301</h6>
                                            </div>
                                        </div>
                                    </router-link>
                                </div>
                            </div>
                        </div>
                        <div class="summary-card col-md-6 col-lg-3 grid-margin stretch-card">
                            <div class="card card-clickable">
                                <div class="card-body">
                                    <router-link :to="{ name: 'jobcards'}" class="overview-link">
                                        <div class="d-flex align-items-center justify-content-md-center">
                                            <i class="icon-exclamation icons icon-lg"></i>
                                            <div class="ml-3">
                                                <p class="mb-0">Overdue</p>
                                                <h6>24</h6>
                                            </div>
                                        </div>
                                    </router-link>
                                </div>
                            </div>
                        </div>
                        <div class="summary-card col-md-6 col-lg-3 grid-margin stretch-card">
                            <div class="card card-clickable">
                                <div class="card-body">
                                    <router-link :to="{ name: 'clients'}" class="overview-link">
                                    <div class="d-flex align-items-center justify-content-md-center">
                                        <i class="icon-lg icon-emotsmile icons"></i>
                                        <div class="ml-3">
                                            <p class="mb-0">Clients</p>
                                            <h6>984</h6>
                                        </div>
                                    </div>
                                    </router-link>
                                </div>
                            </div>
                        </div>
                        <div class="summary-card col-md-6 col-lg-3 grid-margin stretch-card">
                            <div class="card card-clickable">
                                <div class="card-body">
                                    <router-link :to="{ name: 'contractors'}" class="overview-link">
                                        <div class="d-flex align-items-center justify-content-md-center">
                                            <i class="icon-lg icon-note icons"></i>
                                            <div class="ml-3">
                                                <p class="mb-0">Contractors</p>
                                                <h6>88</h6>
                                            </div>
                                        </div>
                                    </router-link>
                                </div>
                            </div>
                        </div>
                        <div class="summary-card col-md-6 col-lg-3 grid-margin stretch-card" style="">
                            <div class="card card-clickable">
                                <div class="card-body">
                                    <router-link :to="{ name: 'staff'}" class="overview-link">
                                        <div class="d-flex align-items-center justify-content-md-center">
                                            <i class="icon-lg icon-user icons"></i>
                                            <div class="ml-3">
                                                <p class="mb-0">Staff</p>
                                                <h6>1027</h6>
                                            </div>
                                        </div>
                                    </router-link>
                                </div>
                            </div>
                        </div>
                        <div class="summary-card col-md-6 col-lg-3 grid-margin stretch-card" style="">
                            <div class="card card-clickable">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-md-center">
                                        <i class="icon-lg icon-notebook icons"></i>
                                        <div class="ml-3">
                                            <p class="mb-0">Contacts</p>
                                            <h6>5734</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="toggle-summary-cards-btn col-12">
                            <button class="btn btn-dark d-block mr-auto ml-auto mb-3  rounded">
                                <i class="icons icon-arrow-down-circle"></i>
                                <span>Show More</span>
                            </button>
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
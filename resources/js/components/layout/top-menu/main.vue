<style>

    .navbar {
        background: #fff;
        box-shadow: 5px 2px 5px #00000030;
        position: relative;
        z-index: 11;
    }

    .navbar .navbar-menu-wrapper{
        width:100% !important;
    }

    .navbar #menu_create_options{
        border-color: #ffb400 !important;
        background-color: #ffb400 !important;
    }

    .navbar .text-warning {
        color: #ffbc1a !important;
    }

    .navbar .badge{
        background-color: #ffb400 !important;
    }

    .navbar .icon-menu,
    .navbar .icon-bell,
    .navbar .nav-item > a > i.icon-user,
    .navbar .icon-grid{
        color:#000;
    }

    .navbar #menu_search_options i {
        border: 1px solid #00000057;
        color: #000;
    }

    .navbar .navbar-menu-wrapper .navbar-nav .nav-item.dropdown .count-indicator:hover {
        background: #00000012;
        border: 1px solid rgba(0, 0, 0, 0.2);
    }

    .navbar .navbar-menu-wrapper .navbar-nav .nav-item.dropdown .count-indicator,
    .navbar .navbar-menu-wrapper .navbar-nav .nav-item.nav-settings {
        border: 1px solid rgba(0, 0, 0, 0.2);
    }

    .btn-gold{
        border-color: #ffb400 !important;
        background-color: #ffb400 !important;
    }

    .btn-gold:hover{
        border-color: #ffb400 !important;
        background-color: #ffb400 !important;
    }

</style>

<template>

    <nav class="navbar col-lg-12 col-12 p-0 d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center d-lg-none">
            <a class="navbar-brand brand-logo" href="/overview">
                <img src="/images/assets/logo/OQ-INFINITE-B-150X84.gif" alt="logo">
            </a>
            <a class="navbar-brand brand-logo-mini pr-2 pl-2" href="/overview">
                <img src="/images/assets/logo/OQ-INFINITE-B-150X84.gif" alt="logo">
            </a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                <span class="icon-menu"></span>
            </button>
            <ul class="navbar-nav">

                <li class="nav-item dropdown d-none d-lg-flex">
                    <a class="nav-link dropdown-toggle nav-btn pt-1 pr-3 " id="menu_search_options" href="#" data-toggle="dropdown">
                        <i class="icon-magnifier icons"></i>
                    </a>
                    <div class="dropdown-menu navbar-dropdown dropdown-left" aria-labelledby="menu_search_options">
                        <p class="bg-primary p-2 pl-4 text-white">Search for...</p>
                        <a class="dropdown-item" href="#">
                            <i class="icon-user text-warning"></i>
                            Client
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">
                            <i class="icon-briefcase text-warning"></i>
                            Contractor
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">
                            <i class="icon-user-following text-warning"></i>
                            Staff Member
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">
                            <i class="icon-docs text-warning"></i>
                            Jobcard
                        </a>
                    </div>
                </li>
                <li class="nav-item dropdown d-none d-lg-flex">
                    <a class="nav-link dropdown-toggle nav-btn" id="menu_create_options" href="#" data-toggle="dropdown">
                        <span class="btn">+ Create new</span>
                    </a>

                    <div class="dropdown-menu navbar-dropdown dropdown-left" aria-labelledby="menu_create_options">
                        <a class="dropdown-item" href="#">
                            <i class="icon-user text-warning"></i>
                            Client
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">
                            <i class="icon-briefcase text-warning"></i>
                            Contractor
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">
                            <i class="icon-user-following text-warning"></i>
                            Staff Member
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">
                            <i class="icon-docs text-warning"></i>
                            Jobcard
                        </a>
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav navbar-nav-right">
                <li class="nav-item dropdown">
                    <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                        <i class="icon-bell mx-0"></i>
                        <span class="count"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-5" aria-labelledby="notificationDropdown">
                        <a class="dropdown-item">
                            <p class="mb-0 font-weight-normal float-left">You have 4 new notifications
                            </p>
                            <span class="badge badge-pill float-right" style="background: #006230;color: #fff;">View all</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="p-0 m-0">

                            <div class=" d-flex align-items-center border-bottom p-2">
                                <img class="img-sm rounded-circle" src="/images/assets/placeholders/profile_placeholder.png" alt="">
                                <div class="wrapper w-100 ml-3">
                                    <p class="mb-0" style="font-size:  12px;">
                                        <a href="#">Keletso Sesiane</a> assigned you to inspect
                                        <a href="#">Contract #421</a> for <a href="#">Mr. Kelebogile</a>
                                        <br/>
                                        <i class="mdi mdi-clock text-muted mr-1"></i>
                                        <small class="text-muted ml-auto">14 May 2017 - 08:26AM</small>
                                    </p>
                                </div>
                            </div>
                            <div class=" d-flex align-items-center border-bottom p-2">
                                <img class="img-sm rounded-circle" src="/images/assets/placeholders/profile_placeholder.png" alt="">
                                <div class="wrapper w-100 ml-3">
                                    <p class="mb-0" style="font-size:  12px;">
                                        <a href="#">Mpho Mosinyi</a> requested your authorization for publishing
                                        <a href="#">Internet Package</a> contract template
                                        <br/>
                                        <i class="mdi mdi-clock text-muted mr-1"></i>
                                        <small class="text-muted ml-auto">14 May 2017 - 08:21AM</small>
                                    </p>
                                </div>
                            </div>
                            <div class=" d-flex align-items-center border-bottom p-2">
                                <img class="img-sm rounded-circle" src="/images/assets/placeholders/profile_placeholder.png" alt="">
                                <div class="wrapper w-100 ml-3">
                                    <p class="mb-0" style="font-size:  12px;">
                                        <a href="#">Tumisang Mooketsi</a> requested your authorization for adding 
                                        <a href="#">Mr. Mapei</a> under staff members
                                        <br/>
                                        <i class="mdi mdi-clock text-muted mr-1"></i>
                                        <small class="text-muted ml-auto">14 May 2017 - 08:15AM</small>
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                        <i class="icon-user mx-0"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                        <div class="dropdown-divider"></div>
                        <ul class="list-group" style="min-width: 180px;">
                            <li class="list-group-item border-top-0">
                                <a href="http://127.0.0.1:8000/profiles/1">
                                <i class="icon-user icons mr-1"></i>
                                <span class="ml-auto">Profile</span></a>
                            </li>
                            <li class="list-group-item">
                            <a href="#"><i class="icon-settings icons mr-1"></i>
                                <span class="ml-auto">Settings</span></a>
                            </li><li class="list-group-item">
                            <a href="#"><i class="icon-support icons mr-1"></i>
                                <span class="ml-auto">Support</span></a>
                            </li><li class="list-group-item">
                            <a href="https://saleschief.co.bw/login"><i class="icon-graduation icons mr-1"></i>
                                <span class="ml-auto">Training</span></a>
                            </li><li class="list-group-item">
                            <a href="http://btcsupport.optimumqbw.com/" target="_blank"><i class="icon-book-open icons mr-1"></i>
                                <span class="ml-auto">Documentation</span></a>
                            </li>
                            <li class="list-group-item" @click="logout">
                                <a class="nav-link text-success btn btn-outline-success" href="#">
                                    <i class="icon-logout icons mr-1"></i>
                                    <span>Logout</span>
                                </a>
                        
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item nav-settings d-none d-lg-block">
                    <a class="nav-link" href="#">
                        <i class="icon-grid"></i>
                    </a>
                </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                <span class="icon-menu"></span>
            </button>
        </div>
    </nav>

</template>

<script>
    export default {
        data() {
            return {
                
            };
        },
        methods: {
            logout() {
                //  Log user out
                Event.$emit('performLogout');
            }
        }
    }
</script>
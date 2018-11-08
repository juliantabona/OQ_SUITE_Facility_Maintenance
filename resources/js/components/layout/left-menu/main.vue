<style>



    .support-color-band{
        background: #23ad00 !important;
    }
    
    #sidebar{
        box-shadow: 2px 0px 10px #00000030;
    }
    /*  Navigation Styles*/
    #sidebar li:not(.submenu-parent-link):hover a,
    #sidebar li a.router-link-active,
    #sidebar li a.router-link-exact-active {
        background: #ffb400 !important;
        cursor: pointer !important;
    }

    /*
    .sidebar .nav:not(.sub-menu) > .nav-item:hover:not(.nav-profile) > .nav-link
    */
    
    #sidebar li:not(.submenu-parent-link):hover a i,
    #sidebar li a.router-link-active i,
    #sidebar li a.router-link-exact-active i,
    #sidebar li:not(.submenu-parent-link):hover a span,
    #sidebar li a.router-link-active span,
    #sidebar li a.router-link-exact-active span{
        color: #fff !important;
    }

    #sidebar li.submenu-parent-link:hover > a{
        background:#fff1d1 !important;
    }

</style>

<template>

    <nav class="sidebar sidebar-offcanvas" id="sidebar" style="min-height: 3016.38px;">
        <ul class="nav">
            <li class="nav-item nav-profile nav-unhighlightable pt-4">
                <div class="nav-link pb-4">
                    <a href="#" class="nounderline">
                        <div class="profile-image">
                            <img src="https://scontent.fgbe2-2.fna.fbcdn.net/v/t1.0-1/p160x160/27540018_1876784582363504_131854681115569669_n.jpg?_nc_cat=108&_nc_ht=scontent.fgbe2-2.fna&oh=942e649e1e7c64c89b2cf25adbe74ab9&oe=5C3BEE16" alt="Profile Image">
                            <span class="online-status online"></span>
                        </div>
                        <div class="profile-name">
                            <p class="name">
                            John Doe
                            </p>
                            <p class="designation">
                                Director
                            </p>
                        </div>
                    </a>
                </div>
            </li>
            <li class="nav-item border-top border-bottom">
                <router-link :to="{ name: 'dashboard'}" class="nav-link">
                    <i class="icon-rocket menu-icon text-dark"></i>
                    <span class="menu-title text-dark">Overview</span>
                </router-link>
            </li>
            <li class="nav-item">
                <router-link :to="{ name: 'staff'}" class="nav-link">
                    <i class="icon-user menu-icon text-dark"></i>
                    <span class="menu-title text-dark">Staff</span>
                </router-link>
            </li>
            <li class="nav-item">
                <router-link :to="{ name: 'clients'}" class="nav-link">
                    <i class="icon-emotsmile menu-icon text-dark"></i>
                    <span class="menu-title text-dark">Clients</span>
                </router-link>
            </li>
            <li class="nav-item">
                <router-link :to="{ name: 'contractors'}" class="nav-link">
                    <i class="icon-briefcase menu-icon text-dark"></i>
                    <span class="menu-title text-dark">Contractors</span>
                </router-link>
            </li>
            <li class="nav-item submenu-parent-link d-none d-lg-block">
                <a data-toggle="collapse" href="#sub-jobcards" aria-expanded="false" aria-controls="sidebar-layouts" class="nav-link collapsed">
                    <i class="icon-docs menu-icon text-dark"></i>
                    <span class="menu-title text-dark">Jobcards</span>
                    <i class="dropdown-arrow icon-arrow-down icons"></i>
                </a>
                <div id="sub-jobcards" class="collapse">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <hr class="m-0">
                            <router-link :to="{ name: 'create-jobcard'}">
                                <div class="badge badge-success badge-fw pt-2 pb-2 mt-2 mb-2">
                                    <i class="icon-plus menu-icon text-light mr-1"></i>Create Jobcard
                                </div>
                            </router-link>
                            <hr class="m-0">
                        </li>
                        <li class="nav-item">
                            <router-link :to="{ name: 'jobcards'}" class="nav-link">
                                <i class="icon-docs menu-icon text-dark mr-1 ml-2"></i>
                                <span class="menu-title text-dark">Recent Jobcards</span>
                            </router-link>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item submenu-parent-link d-none d-lg-block">
                <a data-toggle="collapse" href="#sub-templates" aria-expanded="false" aria-controls="sidebar-layouts" class="nav-link collapsed">
                    <i class="icon-puzzle menu-icon text-dark"></i>
                    <span class="menu-title text-dark">Templates</span>
                    <i class="dropdown-arrow icon-arrow-down icons"></i>
                </a>
                <div id="sub-templates" class="collapse">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <hr class="m-0">
                            <a href="#">
                                <div class="badge badge-success badge-fw pt-2 pb-2 mt-2 mb-2">
                                    <i class="icon-plus menu-icon text-light mr-1"></i>Create Template
                                </div>
                            </a>
                            <hr class="m-0">
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">View Templates</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <router-link :to="{ name: 'recentactivities'}" class="nav-link">
                    <i class="icon-graph menu-icon text-dark"></i>
                    <span class="menu-title text-dark">Activities</span>
                </router-link>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="icon-calendar menu-icon text-dark"></i>
                    <span class="menu-title text-dark">Calendar</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="icon-pie-chart menu-icon text-dark"></i>
                    <span class="menu-title text-dark">Reports</span>
                </a>
            </li>
            <li class="nav-item nav-doc m-0">
                <a href="#" class="nav-link support-color-band pl-4">
                    <i class="icon-support menu-icon mr-2"></i>
                    <span class="menu-title mt-1">Support</span>
                </a>
            </li>


            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="icon-user menu-icon text-dark"></i>
                    <span class="menu-title text-dark">Accounts</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link border-bottom">
                    <i class="icon-key menu-icon text-dark"></i>
                    <span class="menu-title text-dark">Roles/Permissions</span>
                </a>
            </li>
            <hr>
        </ul>
    </nav>

</template>

<script>
    export default {
        data() {
            return {
                
            };
        }
    }
</script>
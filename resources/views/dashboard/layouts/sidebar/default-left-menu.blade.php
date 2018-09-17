<nav class="sidebar sidebar-offcanvas" id="sidebar" style="min-height: 3016.38px;">
    <ul class="nav">
        <li class="nav-item nav-profile nav-unhighlightable pt-5">
            <div class="nav-link">
                <a href="#" class="nounderline">
                    <div class="profile-image">
                        <img src="{{ Auth::user()->avatar }}" alt="Profile Image">
                        <span class="online-status online"></span>
                    </div>
                    <div class="profile-name">
                        <p class="name">
                        {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                        </p>
                        <p class="designation">
                            {{ Auth::user()->position  != ''? '-- '.Auth::user()->position.' --':'' }}
                        </p>
                    </div>
                </a>
            </div>
        </li>
        <li class="nav-item">
            <a href="/overview" class="nav-link">
                <i class="icon-rocket menu-icon text-dark"></i>
                <span class="menu-title text-dark">Overview</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="http://127.0.0.1:8080/profiles" class="nav-link">
                <i class="icon-user menu-icon text-dark"></i>
                <span class="menu-title text-dark">Staff</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="/clients" class="nav-link">
                <i class="icon-emotsmile menu-icon text-dark"></i>
                <span class="menu-title text-dark">Clients</span>
            </a>
        </li>
        <li class="nav-item d-none d-lg-block">
            <a data-toggle="collapse" href="#sub-contracts" aria-expanded="false" aria-controls="sidebar-layouts" class="nav-link collapsed">
                <i class="icon-docs menu-icon text-dark"></i>
                <span class="menu-title text-dark">Contracts</span>
                <i class="dropdown-arrow icon-arrow-down icons"></i>
            </a>
            <div id="sub-contracts" class="collapse">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <hr class="m-0">
                        <a href="#">
                            <div class="badge badge-success badge-fw pt-2 pb-2 mt-2 mb-2">
                                <i class="icon-plus menu-icon text-light mr-1"></i>Create Contract
                            </div>
                        </a>
                        <hr class="m-0">
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Requests</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Authorized</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Currently Reviewed</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Inspection</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Waiting Signoff</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Completed</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Overdue</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item d-none d-lg-block">
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
                    <li class="nav-item">
                        <a href="#" class="nav-link">Categories</a>
                    </li>
                </ul>
            </div>
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
            <a href="#" class="nav-link" style="background: #ca9603;">
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
            <a href="#" class="nav-link">
                <i class="icon-key menu-icon text-dark"></i>
                <span class="menu-title text-dark">Roles/Permissions</span>
            </a>
        </li>
        
        
        
        <hr>



    </ul>
</nav>
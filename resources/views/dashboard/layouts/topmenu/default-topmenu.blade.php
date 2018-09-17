<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
            <a class="navbar-brand brand-logo" href="/overview">
                <img src="/assets/logos/btc-logo-260x145.png" alt="logo">
            </a>
            <a class="navbar-brand brand-logo-mini pr-2 pl-2" href="/overview">
                <img src="/assets/logos/btc-logo-42x42.png" alt="logo">
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
                        <a class="dropdown-item" href="{{ route('jobcard-create') }}">
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
                                <img class="img-sm rounded-circle" src="/assets/placeholders/profile_placeholder.png" alt="">
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
                                <img class="img-sm rounded-circle" src="/assets/placeholders/profile_placeholder.png" alt="">
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
                                <img class="img-sm rounded-circle" src="/assets/placeholders/profile_placeholder.png" alt="">
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
                                <a href="http://127.0.0.1:8000/profiles/1"><i class="icon-user icons mr-1"></i>
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
                            <li class="list-group-item">
                                <a class="nav-link text-success btn btn-outline-success" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    <i class="icon-logout icons mr-1"></i>
                                    <span class="">Logout</span>
                                </a>
                                
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                        
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
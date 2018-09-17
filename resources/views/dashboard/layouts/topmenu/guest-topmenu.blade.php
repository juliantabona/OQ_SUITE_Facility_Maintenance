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
            <ul class="navbar-nav">
    
                
                <li class="nav-item dropdown d-none d-lg-flex">
                    <h3 class="ml-2 mt-2">{{ $template->name }}</h3>
                </li>
            </ul>
            <ul class="navbar-nav navbar-nav-right">
                <li class="nav-item dropdown">
                    <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                        <i class="icon-user mx-0"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                        <div class="dropdown-divider"></div>
                        <ul class="list-group" style="min-width: 180px;">
                            <li class="list-group-item">
                                <a href="#">
                                    <i class="icon-support icons mr-1"></i>
                                    <span class="ml-auto">Support</span>
                                </a>
                            </li>
                            <li class="list-group-item">
                                <a href="#">
                                    <i class="icon-phone icons mr-1"></i>
                                    <span class="ml-auto">Contact</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                <span class="icon-menu"></span>
            </button>
        </div>
    </nav>
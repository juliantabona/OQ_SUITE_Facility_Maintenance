<div class="container">
    <nav class="navbar navbar-expand-lg navbar-dark pt-0">
        <a class="navbar-brand" href="#">
            <img src="http://www.optimumqbw.com/optimumqbw.com/wp-content/uploads/2018/09/OQ-INFINITE-B-150X84.gif">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ml-5">
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('welcome') }}">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('features') }}">Features</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Company</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Support</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Pricing</a>
            </li>
            </ul>
            <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="icon-social-facebook icons"></i></a>
            </li>
            <li class="nav-item mr-4">
                <a class="nav-link" href="#"><i class="icon-social-twitter icons"></i></a>
            </li>
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                </li>
            @endguest
            <li class="nav-item">
                @auth
                    <a class="nav-link" href="{{ route('overview') }}">Dashboard</a>
                @else
                    <a class="nav-link" href="{{ route('login') }}">Sign In</a>
                @endauth
            </li>
            </ul>
        </div>
    </nav>
</div>
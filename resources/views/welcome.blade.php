<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ env('APP_NAME', 'OQ - Facility Maintenance') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    
        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.css">

        <!-- CSS Dependencies -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="{{ URL::asset('css/themes/shards/shards.min.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('css/extra/web.css') }}">
    
    </head>
    <body class="shards-landing-page--1">

    <!-- Welcome Section -->
    <div class="container">
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark pt-0">
        <a class="navbar-brand" href="#">
            <img src="http://www.optimumqbw.com/optimumqbw.com/wp-content/uploads/2018/09/OQ-INFINITE-B-150X84.gif"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ml-5">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Features</a>
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
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="icon-social-twitter icons"></i></a>
            </li>
            <li class="nav-item ml-4">
                @auth
                    <a href="{{ url('/home') }}">Dashboard</a>
                @else
                    <a class="nav-link" href="#">Sign In</a>
                @endauth
            </li>
            </ul>
        </div>
        </nav>
        <!-- / Navigation -->
    </div>
    <div class="d-flex flex-column justify-content-center welcome">
        <!-- .container -->

        <!-- Inner Wrapper -->
        <div class="intro-container container inner-wrapper mb-auto mt-auto">
        <div class="mb-5 row">
            <div class="col-md-7 mb-5 col-sm-12">
            <h1 class="display-4 text-white welcome-heading">Facility<br> Management</h1>
            <p class="text-white">Manage, monitor and accelerate facility management processes with the best tools on the cloud.
                Manage all your jobs, projects, jobcards, assets, customers, contractors, staff members, documents, accounts,
                quotations, invoices, receipts, contracts and reports all in one place! </p>
            <a href="#" class="btn btn-lg btn-pill btn-success mr-3 mb-3">Get Started</a>
            <a href="#" class="btn btn-lg btn-outline-white btn-pill mr-3 mb-3">Learn More</a>
            </div>
            <div class="col-md-5 mb-5">
            <img src="https://designrevision.com/app/uploads/edd/shards-dashboards-main-featured-image-3.jpg" alt="Facility Maintenance Dashboard Screenshot">
            </div>
        </div>
        </div>
        <!-- / Inner Wrapper -->
    </div>
    <!-- / Welcome Section -->

    <!-- Our Services Section -->
    <div id="our-services" class="our-services py-4 section">\
        <div id="stripes" aria-hidden="true"></div>
        <!-- Features -->
        <h3 class="section-title text-center">Overview</h3>
        <div class="features py-4 mb-4">
        <div class="container">

            <div class="row">

            <div class="col-12 col-lg-6 mb-4">
                <div class="card">
                <div class="card-body pb-0"><div class="feature d-flex pb-0">
                    <div class="icon text-primary mr-3"><i class="icon-briefcase icons"></i></div>
                    <div class="px-4">
                        <h5>Job Management</h5>
                        <p>Receive jobs from new and exsiting customers stored on your customer management dashboard. Assign contractors
                        to ongoing jobs and manage job lifecycles from start to finish
                        <br><a href="#" class="btn pl-0">Learn More</a></p>
                    </div>
                    </div>
                </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 mb-4">
                <div class="card">
                <div class="card-body pb-0"><div class="feature d-flex pb-0">
                    <div class="icon text-primary mr-3"><i class="icon-organization icons"></i></div>
                    <div class="px-4">
                        <h5>Project Management</h5>
                        <p>Create projects and assign team members, budgets, jobcards, timelines and performance trackers to effectively
                        manage project resources and deliverables with ease
                        <br>
                        <a href="#" class="btn pl-0">Learn More</a></p>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>

            <div class="row mb-5">

            <div class="col-12 col-lg-6 mb-4">
                <div class="card">
                <div class="card-body pb-0"><div class="feature d-flex pb-0">
                    <div class="icon text-primary mr-3"><i class="icon-docs icons"></i></div>
                    <div class="px-4">
                        <h5>Jobcard Management</h5>
                        <p>Manage jobcards efficiently with record of job details, assigned staff, used assets, contractors selected
                        and timelines. Receive a complete history of recent changes or activities committed to all jobcards
                        <br>
                        <a href="#" class="btn pl-0">Learn More</a></p>
                    </div>
                    </div>
                </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 mb-4">
                <div class="card">
                <div class="card-body pb-0"><div class="feature d-flex pb-0">
                    <div class="icon text-primary mr-3"><i class="icon-wrench icons"></i></div>
                    <div class="px-4">
                        <h5>Asset Management</h5>
                        <p>Stay in touch with your assets, knowing how much stock you have and how your assets are allocated as per
                        workdone. Understand your in-demand assets and cut costs from assets not in use
                        <br>
                        <a href="#" class="btn pl-0">Learn More</a></p>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            <a href="#" class="btn btn-lg btn-pill btn-primary more-features-btn">View All Features</a>
            </div>

        </div>
        </div>
        <!-- / Features -->
    </div>
    <!-- / Our Services Section -->

    <!-- Our Blog Section -->

    <!-- / Our Blog Section -->

    <!-- / Testimonials Section -->

    <!-- Contact Section -->
    <div class="contact section-invert mb-5 mb-md-0 py-4">
        <h3 class="section-title text-center m-5">Contact Us</h3>
        <div class="container py-4">
        <div class="row justify-content-md-center px-4">
            <div class="contact-form col-sm-12 col-md-10 col-lg-7 p-4 mb-4 card">
            <form>
                <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                    <label for="contactFormFullName">Full Name</label>
                    <input type="email" class="form-control" id="contactFormFullName" placeholder="Enter your full name">
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                    <label for="contactFormEmail">Email address</label>
                    <input type="email" class="form-control" id="contactFormEmail" placeholder="Enter your email address">
                    </div>
                </div>
                </div>
                <div class="row">
                <div class="col">
                    <div class="form-group">
                    <label for="exampleInputMessage1">How Can We Help?</label>
                    <textarea id="exampleInputMessage1" class="form-control mb-4" rows="5" placeholder="Enter your message..." name="message"></textarea>
                    </div>
                </div>
                </div>
                <input class="btn btn-primary btn-pill d-flex ml-auto mr-auto" type="submit" value="Send Your Message">
            </form>
            </div>
        </div>
        </div>
    </div>
    <!-- / Contact Section -->

    <!-- Footer Section -->
    <footer>
        <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="d-block m-auto pb-3 pt-3" href="#">
            <span>Powered By</span>
            <img src="http://www.optimumqbw.com/optimumqbw.com/wp-content/uploads/2018/09/OQ-INFINITE-B-150X84.gif" class="d-block m-auto"></a>
        </div>
        </nav>
    </footer>
    <!-- / Footer Section -->

        
        <!-- JavaScript Dependencies: jQuery, Popper.js, Bootstrap JS, Shards JS -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="{{ URL::asset('js/themes/shards/shards.min.js') }}"></script>
    </body>
</html>

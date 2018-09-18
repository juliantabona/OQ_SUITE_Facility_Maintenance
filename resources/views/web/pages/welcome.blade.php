@extends('web.layouts.app')

@section('style')
    <style>
        .card{
            height:100% !important;
        }
    </style>
@endsection

@section('content')
    <div class="landing-page">
        <!-- Slider Section -->
        <div class="d-flex welcome">
            <div class="container intro-container mb-auto mt-auto">
                <div class="mb-5 row">
                    <div class="col-md-7 mb-5 col-sm-12">
                        <h1 class="display-4 text-white">Facility<br> Management</h1>
                        <p class="text-white">Manage, monitor and accelerate facility management processes with the best tools on the cloud.
                            Manage all your jobs, projects, jobcards, assets, customers, contractors, staff members, documents, accounts,
                            quotations, invoices, receipts, contracts and reports all in one place! </p>
                        <a href="{{ route('register') }}" class="btn btn-lg btn-success mr-3 mb-3">Get Started</a>
                        <a href="{{ route('features') }}" class="btn btn-lg btn-outline-white mr-3 mb-3">Learn More</a>
                    </div>
                    <div class="col-md-5 mb-5">
                        <img src="/images/samples/dashboard-overview-1.jpg" alt="Facility Maintenance Dashboard">
                    </div>
                </div>
            </div>
        </div>
        <!-- / Slider Section -->

        <!-- Features -->
        <div id="our-services" class="our-services py-4 section">
            <div id="stripes" aria-hidden="true"></div>
            <h3 class="section-title text-center">Overview</h3>
            <div class="features py-4 mb-4">
                <div class="container">
                    <div class="row row-eq-height mb-5">
                        <div class="col-12 col-lg-6 mb-4">
                            <div class="card">
                                <div class="card-body pb-0">
                                    <div class="feature d-flex pb-0">
                                        <div class="icon text-primary mr-3">
                                            <i class="icon-briefcase icons"></i>
                                        </div>
                                        <div class="px-4">
                                            <h5>Job Management</h5>
                                            <p>Receive jobs from new and exsiting customers stored on your customer management dashboard. Assign contractors
                                            to ongoing jobs and manage job lifecycles from start to finish
                                            <br><a href="{{ route('features') }}" class="btn pl-0">Learn More</a></p>
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
                                            <a href="{{ route('features') }}" class="btn pl-0">Learn More</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row row-eq-height mb-5">
                        <div class="col-12 col-lg-6 mb-4">
                            <div class="card">
                                <div class="card-body pb-0"><div class="feature d-flex pb-0">
                                    <div class="icon text-primary mr-3"><i class="icon-docs icons"></i></div>
                                    <div class="px-4">
                                        <h5>Jobcard Management</h5>
                                        <p>Manage jobcards efficiently with record of job details, assigned staff, used assets, contractors selected
                                        and timelines. Receive a complete history of recent changes or activities committed to all jobcards
                                        <br>
                                        <a href="{{ route('features') }}" class="btn pl-0">Learn More</a></p>
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
                                            <a href="{{ route('features') }}" class="btn pl-0">Learn More</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('features') }}" class="btn btn-pill btn-lg btn-success more-features-btn">View All Features</a>
                    </div>

                </div>
            </div>
        </div>
        <!-- / Features -->

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
                            <input class="btn btn-success d-flex ml-auto mr-auto" type="submit" value="Send Your Message">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- / Contact Section -->
    </div>
@endsection

@section('js')

@endsection

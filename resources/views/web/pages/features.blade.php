@extends('web.layouts.app')

@section('style')

    <style>

        .landing-page .overlay {
            border-bottom: 3px solid #fff;
            position: relative;
            height: 90vh;
            min-height: 90vh;
            background: url(../../images/backgrounds/welcome-cover.jpg) no-repeat center center fixed;
            background-size: cover;
        }

        .landing-page .overlay:before {
            position: absolute;
            z-index: 0;
            content: '';
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: .9;
            background: #007bff;
        }

        .landing-page .overlay:nth-child(2):before {
            background: #00a023;
        }
        
    </style>

@endsection

@section('content')
    <div class="landing-page">
        <!-- Slider Section -->
        <div class="d-flex overlay">
            <div class="container-fluid intro-container">
                <div class="mb-5 pl-5 row">
                    <div class="col-md-6 col-sm-12 mb-5 mt-5 pl-5 pt-5">
                        <h1 class="display-4 text-white mt-5">Everything!
                        <br>
                        <span class="" style="font-size: 0.6em;">In One Package. On The Cloud</span></h1>
                        <p class="text-white">We bring you all the best tools on the cloud so that you can worry less and deliver more without doing any of the heavy lifting. Our solution helps you do more with less so that you can focus on what really matters, Managing Costs and Maximizing Profits</p>
                        <a href="#" class="btn btn-lg btn-pill btn-success mr-3 mb-3">Get Started</a>
                        <a href="#" class="btn btn-lg btn-outline-white btn-pill mr-3 mb-3">Learn More</a>
                    </div>
                    <div class="col-md-6 mb-5">
                        <img src="/images/backgrounds/jobcard-search.png" class="w-100">
                    </div>
                </div>
            </div>
        </div>
        <!-- / Slider Section -->
        <div class="d-flex overlay">
            <div class="row">
                <div class="col-md-6 mt-5 mb-5 ">
                    <img src="/images/backgrounds/Everyone.png" class="w-100">
                </div>
                <div class="col-md-6 col-sm-12 mb-5 mt-5 pr-5 pt-5">
                    <h1 class="display-4 text-white">
                        Everyone!
                        <br> 
                        <span style="font-size: 0.6em;">In One place. With A Goal</span>
                    </h1>
                    <p class="text-white">Enjoy the ease of staying connected with all your stakeholders all in one place. Just send your managers, staff members, contractors and customers login details received via Email/SMS to keep everyone in the loop with all jobs, projects and work thats pending, in progress or completed</p>
                    <p class="text-white">Keep communication going by updating clients and receiving constructive feedback using well designed surveys and forms. Get realtime progress reports from contractors and when the work is done close your jobs while sending automatic emails to everyone for job well done!</p>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('js')

@endsection

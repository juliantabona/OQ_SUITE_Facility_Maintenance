@extends('dashboard.layouts.app-plain')

@section('style')

<style>

    .register-page .overlay {
        border-bottom: 3px solid #fff;
        position: relative;
        min-height: 120vh;
        background: url(../../images/backgrounds/welcome-cover.jpg) no-repeat center center fixed;
        background-size: cover;
    }

    .register-page .overlay:before {
        position: absolute;
        z-index: 0;
        content: '';
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: .9;
        background: #0e1c3a;
    }
    
    .register-box{
        position: relative;
        background: #fff;
        overflow: hidden;
        min-height: 400px;
    }

    .main-registration-container {
        position: absolute;
        width: 100%;
        top: 0px;
    }

    #slider_box{
        overflow: hidden;
        position: relative;
        padding: 10px 0;
    }

    .slide {
        width: 100%;
        background:#fff;
        position: absolute;
        padding: 0 10px;
        top: 0px;
        left:0px;
        z-index:1;
        transition: left 1s ease;
    }

    .slide.active {
        z-index:2;
    }

    .final_slide h4{
        text-align: center;
        margin: 25px auto;
        color: #fbbd19;
    }

    .final_slide img{
        width: 50%;
        margin: auto;
        display: block;
    }

    .action_box{
        position: absolute;
        bottom: 0;
        right: 10px;
    }

    .loader-box {
        border-radius: 6px;
        display: none;
        background: #27334e57;
        position: absolute;
        z-index: 1000;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }

    .loader {
        align-items: center;
        justify-content: center;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #fbbd19;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        margin: 25% auto;
        display: block;
        animation: spin 0.75s linear infinite;
    }

    @keyframes  spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .bs-wizard {margin-top: 40px;}

    /*Form Wizard*/
    .bs-wizard {border-bottom: solid 1px #e0e0e0;padding: 0 0 5px 0;margin-bottom: 20px;}
    .bs-wizard > .bs-wizard-step {padding: 0; position: relative;}
    .bs-wizard > .bs-wizard-step + .bs-wizard-step {}
    .bs-wizard > .bs-wizard-step .bs-wizard-stepnum {font-size: 16px; margin-bottom: 5px;}
    .bs-wizard > .bs-wizard-step .bs-wizard-info {color: #999; font-size: 14px;}
    .bs-wizard > .bs-wizard-step > .bs-wizard-dot {position: absolute; width: 30px; height: 30px; display: block; background: #fbe8aa; top: 45px; left: 50%; margin-top: -15px; margin-left: -15px; border-radius: 50%;} 
    .bs-wizard > .bs-wizard-step > .bs-wizard-dot:after {content: ' '; width: 14px; height: 14px; background: #fbbd19; border-radius: 50px; position: absolute; top: 8px; left: 8px; } 
    .bs-wizard > .bs-wizard-step > .progress {position: relative;border-radius: 0px;height: 8px;box-shadow: none;margin: 20px 0;left: 50%;}
    .bs-wizard > .bs-wizard-step > .progress > .progress-bar {width:0px; box-shadow: none; background: #fbe8aa;}
    .bs-wizard > .bs-wizard-step.complete > .progress > .progress-bar {width:100%;}
    .bs-wizard > .bs-wizard-step.active > .progress > .progress-bar {width: 0%;}
    .bs-wizard > .bs-wizard-step:first-child.active > .progress > .progress-bar {width:0%;}
    .bs-wizard > .bs-wizard-step:last-child.active > .progress > .progress-bar {width: 100%;}
    .bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot {background-color: #f5f5f5;}
    .bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot:after {opacity: 0;}
    .bs-wizard > .bs-wizard-step:first-child  > .progress {left: 50%;w;width: 100%;}
    .bs-wizard > .bs-wizard-step:last-child  > .progress {width: 0%;}
    .bs-wizard > .bs-wizard-step.disabled a.bs-wizard-dot{ pointer-events: none; }
    /*END Form Wizard*/

</style>



@endsection

@section('content')
    <div class="register-page">
        <!-- register Section -->
        <div class="overlay">
            <div class="container-fluid pt-2">
                <div class="row mt-5">
                    <div class="register-box col-lg-6 mx-auto p-5">
                        <h2 class="font-weight-light mt-4 text-center">Register</h2>

                        <div class="container">
                            <div class="row bs-wizard">
                                
                                <div class="col-4 bs-wizard-step active">
                                    <div class="text-center bs-wizard-stepnum">Account</div>
                                    <div class="progress"><div class="progress-bar"></div></div>
                                    <a href="#" class="bs-wizard-dot"></a>
                                </div>
                                
                                <div class="col-4 bs-wizard-step disabled">
                                    <div class="text-center bs-wizard-stepnum">Company</div>
                                    <div class="progress"><div class="progress-bar"></div></div>
                                    <a href="#" class="bs-wizard-dot"></a>
                                </div>
                                
                                <div class="col-4 bs-wizard-step disabled">
                                    <div class="text-center bs-wizard-stepnum">Dashboard</div>
                                    <div class="progress"><div class="progress-bar"></div></div>
                                    <a href="#" class="bs-wizard-dot"></a>
                                </div>
                            </div>
                        </div>
                        @include('dashboard/layouts/alerts/default-top-alerts') 
                        <div id = "slider_box">
                            <div class="loader-box">
                                <div class="loader"></div>
                            </div>
                            
                            <form method="POST" action="{{ route('register') }}" class="pt-4">
                                @csrf
                                <div class="slide active">
                                    <div class="form-group row">
                                        <div class="col-12 col-sm-6 mb-3">
                                            <input id="first_name" type="text" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{ old('first_name') }}" placeholder = "First name">
                                            @if ($errors->has('first_name'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('first_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <input id="last_name" type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ old('last_name') }}" placeholder = "Last name">
                                            @if ($errors->has('last_name'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('last_name') }}</strong>
                                                </span>
                                            @endif                                
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" placeholder = "Username">
                                            @if ($errors->has('username'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('username') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
            
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input id="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder = "Email Address">
                                            @if ($errors->has('email'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
            
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder = "Password">
                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
            
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder = "Confirm Password">
                                        </div>
                                    </div>
                                    <div class="action_box mt-5 clearfix">
                                        <button type="button" class="btn next_btn btn-success float-right pb-3 pl-5 pr-5 pt-3 ml-2">
                                            Next
                                            <i class="icon-arrow-right icons"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="slide">
                                    <div class="form-group row mt-5">
                                        <div class="col-12 col-sm-6 mb-3">
                                            <input id="company_name" type="text" class="form-control{{ $errors->has('company_name') ? ' is-invalid' : '' }}" name="company_name" value="{{ old('company_name') }}" placeholder = "Company name">
                                            @if ($errors->has('company_name'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('company_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <input id="company_branch_name" type="text" class="form-control{{ $errors->has('company_branch_name') ? ' is-invalid' : '' }}" name="company_branch_name" value="{{ old('company_branch_name') }}" placeholder = "Branch name">
                                            @if ($errors->has('company_branch_name'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('company_branch_name') }}</strong>
                                                </span>
                                            @endif                                
                                        </div>
                                    </div>
            
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input id="company_destination" type="text" class="form-control{{ $errors->has('company_destination') ? ' is-invalid' : '' }}" name="company_destination" value="{{ old('company_destination') }}" placeholder = "Company location">
                                            @if ($errors->has('company_destination'))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('company_destination') }}</strong>
                                                </span>
                                            @endif                                
                                        </div>
                                    </div>
                                    <div class="action_box mt-5 clearfix">
                                        <button type="submit" class="btn next_btn submit_btn btn-success float-right pb-3 pl-5 pr-5 pt-3 ml-2">
                                            Done
                                            <i class="icon-arrow-right icons"></i>
                                        </button>
                                        <button type="button" class="btn back_btn btn-inverse-light float-right pb-3 pl-5 pr-5 pt-3">
                                            <i class="icon-arrow-left icons"></i>
                                            Back
                                        </button>
                                    </div>
                                </div>
                                <div class="slide final_slide">
                                    <h4>Creating Account</h4>
                                    <br>
                                    <img src="/images/backgrounds/Everyone.png">
                                </div> 
                            </form>
                        </div>
                        <div class="mt-3 text-center">
                            <a href="{{ route('login') }}">Login?</a>
                        </div>
                    </div>            
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function(){
            /*  
             *  Make the sliders and slide container the same height as the longest slider
             */
            var maxHeight = -1;
            
            $('div.slide').each(function() {
                if ($(this).height() > maxHeight) {
                    maxHeight = $(this).height();
                }
            });

            var action_box = $('.action_box').height();
            $('#slider_box, .slide').height(maxHeight + action_box);
            
            /*  
             *  Move all the next sliders to the left out of view 
             *  except the first slide
             */

            var slideBoxWidth = $('#slider_box').width();
            
            $('.slide:not(:first)').css('left', slideBoxWidth);

        });

        $(document).on("click",".next_btn",function(e){
            moveForward();
        });

        $(document).on("click",".back_btn",function(e){
            moveBack();
        });

        $(document).on("click",".submit_btn", function(){
            showLoader();
            hideLinks();
        });

        function moveSlideIndicator(){
            $('.slide').each(function(index ,el) {
                if( $(this).hasClass('active') ){

                    //  all elements are made completed
                    $('.bs-wizard-step').removeClass('active').removeClass('disabled').addClass('complete');

                    //  only the current element is active
                    $('.bs-wizard-step:nth-child('+(index+1)+')').removeClass('complete').removeClass('disabled').addClass('active');
                    
                    //  everything else is disabled
                    $('.bs-wizard-step:nth-child(n+'+(index+2)+')').removeClass('complete').removeClass('active').addClass('disabled');
                    
                }
            });
        }

        function moveBack(){
            var curr_slide = $('.slide.active');
            var prev_slide = $(curr_slide).prev();

            /*  
             *  Move the current slide to the right out of view
             *  Move the previous slide to the right into view
             */

             var slideBoxWidth = $('#slider_box').width();

             $(curr_slide).css('left', slideBoxWidth).removeClass('active');
             $(prev_slide).css('left', '0px').addClass('active');

             moveSlideIndicator();
        }

        function moveForward(){
            var curr_slide = $('.slide.active');
            var next_slide = $(curr_slide).next();

            /*  
             *  Move the current slide to the left out of view
             *  Move the next slide to the left into view
             */

             var slideBoxWidth = $('#slider_box').width();

             $(curr_slide).css('left', -slideBoxWidth).removeClass('active');
             $(next_slide).css('left', '0px').addClass('active');

             moveSlideIndicator();
        }

        function showLoader(){
            $('.loader-box').css('display','block');
        }

        function hideLinks(){
            $('form a').hide();
        }
        
    </script>
@endsection

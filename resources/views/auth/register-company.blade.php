@extends('dashboard.layouts.app-plain')

@section('style')

    <style>

        .login-page .overlay {
            border-bottom: 3px solid #fff;
            position: relative;
            min-height: 120vh;
            background: url(../../images/backgrounds/welcome-cover.jpg) no-repeat center center fixed;
            background-size: cover;
        }

        .login-page .overlay:before {
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
        
        .login-box{
            background:#fff;
        }
        
        .bs-wizard {margin-top: 40px;}

        /*Form Wizard*/
        .bs-wizard {border-bottom: solid 1px #e0e0e0; padding: 0 0 10px 0;}
        .bs-wizard > .bs-wizard-step {padding: 0; position: relative;}
        .bs-wizard > .bs-wizard-step + .bs-wizard-step {}
        .bs-wizard > .bs-wizard-step .bs-wizard-stepnum {font-size: 16px; margin-bottom: 5px;}
        .bs-wizard > .bs-wizard-step .bs-wizard-info {color: #999; font-size: 14px;}
        .bs-wizard > .bs-wizard-step > .bs-wizard-dot {position: absolute; width: 30px; height: 30px; display: block; background: #fbe8aa; top: 45px; left: 50%; margin-top: -15px; margin-left: -15px; border-radius: 50%;} 
        .bs-wizard > .bs-wizard-step > .bs-wizard-dot:after {content: ' '; width: 14px; height: 14px; background: #fbbd19; border-radius: 50px; position: absolute; top: 8px; left: 8px; } 
        .bs-wizard > .bs-wizard-step > .progress {position: relative; border-radius: 0px; height: 8px; box-shadow: none; margin: 20px 0;}
        .bs-wizard > .bs-wizard-step > .progress > .progress-bar {width:0px; box-shadow: none; background: #fbe8aa;}
        .bs-wizard > .bs-wizard-step.complete > .progress > .progress-bar {width:100%;}
        .bs-wizard > .bs-wizard-step.active > .progress > .progress-bar {width:50%;}
        .bs-wizard > .bs-wizard-step:first-child.active > .progress > .progress-bar {width:0%;}
        .bs-wizard > .bs-wizard-step:last-child.active > .progress > .progress-bar {width: 100%;}
        .bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot {background-color: #f5f5f5;}
        .bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot:after {opacity: 0;}
        .bs-wizard > .bs-wizard-step:first-child  > .progress {left: 50%; width: 50%;}
        .bs-wizard > .bs-wizard-step:last-child  > .progress {width: 50%;}
        .bs-wizard > .bs-wizard-step.disabled a.bs-wizard-dot{ pointer-events: none; }
        /*END Form Wizard*/

    </style>

@endsection

@section('content')
    <div class="login-page">
        <!-- Login Section -->
        <div class="overlay">
            <div class="container-fluid pt-2">
                <div class="row mt-5">
                    <div class="login-box col-lg-6 mx-auto p-5">
                        <h2 class="font-weight-light mt-4 text-center">Register</h2>

                        <div class="container">
                            <div class="row bs-wizard">
                                
                                <div class="col-4 bs-wizard-step complete">
                                    <div class="text-center bs-wizard-stepnum">Account</div>
                                    <div class="progress"><div class="progress-bar"></div></div>
                                    <a href="#" class="bs-wizard-dot"></a>
                                </div>
                                
                                <div class="col-4 bs-wizard-step active">
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
                        <form method="POST" action="{{ route('register-company-store') }}" class="pt-4">
                            @csrf
                            <div class="form-group row">
                                <div class="col-12 col-sm-6">
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
                            <div class="mt-5 clearfix">
                                <button type="submit" class="btn next_btn btn-warning float-right pb-3 pl-5 pr-5 pt-3 ml-2">
                                    Done
                                    <i class="icon-arrow-right icons"></i>
                                </button>
                                <button type="button" class="btn back_btn btn-inverse-light float-right pb-3 pl-5 pr-5 pt-3">
                                    <i class="icon-arrow-left icons"></i>
                                    Back
                                </button>
                            </div>
                            <div class="mt-3 text-center">
                                <a href="/login" class="auth-link text-white">Login?</a>
                            </div>
                        </form>
                    </div>            
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

@endsection

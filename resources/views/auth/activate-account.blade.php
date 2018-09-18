@extends('dashboard.layouts.app-plain')

@section('style')

<style>

    .activate-page .overlay {
        border-bottom: 3px solid #fff;
        position: relative;
        min-height: 120vh;
        background: url(../../images/backgrounds/welcome-cover.jpg) no-repeat center center fixed;
        background-size: cover;
    }

    .activate-page .overlay:before {
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
    
    .activate-box{
        position: relative;
        background: #fff;
        overflow: hidden;
        min-height: 400px;
    }

    .mail{
        margin: auto;
        max-height: 200px;
        display: block;
    }


</style>



@endsection

@section('content')
    <div class="activate-page">
        <!-- activate Section -->
        <div class="overlay">
            <div class="container-fluid pt-2">
                <div class="row mt-5">
                    <div class="activate-box col-lg-6 mx-auto p-5">
                        @include('dashboard/layouts/alerts/default-top-alerts') 
                        <img class="mail" src="/images/assets/icons/closed-envelope.png">
                        <h2 class="font-weight-light mt-4 text-center">Activate Account</h2>
                        <p style="text-align:center;">Visit your email to activate your account!</p>
                        <form method="POST" action="{{ route('activate-resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-success d-block m-auto">Resend Activation</button>
                        </form>
                    </div>            
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

@endsection

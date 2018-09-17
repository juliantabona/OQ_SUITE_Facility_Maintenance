@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12 d-flex flex-column">
            <div class="row flex-grow">
                <div class="col-12 col-md-12 col-lg-12 grid-margin stretch-card">
                    <div class="card card-hoverable">
                        <div class="card-body">
                            <div class="col-4 offset-4">
                                <div class="mt-3 text-center">
                                    <i class="icon-ghost icon-sm icons ml-3"></i>
                                    <h6 class="card-title mt-2 mb-3 ml-2">Sorry, jobcard was not found</h6>
                                </div>
                                <div data-toggle="tooltip" data-placement="top" title="Create a new jobcard">
                                    <a href="{{ route('jobcard-create') }}" class="btn btn-success p-5 w-100 animated-strips">                                            
                                        <i class="d-block icon-sm icon-flag icons" style="font-size: 25px;"></i>
                                        <span class="d-block mt-4">Create Jobcard</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')

    <!-- Custom js for this page-->
    <script src="{{ asset('js/custom/dashboard.js') }}"></script>
    <script src="{{ asset('js/custom/chart.js') }}"></script>
    <!-- End custom js for this page-->

@endsection
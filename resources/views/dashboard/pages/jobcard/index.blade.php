@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12 d-flex flex-column">
            <div class="row flex-grow">
                <div class="col-12 col-md-12 col-lg-12 grid-margin stretch-card">
                    <div class="card card-hoverable">
                        <div class="card-body">
                            <i class="float-left icon-flag icon-sm icons ml-3"></i>
                            @if( COUNT($jobcards) )
                                <h6 class="card-title float-left mb-0 ml-2">All Jobs</h6>
                                <div class="d-flex table-responsive">
                                    <div class="btn-group ml-auto mr-2 border-0">
                                    <input type="text" class="form-control" placeholder="Search Here">
                                    <button class="btn btn-sm btn-primary"><i class="icon-magnifier icons"></i> Search</button>
                                    </div>
                                    <div class="btn-group">
                                    <button type="button" class="btn btn-light"><i class="mdi mdi-printer"></i></button>
                                    <button type="button" class="btn btn-light"><i class="mdi mdi-dots-vertical"></i></button>
                                    </div>
                                </div>
                                <div class="table-responsive table-hover">
                                    <table class="table mt-3 border-top">
                                        <thead>
                                            <tr>
                                                <th style="width: 28%">Customer</th>
                                                <th style="width: 15%">Start Date</th>
                                                <th style="width: 15%">End Date</th>
                                                <th style="width: 18%" class="d-sm-none d-md-table-cell">Contractor</th>
                                                <th style="width: 14%">Due</th>
                                                <th class="d-sm-none d-md-table-cell">Priority</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($jobcards as $jobcard)
                                                <tr class='clickable-row' data-href='/jobcards/{{ $jobcard->id }}'>
                                                    <td data-toggle="tooltip" data-placement="top" title="{{ $jobcard->description }}">{{ $jobcard->title ? $jobcard->title:'____' }}</td>
                                                    <td>{{ $jobcard->start_date ? Carbon\Carbon::parse($jobcard->start_date)->format('d M Y'):'____' }}</td>
                                                    <td>{{ $jobcard->end_date ? Carbon\Carbon::parse($jobcard->end_date)->format('d M Y'):'____' }}</td>
                                                    
                                                    @php
                                                        $selectedContractor = $jobcard->selectedContractor;
                                                    @endphp

                                                    <td data-toggle="tooltip" data-placement="top" data-html="true" 
                                                        title="Phone: {{ $selectedContractor['phone_ext'] ? '+'.$selectedContractor['phone_ext'].'-':'___-' }}
                                                                      {{ $selectedContractor['phone_num'] ? $selectedContractor['phone_num']:'____' }}
                                                                <br>
                                                                Email: {{ $selectedContractor['email'] ? $selectedContractor['email']:'____' }}" class="d-none d-md-table-cell">{{ $selectedContractor['name'] ? $selectedContractor['name']:'____' }}</td>      
                                                    <td class="d-none d-md-table-cell">
                                                        @php
                                                            $deadline = round((strtotime($jobcard->end_date)  
                                                                            - strtotime(\Carbon\Carbon::now()->toDateTimeString()))  
                                                                            / (60 * 60 * 24)) 
                                                        @endphp
                                                        @if($deadline > 0)
                                                            {{ $deadline == 1 ? $deadline.' day' : $deadline.' days'  }}
                                                        @else
                                                            <i class="icon-exclamation icons mr-1 text-danger"></i>
                                                            <span class="text-danger">due</span>
                                                        @endif
                                                    </td>                                             
                                                    <td class="d-none d-md-table-cell">{{ $jobcard->priority->name }}</td>                                           
                                                    <td>
                                                        @if($jobcard->processFormStep)  
                                                            @php
                                                                $status = $jobcard->processFormStep->step_instruction;
                                                            @endphp
                                                            @if($status)
                                                                <div class="badge badge-success badge-fw" style="background:{{ $status['color'] }};">
                                                                    {{ $status['name'] }}
                                                                </div>
                                                            @else
                                                                ____
                                                            @endif 
                                                        @else
                                                            ____
                                                        @endif 
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex align-items-center justify-content-between flex-column flex-sm-row mt-4">
                                        <p class="mb-3 ml-3 mb-sm-0"><strong>{{ $jobcards->total() }}</strong>{{ $jobcards->total() == 1 ? ' result': '  results' }} found</p>
                                    <nav>
                                        {{ $jobcards->links() }}
                                    </nav>
                                </div>
                                <div class="d-flex float-right mt-4">
                                    <button class="btn btn-primary btn-sm">View All
                                        <i class="icon-arrow-right-circle icons ml-1"></i>
                                    </button>
                                </div>
                            @else
                                <h6 class="card-title float-left mb-0 ml-2">No Jobcards</h6>
                                <div class="col-4 offset-4">
                                    <div data-toggle="tooltip" data-placement="top" title="Create a new jobcard">
                                        <a href="/jobcards/create" class="btn btn-success p-5 w-100 animated-strips">                                            
                                            <i class="d-block icon-sm icon-flag icons" style="font-size: 25px;"></i>
                                            <span class="d-block mt-4">Create Jobcard</span>
                                        </a>
                                    </div>
                                </div>
                            @endif
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
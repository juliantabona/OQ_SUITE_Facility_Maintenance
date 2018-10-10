@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12 d-flex flex-column">
            <div class="row flex-grow">

                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body pt-3 pl-3 pr-3 pb-2">
                            <div class="align-items-center d-flex float-left justify-content-between mr-3 mt-0">
                                <div class="d-inline-block">
                                    <div class="bg-success px-md-4 rounded">
                                        <i class="d-inline-block icon-md icon-briefcase pb-2 pt-3 text-white"></i>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="d-inline-block pt-1">
                                    <span class="text-success card-title mb-0 d-block">Statistics</span>
                                    <div class="d-lg-flex">
                                        <h4 class="mb-0">{{ $contractors->total() }} {{ $contractors->total() == 1 ? 'Contractor':'Contractors' }}</h4>
                                    </div>
                                </div>
                                <a href="{{ route('company-create') }}?type=contractor" class="btn btn-primary btn-sm float-right mt-2">
                                        + Create contractor
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <div class="col-12 col-md-12 col-lg-12 grid-margin stretch-card">
                        <div class="card card-hoverable">
                            <div class="card-body">
                                @if( COUNT($contractors) )
                                    <h3 class="card-title mb-3 mt-4">contractors</h3>
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
                                                    <th style="width: 25%">Customer</th>
                                                    <th style="width: 15%">City/Town</th>
                                                    <th style="width: 15%">Email</th>
                                                    <th style="width: 15%">Phone</th>
                                                    <th style="width: 15%">Added On</th>
                                                    <th style="width: 20%">Added By</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($contractors as $contractor)
                                                    <tr class='clickable-row' data-href="{{ route('contractor-show', [$contractor->id]) }}">
                                                        <td>{{ $contractor->name }}</td>
                                                        <td>{{ $contractor->city ? $contractor->city:'____' }}</td>
                                                        <td>{{ $contractor->email ? $contractor->email:'____' }}</td>
                                                        <td>
                                                            {{ $contractor->phone_ext ? '+'.$contractor->phone_ext.'-':'___-' }}
                                                            {{ $contractor->phone_num ? $contractor->phone_num:'____' }}
                                                        </td>
                                                        <td>{{ $contractor->created_at ? Carbon\Carbon::parse($contractor->created_at)->format('d M Y'):'____' }}</td>
                                                        <td><a href="{{ route('contact-show', [$contractor->createdBy->id]) }}">{{ $contractor->createdBy->first_name ? $contractor->createdBy->first_name:'____' }} {{ $contractor->createdBy->last_name ? $contractor->createdBy->last_name:'____' }}</a></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between flex-column flex-sm-row mt-4">
                                        @if($contractors->total() != 0)
                                            <p class="mb-3 ml-3 mb-sm-0">
                                                <strong>{{ $contractors->total() }}</strong>{{ $contractors->total() == 1 ? ' result': '  results' }} found
                                            </p>
                                        @else
                                            <div class="col-6 offset-3" data-toggle="tooltip" data-placement="top" title="Create a new jobcard for {{ $contractor->name }}">
                                                <a href="{{ route('jobcard-create') }}?company_id={{ $contractor->id }}" class="btn btn-success p-5 w-100 animated-strips">                                            
                                                    <i class="d-block icon-sm icon-flag icons" style="font-size: 25px;"></i>
                                                    <span class="d-block mt-4">Create Jobcard</span>
                                                </a>
                                            </div>
                                        @endif
                                        <nav>
                                            {{ $contractors->links() }}
                                        </nav>
                                    </div>
                                @else
                                    <h6 class="card-title float-left mb-0 ml-2">No contractors</h6>
                                    <div class="col-4 offset-4">
                                        <div data-toggle="tooltip" data-placement="top" title="Create a new jobcard">
                                            <a href="#" class="btn btn-success p-5 w-100 animated-strips">                                            
                                                <i class="d-block icon-sm icon-flag icons" style="font-size: 25px;"></i>
                                                <span class="d-block mt-4">Create contractor</span>
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
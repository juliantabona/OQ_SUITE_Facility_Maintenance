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
                                        <i class="d-inline-block icon-md icon-emotsmile pb-2 pt-3 text-white"></i>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="d-inline-block pt-1">
                                    <span class="text-success card-title mb-0 d-block">Statistics</span>
                                    <div class="d-lg-flex">
                                        <h4 class="mb-0">{{ $clients->total() }} {{ $clients->total() == 1 ? 'Client':'Clients' }}</h4>
                                    </div>
                                </div>
                                <a href="{{ route('company-create') }}?type=client" class="btn btn-primary btn-sm float-right mt-2">
                                    + Create Client
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-12 col-lg-12 grid-margin stretch-card">
                    <div class="card card-hoverable">
                        <div class="card-body">
                            @if( COUNT($clients) )
                                <h3 class="card-title mb-3 mt-4">Clients</h3>
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
                                            @foreach($clients as $client)
                                                <tr class='clickable-row' data-href="{{ route('client-show', [$client->id]) }}">
                                                    <td>{{ $client->name }}</td>
                                                    <td>{{ $client->city ? $client->city:'____' }}</td>
                                                    <td>{{ $client->email ? $client->email:'____' }}</td>
                                                    <td>
                                                        {{ $client->phone_ext ? '+'.$client->phone_ext.'-':'___-' }}
                                                        {{ $client->phone_num ? $client->phone_num:'____' }}
                                                    </td>
                                                    <td>{{ $client->created_at ? Carbon\Carbon::parse($client->created_at)->format('d M Y'):'____' }}</td>
                                                    <td><a href="{{ route('contact-show', [$client->createdBy->id]) }}">{{ $client->createdBy->first_name ? $client->createdBy->first_name:'____' }} {{ $client->createdBy->last_name ? $client->createdBy->last_name:'____' }}</a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex align-items-center justify-content-between flex-column flex-sm-row mt-4">
                                    @if($clients->total() != 0)
                                        <p class="mb-3 ml-3 mb-sm-0">
                                            <strong>{{ $clients->total() }}</strong>{{ $clients->total() == 1 ? ' result': '  results' }} found
                                        </p>
                                    @else
                                        <div class="col-6 offset-3" data-toggle="tooltip" data-placement="top" title="Create a new jobcard for {{ $client->name }}">
                                            <a href="{{ route('jobcard-create') }}?company_id={{ $client->id }}" class="btn btn-success p-5 w-100 animated-strips">                                            
                                                <i class="d-block icon-sm icon-flag icons" style="font-size: 25px;"></i>
                                                <span class="d-block mt-4">Create Jobcard</span>
                                            </a>
                                        </div>
                                    @endif
                                    <nav>
                                        {{ $clients->links() }}
                                    </nav>
                                </div>
                            @else
                                <h6 class="card-title float-left mb-0 ml-2">No Clients</h6>
                                <div class="col-4 offset-4">
                                    <div data-toggle="tooltip" data-placement="top" title="Create a new jobcard">
                                        <a href="#" class="btn btn-success p-5 w-100 animated-strips">                                            
                                            <i class="d-block icon-sm icon-flag icons" style="font-size: 25px;"></i>
                                            <span class="d-block mt-4">Create Client</span>
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
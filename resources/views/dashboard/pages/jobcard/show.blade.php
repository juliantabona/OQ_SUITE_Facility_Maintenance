@extends('dashboard.layouts.app')

@section('style')

<link rel="stylesheet" href="{{ asset('css/plugins/lightgallery/dist/css/lightgallery.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/plugins/dropify/dist/css/dropify.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/plugins/icheck/skins/all.css') }}">

<style>

    .jobcard-container .card {
        border: 1px solid #dbe3e6;
    }

    .lower-font {
        font-size: 14px;
    }

    .reference-details span {
        padding-top: 3px;
        display: block;
    }

    .company-logo{
        width: auto !important;
        max-width: 80px;
        height: 80px !important;
    }

    .modal-content-max-height {
        overflow-y: auto;
        overflow-x: hidden;
        max-height: 320px;
        padding: 20px 15px;
    }

    .process_notice {
        color: #ffab00;
        font-size: 25px;
        position: absolute;
        top: -13px;
        right: 15px;
    }

    .process_notice > a{
        position: absolute;
        font-size: 14px;
        top: 8px;
        right: 0;
    }

    .process_notice > i {
        top: 0;
        right: 68px;
        position: absolute;
        animation: beat 0.5s infinite alternate;
    }

</style>

@endsection 

@section('content')
    <div class="row jobcard-container">
        <a href="/jobcards" class="btn btn-primary mt-3 ml-2 mb-2">
            <i class="icon-arrow-left icons"></i>
            Go Back
        </a>
        <a href="/jobcards/1/viewers" class="btn btn-inverse-light mt-3 ml-2 mb-2">
            <i class="icon-eye icons"></i>
            1 viewer
        </a>

        <div class="col-lg-12 d-flex flex-column">
            <div class="row flex-grow">
                <div class="col-12 col-md-8 col-lg-8 grid-margin stretch-card">
                    <div class="card card-hoverable">
                        <div class="card-body p-3 pt-4">
                            <div class="row">
                                <div class="col-12">
                                    @if( $deadline !== null )
                                        @if($deadline >= 0)
                                            <div class="badge badge-warning">{{ $deadline == 1 ? $deadline.' day': $deadline.' days' }} until deadline</div>
                                        @else
                                        <div class="badge badge-danger">Deadline passed</div>
                                        @endif
                                    @endif
                                    <span class="process_notice">
                                        <i class="icon-exclamation icons"></i>
                                        <a href="#" class="text-warning">Authorize</a>
                                    </span>
                                    <div id="jobcard_lifecycle">
                                        <input type="hidden" value="{{ $processForm->form_structure }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <h3 class="card-title mb-3 mt-4 border-bottom pb-3">{{ $jobcard->title ? $jobcard->title:'____' }}</h3>
                                            <b>Description: </b>
                                            <p class="mt-2">{{ $jobcard->description ? $jobcard->description:'____' }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <span class="lower-font mr-4">
                                                <b>Start Date: </b>{{ $jobcard->start_date ? Carbon\Carbon::parse($jobcard->start_date)->format('d M Y'):'____' }}
                                            </span>
                                        </div>        
                                        <div class="col-6">    
                                            <span class="lower-font">
                                                <b>End Date: </b>{{ $jobcard->end_date ? Carbon\Carbon::parse($jobcard->end_date)->format('d M Y'):'____' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <span data-toggle="tooltip" data-placement="top" title="{{ $jobcard->category ? $jobcard->category->description:'____' }}" class="lower-font mr-4">
                                                <b>Catergory: </b>{{ $jobcard->category ? $jobcard->category->name:'____' }}
                                            </span>
                                        </div>
                                        <div class="col-6">
                                            <span class="lower-font" data-toggle="tooltip" data-placement="top" title="{{ $jobcard->costCenter ? $jobcard->costCenter->description:'' }}">
                                                <b>Cost Center: </b>{{ $jobcard->costCenter ? $jobcard->costCenter->name:'____' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <span class="lower-font">
                                                <b>Created By: </b>
                                                @if($jobcard->createdBy)
                                                    <a href="/staff/1">{{ $jobcard->createdBy->first_name.' '.$jobcard->createdBy->last_name }}</a> 
                                                    <a href="/jobcards/1/views/1"> 
                                                        - viewed({{ 
                                                                $jobcard->views()
                                                                        ->where('who_viewed_id', $jobcard->createdBy->id)
                                                                        ->get()->count() 
                                                                }})
                                                    </a>
                                                @else
                                                    ____
                                                @endif
                                            </span>
                                        </div>
                                        <div class="col-6">
                                            <span class="lower-font">
                                                <b>Assigned: </b><a href="/staff/1">Tumisang Mogotsi</a> <a href="/jobcards/1/views/1"> - viewed(0)</a>
                                            </span>
                                        </div>
                                    </div>
                                    <span class="lower-font mt-3 d-block">
                                        <b>Priority: </b>
                                        @if($jobcard->priority)
                                            <div  data-toggle="tooltip" data-placement="top" title="{{ $jobcard->priority->description }}"
                                                class="badge badge-success" style="background:{{ $jobcard->priority->color_code }};">{{ $jobcard->priority ? $jobcard->priority->name:'____' }}</div>
                                        @else
                                            ____
                                        @endif
                                    </span>
                                    <div>
                                        @if($jobcard->img_url)
                                        <div class="lightgallery mt-3">
                                            <a href="{{ $jobcard->img_url }}">
                                                <img class = "w-50" src="{{ $jobcard->img_url }}" />
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mt-0">
                                        <a class="btn btn-primary mr-2 float-right" href="#" target="_blank" class="btn btn-primary">
                                            <i class="icon-cloud-download icons"></i>
                                            Download Jobcard
                                        </a>
                                        <a href="#" class="btn btn-primary mr-2 float-right" data-toggle="modal" data-target="#exampleModal-3">
                                            Send Jobcard
                                            <i class="icon-paper-plane icons"></i>
                                        </a>
                                    </div>                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-4 grid-margin stretch-card">
                    <div class="card card-hoverable">
                        <div class="card-body p-3 pt-4">
                            <div class="row">
                                @if($jobcard->client)
                                    <div class="col-12">
                                        <div class="bg-primary p-2 text-white">
                                            <i class="float-left icon-emotsmile icon-sm icons ml-3 mr-2"></i>
                                            <h6 class="card-title mb-0 ml-2 text-white">Client Details</h6>
                                        </div>
                                        <div class="mt-3 ml-3 reference-details">
                                            @if($jobcard->client->logo_url)
                                                <div class="lightgallery">
                                                    <a href="{{ $jobcard->client->logo_url }}">
                                                        <img class="company-logo img-thumbnail mb-2 p-2 rounded rounded-circle w-50" src="{{ $jobcard->client->logo_url }}" />
                                                    </a>
                                                </div>
                                            @endif
                                            <span class="lower-font">
                                                <b>Client Name: </b>{{ $jobcard->client->name ? $jobcard->client->name:'____' }}<br/>
                                                <b>City/Town: </b>{{ $jobcard->client->city ? $jobcard->client->city:'____' }}<br/>
                                                <b>Address: </b>{{ $jobcard->client->address ? $jobcard->client->address:'____' }}
                                            </span>
                                            <br/>
                                            <span class="lower-font">
                                                <b>Phone: </b>
                                                {{ $jobcard->client->phone_ext ? '+'.$jobcard->client->phone_ext.'-':'___-' }}
                                                {{ $jobcard->client->phone_num ? $jobcard->client->phone_num:'____' }}
                                            </span>
                                            <span class="lower-font mb-3">
                                                <b>Email: </b>{{ $jobcard->client->city ? $jobcard->client->email:'____' }}
                                            </span>
                                            <span class="lower-font clearfix mb-3">
                                                <form method="POST" action="{{ route('jobcard-remove-client', [$jobcard->id, $jobcard->client_id]) }}" class="d-inline">
                                                    {{ method_field('DELETE') }}
                                                    @csrf
                                                    <button type="submit" style="font-size:  12px;" class="btn-link float-right mr-1">
                                                        <i class="icon-trash"></i> 
                                                        Remove
                                                    </button>
                                                </form>
                                                <a href="{{ route('company-edit', $jobcard->client_id) }}" style="font-size:  12px;" class="float-right mr-1"><i class="icon-pencil"></i> Edit</a>   
                                                <a href="#" style="font-size:  12px;" class="float-right mr-1"><i class="icon-refresh"></i> Change Client</a>
                                                <a href="{{ route('client-show', [$jobcard->client_id]) }}" style="font-size:  12px;" class="float-right mr-1"><i class="icon-eye"></i> View</a>
                                            </span> 
                                        </div>
                                    </div>

                                    @if($contacts->total())
                                        <div class="col-12 mb-2">
                                            <div class="bg-primary p-2 text-white">
                                                <i class="float-left icon-user icon-sm icons ml-3 mr-2"></i>
                                                <h6 class="card-title mb-0 ml-2 text-white d-inline">Contact Details ({{ $contacts->total() }})</h6>
                                                <a href="#" style="font-size:  12px;" class="float-right mr-1 mt-1 text-white"><i class="icon-eye"></i> View All</a>
                                            </div>
                                            
                                            @foreach($contacts as $contact)
                                                <div class="mt-1 ml-2 reference-details">
                                                    <div class=" d-flex align-items-center border-bottom p-2">
                                                        <a class="p-0 m-0">
                                                            <img class="img-sm rounded-circle" src="http://127.0.0.1:8000/images/profile_placeholder.svg" alt="">
                                                        </a>
                                                        <div class="wrapper w-100 ml-3">
                                                            <p class="pt-2 mb-2" style="font-size:  12px;">
                                                                <a href="#" class="mr-1">{{ $contact->first_name ? $contact->first_name:'____' }} {{ $contact->last_name ? $contact->last_name:'____' }}</a>
                                                            </p>
                                                            <div>
                                                                @if($contact->position)
                                                                    <div class="d-inline mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ $contact->position ? $contact->position:'____' }}">
                                                                        <i class="icon-info text-dark"></i>
                                                                    </div>
                                                                @endif
                                                                @if($contact->phone_num)
                                                                    <div class="d-inline mr-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ $contact->phone_ext ? '+'.$contact->phone_ext.'-':'___-' }} {{ $contact->phone_num ? $contact->phone_num:'____' }}">
                                                                        <i class="icon-phone text-dark"></i>
                                                                    </div>
                                                                @endif
                                                                @if($contact->email)
                                                                    <div class="d-inline" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ $contact->email ? $contact->email:'____' }}">
                                                                        <i class="icon-envelope text-dark"></i>
                                                                    </div>
                                                                @endif
                                                                <a href="#" style="font-size:  12px;" class="float-right mr-1"><i class="icon-trash"></i> Remove</a>
                                                                <a href="#" style="font-size:  12px;" class="float-right mr-1"><i class="icon-pencil"></i> Edit</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                            <div class="float-right d-flex align-items-center justify-content-between flex-column flex-sm-row mt-4">
                                                <nav>
                                                    {{ $contacts->links() }}
                                                </nav>
                                            </div>

                                        </div>
                                    @endif
                                    <div class="col-12">
                                        <div data-toggle="tooltip" data-placement="top" title="Add another contact/reference working at this company or organisation" >
                                            <button type="button" class="animated-strips btn btn-success float-right pt-3 pb-3 pl-4 pr-4 w-100" data-toggle="modal" data-target="#add-reference-modal">                                            
                                                <i class="icon-sm icon-user icons"></i>
                                                <span class="mt-4">Add Contact</span>
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-12">
                                        <div data-toggle="tooltip" data-placement="top" title="Add a company or organisation corresponding to this jobcard">
                                            <button type="button" class="btn btn-success p-5 w-100 animated-strips" data-toggle="modal" data-target="#add-client-modal">                                            
                                                <i class="d-block icon-sm icon-emotsmile icons" style="font-size: 25px;"></i>
                                                <span class="d-block mt-4">Add Client</span>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-12 col-lg-12 grid-margin stretch-card">
                    <div class="card card-hoverable">
                        <div class="card-body p-3 pt-4">
                            <div class="row">
                                @if($contractors->total())
                                    <div class="col-12 clearfix">
                                        <h4 class="card-title mb-3 mt-4 ml-2">Potential Contractors ({{ $contractors->total() }})</h4>
                                        <div class="table-responsive table-hover">
                                            <table class="table mt-3 border-top">
                                                <thead>
                                                    <tr>
                                                        <td>Choose</td>
                                                        <th>Logo</th>
                                                        <th>Company Name</th>
                                                        <th style="min-width: 18%">Tel</th>
                                                        <th style="min-width: 18%">Email</th>
                                                        <th>Submitted On</th>
                                                        <th class="d-sm-none d-md-table-cell">Price</th>
                                                        <th class="d-sm-none d-md-table-cell">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($contractors as $contractor)
                                                        <tr class="clickable-row show-contractor-modal-btn" data-toggle="modal" data-target="#show-contractor-modal">
                                                            <td>
                                                                <form method="POST" action="{{ route('jobcard-select-contractor', [$jobcard->id, $contractor->id]) }}">
                                                                    {{ method_field('PUT') }}
                                                                    @csrf

                                                                    @if(!empty($jobcard->select_contractor_id) && $jobcard->select_contractor_id == $contractor->id)
                                                                        <input class="icheck" type="checkbox" name="selected_contractor" checked>
                                                                    @else
                                                                        <input class="icheck" type="checkbox" name="selected_contractor">
                                                                    @endif

                                                                </form>
                                                            </td>
                                                            <td>
                                                                @if($contractor->logo_url)
                                                                    <img style="max-width:50px;max-height:50px;"
                                                                            class="company-logo img-thumbnail p-0 rounded rounded-circle w-100" src="{{ $contractor->logo_url }}" />
                                                                @endif
                                                            </td>
                                                            <td class="company-name">{{ $contractor->name ? $contractor->name:'___' }}</td>
                                                            <td class="company-phone">
                                                                {{ $contractor->phone_ext ? '+'.$contractor->phone_ext.'-':'___-' }}
                                                                {{ $contractor->phone_num ? $contractor->phone_num:'____' }}
                                                            </td>
                                                            <td class="company-email">{{ $contractor->email ? $contractor->email:'____' }}</td>
                                                            <td class="company-created_at">{{ $contractor->pivot->created_at ? Carbon\Carbon::parse($contractor->pivot->created_at)->format('d M Y'):'____' }}</td>
                                                            <td class="company-price">{{ $contractor->pivot->amount ? $contractor->pivot->amount:'____' }}</td>
                                                            <td>
                                                                <div>
                                                                    <form method="POST" action="{{ route('jobcard-remove-contractor', [$jobcard->id, $contractor->id, $contractor->pivot->id]) }}" class="d-inline">
                                                                        {{ method_field('DELETE') }}
                                                                        @csrf
                                                                        <button type="submit" style="font-size:  12px;" class="btn-link float-right mr-1">
                                                                            <i class="icon-trash"></i> 
                                                                            Remove
                                                                        </button>
                                                                    </form>
                                                                    <a href="#" style="font-size:  12px;" class="float-right mr-1">
                                                                        <i class="icon-pencil"></i> Edit
                                                                    </a>
                                                                </div>
                                                            </td>
                                                            <input type="hidden" class="company-id" value="{{ $contractor->id ? $contractor->id:'' }}">
                                                            <input type="hidden" class="company-quote-url" value="{{ $contractor->pivot->quotation_doc_url ? $contractor->pivot->quotation_doc_url:'' }}">
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 mt-2">
                                                <nav class="float-right">
                                                    {{ $contractors->links() }}
                                                </nav>
                                            </div>
                                            <div class="col-12 mt-2">
                                                <div class="float-right" data-toggle="tooltip" data-placement="top" title="Add a contractor aligned with this job. You can add more than one">
                                                    <button type="button" class="animated-strips btn btn-success float-right pt-3 pb-3 pl-4 pr-4" data-toggle="modal" data-target="#add-contractor-modal">                                         
                                                        <i class="icon-briefcase icon-sm icons"></i>
                                                        <span class="mt-4">Add Contractor</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-4 offset-4">
                                            <div data-toggle="tooltip" data-placement="top" title="Add a contractor aligned with this job. You can add more than one">
                                                <button type="button" class="btn btn-success p-5 w-100 animated-strips" data-toggle="modal" data-target="#add-contractor-modal">                                         
                                                    <i class="d-block icon-briefcase icon-md icons" style="font-size: 25px;"></i>
                                                    <span class="d-block mt-4">Add Contractor</span>
                                                </button>
                                            </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-12 col-lg-12 grid-margin stretch-card">
                    <div class="card card-hoverable">
                        <div class="card-body">
                            @if( COUNT($jobcard->recentActivities) )
                                <h4 class="card-title text-center">Jobcard Timeline</h4>
                                <div>
                                    <p class="mt-3 p-2 text-center">Today</p>
                                    <div class="timeline">
                                        @foreach($jobcard->recentActivities as $position => $recentActivity)
                                            
                                            @include('layouts.recentActivity.default-activity-layout')

                                        @endforeach
                                    </div>
                                    <p class="mt-3 p-2 text-center">Start</p>
                                </div>
                            @else
                                <div class="col-4 offset-4">
                                    <div class="mt-3 text-center">
                                        <i class="icon-ghost icon-sm icons ml-3"></i>
                                        <h6 class="card-title mt-2 mb-3 ml-2">Sorry, no recent activity found</h6>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection @section('js') 

    <script src="{{ asset('js/plugins/jquery-sortable/jquery-sortable.js') }}"></script>
    <script src="{{ asset('js/plugins/dropify/dist/js/dropify.min.js') }}"></script>
    <script src="{{ asset('js/custom/dropify.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-asColor/dist/jquery-asColor.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-asColorPicker/dist/jquery-asColorPicker.min.js') }}"></script>
    <script src="{{ asset('js/plugins/lightgallery/dist/js/lightgallery-all.min.js') }}"></script>
    <script src="{{ asset('/js/custom/oq-process-forms/jquery.oq-process-forms.js') }}"></script>

    <script type="text/javascript">

        $(document).ready(function(){
            $('#jobcard_lifecycle').processForm();
        });

        $(document).ready(function() {



            $('input.icheck').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square',
                increaseArea: '20%' // optional
              });

              $('input.icheck').on('ifToggled', function(event){
                $(this).closest('form').submit();
              });

            $(".lightgallery").lightGallery({
                'share':false,
                'download':false,
                'actualSize':false
            }); 

            $("body").on("click",".breadcrumb-item", buildProgressModal);
            $("body").on("click","#progress-status-btn", submitProgressModal); 

            // For building progress status modals
            function buildProgressModal(){
                //Get all the progress tabs
                var progress_tabs = $('.progress-status-tabs');
                //Remember the current tab
                var curr = $(this);
                
                //Foreach of the progress status tabs
                $(progress_tabs).each(function( index, tab ) {
                    //Get the current position of the clicked tab
                    var current_index = $(curr).parent().children().index(curr);
                    //If we have reached the position of the active class
                    if( $(tab).hasClass('active') ){
                        //Check if we are moving forward or backward of the active tab
                        if( current_index - index > 0 ){
                            //Get the plugin data
                            var process_step_id = $(curr).find('.process_step_id').val();
                            var plugin = $(curr).find('.plugin');
                            //If it exists lets build it
                            if( $(plugin).length ){
                                //Get the build content
                                console.log('building...'); 
                                var pluginData = $(plugin).val();
                                var build = JSON.parse( pluginData ); 
                                
                                
                                //If the build contents are available
                                if(build != '' && build != undefined){
                                    //Prepare data fields to store
                                    var data = '';
                                    
                                    $.each(build, function(index, tab) { 
                                        //  Build for alerts
                                        if(tab.tag == "alert"){
                                            data +=  '<div class="'+(tab.full_width ? 'col-12': 'col-6' )+'">'+
                                                        '<div id="'+tab.id+'" class="alert alert-'+tab.type+'" role="alert">'+
                                                            '<i class="'+tab.icon+' icons mr-1" style="font-size: 12px;"></i>'+
                                                            '<span style="font-size: 12px;">'+tab.message+'</span>'+
                                                        '</div>'+
                                                    '</div>';
                                        
                                        //  Build for text inputs                                                    
                                        }else if(tab.tag == "input"){
                                            data +=  '<div class="'+(tab.full_width ? 'col-12': 'col-6' )+'">'+
                                                        '<div class="form-group">'+
                                                            '<label for="'+tab.id+'">'+tab.label+'</label>'+
                                                            '<input id="'+tab.id+'" type="text"'+
                                                                'placeholder="'+tab.placeholder+'"'+
                                                                'value="'+tab.update.response+'" class="form-control fillable'+(tab.optional ? ' optional-field': '' )+'">'+
                                                        '</div>'+
                                                    '</div>';

                                        //  Build for select inputs                                        
                                        }else if(tab.tag == "select"){
                                            var options = '';

                                            $.each(tab.options, function(index, option) {
                                                options += '<option value="'+option.id+'" '+( option.id == tab.update.response ? ' selected':'' )+'>'+option.label+'</option>';
                                            });

                                            data +=  '<div class="'+(tab.full_width ? 'col-12': 'col-6' )+'">'+
                                                        '<div class="form-group">'+
                                                            '<label for="'+tab.id+'">'+tab.label+'</label>'+
                                                            '<select id="'+tab.id+'" class="form-control fillable'+(tab.optional ? ' optional-field': '' )+'">'+    
                                                                options+
                                                            '</select>'+
                                                        '</div>'+
                                                    '</div>';

                                        //  Build for textareas
                                        }else if(tab.tag == "textarea"){    
                                            data +=  '<div class="'+(tab.full_width ? 'col-12': 'col-6' )+'">'+
                                                        '<div class="form-group">'+
                                                            '<label for="'+tab.id+'">'+tab.label+'</label>'+
                                                            '<textarea id="'+tab.id+'" placeholder="'+tab.placeholder+'"'+
                                                                       'class="form-control fillable'+(tab.optional ? ' optional-field': '' )+'">'+
                                                                        tab.update.response+
                                                            '</textarea>'+
                                                        '</div>'+
                                                    '</div>';

                                        //  Build for date pickers                                        
                                        }else if(tab.tag == "date"){
                                            data +=  '<div class="'+(tab.full_width ? 'col-12': 'col-6' )+'">'+
                                                        '<p class="m-0 mb-0">'+tab.label+'</p>'+    
                                                        '<div class="input-group date datepicker p-0 mb-3">'+
                                                            '<input id="'+tab.id+'" type="text"'+
                                                                'placeholder="'+tab.placeholder+'"'+
                                                                'value="'+tab.update.response+'"'+
                                                                'class="date-picker form-control form-control-sm fillable'+(tab.optional ? ' optional-field': '' )+'"'+
                                                                'autocomplete="off" />'+
                                                            '<div class="input-group-addon">'+
                                                                '<span class="mdi mdi-calendar"></span>'+
                                                            '</div>'+
                                                        '</div>'+
                                                    '</div>';

                                        //  Build for file attachments
                                        }else if(tab.tag == "attach"){
                                            data +=  '<div class="'+(tab.full_width ? 'col-12': 'col-6' )+'">'+
                                                        '<div class="form-group">'+
                                                            '<label for="'+tab.id+'">'+tab.label+'</label>'+
                                                            '<input id="'+tab.id+'" type="file" data-height="75"'+
                                                                'data-default-file="'+tab.update.response+'"'+
                                                                'class="dropify form-control'+(tab.optional ? 'optional-field': '' )+'"'+
                                                                'name="upload_'+tab.id+'">'+
                                                        '</div>'+
                                                    '</div>';
                                        }

                                    });

                                    //  Attach data fields to modal
                                    //  Also attach the position of the currently clicked step
                                    $('#progress-status-modal .modal-body')
                                        .html( data )
                                        .append('<input type="hidden" id="plugin_step" name="plugin_step" value="'+current_index+'">');

                                    //  Record the instruction step id [This is the unique ID for each plugin build]
                                    $('#progress-status-modal .modal-body')
                                        .append('<input type="hidden" id="process_step_id" name="process_step_id" value="'+process_step_id+'">');
                                        
                                    //Show modal with data fields
                                    $('#progress-status-modal').modal('show');
                                    
                                    //Initialize data field file dropbox
                                    $('.dropify').dropify();
                                    
                                    //Initialize data field date picker
                                    $('.date-picker').datepicker({
                                        format: "yyyy-mm-dd",
                                        enableOnReadonly: true,
                                        todayHighlight: true
                                    });

                                }
                                
                            }

                            console.log('Moving forward');

                            

                        }else if( current_index - index < 0 ){

                            console.log('Are you sure you want to rollback? State your reason');
                        }

                    }
                    
                });

            }

            //  For validating and submitting process status modals
            function submitProgressModal(){
                //Get the instructions of the whole complete process
                var processInstructions = $('#processInstructions').val();
                var pluginPosition = $('#plugin_step').val();

                //Get the current build
                var selectedTab = $('.progress-status-tabs')[pluginPosition];
                var build = JSON.parse( $(selectedTab).find('.plugin').val() );
                var upload, value = [], responses = [], errors = 0, errorHeading;

                //Get the tab names
                var selectedTabText = $(selectedTab).find('span').text().trim();
                var activeTabText = $('.progress-status-tabs.active:last').find('span').text().trim();

                //Get all the fillable data input fields
                var dataFields = $('#progress-status-modal').find('.fillable');

                $('#progress-status-modal').find('.invalid-feedback').remove();
                $('#progress-status-modal').find('.modal-form-error').remove();

                //  If we have fillables then lets extract data
                if( build.length ){
                    //Foreach component in the build
                    $(build).each(function( index, component ) {
                        //  Confirm if its a fillable component [Means the user can add data to it]
                        if( component.fillable ){
                            //  Find the related component using the unique id assigned
                            var fillable = $('#'+component.id);
                            
                            //  If the fillable component is not empty
                            //  Or is empty but optional
                            //  Or is empty but already has older data [in the case of dropify files]      
                            if(     $(fillable).val() != '' 
                                || ($(fillable).val() == '' && $(fillable).hasClass('optional-field') )
                                || ($(fillable).val() == '' && $(fillable).attr('data-default-file') ) != ''
                            ){
                                console.log('Stage 3');
                                //  If the component is an text input, textarea or select input
                                if( $(fillable).is( "input" ) || $(fillable).is( "textarea" ) || $(fillable).is( "select" ) ){
                                    //  Update done status to true [Means it has been updated]
                                    build[index].update.done = true;
                                    
                                    //  If the component is a dropify/attachment fillable, and is empty [No new file attached]
                                    if( $(fillable).hasClass('dropify') && $(fillable).val() == '' ){
                                        //  Then update dropify component with the current file
                                        build[index].update.response = $(fillable).attr('data-default-file');
                                    }else{
                                        //  Then update dropify component with the new uploaded file
                                        //  Also update input/textarea/select values aswell 
                                        build[index].update.response = $(fillable).val();
                                    }
                                }
                            }else{
                                console.log(fillable);
                                //  Increment the error count value
                                errors++;
                                
                                //  Update the modal header with the error count
                                modalHeading = $(fillable).closest('.modal-content').find('.modal-header');
                                modalErrorHeading = $(modalHeading).find('.modal-form-error')
                                $(modalErrorHeading).remove();

                                $('<span class="modal-form-error">'+
                                        '<span class="badge badge-danger text-white ml-2">'+(errors == 1 ? errors+' error found': errors+' errors found' )+'</span>'+
                                  '</span>').insertAfter( $(modalHeading).find('h5') );

                                //  Update the fillable field with the associated error
                                if( !$(fillable).parent().find('.invalid-feedback').length ){
                                    console.log('Stage 5');
                                    //  If this is a dropify attachment
                                    if( $(fillable).parent().hasClass('dropify-wrapper') ){
                                        console.log('Stage 6');
                                        //  Use this error for the dropify attachment field
                                        $('<span class="help-block invalid-feedback d-block">'+
                                                '<strong>Attach file</strong>'+
                                            '</span>').insertBefore( $(fillable).parent() );
                                                
                                    }else{
                                        console.log('Stage 7');
                                        //  Otherwise for normal input/textarea or select fields use this error
                                        $(fillable).parent().append(
                                            '<span class="help-block invalid-feedback d-block">'+
                                                '<strong>Enter field above</strong>'+
                                            '</span>'
                                        );
                                    }


                                }
                            }
                        }
                    });

                    //  If we do not have any errors then lets submit data
                    if(!errors){
                        console.log('submitting form!');
                        //var data = JSON.stringify( JSON.decycle( build, true) );

                        //  Update the new process instructions data using the build information
                        var updatedProcessInstructions = JSON.parse( processInstructions );
                        updatedProcessInstructions[0]['process_form'][pluginPosition]['plugin'] = build;
                        
                        $('#progress-status-modal .modal-body')
                            .append('<input type="hidden" name="updated_process_instructions" value="'+ encodeURIComponent(JSON.stringify( updatedProcessInstructions )) +'">')
                            .append('<input type="hidden" name="old_step_name" value="'+ activeTabText +'">')
                            .append('<input type="hidden" name="new_step_name" value="'+ selectedTabText +'">');
                        
                        //  Submit the form and make button show loading state
                        //  "makeSubmitableBtn()" is found in js/custom/misc.js
                        makeSubmitableBtn(this);  

                        //$( "#progress-status-form" ).submit();

                    }
                }

            }

            // For capturing modal errors
            // When the form is sumitted, this captures any issues and reopens the modal
            @if(COUNT($errors))
                $('#{{ old('modal_id') }}').modal('show');

                var errors = $('#{{ old('modal_id') }} .help-block.invalid-feedback');

                $.each(errors, function( index, value ) {
                    var tabId = $(value).closest('.tab-pane').attr('id');
                    var tabNavItem = $('#myTab .nav-item #'+tabId+'-tab');
                    var currCount = $(tabNavItem).parent('li').attr('data-error');
                    var count = (currCount == undefined) ? 1 : parseInt(currCount) + 1;
                    var tabNavText = $(tabNavItem).text();

                    console.log('count value: ' + count);
                    var msg = (count == 1) ? count+' error here': count+' errors here';
                    console.log('$(tabNavItem).text() value: ' + $(tabNavItem).text());
                    
                    //  If this is the last error in the loop, then print out the html message
                    if(errors.length == (index+1)){
                        $(tabNavItem).html(
                            tabNavText + 
                            '<div class="badge badge-danger rounded" style="position: absolute;top: -4px;transform: rotate;">'
                                +msg+
                            '</div>'
                        );
                    }

                    $(tabNavItem).parent('li').attr('data-error', count);

                    
                });

            @endif

            $('.show-contractor-modal-btn').click(function() {
                var btn = $(this);
                $('#show-contractor-modal').on('show.bs.modal', function (event) {
                    var company_id = $(btn).find('.company-id').val();
                    var company_logo = $(btn).find('.company-logo').attr('src');
                    var company_name = $(btn).find('.company-name').text();
                    var company_phone = $(btn).find('.company-phone').text();
                    var company_email = $(btn).find('.company-email').text();
                    var company_created_at = $(btn).find('.company-created_at').text();
                    var company_price = $(btn).find('.company-price').text();
                    var company_quote_url = $(btn).find('.company-quote-url').val();
                    var download_quote_btn = '';
                    
                    $('#show-contractor-modal').find('.modal-title').html(
                        '<span class="company-name lower-font mr-4">'+company_name+'</span><br/>'
                    );

                    $('#show-contractor-modal').find('.modal-body .col-3').html(
                        '<div class="lightgallery">'+
                            '<a href="'+company_logo+'">'+
                                '<img class="company-logo img-thumbnail mb-2 p-2 rounded rounded-circle w-50" src="'+company_logo+'" />'+
                            '</a>'+
                        '</div>'
                    );
                    
                    if(company_quote_url != '' && company_quote_url != undefined){
                        download_quote_btn = '<p class="mt-4">'+
                                                '<a href="'+company_quote_url+'" target="_blank" class="company-quote btn btn-primary">'+
                                                    '<i class="icon-cloud-download icons"></i>'+
                                                    'Quotation'+
                                                '</a> '+
                                              '</p>';
                    }

                    $('#show-contractor-modal').find('.modal-body .col-9').html(
                        '<span class="company-phone lower-font mr-4"><b>Tel: </b>'+company_phone+'</span><br/>'+
                        '<span class="company-email lower-font mr-4"><b>Email: </b>'+company_email+'</span><br/>'+
                        '<span class="company-phone lower-font mr-4"><b>Price: </b>'+company_price+'</span><br/>'+
                        download_quote_btn
                    );

                    $('#show-contractor-modal .modal-footer').find('a').attr('href', '/contractors/'+company_id)

                    $(".lightgallery").lightGallery({
                        'share':false,
                        'download':false,
                        'actualSize':false
                    }); 
                    
                });
            });
            
        });
    </script>

@endsection
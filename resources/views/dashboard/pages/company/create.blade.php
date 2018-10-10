@extends('layouts.app') 

@section('style')
    
    <link rel="stylesheet" href="{{ asset('css/plugins/jquery-asColorPicker/dist/css/asColorPicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/dropify/dist/css/dropify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/jquery-asColorPicker/dist/css/asColorPicker.min.css') }}">

@endsection 

@section('content')

    <div class="row">
        <div class="col-lg-12 d-flex flex-column">
            <div class="row flex-grow">
                <div class="col-12 col-lg-8 col-md-8 grid-margin offset-1 stretch-card mb-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group mt-0">
                                <h3 class="float-left">
                                    {{ app('request')->input('type') == "contractor" ? 'Create Contractor': 'Create Client' }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-8 col-md-8 grid-margin offset-1 stretch-card">
                    <div class="card card-hoverable">
                        <div class="card-body p-3 pt-4">
                            <form method="POST" action="{{ route('company-store') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" value="{{ app('request')->input('type') == "contractor" ? 'contractor': 'client' }}" name="new_company_type">
                                <div class="row">

                                    <div class="col-12">
                                        <div class="wrapper mt-0 mb-3">
                                            <span class="badge badge-warning text-white">Attach Company Logo : </span><br/>
                                            <p class="d-inline text-muted">Logo size is limited to not greater than <b>2MB</b>. Only <b>jpeg, jpg, png and gif</b> are accepted.</p>
                                        </div>
                                        <div class="form-group{{ $errors->has('new_company_logo') ? ' has-error' : '' }}">
                                                @if ($errors->has('new_company_logo'))
                                                    <span class="help-block invalid-feedback d-block">
                                                        <span class="badge badge-danger text-white mr-2">Error : </span>
                                                        <strong>{{ $errors->first('new_company_logo') }}</strong>
                                                    </span>
                                                @endif  
                                                <input type="file" data-max-file-size="2mb" data-default-file="{{ old('new_company_logo') }}" data-height="100"
                                                class="dropify form-control {{ $errors->has('new_company_logo') ? ' is-invalid' : '' }}" name="new_company_logo">                                      
                                        </div>
                                    </div>
    
                                    <div class="col-12">
                                        <h4>Company Details*</h4>
                                        <div class="form-group">
                                            <input type="text" name="new_company_name" placeholder="Enter company name..." value="{{ old('new_company_name') }}" class="form-control{{ $errors->has('new_company_name') ? '  is-invalid' : '' }}" autocomplete="off">
                                            @if ($errors->has('new_company_name'))
                                                <span class="help-block invalid-feedback d-block">
                                                    <strong>{{ $errors->first('new_company_name') }}</strong>
                                                </span>
                                            @endif        
                                        </div>
                                    </div>
        
                                    <div class="col-12">
                                        <div class="form-group">
                                            <input type="text" name="new_company_city" placeholder="Enter company city..." value="{{ old('new_company_city') }}" class="form-control{{ $errors->has('new_company_city') ? '  is-invalid' : '' }}" autocomplete="off">
                                            @if ($errors->has('new_company_city'))
                                                <span class="help-block invalid-feedback d-block">
                                                    <strong>{{ $errors->first('new_company_city') }}</strong>
                                                </span>
                                            @endif        
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="form-group">
                                            <input type="text" name="new_company_state_or_region" placeholder="Enter company state/region..." value="{{ old('new_company_state_or_region') }}" class="form-control{{ $errors->has('new_company_state_or_region') ? '  is-invalid' : '' }}" autocomplete="off">
                                            @if ($errors->has('new_company_state_or_region'))
                                                <span class="help-block invalid-feedback d-block">
                                                    <strong>{{ $errors->first('new_company_state_or_region') }}</strong>
                                                </span>
                                            @endif        
                                        </div>
                                    </div>
        
                                    <div class="col-12">
                                        <div class="form-group">
                                            <input type="text" name="new_company_address" placeholder="Enter company address..." value="{{ old('new_company_address') }}" class="form-control{{ $errors->has('new_company_address') ? '  is-invalid' : '' }}" autocomplete="off">
                                            @if ($errors->has('new_company_address'))
                                                <span class="help-block invalid-feedback d-block">
                                                    <strong>{{ $errors->first('new_company_address') }}</strong>
                                                </span>
                                            @endif        
                                        </div>
                                    </div>
        
                                    <div class="col-12">
                                        <h4>Contact Details*</h4>
                                        <div class="form-group">
                                            <input type="email" name="new_company_email" placeholder="Enter company email address"
                                                value="{{ old('new_company_email') }}"  class="form-control{{ $errors->has('new_company_email') ? '  is-invalid' : '' }}" autocomplete="off">
                                                @if ($errors->has('new_company_email'))
                                                    <span class="help-block invalid-feedback d-block">
                                                        <strong>{{ $errors->first('new_company_email') }}</strong>
                                                    </span>
                                                @endif                                                
                                        </div>
                                    </div>
        
                                    <div class="col-12">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">+</span>
                                            </div>
                                            <input type="text"  class="form-control{{ $errors->has('new_company_phone_ext') ? '  is-invalid' : '' }}" name="new_company_phone_ext" value="{{ old('new_company_phone_ext') }}" placeholder="Country code..." autocomplete="off">
                                            <input type="text"  class="form-control{{ $errors->has('new_company_phone_num') ? '  is-invalid' : '' }}" name="new_company_phone_num" value="{{ old('new_company_phone_num') }}" placeholder="Phone number..." autocomplete="off">
                                            @if ($errors->has('new_company_phone_ext'))
                                                <span class="help-block invalid-feedback d-block">
                                                    <strong>{{ $errors->first('new_company_phone_ext') }}</strong>
                                                </span>
                                                <br/>
                                            @endif
                                            @if ($errors->has('new_company_phone_num'))
                                                <span class="help-block invalid-feedback d-block">
                                                    <strong>{{ $errors->first('new_company_phone_num') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary mt-3 mr-2 float-right">
                                            Create Client
                                            <i class="icon-paper-plane icons"></i>
                                        </button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal starts -->
    <div class="modal fade" id="select-option-creation-box" tabindex="-1" role="dialog" aria-labelledby="select-option-creation-box-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="select-option-creation-box-label">Update to "Open"</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-3">
                    <div class="row">
                        <div class="col-12" id="select-option-name-box">
                            <div class="form-group">
                                <label for="select-option-creation-name">Name</label>
                                <input id="select-option-creation-name" 
                                        data-toggle="tooltip" data-placement="top" title=""
                                        type="text" placeholder="" class="form-control"></input>
                                <span id = "select-option-creation-name-error" class="help-block invalid-feedback d-none">
                                    <span class="badge badge-danger text-white mr-2">Error : </span>
                                    <strong></strong>
                                </span>
                            </div>
                        </div>

                        <div class="col-12" id="select-option-desc-box">
                            <div class="form-group">
                                <label for="select-option-creation-desc">Description</label>
                                <textarea data-toggle="tooltip" data-placement="top" title=""
                                            id="select-option-creation-desc" placeholder="" class="form-control" ></textarea>
                                <span id = "select-option-creation-desc-error" class="help-block invalid-feedback d-none">
                                    <span class="badge badge-danger text-white mr-2">Error : </span>
                                    <strong></strong>
                                </span>            
                            </div>
                        </div>

                        <div class="col-12" id="select-option-color-box">
                            <div class="form-group">
                                <label for="select-option-creation-color">Pick Color</label>
                                <input id="select-option-creation-color" type="text" data-toggle="tooltip" 
                                        data-placement="top" title=""
                                       class="color-picker form-control" value="#00aeff" />
                                <span id = "select-option-creation-color-error" class="help-block invalid-feedback d-none">
                                    <span class="badge badge-danger text-white mr-2">Error : </span>
                                    <strong></strong>
                                </span>                             
                            </div>
                        </div>

                        <input type="hidden" value="">

                    </div>
                </div>
                <div class="modal-footer">
                    <button id="select-option-creation-add-btn" type="button" class="btn btn-primary">
                            <i class="icon-plus icons m-0 mr-1"></i>
                        Add
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Ends -->

@endsection 

@section('js') 

<script src="{{ asset('js/plugins/dropify/dist/js/dropify.min.js') }}"></script>
<script src="{{ asset('js/custom/dropify.js') }}"></script>
<script src="{{ asset('js/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('js/plugins/jquery-asColor/dist/jquery-asColor.min.js') }}"></script>
<script src="{{ asset('js/plugins/jquery-asColorPicker/dist/jquery-asColorPicker.min.js') }}"></script>

<script>

    $(document).ready(function(){

        (function($) {
            'use strict';

            if ($(".date-picker").length) {
              $('.date-picker').datepicker({
                format: "yyyy-mm-dd",
                enableOnReadonly: true,
                todayHighlight: true,
              });
            }

            if(('.color-picker').length){
                $('.color-picker').asColorPicker();
            }

          })(jQuery);
          
            

        $('.select-option-creation-btn').click(function(){
            var selectBox = $(this).closest('.form-group').find('select');
            //Grab the select box field name
            var name_field = $(selectBox).attr('name');
            var name_field_formatted = $(selectBox).attr('name').split('_').join(' ');
            //Capitalize first letter
            var name_field_uppercase = name_field_formatted.charAt(0).toUpperCase() + name_field_formatted.slice(1);
            var modalTitle = 'Create New ' + name_field_uppercase;
            var modalInputNameToolTip = 'Enter the ' + name_field_formatted + ' name';
            var modalInputDescToolTip = 'Describe this ' + name_field_formatted + ' for other users';

            $('#select-option-creation-box .modal-title').text(modalTitle);

            if(name_field == 'branch'){
                $('#select-option-desc-box').hide();
            }else{
                $('#select-option-desc-box').show();
            }

            if(name_field == 'priority'){
                $('#select-option-color-box').show();
            }else{
                $('#select-option-color-box').hide();
            }

            $('#select-option-creation-name').attr('data-original-title', modalInputNameToolTip).attr('placeholder', name_field_uppercase+' name...');
            $('#select-option-creation-desc').attr('data-original-title', modalInputDescToolTip).attr('placeholder', name_field_uppercase+' description...');

            $('#select-option-creation-box .modal-body input[type="hidden"]').val(name_field);
            
            $('#select-option-creation-box').modal('show');
            $('.tooltips').tooltip()

        });

        $('#select-option-creation-add-btn').click(function(){
            var selectBox = $(this).closest('.form-group').find('select');
            var title = $('#select-option-creation-name').val();
            var desc = $('#select-option-creation-desc').val();
            var color = $('#select-option-creation-color').val();
            var type = $('#select-option-creation-box .modal-body input[type="hidden"]').val();
            var new_option = '<option value = "'+title+'_&_'+desc+'_&_'+color+'" selected>'+title+'</option>';
            
            //Check if we have what we need
            if( (title != '' && desc != '' && type != 'branch') ||
                (title != '' && desc != '' && color != '' && type == 'priority') ||
                (title != '' && type == 'branch')
            ){
                //Append the new option
                $('select[name='+type+']').append(new_option);
                //Hide the modal
                $('#select-option-creation-box').modal('hide');
            }else{

                if( title == '' ){
                    $('#select-option-creation-name-error').removeClass('d-none').addClass('d-block').find('strong').text('Enter '+type+' name');
                }

                if( desc == '' ){
                    $('#select-option-creation-desc-error').removeClass('d-none').addClass('d-block').find('strong').text('Enter '+type+' description');
                }

                if( color == '' ){
                    $('#select-option-creation-color-error').removeClass('d-none').addClass('d-block').find('strong').text('Enter '+type+' color');
                }
            }
        });

        $('.modal').on('hidden.bs.modal', function (e) {
            //Empty the input fields
            $('#select-option-creation-name').val('');
            $('#select-option-creation-desc').val('');
            //Restore error modal errors
            $('#select-option-creation-name-error').removeClass('d-block').addClass('d-none').find('strong').text('');
            $('#select-option-creation-desc-error').removeClass('d-block').addClass('d-none').find('strong').text('');
        });
        
    });

</script>

@endsection
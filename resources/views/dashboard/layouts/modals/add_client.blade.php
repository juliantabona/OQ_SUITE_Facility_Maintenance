<!-- Add client modal starts here -->
<div class="modal fade" id="add-client-modal" tabindex="-1" role="dialog" aria-labelledby="add-client-modalLabel" aria-hidden="true">
    <div class="modal-dialog mt-5" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-client-modalLabel">Add Client</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('company-store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="{{ $jobcard->id }}" name="jobcard_id">
                <input type="hidden" value="add-client-modal" name="modal_id">
                <input type="hidden" value="client" name="company_type">
                <div class="modal-body pt-3">

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link{{ !$errors->has('company_name') ? ' active': '' }}" id="search-existing-client-tab" data-toggle="tab" href="#search-existing-client" role="tab" aria-controls="search-existing-client" aria-selected="true">Search Existing</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link{{ $errors->has('company_name') ? ' active': '' }}" id="create-new-client-tab" data-toggle="tab" href="#create-new-client" role="tab" aria-controls="create-new-client" aria-selected="false">Create New</a>
                        </li>
                    </ul>

                    <div class="tab-content p-0" id="myTabContent">
                        <div class="tab-pane modal-content-max-height pt-2 fade{{ !$errors->has('company_name') ? ' show active': '' }}" id="search-existing-client" role="tabpanel" aria-labelledby="search-existing-client-tab">
                            <div class="col-12">
                                    <div class="form-group">
                                        <h4>Search Client:</h4>
                                        <div class="input-group mb-2">
                                            <input data-toggle="tooltip" data-placement="top" title="Company name..."
                                                    type="text" name="existing_company_name" placeholder="Company name..." value="{{ old('existing_company_name') }}" class="form-control{{ $errors->has('existing_company_name') ? '  is-invalid' : '' }}"
                                                    data-toggle="tooltip" data-placement="top" title="Search company name..." autocomplete="off">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text p-0">
                                                    <button type="button" class="btn btn-success pl-3 pr-3"
                                                            data-toggle="tooltip" data-placement="top" title="search...">
                                                        <i class="icon-magnifier icons m-0"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @if ($errors->has('existing_company_name'))
                                                <span class="help-block invalid-feedback d-block">
                                                    <strong>{{ $errors->first('existing_company_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="tab-pane modal-content-max-height pt-0 fade{{ $errors->has('company_name') ? ' show active': '' }}" id="create-new-client" role="tabpanel" aria-labelledby="create-new-client-tab">
                            <div class="row">

                                <div class="col-12">
                                    <div class="wrapper mt-4 mb-3">
                                        <span class="badge badge-warning text-white">Attach Company Logo : </span><br/>
                                        <p class="d-inline text-muted">Logo size is limited to not greater than <b>2MB</b>. Only <b>jpeg, jpg, png and gif</b> are accepted.</p>
                                    </div>
                                    <div class="form-group{{ $errors->has('company_logo') ? ' has-error' : '' }}">
                                            @if ($errors->has('company_logo'))
                                                <span class="help-block invalid-feedback d-block">
                                                    <span class="badge badge-danger text-white mr-2">Error : </span>
                                                    <strong>{{ $errors->first('company_logo') }}</strong>
                                                </span>
                                            @endif  
                                            <input type="file" data-max-file-size="2mb" data-default-file="{{ old('company_logo') }}" data-height="100"
                                            class="dropify form-control {{ $errors->has('company_logo') ? ' is-invalid' : '' }}" name="company_logo">                                      
                                    </div>
                                </div>

                                <div class="col-12">
                                    <h4>Company Details*</h4>
                                    <div class="form-group">
                                        <input type="text" name="company_name" placeholder="Enter company name..." value="{{ old('company_name') }}" class="form-control{{ $errors->has('company_name') ? '  is-invalid' : '' }}" autocomplete="off">
                                        @if ($errors->has('company_name'))
                                            <span class="help-block invalid-feedback d-block">
                                                <strong>{{ $errors->first('company_name') }}</strong>
                                            </span>
                                        @endif        
                                    </div>
                                </div>
    
                                <div class="col-12">
                                    <div class="form-group">
                                        <input type="text" name="company_city" placeholder="Enter company city..." value="{{ old('company_city') }}" class="form-control{{ $errors->has('company_city') ? '  is-invalid' : '' }}" autocomplete="off">
                                        @if ($errors->has('company_city'))
                                            <span class="help-block invalid-feedback d-block">
                                                <strong>{{ $errors->first('company_city') }}</strong>
                                            </span>
                                        @endif        
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-group">
                                        <input type="text" name="company_state_or_region" placeholder="Enter company state/region..." value="{{ old('company_state_or_region') }}" class="form-control{{ $errors->has('company_state_or_region') ? '  is-invalid' : '' }}" autocomplete="off">
                                        @if ($errors->has('company_state_or_region'))
                                            <span class="help-block invalid-feedback d-block">
                                                <strong>{{ $errors->first('company_state_or_region') }}</strong>
                                            </span>
                                        @endif        
                                    </div>
                                </div>
    
                                <div class="col-12">
                                    <div class="form-group">
                                        <input type="text" name="company_address" placeholder="Enter company address..." value="{{ old('company_address') }}" class="form-control{{ $errors->has('company_address') ? '  is-invalid' : '' }}" autocomplete="off">
                                        @if ($errors->has('company_address'))
                                            <span class="help-block invalid-feedback d-block">
                                                <strong>{{ $errors->first('company_address') }}</strong>
                                            </span>
                                        @endif        
                                    </div>
                                </div>
    
                                <div class="col-12">
                                    <h4>Contact Details*</h4>
                                    <div class="form-group">
                                        <input type="email" name="company_email" placeholder="Enter company email address"
                                            value="{{ old('company_email') }}"  class="form-control{{ $errors->has('company_email') ? '  is-invalid' : '' }}" autocomplete="off">
                                            @if ($errors->has('company_email'))
                                                <span class="help-block invalid-feedback d-block">
                                                    <strong>{{ $errors->first('company_email') }}</strong>
                                                </span>
                                            @endif                                                
                                    </div>
                                </div>
    
                                <div class="col-12">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">+</span>
                                        </div>
                                        <input type="text"  class="form-control{{ $errors->has('company_phone_ext') ? '  is-invalid' : '' }}" name="company_phone_ext" value="{{ old('company_phone_ext') }}" placeholder="Country code..." autocomplete="off">
                                        <input type="text"  class="form-control{{ $errors->has('company_phone_num') ? '  is-invalid' : '' }}" name="company_phone_num" value="{{ old('company_phone_num') }}" placeholder="Phone number..." autocomplete="off">
                                        @if ($errors->has('company_phone_ext'))
                                            <span class="help-block invalid-feedback d-block">
                                                <strong>{{ $errors->first('company_phone_ext') }}</strong>
                                            </span>
                                            <br/>
                                        @endif
                                        @if ($errors->has('company_phone_num'))
                                            <span class="help-block invalid-feedback d-block">
                                                <strong>{{ $errors->first('company_phone_num') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="add-client-btn" type="button" class="btn btn-primary">
                            <i class="icon-plus icons m-0 mr-1"></i>
                        Add Client
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Add client modal ends here -->
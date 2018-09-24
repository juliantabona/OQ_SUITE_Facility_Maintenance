<!-- Add contact modal starts here -->
<div class="modal fade" id="add-reference-modal" tabindex="-1" role="dialog" aria-labelledby="add-reference-modalLabel" aria-hidden="true">
    <div class="modal-dialog mt-5" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-reference-modalLabel">Add Contact</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('profile-store') }}" enctype="multipart/form-data">
                @csrf
                @if( !empty($jobcard) ){
                    <input type="hidden" value="{{ $jobcard->id }}" name="jobcard_id">
                    <input type="hidden" value="{{ $jobcard->client_id }}" name="client_id">
                @endif
                <input type="hidden" value="add-reference-modal" name="modal_id">
                <div class="modal-body pt-3">

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link{{ !$errors->has('new_reference_name') ? ' active': '' }}" id="search-existing-reference-tab" data-toggle="tab" href="#search-existing-reference" role="tab" aria-controls="search-existing-reference" aria-selected="true">Search Existing</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link{{ $errors->has('new_reference_name') ? ' active': '' }}" id="create-new-reference-tab" data-toggle="tab" href="#create-new-reference" role="tab" aria-controls="create-new-reference" aria-selected="false">Create New</a>
                        </li>
                    </ul>

                    <div class="tab-content p-0" id="myTabContent">
                        <div class="tab-pane modal-content-max-height pt-2 fade{{ !$errors->has('new_reference_name') ? ' show active': '' }}" id="search-existing-reference" role="tabpanel" aria-labelledby="search-existing-reference-tab">
                            <div class="col-12">
                                    <div class="form-group">
                                        <h4>Search Contact:</h4>
                                        <div class="input-group mb-2">
                                            <input data-toggle="tooltip" data-placement="top" title="Contact name..."
                                                    type="text" id="existing_user_search_name" name="existing_user_search_name" placeholder="Contact name..." value="{{ old('existing_user_search_name') }}" class="form-control{{ $errors->has('existing_user_search_name') ? '  is-invalid' : '' }}"
                                                    data-toggle="tooltip" data-placement="top" title="Search contact name..." autocomplete="off">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text p-0">
                                                    <button type="button" class="btn btn-success pl-3 pr-3"
                                                            data-toggle="tooltip" data-placement="top" title="search...">
                                                        <i class="icon-magnifier icons m-0"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            @if ($errors->has('existing_user_search_name'))
                                                <span class="help-block invalid-feedback d-block">
                                                    <strong>{{ $errors->first('existing_user_search_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="tab-pane modal-content-max-height pt-0 fade{{ $errors->has('new_reference_name') ? ' show active': '' }}" id="create-new-reference" role="tabpanel" aria-labelledby="create-new-reference-tab">
                            <div class="row">

                                <div class="col-12">
                                    <h4>Contact Details*</h4>
                                    <div class="form-group">
                                        <input type="text" id="user_first_name" name="user_first_name" placeholder="Enter first name..." value="{{ old('user_first_name') }}" class="form-control{{ $errors->has('user_first_name') ? '  is-invalid' : '' }}" autocomplete="off">
                                        @if ($errors->has('user_first_name'))
                                            <span class="help-block invalid-feedback d-block">
                                                <strong>{{ $errors->first('user_first_name') }}</strong>
                                            </span>
                                        @endif        
                                    </div>
                                </div>
    
                                <div class="col-12">
                                    <div class="form-group">
                                        <input type="text" id="user_user_last_name" name="user_last_name" placeholder="Enter last name..." value="{{ old('user_last_name') }}" class="form-control{{ $errors->has('user_last_name') ? '  is-invalid' : '' }}" autocomplete="off">
                                        @if ($errors->has('user_last_name'))
                                            <span class="help-block invalid-feedback d-block">
                                                <strong>{{ $errors->first('user_last_name') }}</strong>
                                            </span>
                                        @endif        
                                    </div>
                                </div>

                                <div class="col-12">
                                        <div class="form-group">
                                            <input type="text" data-toggle="tooltip" data-placement="top" title="E.g) Director, Manager, Secretary, e.t.c"
                                                    id="user_position" name="user_position" placeholder="Enter company occupation/position..." value="{{ old('user_position') }}" class="form-control{{ $errors->has('user_position') ? '  is-invalid' : '' }}" autocomplete="off">
                                            @if ($errors->has('user_position'))
                                                <span class="help-block invalid-feedback d-block">
                                                    <strong>{{ $errors->first('user_position') }}</strong>
                                                </span>
                                            @endif        
                                        </div>
                                    </div>
    
                                <div class="col-12">
                                    <h4>Contact Details*</h4>
                                    <div class="form-group">
                                        <input type="email" id="user_email" name="user_email" placeholder="Enter contact email address"
                                            value="{{ old('user_email') }}"  class="form-control{{ $errors->has('user_email') ? '  is-invalid' : '' }}" autocomplete="off">
                                            @if ($errors->has('user_email'))
                                                <span class="help-block invalid-feedback d-block">
                                                    <strong>{{ $errors->first('user_email') }}</strong>
                                                </span>
                                            @endif                                                
                                    </div>
                                </div>
    
                                <div class="col-12">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">+</span>
                                        </div>
                                        <input id="new_reference_mobile_info" type="text"  class="form-control{{ $errors->has('user_phone_ext') ? '  is-invalid' : '' }}" name="user_phone_ext" value="{{ old('user_phone_ext') }}" placeholder="Country code..." autocomplete="off">
                                        <input type="text"  class="form-control{{ $errors->has('user_phone_num') ? '  is-invalid' : '' }}" name="user_phone_num" value="{{ old('user_phone_num') }}" placeholder="Phone number..." autocomplete="off">
                                        @if ($errors->has('user_phone_ext'))
                                            <span class="help-block invalid-feedback d-block">
                                                <strong>{{ $errors->first('user_phone_ext') }}</strong>
                                            </span>
                                            <br/>
                                        @endif
                                        @if ($errors->has('user_phone_num'))
                                            <span class="help-block invalid-feedback d-block">
                                                <strong>{{ $errors->first('user_phone_num') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="add-reference-btn" type="button" class="btn btn-primary">
                            <i class="icon-plus icons m-0 mr-1"></i>
                        Add Reference
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Add contact modal ends here -->
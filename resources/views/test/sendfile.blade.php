@extends('dashboard.layouts.app')

@section('style')
    
    <link rel="stylesheet" href="{{ asset('css/plugins/jquery-asColorPicker/dist/css/asColorPicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/plugins/dropify/dist/css/dropify.min.css') }}">

@endsection 

@section('content')

    <div class="row">
        <div class="col-lg-12 d-flex flex-column">
            <div class="row flex-grow">
                <div class="col-12 col-md-10 col-lg-9 grid-margin offset-md-1 stretch-card mb-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group mt-0">
                                <h3 class="float-left">Send File</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-10 col-lg-9 grid-margin offset-md-1 stretch-card">
                    <div class="card card-hoverable">
                        <div class="card-body p-3 pt-4">
                            <form id="uploadForm" action="" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="wrapper mb-3">
                                                    <span class="badge badge-warning text-white">Attach Image : </span><br/>
                                                    <p class="d-inline text-muted">Image size is limited to not greater than <b>2MB</b>. Only <b>jpeg, jpg, png and gif</b> are accepted.</p>
                                                </div>
                                                <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                                                        @if ($errors->has('image'))
                                                            <span class="help-block invalid-feedback d-block">
                                                                <span class="badge badge-danger text-white mr-2">Error : </span>
                                                                <strong>{{ $errors->first('image') }}</strong>
                                                            </span>
                                                        @endif  
                                                        <input type="file" data-max-file-size="2mb" data-default-file="{{ old('image') }}"
                                                        class="dropify form-control {{ $errors->has('image') ? ' is-invalid' : '' }}" name="image[]" multiple>                                      
                                                </div>

                                                <button type="submit" class="btn btn-success float-right pb-3 pl-5 pr-5 pt-3 ml-2">
                                                    Send
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection 

@section('js') 

<script src="{{ asset('js/plugins/dropify/dist/js/dropify.min.js') }}"></script>
<script src="{{ asset('js/custom/dropify.js') }}"></script>
<script src="{{ asset('js/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('js/plugins/jquery-asColor/dist/jquery-asColor.min.js') }}"></script>
<script src="{{ asset('js/plugins/jquery-asColorPicker/dist/jquery-asColorPicker.min.js') }}"></script>

<script>

    $(document).ready(function(){

        var form = $('#uploadForm');
        var request = new XMLHttpRequest();

        $(form).submit(function(e){
            e.preventDefault();

            var formData = new FormData(form);

            request.open('post', '{{ route('sendfile-store') }}');
            request.send(formData);
        });
        
        request.addEventListener('load', function(e){
            console.log( JSON.parse(e.target.responseText) );

        }, false);

        request.upload.addEventListener('progress', function(e){
            console.log(e.loaded/e.total*100 +'%');
        }, false);

    });

</script>

@endsection
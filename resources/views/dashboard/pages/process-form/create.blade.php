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
                <div class="col-12 col-md-12 col-lg-12 grid-margin stretch-card">
                    <div class="card card-hoverable">
                        <div class="card-body">
                            <div id="processor">
                                <input type="hidden" value="{{ $processForm->form_structure }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')

    <script src="{{ asset('js/plugins/jquery-sortable/jquery-sortable.js') }}"></script>
    <script src="{{ asset('js/plugins/dropify/dist/js/dropify.min.js') }}"></script>
    <script src="{{ asset('js/custom/dropify.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-asColor/dist/jquery-asColor.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-asColorPicker/dist/jquery-asColorPicker.min.js') }}"></script>

    <script src="{{ asset('/js/custom/oq-process-forms/jquery.oq-process-forms.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('#processor').processForm();
        });
    </script>

@endsection
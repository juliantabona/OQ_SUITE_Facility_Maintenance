(function ($) {
    $.fn.processForm = function(options){
        
        //  Currently selected object
        var currSelection = this;

        //  Plugin Settings & Configurations

        var config = $.extend({
                        formSettings:{
                            type: 'pop_process',   //  basic, pop_process
                            heading: '',
                            description: '',
                            showProgess: true
                        }, 
                        sectionSettings:{
                            draggable: false,
                            editable: false,
                            showHeading: true,
                            showHeadingHighlight: false,
                            customHeadingText: '',
                            showBody: true,
                            customBodyText: '',
                        }, 
                        fieldSettings:{
                            draggable: false,
                            editable: false
                        }, 
                        optionSettings:{
                            draggable: true
                        }
                    }, options);


        //  Build the form structure
        startBuild();

        /*  Functionality to start building the the actual form to display along with the corresponding 
            sections, fields, options and responses. If the function locates an existing build structure, 
            it will use the build to make the form, otherwise it will display nothing. If the "formSettings" 
            allow for sections and fields to be created then buttons will be created to allow such actions. 
            The most important purpose of this function is that we determine if we have a build and then 
            actually make the form, or otherwise do nothing unless we have the configurations to allow 
            sections, fields and options to be crafted.
        */
        function startBuild(){
            
            $(currSelection).addClass('process-form-container');

            /*  First thing is to get the form build if it exists.
                This build will allow us to actually know what we are building, it is the blueprints
                to actually know the type of form, sections, fields and options we are building
            */
            var form_build = JSON.parse(  $(currSelection).find('input[type="hidden"]').val() );
       
            //  If the build contents are available, then we can continue to build
            if(form_build != '' && form_build != undefined){
                
                /*  Using the sections html, fields html, options html and responses values compiled so far, we can now contruct
                    the complete form structure to display out to the user. We must now determine the type of form to display 
                    using the content we have. The "sections" [in 2nd parameter] is the object of the form sections. It will
                    be used to calculate the progress percentage.
                */
               var build = buildForm(form_build);

                //  Attach form
                $(currSelection).append( build );

                //  Refresh all section drag&drop, fields drag&drop, options drag&drop, dropify fields, date-picker fields, e.t.c
                refresh_plugins();

            }
        }

        /*  Functionality to build the type of form to display. In this case we already have all the sections,
            fields, options and responses put together as HTML concatenations but we can want to display the 
            content in many different ways, e.g) as one long form, or a form divided in many stages and so on.
            This is more a design and user experience consideration. 
        */
        function buildForm(form_build, custom_type){

            //  Lets start with an empty form
            var form_html = '';

            //  Lets get the collection of sections in the current build structure
            
            var sections = form_build.structure;
            
            sections_html = sectionBuild(form_build, custom_type);

            /*  lets calculate the percentage completion of the entire form process. 
                This indicates how much of the form has already been completed by the user
            */
            var progress_percentage = calculateProgress(sections);

            //   If the progress exists and the user has not restricted to display it,
            //  then build the progress bar structure
            var progress = (progress_percentage != '' && config.formSettings.showProgess != false) 
                            ? '<div class="progress" data-toggle="tooltip" data-placement="top" title="'+progress_percentage+'% completed">'+
                                '<div class="progress-bar" role="progressbar" style="width: '+progress_percentage+'%" aria-valuemin="0" aria-valuemax="100"></div>'+
                                '</div>'
                            : '' ;

            /*  Lets determine the type of form we want to build, then display the build
                out to the user. 
            */
            if(config.formSettings.type == 'basic' ||
               custom_type == 'basic'
            ){
                /*  "basic" represents a simple and straight forward form showcasing all sections, fields and options available.
                    The user can interact with the sections, fields and options as well as edit the entire form at once with no
                    hidden inputs and values.
                */
               
                form_html = progress + '<ul class="pr-form-container p-0">' + sections_html + '</ul>';

            }else if(config.formSettings.type == 'pop_process'){
                /*  "pop_process" represents a breadcrumb process where relevant section 
                    content is only displayed when the user clicks the breadcrumb navigation 
                    to launch a modal with the supporting fields and options. The user will
                    be able to edit each section at a time.
                */

                form_html = '<div class="col-12">'+
                                '<nav id = "prf_nav" aria-label="breadcrumb" role="navigation">'+
                                    '<ul class="breadcrumb breadcrumb-custom mb-2 pt-2">'+
                                    sections_html+
                                    '</ul>'+
                                    progress +
                                '</nav>'+
                            '</div>';
                
            }

            return form_html;

        }

        function sectionBuild(form_build, custom_type){
            //  Lets get the collection of sections in the current build structure
            
            var sections = form_build.structure;
            
            //  Prepare sections to store
            var sections_html = '';
            
            /*  Foreach section of our build, lets put together the html that represents the section,
                fields and option details available and their corresponding responses from the last user 
                updating the form. In this case a section is an instance of a complete form with fields, 
                options and responses
            */
            $.each(sections, function( index, section ) {

                /*  Lets put together the html that represents the section. We do this by passing the section details,
                    as well as the section fields if available. The fields in this case are already concatenated HTML fields.
                */
                sections_html += section_mockup(section, custom_type);

            });

            return sections_html;
        }

        /*  Functionality to build the section HTML along with the corresponding fields, options and responses.
            This function also controls the availability of the section editing tools such as for deleting, editing,
            moving the sections around. It also defines the availability of the "add field" button at the bottom
            of the section depending if the user does not restrict
        */
        function section_mockup(section, custom_type){

            //  Declare the section build components
            var section_heading = '', section_body = '', tools = '', add_field_btn = '', dragTool = '', draggableClass = '', 
                active_state = '', tick = '', fields_html = '', build = '';

            //  Determine whether or not to show the section heading               
            config.sectionSettings.showHeading ? (
                //  Determine whether or not to show the original section heading or custom heading
                config.sectionSettings.customHeadingText != '' ? section_heading = config.sectionSettings.customHeadingText
                                                               : section_heading = section.name
                                               )
                                               : section_heading = '';

            //  Determine whether or not to show the heading highlight on hover
            config.sectionSettings.showHeadingHighlight ? heading_highlight = 'heading-highlight '
                                                        : heading_highlight = '';

            //  Determine whether or not to show the section body    
            config.sectionSettings.showBody ? (
                //  Determine whether or not to show the original section body or custom body
                config.sectionSettings.customBodyText != '' ? section_body = config.sectionSettings.customBodyText
                                                            : section_body = section.description
                                            )
                                            : section_body = '';

            /*  Determine whether or not the section is editable. If it is then display the editing
                tools to edit and delete
            */
            config.sectionSettings.editable ? tools =  '<i class="delete-section-btn icon-trash icons"></i>'+
                                                        '<i class="edit-section-btn icon-pencil" aria-hidden="true"></i>'
                                            : tools =  '';

            //  If this section is editable then display the button to add additional fields  
            config.sectionSettings.editable ? add_field_btn =  '<div class="col-12">'+
                                                                    '<button type="button" id="'+section.id+'_add_field_btn" class="field-creation-box-btn btn btn-rounded btn-success d-block mb-3 ml-auto mr-auto mt-2 p-1">'+
                                                                        '<i class="d-block icon-md icon-plus icons m-0"></i>'+
                                                                    '</button>'+
                                                                '</div>'
                                            : add_field_btn =  '';

            /*  Determine whether or not the section is draggable. If it is then display the drag
                tool to move the section. Also add the class "draggable-form-container" so that
                the section can be initialized for drag&drop by the sortable plugin
            */ 
            config.sectionSettings.draggable ? dragTool =  '<i class="icon-cursor-move dragger-btn section-handle" aria-hidden="true"></i>'
                                             : dragTool =  '';
                
            config.sectionSettings.draggable ? draggableClass =  ' draggable-form-container '
                                             : draggableClass =  '';

            /*  Determine whether or not to make the tabs active 
                This setting is desired primary for the breadcrumb navigation forms
            */
            section.last_update != '' ? active_state =  ' active'
                                      : active_state =  '';

            /*  Determine whether or not to show the tick icon if the section has been updated before
                This setting is desired primary for the breadcrumb navigation forms
            */
            section.last_update != '' ? tick =  '<i class="icon-check icons"></i>'
                                      : tick =  '';
            

            /*  Lets put together the html that represents the fields and option details available and their corresponding responses from the last user 
                updating the form. In this case a field is an instance of a single form input e.g) Text, Select, Radio, Checkbox, e.t.c with
                corresponding options [mainly for select, radio and checkbox fields] and responses [meaning the values set by the user for that input].
                The "section.form" returns the object array of many fields which can be manupilated using the "buildFields()" function to return a list 
                of concatenated HTML fields.
            */

           fields_html = buildFields(section.form);

            //  Build the section according to the form type    
            //  The build will be determined by the "config.formSettings.type" unless
            //  the user provides a custom build type using the "custom_type"
            if( config.formSettings.type == 'basic' ||
                custom_type == 'basic'
            ){
                
                build = '<li id = "'+section.id+'" class="section-container'+draggableClass+'">'+
                            '<div class="col-12">'+
                                tools+
                                dragTool+
                            '</div>'+
                            '<h4 class="'+ heading_highlight +'pl-2 pb-2 section-name">'+section_heading+'</h4>'+
                            '<p class="ml-2 section-desc">'+section_body+'</p>'+
                            '<div class="form-box mb-2">'+
                                '<ol class="row field-container vertical">'+
                                fields_html+
                                '</ol>'+
                                add_field_btn+
                            '</div>'+
                        '</li>';
                        
            }else if(config.formSettings.type == 'pop_process' ||
                     custom_type == 'pop_process'){
                
                build = '<li id="'+section.id+'" data-toggle="tooltip" data-placement="top" title=""'+
                            'class="breadcrumb-item progress-status-tabs section-container'+draggableClass+active_state+'"'+
                            'data-original-title="'+section_body+'">'+
                            '<h4 class="section-name d-none">'+section_heading+'</h4>'+
                            '<p class="section-desc d-none">'+section_body+'</p>'+
                            '<span>'+
                                section_heading+
                                tick+
                            '</span>'+
                        '</li>';
                
            }

            return build;

        }

        /*  Functionality to begins the build process for the fields and options contained in them.
            It calls the field_mockup to construct the field HTML structure as while also calling
            the option_mockup t build the option HTML for fields such as select, radio and checkboxes
            which have numerous options in them. The option HTML's are put inside their respective 
            input fields and the fields are concatenated together and returned.
        */
        function buildFields(fields){

            //  Prepare fields to store
            var build = '';
            
            $.each(fields, function( index, field ) {
                
                /*  return the HTML build structure of the field. The options 
                    in this case are already concatenated HTML options.
                */
                build += field_mockup(field);

            });

            return build;
        }

        function field_mockup(field){
            
            var build = '', tools, dragTool, options_html, CompleteBuild = null;

            /*  Determine whether or not the field is editable. If it is then display the editing
                tools to resize, edit and delete
            */
           config.fieldSettings.editable ? tools = '<i class="delete-column-btn icon-trash icons"></i>'+
                                                   '<i class="edit-column-btn icon-pencil" aria-hidden="true"></i>'+
                                                   '<i class="decrease-column-btn icon-arrow-left-circle icons"></i>'+
                                                   '<i class="increase-column-btn icon-arrow-right-circle icons"></i>'
                                         : tools =  '';

            /*  Determine whether or not the field is draggable. If it is then display the drag
                tool to move the section. Also add the class "draggable-form-container" so that
                the section can be initialized for drag&drop by the sortable plugin
            */ 
            config.fieldSettings.draggable ? dragTool =  '<i class="icon-cursor-move dragger-btn field-handle" aria-hidden="true"></i>'
                                           : dragTool =  '';
            
            options_html = buildOptions(field);

            //  If the input is a type of text
            if(field.type == 'text'){
                build = '<label for="'+field.id+'" class="field-label">'+field.label+'</label>'+
                        '<input data-toggle="tooltip" data-placement="top" title="'+field.description+'" '+
                            'type="text" id="'+field.id+'" name="'+field.name+'" placeholder="'+field.placeholder+'" class="form-control">';

            //  If the input is a type of dropdown
            }else if(field.type == 'dropdown'){
                build = '<label for="'+field.id+'" class="field-label">'+field.label+'</label>'+
                        '<select data-toggle="tooltip" data-placement="top" title="'+field.description+'" '+
                            'id="'+field.id+'" name="'+field.name+'" class="form-control">'+
                            options_html +
                        '</select>';

            }else if(field.type == 'checkbox'){
                build = '<label for="'+field.id+'" class="field-label">'+field.label+'</label>'+
                        '<input type="hidden" id="'+field.id+'" name="'+field.name+'" placeholder="'+field.placeholder+'" class="form-control">'+
                        '<br>'+
                        '<div class="field_choices_box">'+
                            options_html +
                        '</div>';
            }else if(field.type == 'radio'){
                build = '<label for="'+field.id+'" class="field-label">'+field.label+'</label>'+
                        '<input type="hidden" id="'+field.id+'" name="'+field.name+'" placeholder="'+field.placeholder+'" class="form-control">'+
                        '<br>'+
                        '<div class="field_choices_box">'+
                            options_html +
                        '</div>';
            }else if(field.type == 'date'){

                build = '<span id="'+field.id+'" class="field-label">'+field.label+'</span>'+
                        '<div class="input-group date datepicker p-0"'+
                                'data-toggle="tooltip" data-placement="top" title="'+field.description+'">'+
                            '<input id="'+field.id+'"'+
                                    'type="text" placeholder="'+field.placeholder+'"'+
                                    'name="'+field.name+'"'+
                                    'class="date-picker form-control form-control-sm"'+
                                    'autocomplete="off" />'+
                            '<div class="input-group-addon">'+
                                '<span class="mdi mdi-calendar"></span>'+
                            '</div>'+
                        '</div>';

            }else if(field.type == 'attach'){
                build = '<label for="'+field.id+'" class="badge badge-warning text-white field-label">'+field.label+'</label>'+
                        '<div class="wrapper mb-3">'+
                            '<p class="d-inline text-muted">File size is limited to not greater than <b>2MB</b>. Only <b>pdf/jpeg</b> are accepted.</p>'+
                        '</div>'+
                        '<input data-toggle="tooltip" data-max-file-size="2mb" data-placement="top" title="'+field.description+'"'+
                            'type="file" id="'+field.id+'" name="'+field.name+'" value="" class="dropify form-control" data-default-file="" data-height="70">';

            }

            return  CompleteBuild = '<li class="'+(field.width != '' ? field.width : 'col-12 col-sm-12')+'" inputType="'+field.type+'">'+
                                        tools+
                                        dragTool+
                                        '<div class="form-group">'+
                                            build +                                             
                                        '</div>'+
                                    '</li>';
        }

        function buildOptions(field){
            //  Prepare options to store
            var build = '';
            
            //  If we have any options
            if(field.options != ''){
                //  Foreach option
                $.each(field.options, function( index, option ) {
                    //  return the HTML build structure of the option
                    build += option_mockup(field, option);
                    
                });
            }
            
            return build;
        }

        function option_mockup(field, option){
            
            var option_html = '',    //  the option is empty string by default so that we never return "undefined" 
                checked_state = '';

            //  Determine if the option is checked or selected
            if(option.checked == true){
                field.type == 'dropdown' ? checked_state = ' selected' : checked_state = ' checked';
            }
            
            //  If this is a dropdown then the option is as follows
            if(field.type == 'dropdown'){
                option_html = '<option id="'+option.id+'" value="'+option.label+'"'+checked_state+'>'+option.label+'</option>';
            
            //  If this is a checkbox/radio then the option is as follows
            }else if(field.type == 'checkbox' || field.type == 'radio'){
                option_html =  '<div class="form-check-inline">'+
                                    '<label class="form-check-label p-0" for="'+option.id+'">'+
                                        '<input type="'+field.type+'" class="form-check-input" id="'+option.id+'" name="'+field.name+'" value="'+option.label+'"'+checked_state+'>'+
                                        option.label+
                                    '</label>'+
                                '</div>';
            }

            return option_html;
        }

        function calculateProgress(sections){
            //  Go through the sections and calculate the progress based on whether they were updated or not

            var total_fields = 0, total_fields_updated = 0, percentage = 0;

            $.each(sections, function( index, section ) {
                
                var structure_fields = section.form;

                $.each(structure_fields, function( index, field ) {
                    var field__update_done =  field.update.done;
                    var field__update_response =  field.update.response;

                    total_fields++;

                    if( field__update_done != false && field__update_response != '' ){
                        total_fields_updated++;
                    }

                });

            });

            percentage = (total_fields_updated/total_fields) * 100;

            return percentage;

        }

        //  Refresh all section drag&drop, fields drag&drop, dropify fields, date-picker fields, e.t.c
        function refresh_plugins(){

            //  Initialize file/dropbox field
            $('.dropify').dropify();

            //  Initialize date field
            $('.date-picker').datepicker({
                format: "yyyy-mm-dd",
                enableOnReadonly: true,
                todayHighlight: true,
            });

            //  Refresh the draggable to detect new draggable sections
            makeSortable('.pr-form-container', 'sections', '.section-handle');

            //  Refresh the draggable to detect new draggable input fields
            makeSortable('.field-container', 'fields', '.field-handle');
        }

        /*  Functionality to initiate drag & drop with animation effects
            This allows us to make the sections, fields, options, e.t.c
            draggable. 
        */
        var adjustment;

        function makeSortable(el, group, handle){
            $(el).sortable({
            group: group,
            handle: handle,
            pullPlaceholder: false,
            // animation on drop
            onDrop: function  ($item, container, _super) {
                var $clonedItem = $('<li/>').css({height: 0});
                $item.before($clonedItem);
                $clonedItem.animate({'height': $item.height()});
            
                $item.animate($clonedItem.position(), function  () {
                $clonedItem.detach();
                _super($item, container);
                });

                //  Get the form field structure
                updateFormBuild();

            },
            
            // set $item relative to cursor position
            onDragStart: function ($item, container, _super) {
                var offset = $item.offset(),
                    pointer = container.rootGroup.pointer;
            
                adjustment = {
                left: pointer.left - offset.left,
                top: pointer.top - offset.top
                };
            
                _super($item, container);
            },
            onDrag: function ($item, position) {
                $item.css({
                left: position.left - adjustment.left,
                top: position.top - adjustment.top
                });
            }
            });
        }

        /*  Functionality to actually build out the fields that the user created
            into one big collection that can be saved to the database
        */
        function updateFormBuild(){
                
            //  Initialize the storage array
            //  It will hold all the data compiled from the existing form fields
            var storage = [];
            
            //  For the 
            var structure = $(currSelection).find('.section-container').map(function(){

                var section_id = $(this).attr('id');
                var section_name = $(this).find('.section-name').text();
                var section_description = $(this).find('.section-desc').text();
                if( $(this).has('nav') ){
                    var section_fields = $(this).find('.field-container').find('li');
                }else{
                    var section_fields = $('#prf_modal').find('.field-container').find('li');
                }
                
                //  Get data from form fields and store inside the fields array
                var fields = $(section_fields).map(function(){

                        //  Get the current field in this element
                        //  input[type="hidden"] represents the "field id" and "field name" for form-groups with multiple inputs such as radios/checkboxes
                        var field = $(this).find('input[type="text"], input[type="file"], input[type="hidden"], select, textarea');

                        //  Get all the properties we can retrieve from this field
                        var width = $(this).attr('class');
                        var id = $(field).attr('id');
                        var label = $(field).closest('.form-group').find('.field-label').text();
                        var name = $(field).attr('name');
                        var type = $(field).closest('li').attr('inputType');
                        var classes = $(field).attr('class');
                        var placeholder = $(field).attr('placeholder');
                        var description = $(field).attr('title');
                        var required = $(field).attr('field-required');

                        //  Check for any other unique properties such as what kind of input type this field has
                        //  If we have any, chain them to the
                        
                        //  Remove common classes
                        var removeItem = 'form-control';

                        //  Identify unique classes
                        if(classes != '' && classes != undefined){
                            classes = jQuery.grep(classes.split(' '), function(value) {
                                return value != removeItem;
                            }).join(' ');
                        }else{
                            classes = '';
                        }

                        //  If the required field is not set to true or does not exist at all,
                        //  then say that the field is not really required (its optional) by setting it to false. 
                        if(required != true){
                            required = false;
                        }
                        
                        //  If this field is a select input then it will have options
                        //  lets capture those options
                        
                        //  First define the choices and options objects
                        //  Choices are the individual options we want to get and retreive data from
                        //  Options are the objects holding the information of both choices  
                        var choices, options;
                        
                        if( $(field).is('select') ){
                            //  Get the options from the field
                            choices = $(field).find('option');

                            //  Go through the options and return the id and label for each choice
                            //  Store the result in the options object
                            options = $(choices).map(function(){
                                return {'id':$(this).attr('id'), 'label':$(this).val(), 'checked':$(this).is(':checked')}
                            }).get();

                        }else if( $(field).has('input[type="radio"], input[type="checkbox"]') ){
                            //  Get the options from the field
                            choices = $(field).closest('.form-group').find('.field_choices_box input');
                            
                            //  Go through the options and return the id and label for each choice
                            //  Store the result in the options object
                            options = $(choices).map(function(){
                                
                                return {'id':$(this).attr('id'), 'label':$(this).val(), 'checked':$(this).is(':checked')}
                            }).get();                                
                        }
                        
                        //  Build up all the properties into an object called data
                        var data = {
                                    'width': width, 
                                    'id':id, 
                                    'type':type, 
                                    'label':label, 
                                    'name':name, 
                                    'placeholder':placeholder, 
                                    'description':description,
                                    'options': options, 
                                    'classes': classes,
                                    'fillable':true,
                                    'validation': {
                                        'required':required
                                    },
                                    'update': {
                                        'done':false,
                                        'response':''
                                    }
                                };

                        // return the data, we have what we need
                        return data;
                        
                }).get();

                return {'id': section_id, 'name': section_name, 'description': section_description, 'form': fields};

            }).get();

            //  Deefine the type of form we are building. 
            //  form_type: The nature of form we are creating. E.g) is the form
            //      "basic": meaning that its just a long form with one submit button
            //      "breadcrum": meaning that its partitioned into breadcrumbs which popup forms that can be saved individually
            storage.push(
                            {
                                'form_type': 'basic',   // Type of form we are creating
                                structure               //  Actual form structure in organised sections and fields 
                            }
                    );

            //  JSON.decycle to handle Error => [Converting circular structure to JSON]
            var formBuild = JSON.stringify(JSON.decycle(storage, true));

            //  Add the formBuild back to the submittion form under the hidden input
            $('#formBuild').val(formBuild);
        }

        $(document).on("click","#prf_nav > ul > li",function(){

            /*  First thing is to get the form build if it exists.
                This build will allow us to actually know what we are building, it is the blueprints
                to actually know the type of form, sections, fields and options we are building
            */
           var curr =  $(this);
           var form_build = JSON.parse(  $(currSelection).find('input[type="hidden"]').val() );
       
           //  If the build contents are available, then we can continue to build
           if(form_build != '' && form_build != undefined){

                form_html = buildForm(form_build, 'basic')

                //  Lets get the collection of sections in the current build structure
                var sections = form_build.structure;
                var section_name = '';

                $.each(sections, function( index, section ) {
                    if(section.id == $(curr).attr('id')){
                        section_name =  section.name;
                    }
                });

                createModal(section_name, form_html, $(curr).attr('id') );
           }
            
        });
        
        function createModal(title, body, show_only_section_id = null, button_name = 'Save'){

            $('#prf_modal').remove();
            
            var capitalizedTitle = title.charAt(0).toUpperCase() + title.slice(1).toLowerCase();

            var modal = '<div id="prf_modal" class="modal" tabindex="-1" role="dialog">'+
                            '<div class="modal-dialog" role="document">'+
                                '<div class="modal-content">'+
                                    '<div class="modal-header pb-0">'+
                                        '<h4 class="modal-title">'+capitalizedTitle+'</h4>'+
                                        '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'+
                                            '<span aria-hidden="true">&times;</span>'+
                                        '</button>'+
                                    '</div>'+
                                    '<div class="modal-body p-2">'+
                                        body+
                                    '</div>'+
                                    '<div class="modal-footer">'+
                                        '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>'+
                                        '<button type="button" class="btn btn-primary">'+button_name+'</button>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>';

            //  Place the content on the modal
            $('body').append(modal);

            //  If we only want to show a specific section then hide all others
            if(show_only_section_id != null){
                //  Hide all sections
                $('.pr-form-container li.section-container').hide();
                //  Show only the active section (the one that was clicked on)
                $('.pr-form-container li#'+show_only_section_id).show();
                //  Hide the active section name
                $('.pr-form-container li#'+show_only_section_id+' .section-name').hide();
            }

            //  Show the modal
            $('#prf_modal').modal('show');

            //  Refresh all section drag&drop, fields drag&drop, options drag&drop, dropify fields, date-picker fields, e.t.c
            refresh_plugins();

        }

    }
}(jQuery));
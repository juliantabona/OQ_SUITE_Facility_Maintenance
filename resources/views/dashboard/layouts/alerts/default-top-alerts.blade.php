@if(Session::has('alert'))
    <div class="alert alert-{{ Session::get('alert')[2] }}" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true" class="d-block mt-1 text-small">×</span>
        </button>
        @if(!empty(Session::get('alert')[1]))
            <i class = "{{ Session::get('alert')[1] }} mr-2"></i>
        @endif
        {{ Session::get('alert')[0] }}
    </div>
@endif
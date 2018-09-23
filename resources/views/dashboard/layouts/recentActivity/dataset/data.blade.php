
@if($recentActivity->activity["type"] == 'created')
    <a href="{{ route('jobcard-show', [$recentActivity->trackable_id]) }}" data-toggle="tooltip" data-placement="top" title="{{ $recentActivity->jobcard ? $recentActivity->jobcard->title : '____' }}">
        Jobcard {{ '#' . $recentActivity->trackable_id }}
    </a> 
    <b>created</b>
@elseif($recentActivity->activity["type"] == 'status_changed')
    Status update from <b>{{ $recentActivity->activity["old_status"] ? $recentActivity->activity["old_status"]:'____' }}</b> to 
    <b>{{ $recentActivity->activity["new_status"] ? $recentActivity->activity["new_status"]:'____' }}</b>


@elseif($recentActivity->activity["type"] == 'client_added')
    <a href="#">{{ $recentActivity->activity["company"]["name"] ? $recentActivity->activity["company"]["name"]:'____' }}</a>
    <b>added</b> as <b>client</b> for 
    <a href="{{ route('jobcard-show', [$recentActivity->trackable_id]) }}" data-toggle="tooltip" data-placement="top" title="{{ $recentActivity->jobcard ? $recentActivity->jobcard->title : '____' }}">Jobcard {{ '#' . $recentActivity->trackable_id }}</a>
@elseif($recentActivity->activity["type"] == 'client_removed')
    <a href="#">{{ $recentActivity->activity["company"]["name"] ? $recentActivity->activity["company"]["name"]:'____' }}</a>
    <b>removed</b> as <b>client</b> for 
    <a href="{{ route('jobcard-show', [$recentActivity->trackable_id]) }}" data-toggle="tooltip" data-placement="top" title="{{ $recentActivity->jobcard ? $recentActivity->jobcard->title : '____' }}">Jobcard {{ '#' . $recentActivity->trackable_id }}</a>


@elseif($recentActivity->activity["type"] == 'contractor_added')
    <a href="{{ route('contractor-show', [$recentActivity->activity["company"]["id"]]) }}">{{ $recentActivity->activity["company"]["name"] ? $recentActivity->activity["company"]["name"]:'____' }}</a>
    added to list of <b>contractors</b> for 
    <a href="{{ route('jobcard-show', [$recentActivity->trackable_id]) }}" data-toggle="tooltip" data-placement="top" title="{{ $recentActivity->jobcard ? $recentActivity->jobcard->title : '____' }}">Jobcard {{ '#' . $recentActivity->trackable_id }}</a>
@elseif($recentActivity->activity["type"] == 'contractor_removed')
    <a href="{{ route('contractor-show', [$recentActivity->activity["company"]["id"]]) }}">{{ $recentActivity->activity["company"]["name"] ? $recentActivity->activity["company"]["name"]:'____' }}</a>
    removed from list of <b>contractors</b>
@elseif($recentActivity->activity["type"] == 'contractor_selected')
    <a href="{{ route('contractor-show', [$recentActivity->activity["company"]["id"]]) }}">{{ $recentActivity->activity["company"]["name"] ? $recentActivity->activity["company"]["name"]:'____' }}</a>
    selected as contractor for 
    <a href="{{ route('jobcard-show', [$recentActivity->trackable_id]) }}" data-toggle="tooltip" data-placement="top" title="{{ $recentActivity->jobcard ? $recentActivity->jobcard->title : '____' }}">Jobcard {{ '#' . $recentActivity->trackable_id }}</a>


@elseif($recentActivity->activity["type"] == 'contact_added')
    <a href="{{ route('profile-show', [$recentActivity->activity["contact"]["id"]]) }}">
        {{ $recentActivity->activity["contact"]["first_name"] ? $recentActivity->activity["contact"]["first_name"]:'____' }}
        {{ $recentActivity->activity["contact"]["last_name"] ? $recentActivity->activity["contact"]["last_name"]:'____' }}
    </a>
    added as <b>client contact</b>
@endif

by
<a href="{{ route('profile-show', [$recentActivity->createdBy->id]) }}">
{{ $recentActivity->createdBy->first_name ? $recentActivity->createdBy->first_name:'____' }}
{{ $recentActivity->createdBy->last_name ? $recentActivity->createdBy->last_name:'____' }}
</a>

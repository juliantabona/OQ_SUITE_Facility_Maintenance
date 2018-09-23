@if($position % 2 == 0)
    <div class="timeline-wrapper timeline-wrapper-primary">
        <div class="timeline-badge"></div>
        <div class="timeline-panel">
            <div class="timeline-body">
                <p>      
                    @include('dashboard.layouts.recentActivity.dataset.data')
                </p>
            </div>
            <div class="timeline-footer d-flex align-items-center">
                <span class="ml-auto font-weight-bold mt-2">
                    <i class="icon-clock icons"></i> 
                    {{ $recentActivity->created_at ? Carbon\Carbon::parse($recentActivity->created_at)->format('d M Y @ h:m'):'____' }}
                </span>
            </div>      
        </div>
    </div>
    
@else
    <div class="timeline-wrapper timeline-inverted timeline-wrapper-primary">
        <div class="timeline-badge"></div>
        <div class="timeline-panel">
            <div class="timeline-body">
                <p>
                    @include('dashboard.layouts.recentActivity.dataset.data')
                </p>
            </div>
            <div class="timeline-footer d-flex align-items-center">
                <span class="ml-auto font-weight-bold mt-2">
                    <i class="icon-clock icons"></i> 
                    {{ $recentActivity->created_at ? Carbon\Carbon::parse($recentActivity->created_at)->format('d M Y @ h:m'):'____' }}
                </span>
            </div>               
        </div>
    </div>
@endif
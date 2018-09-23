<div class="recent-activities-box list d-flex align-items-center border-bottom py-3">
    <img class="img-sm rounded-circle" src="{{ $recentActivity->createdBy->avatar }}" alt="">
    <div class="wrapper w-100 ml-3">
        <p class="mb-0">
            @include('layouts.recentActivity.dataset.data')
        </p>
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="mdi mdi-clock text-muted mr-1"></i>
                <span class="text-muted ml-auto">{{ Carbon\Carbon::parse($recentActivity->created_at)->format('d M Y @ h:m') }}</span>
            </div>
        </div>
    </div>
</div>
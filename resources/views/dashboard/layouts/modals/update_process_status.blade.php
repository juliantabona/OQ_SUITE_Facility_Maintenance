<!-- Add progress status modal starts here -->
<div class="modal fade" id="progress-status-modal" tabindex="-1" role="dialog" aria-labelledby="progress-status-modalLabel" aria-hidden="true">
    <div class="modal-dialog mt-5" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-client-modalLabel">Update Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id ="progress-status-form" method="POST" action="{{ route('jobcard-update-progress', [$jobcard->id]) }}" enctype="multipart/form-data">
                {{ method_field('PUT') }}
                @csrf
                <div class="modal-body modal-content-max-height pt-3">

                </div>
                <div class="modal-footer">
                    <button type="button" id="progress-status-btn" type="button" class="btn btn-primary">
                            <i class="icon-refresh icons m-0 mr-1"></i>
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Add progress status modal ends here -->
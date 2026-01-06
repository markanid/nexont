
<!-- resources/views/partials/delete-modal.blade.php -->
<div class="modal fade" id="delete-confirmation-modal" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title"><i class="far fa-trash-alt"></i> Confirm Delete !</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this record? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-flat" data-dismiss="modal">No</button>
                <a href="#" class="btn btn-danger btn-flat" id="confirm-delete-btn">Yes!</a>
            </div>
        </div>
    </div>
</div>
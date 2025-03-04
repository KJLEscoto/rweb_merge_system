<!-- Trigger button for the modal -->
<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#declineModal">
    Decline Operation
</button>

<!-- Modal -->
<div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Form starts here -->

        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="declineModalLabel">Decline Operation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="decline_message" class="form-label">Decline Message</label>
            <textarea name="summary" id="decline_message" class="form-control" rows="5"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Submit Decline</button>
        </div>
      <!-- Form ends here -->
    </div>
  </div>
</div>

<!-- Include CKEditor 5 from CDN and initialize it on the textarea -->
<script src="https://cdn.ckeditor.com/ckeditor5/35.3.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#decline_message' ) )
        .catch( error => {
            console.error( error );
        } );
</script>

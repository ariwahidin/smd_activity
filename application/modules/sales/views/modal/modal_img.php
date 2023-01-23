<div class="modal fade" id="modal-default">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body text-center">
        <img src="<?= $image == '' ? base_url() . 'uploads/image_activity/no_image.jpg' : base_url() . 'uploads/image_activity/' . $image ?>" class="user-image img-responsive" alt="User Image" style="max-width:auto;">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
    $(document).ready(function(){
        $('#modal-default').modal('show');
    });
</script>
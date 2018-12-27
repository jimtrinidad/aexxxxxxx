<div class="modal fade" id="zoneModal" tabindex="-1" role="dialog" aria-labelledby="zoneModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="ZoneForm" name="ZoneForm" action="<?php echo site_url('zones/save_zone') ?>" enctype="multipart/form-data">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Edit Zone</h4>
        </div>
        <div class="modal-body">
          <div id="error_message_box" class="hide row">
            <br>
            <div class="error_messages no-border-radius alert alert-danger" role="alert"></div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-4 col-md-push-8 logo <?php echo ($view == 'city' ? 'padding-top-20' : 'padding-top-0') ?>">
                <div class="image-upload-container">
                  <img class="image-preview" src="<?php echo public_url(); ?>assets/logo/blank-logo.png">
                  <span class="hiddenFileInput hide">
                    <input type="file" data-default="<?php echo public_url(); ?>assets/logo/blank-logo.png" accept="image/*" class="image-upload-input" id="Logo" name="Logo"/>
                  </span>
                </div>
              </div>
              <div class="col-md-8 col-md-pull-4 padding-top-15">
                <div class="row">
                  <?php if ($view == 'city') {?>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="control-label" for="Type">Type</label>
                      <select class="input-sm form-control" id="Type" name="Type">
                        <option value="1">Municipal</option>
                        <option value="2">City</option>
                      </select>
                      <span class="help-block hidden"></span>
                    </div>
                  </div>
                  <?php } ?>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="control-label" for="Name">Location Name</label>
                      <input type="text" class="form-control input-sm" id="Name" name="Name" placehoder="Zone Name">
                      <span class="help-block hidden"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <input type="hidden" id="zonetype" name="zonetype">
          <input type="hidden" id="zonepsgc" name="zonepsgc">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
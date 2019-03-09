<div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="itemForm" name="itemForm" action="<?php echo site_url('businesses/saveitem') ?>">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><b class="text-bold">Add</b> | Item</h4>
        </div>
        <div class="modal-body">
          <div id="error_message_box" class="hide row">
            <br>
            <div class="error_messages no-border-radius alert alert-danger small" role="alert"></div>
          </div>
          <div class="row gutter-5">
            <div class="col-md-4 col-md-push-8 logo padding-top-20">
              <div class="image-upload-container">
                <img class="image-preview" src="<?php echo public_url(); ?>assets/logo/blank-logo.png">
                <span class="hiddenFileInput hide">
                  <input type="file" data-default="<?php echo public_url(); ?>assets/logo/blank-logo.png" accept="image/*" class="image-upload-input" id="Logo" name="Logo"/>
                </span>
              </div>
            </div>
            <div class="col-md-8 col-md-pull-4 padding-top-15">
              <div class="row">
                <div class="col-xs-12">
                  <div class="form-group">
                    <label class="control-label" for="name">Name</label>
                    <input type="text" class="form-control" id="Name" name="Name" placeholder="Product name">
                    <span class="help-block hidden"></span>
                  </div>
                </div>
                <div class="col-xs-12">
                  <div class="form-group">
                    <label class="control-label" for="Description">Description</label>
                    <textarea class="form-control" id="Description" name="Description" placeholder="Product description"></textarea>
                    <span class="help-block hidden"></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xs-12">
              <div class="form-group">
                <label class="control-label" for="Warranty">Warranty</label>
                <input type="text" class="form-control" id="Warranty" name="Warranty" placeholder="Warranty">
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-xs-6">
              <div class="form-group">
                <label class="control-label" for="Measurement">Unit of measurement</label>
                <input type="text" class="form-control" id="Measurement" name="Measurement" placeholder="Unit of measurement">
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-xs-6">
              <div class="form-group">
                <label class="control-label" for="Price">Unit Price</label>
                <input type="number" class="form-control" id="Price" name="Price" placeholder="Unit price">
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-xs-6">
              <div class="form-group">
                <label class="control-label" for="PaymentTerm">Terms of Payment</label>
                <input type="text" class="form-control" id="PaymentTerm" name="PaymentTerm" placeholder="Terms of Payment">
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-xs-6">
              <div class="form-group">
                <label class="control-label" for="LeadTime">Delivery Lead Time</label>
                <input type="text" class="form-control" id="LeadTime" name="LeadTime" placeholder="Delivery Lead Time">
                <span class="help-block hidden"></span>
              </div>
            </div>
          </div>
          <input type="hidden" name="BusinessCode" value="<?php echo (isset($businessData->Code) ? $businessData->Code : '') ?>">
          <input type="hidden" name="Code" id="Code" value="">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="uploadModal" role="dialog" aria-labelledby="uploadModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="UploadForm" name="UploadForm" action="<?php echo site_url('importer/upload_group') ?>" enctype="multipart/form-data">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <div id="error_message_box" class="hide row">
            <br>
            <div class="error_messages no-border-radius alert alert-danger" role="alert"></div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label" for="name">Name</label>
                  <input type="text" class="form-control input-sm" id="name" name="name" placehoder="Name">
                  <span class="help-block hidden"></span>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label" for="file">Excel File</label>
                  <input type="file" class="custom-file-input text-white" id="file" name="file" accept=".xlsx,.xls">
                  <span class="help-block hidden"></span>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label" for="service">Service Code</label>
                  <input type="text" class="form-control input-sm" id="service" name="service" placehoder="Service Code">
                  <span class="help-block hidden"></span>
                </div>
              </div>
              <div class="scopeSelector col-md-12">
                <div class="form-group">
                  <label>Scope</label>
                  <select id="scope" name="scope" onChange="Importer.setLocationSelector(this)" class="form-control">
                    <option value="">--</option>
                    <?php
                      foreach (lookup('location_scope') as $k => $v) {
                        echo "<option value='{$k}'>{$v}</option>";
                      }
                    ?>
                  </select>
                </div>
              </div>
              <div class="col-md-12 hide" id="citySelectorCont">
                <div class="form-group">
                  <label for="citySelector">City/Muni</label>
                  <select class="form-control" id="citySelector" onchange="Importer.loadBarangayOptions('#uploadModal #location', this)">
                      <option value="">--</option>
                      <?php
                      foreach (lookup_muni_city(null, false) as $v) {
                      echo "<option value='" . $v['citymunCode'] . "'>" . $v['provDesc'] . ' | ' . $v['citymunDesc'] . "</option>";
                      }
                      ?>
                  </select>
                </div>
              </div>
              <div class="locationSelector hidden col-md-12">
                <div class="form-group">
                  <label>Location</label>
                  <select id="location" name="location" class="form-control">
                    <option value="">--</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <input type="hidden" id="id" name="id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

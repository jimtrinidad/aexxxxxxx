<div class="modal fade" id="projectModal" tabindex="-1" role="dialog" aria-labelledby="projectModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="projectForm" name="projectForm" action="<?php echo site_url('coa/saveproject') ?>">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><b class="text-bold">Add</b> | Project</h4>
        </div>
        <div class="modal-body">
          <div id="error_message_box" class="hide row">
            <br>
            <div class="error_messages no-border-radius alert alert-danger small" role="alert"></div>
          </div>
          <div class="row gutter-5">
            <div class="col-xs-12">
              <div class="form-group">
                <label class="control-label" for="LocationScopeID">Scope</label>
                <select class="form-control" id="LocationScopeID" name="LocationScopeID">
                    <option value=""></option>
                    <?php
                      foreach (lookup('location_scope') as $k => $v) {
                        echo "<option value='{$k}'>{$v}</option>";
                      }
                    ?>
                 </select>
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-xs-12">
              <div class="form-group">
                <label class="control-label" for="name">Name</label>
                <input type="text" class="form-control" id="Name" name="Name" placehoder="Project name">
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-xs-12">
              <div class="form-group">
                <label class="control-label" for="Description">Description</label>
                <textarea class="form-control" id="Description" name="Description" placeholder="Project description"></textarea>
                <span class="help-block hidden"></span>
              </div>
            </div>
          </div>
          <input type="hidden" name="OrganizationID" value="<?php echo $Organization->id?>">
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
<div class="modal fade" id="documentModal" role="dialog" aria-labelledby="documentModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="DocumentForm" name="DocumentForm" action="<?php echo site_url('documents/save_document') ?>" enctype="multipart/form-data">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><b>Document</b> | <span class="header-action"></span> <span class="document_code padding-right-10 pull-right text-red"></span></h4>
        </div>
        <div class="modal-body">
          <div id="error_message_box" class="hide row">
            <br>
            <div class="error_messages no-border-radius alert alert-danger" role="alert"></div>
          </div>
          <div class="form-group">
            <div class="row">
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
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="control-label" for="Type">Document Type</label>
                      <select id="Type" name="Type" class="form-control">
  	                    <option value="">--</option>
  	                    <?php
  	                    foreach (lookup('document_type') as $k => $v) {
  	                    	echo "<option value='{$k}'>{$v}</option>";
  	                    }
  	                    ?>
  	                  </select>
                      <span class="help-block hidden"></span>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="control-label" for="Name">Document Name</label>
                      <input type="text" class="form-control input-sm" id="Name" name="Name" placehoder="Department Name">
                      <span class="help-block hidden"></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label" for="DepartmentID">Department / Agency</label>
                  <select id="DepartmentID" name="DepartmentID" class="form-control">
                    <option value="">--</option>
                    <?php
                       foreach(lookup_all('Dept_Departments', false, 'Name') as $item) {
                        echo '<option value="' . $item['id'] . '">' . $item['Name'] . '</option>';
                       }
                       foreach(lookup_all('Dept_ChildDepartment', false, 'Name') as $item) {
                        echo '<option value="' . $item['DepartmentID'] .'-'. $item['id'] . '">' . $item['Name'] . '</option>';
                       }
                    ?>
                  </select>
                  <span class="help-block hidden"></span>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label" for="">Generated Document Validity</label>
                  <select id="Validity" name="Validity" class="form-control">
                    <option value="">--</option>
                    <?php
                    foreach (lookup('document_validity') as $k => $v) {
                      echo "<option value='{$k}'>{$v}</option>";
                    }
                    ?>
                  </select>
                  <span class="help-block hidden"></span>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label" for="Description">Description</label>
                  <textarea class="form-control" id="Description" name="Description" placeholder="Description"></textarea>
                  <span class="help-block hidden"></span>
                </div>
              </div>
              <div class="col-xs-12 col-sm-6">
                <div class="form-group">
                  <label class="control-label" for="Size">Size <small>(in)</small></label>
                  <select id="Size" name="Size" class="form-control">
                    <?php
                    foreach (lookup('document_size') as $k => $v) {
                      echo "<option value='{$k}'>{$k}</option>";
                    }
                    ?>
                  </select>
                  <span class="help-block hidden"></span>
                </div>
              </div>
              <div class="col-xs-12 col-sm-6">
                <div class="form-group">
                  <label class="control-label" for="Margin">Margin <small>(mm)</small></label>
                  <select id="Margin" name="Margin" class="form-control">
                    <option>0</option>
                    <option>5</option>
                    <option>10</option>
                    <option>15</option>
                    <option>20</option>
                    <option>25</option>
                  </select>
                  <span class="help-block hidden"></span>
                </div>
              </div>
              <div class="col-xs-12">
                <div class="form-group">
                  <label class="control-label" for="Orientation">Orientation</label>
                  <select id="Orientation" name="Orientation" class="form-control">
                    <option value="P">Portrait</option>
                    <option value="L">Landscape</option>
                  </select>
                  <span class="help-block hidden"></span>
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

<div class="modal fade" id="extraFieldModal" tabindex="-1" role="dialog" aria-labelledby="extraFieldModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="ExtraFieldForm" name="ExtraFieldForm" action="<?php echo site_url('documents/save_extra_field') ?>">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><b>Extra Fields</b> | <span class="header-action"></span></h4>
        </div>
        <div class="modal-body">
          <div id="error_message_box" class="hide row">
            <br>
            <div class="error_messages no-border-radius alert alert-danger" role="alert"></div>
          </div>
          <div class="form-group">
            <div class="row padding-top-5">
              <div class="col-xs-6">
                <div class="form-group">
                  <label class="control-label" for="FieldType">Field Type</label>
                  <select id="FieldType" name="FieldType" class="form-control">
                    <?php
                    foreach (lookup('field_type') as $k => $v) {
                      if ($k <= 3) { 
                        echo "<option value='{$k}'>{$v}</option>";
                      }
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="col-xs-6">
                <div class="form-group">
                  <label class="control-label" for="FieldKey">Template Keyword</label>
                  <input type="text" class="form-control" id="FieldKey" name="FieldKey" placehoder="Keyword" style="text-transform: uppercase;">
                </div>
              </div>
              <div class="col-xs-12">
                <div class="form-group">
                  <label class="control-label" for="FieldLabel">Field Label</label>
                  <input type="text" class="form-control" id="FieldLabel" name="FieldLabel" placehoder="Label">
                </div>
              </div>
            </div>
            <input type="hidden" id="id" name="id">
            <input type="hidden" id="Code" name="Code" value="<?php echo (isset($documentData['Code']) ? $documentData['Code'] : '')?>">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
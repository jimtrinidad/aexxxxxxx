<div class="modal fade" id="departmentModal" tabindex="-1" role="dialog" aria-labelledby="departmentModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="DepartmentForm" name="DepartmentForm" action="<?php echo site_url('department/save_department') ?>" enctype="multipart/form-data">
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
                      <label class="control-label" for="Code">Department Code</label>
                      <input type="text" class="form-control input-sm" id="Code" name="Code" placehoder="DepartmentCode">
                      <span class="help-block hidden"></span>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="control-label" for="Name">Department Name</label>
                      <input type="text" class="form-control input-sm" id="Name" name="Name" placehoder="Department Name">
                      <span class="help-block hidden"></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label" for="FunctionMandate">Function/Mandate</label>
                  <textarea class="form-control" id="FunctionMandate" name="FunctionMandate" placeholder="Department Function/Mandate"></textarea>
                  <span class="help-block hidden"></span>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label" for="Address">Main Office Address</label>
                  <textarea class="form-control" id="Address" name="Address" placeholder="Main Office Address"></textarea>
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


<div class="modal fade" id="subDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="subDepartmentModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="SubDepartmentForm" name="SubDepartmentForm" onsubmit="return false" action="<?php echo site_url('department/save_sub_department') ?>" enctype="multipart/form-data">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <b>Sub department | <strong class="h4 forDepartment"></strong></b>
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
              <div class="col-md-8 col-md-pull-4">
                <div class="row padding-top-15">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="control-label" for="Type">Type</label>
                      <select id="Type" name="Type" class="form-control">
                        <?php
                          foreach (lookup('child_department_types') as $k => $v) {
                            echo "<option value='{$k}'>{$v}</option>";
                          }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="control-label" for="Code">Code</label>
                      <input type="text" class="form-control" id="Code" name="Code" placehoder="DepartmentCode">
                      <span class="help-block hidden"></span>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="control-label" for="Name">Name</label>
                      <input type="text" class="form-control" id="Name" name="Name" placehoder="Department Name">
                      <span class="help-block hidden"></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label" for="FunctionMandate">Function/Mandate</label>
                  <textarea class="form-control" id="FunctionMandate" name="FunctionMandate" placeholder="Department Function/Mandate"></textarea>
                  <span class="help-block hidden"></span>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label" for="Address">Address</label>
                  <textarea class="form-control" id="Address" name="Address" placeholder="Main Office Address"></textarea>
                  <span class="help-block hidden"></span>
                </div>
              </div>
            </div>
          </div>
          <input type="hidden" id="id" name="id">
          <input type="hidden" id="DepartmentID" name="DepartmentID">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="departmentLocationModal" tabindex="-1" role="dialog" aria-labelledby="departmentLocationModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="DepartmentLocationForm" name="DepartmentLocationForm" onsubmit="return false" action="<?php echo site_url('department/save_department_location') ?>">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Department Scope Location</h4>
        </div>
        <div class="modal-body">
          <strong class="h4 forDepartment"></strong></b>
          <div id="error_message_box" class="hide row">
            <br>
            <div class="error_messages no-border-radius alert alert-danger" role="alert"></div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-12 padding-top-15">
                <div class="form-group">
                  <label class="control-label" for="Status">Status</label>
                  <select id="LocationStatus" name="Status" class="form-control input-sm">
                    <option value="0">Disable</option>
                    <option value="1">Enable</option>
                  </select>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label" for="Contact">Contact</label>
                  <textarea class="form-control input-sm" id="Contact" name="Contact" placeholder="Department scope location contacts"></textarea>
                  <span class="help-block hidden"></span>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label" for="Address">Address</label>
                  <textarea class="form-control input-sm" id="Address" name="Address" placeholder="Department Scope Location Address"></textarea>
                  <span class="help-block hidden"></span>
                </div>
              </div>
            </div>
          </div>
          <input type="hidden" id="id" name="id">
          <input type="hidden" id="DepartmentID" name="DepartmentID">
          <input type="hidden" id="SubDepartmentID" name="SubDepartmentID">
          <input type="hidden" id="LocationScope" name="LocationScope">
          <input type="hidden" id="LocationCode" name="LocationCode">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="departmentOfficersListModal" tabindex="-1" role="dialog" aria-labelledby="departmentOfficersListModal">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><b class="departmentName"></b> | Officers</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-xs-12">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <div class="row padding-bottom-10">
                    <div class="col-xs-3">Scope: <b class="officerScope"></b></div>
                    <div class="col-xs-6">Location: <b class="officerLocation"></b></div>
                    <div class="col-xs-3">
                      <div class="box-tools">
                        <div class="input-group input-group-sm">
                          <div class="input-group-btn text-right">
                            <button type="button" class="btn btn-success" onClick="Department.addOfficer();" title="Add Officer"><i class="fa fa-plus"></i> Add</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body table-responsive no-padding">
                    <h4 id="result_message" class="hidden"></h4>
                    <table id="tableData" class="table table-hover">
                      <thead>
                        <tr>
                          <td style="width: 20px;"></td>
                          <th>ID</th>
                          <th>Name</th>
                          <th>Function</th>
                          <th>Position</th>
                          <th class="hiddexn-xs">Contact</th>
                          <th class="hidden hiddexn-xs">Address</th>
                          <th class="c"></th>
                        </tr>
                      </thead>
                      <tbody id="tableBody">
                        
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <input type="hidden" id="DepartmentLocationID" name="DepartmentLocationID">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="departmentOfficerFormModal" tabindex="-1" role="dialog" aria-labelledby="departmentOfficerFormModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="DepartmentOfficerForm" name="DepartmentOfficerForm" onsubmit="return false" action="<?php echo site_url('department/add_department_officer') ?>">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><b>Department Officer</b> | Add</h4>
        </div>
        <div class="modal-body">
          <strong class="h4 forDepartment"></strong></b>
          <div id="error_message_box" class="hide row">
            <br>
            <div class="error_messages no-border-radius alert alert-danger" role="alert"></div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-12 padding-top-15">
                <div class="form-group">
                  <label class="control-label" for="accountFinder">Find officer by id or name</label>
                  <div class="input-group">
                    <input type="text" autocomplete="off" class="form-control" id="accountFinder" placehoder="find officer by id or name">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                  </div>
                </div>
              </div>
              <div class="positionBox hidden col-md-12">
                <div class="form-group">
                  <label class="control-label" for="Type">Function</label>
                  <select id="FunctionTypeID" name="FunctionTypeID" class="form-control">
                    <option value="">--</option>
                    <?php
                      foreach (lookup('function_type') as $k => $v) {
                        echo "<option value='{$k}'>{$v}</option>";
                      }
                    ?>
                  </select>
                </div>
              </div>
              <div class="positionBox hidden col-md-12">
                <div class="form-group">
                  <label class="control-label" for="Position">Position</label>
                  <input type="text" class="form-control" id="Position" name="Position" placehoder="Officer position">
                  <span class="help-block hidden"></span>
                </div>
              </div>
              <div class="positionBox hidden col-xs-3 padding-top-10 text-center">
                <img style="width: 70px;height: 70px;margin: 10px auto" class="photo" src="">
              </div>
              <div class="positionBox accountInfo hidden col-xs-9 padding-top-10">
              </div>
            </div>
          </div>

          <input type="hidden" id="SelectedAccountID" name="SelectedAccountID">
          <input type="hidden" id="DepartmentLocationID" name="DepartmentLocationID">
          <input type="hidden" id="DepartmentID" name="DepartmentID">
          <input type="hidden" id="SubDepartmentID" name="SubDepartmentID">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
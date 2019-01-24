<div class="modal fade" id="supportListModal" tabindex="-1" role="dialog" aria-labelledby="supportListModal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><b class="serviceName"></b> | Supports</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xs-12">
            <div class="box box-primary">
              <div class="box-header">
                <div class="box-tools" style="margin-top: 0;">
                  <div class="input-group input-group-sm" style="max-width: 300px;">
                    <input type="text" autocomplete="off" id="findOfficerBox" name="findOfficerBox" class="form-control pull-right" placeholder="Find Officer">
                    <div class="input-group-btn">
                      <button type="button" class="btn btn-success" onClick="Services.addSupport();" title="Add Support"><i class="fa fa-plus"></i> Add</button>
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
                      <th>Level</th>
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
        <input type="hidden" id="ServiceCode" name="ServiceCode">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="organizationModal" tabindex="-1" role="dialog" aria-labelledby="organizationModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="organizationForm" name="organizationForm" onsubmit="return false" action="<?php echo site_url('services/save_organization') ?>">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><b class="text-red serviceCode"></b> | Organization</h4>
        </div>
        <div class="modal-body">
          <div class="row gutter-5">
            <div class="col-xs-3 padding-top-10 text-center">
              <img style="width: 100%;max-width: 100px;margin: 10px auto" class="serviceLogo" src="">
            </div>
            <div class="infoBox col-xs-9 padding-top-10">

            </div>
          </div>

          <hr>
          <div id="error_message_box" class="hide row">
            <br>
            <div class="error_messages no-border-radius alert alert-danger" role="alert"></div>
          </div>

          <div class="row">
            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                <label class="control-label" for="Status">Status</label>
                <select id="Status" name="Status" class="form-control">
                  <option value="0">Disabled</option>
                  <option value="1">Active</option>
                </select>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                <label class="control-label" for="Category">Category</label>
                <select id="Category" name="Category" class="form-control">
                  <?php
                  foreach (lookup('service_organization_category') as $k => $v) {
                    echo "<option value='{$k}'>{$v}</option>";
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                <label class="control-label" for="MenuName">Menu Name</label>
                <input type="text" name="MenuName" id="MenuName" class="form-control" placeholder="Menu Name">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                <label class="control-label" for="Keyword">Keyword</label>
                <input type="text" name="Keyword" id="Keyword" class="form-control" placeholder="Keyword">
              </div>
            </div>
          </div>

          <input type="hidden" id="id" name="id">
          <input type="hidden" id="ServiceID" name="ServiceID">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
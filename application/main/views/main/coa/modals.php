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
            <div class="col-xs-6">
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
            <div class="col-xs-6">
              <div class="form-group">
                <label class="control-label" for="TargetDate">Target Date</label>
                <input type="text" class="form-control datepicker" id="TargetDate" name="TargetDate" placehoder="Target date">
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
          <input type="hidden" name="OrganizationID" value="<?php echo (isset($Organization->id) ? $Organization->id : '')?>">
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




<div class="modal fade" id="projectCategoryModal" tabindex="-1" role="dialog" aria-labelledby="projectCategoryModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="projectCategoryForm" name="projectCategoryForm" action="<?php echo site_url('coa/addprojectcategory') ?>">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <div id="error_message_box" class="hide row">
            <br>
            <div class="error_messages no-border-radius alert alert-danger small" role="alert"></div>
          </div>
          <div class="row gutter-5">
            <div class="col-xs-12">
              <div class="form-group">
                <label class="control-label" for="Service">Categories</label>
                <select class="form-control" id="Service" name="Service">
                    <option value=""></option>
                    <?php
                      if (isset($availableServices)) {
                        foreach ($availableServices as $k => $v) {
                          echo "<option value='{$v['id']}'>{$v['MenuName']}</option>";
                        }
                      }
                    ?>
                 </select>
                <span class="help-block hidden"></span>
              </div>
            </div>
          </div>
          <input type="hidden" name="Code" id="Code" value="">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Add</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="categoryItemModal" tabindex="-1" role="dialog" aria-labelledby="categoryItemModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="categoryItemForm" name="categoryItemForm" action="<?php echo site_url('coa/savecategoryitem') ?>">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <div id="error_message_box" class="hide row">
            <br>
            <div class="error_messages no-border-radius alert alert-danger small" role="alert"></div>
          </div>
          <div class="row gutter-5">
            <div class="col-xs-12">
              <div class="form-group">
                <label class="control-label" for="name">Name</label>
                <input type="text" class="form-control" id="Name" name="Name" placehoder="Item name">
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-xs-12">
              <div class="form-group">
                <label class="control-label" for="Description">Description</label>
                <textarea class="form-control" id="Description" name="Description" placeholder="Item description"></textarea>
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-xs-6">
              <div class="form-group">
                <label class="control-label" for="Quantity">Quantity</label>
                <input type="text" class="form-control" id="Quantity" name="Quantity" placeholder="Quantity">
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-xs-6">
              <div class="form-group">
                <label class="control-label" for="Allocation">Allocation</label>
                <input type="text" class="form-control" id="Allocation" name="Allocation" placeholder="Allocation Amount"
                <span class="help-block hidden"></span>
              </div>
            </div>
          </div>
          <input type="hidden" name="ProjectServiceID" id="ProjectServiceID" value="">
          <input type="hidden" name="id" id="id" value="">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="supplierItemFinderModal" tabindex="-1" role="dialog" aria-labelledby="supplierItemFinderModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><b class="text-bold">Find Supplier Item</b></h4>
        </div>
        <div class="modal-body" style="max-height: 800px;overflow: auto;">

          <div class="row gutter-5">
            <div class="col-xs-12 matchedItems">
              <div class="panel panel-info">
                <div class="panel-heading">Posible matched items</div>
                <table class="table">
                  <thead>
                    <tr>
                      <th width="50">Image</th>
                      <th>Item name</th>
                      <th>UoM</th>
                      <th>Price</th>
                      <th>Supplier</th>
                      <th width="20"></th>
                    </tr>
                  </thead>
                  <tbody class="matchRows">
                    
                  </tbody>
                </table>
              </div>

              <div class="panel panel-default">
                <div class="panel-heading">Other results</div>
                <table class="table">
                  <thead>
                    <tr>
                      <th width="50">Image</th>
                      <th>Item name</th>
                      <th>UoM</th>
                      <th>Price</th>
                      <th>Supplier</th>
                      <th width="20"></th>
                    </tr>
                  </thead>
                  <tbody class="nonMatchRows">
                    
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>
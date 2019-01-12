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
              <div class="box-header" style="height: 30px;">
                <div class="box-tools">
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
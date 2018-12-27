<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header">
        <h3 class="box-title">Department List</h3>
        <div class="box-tools">
          <form action="" method="post">
            <div class="input-group input-group-sm" style="width: 250px;">
              <input type="text" autocomplete="off" id="searchKeyword" name="searchKeyword" value="<?php echo get_post('searchKeyword'); ?>" class="form-control pull-right" placeholder="Search">
              <div class="input-group-btn">
                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                <button type="button" class="btn btn-success" onClick="Department.addDepartment();" title="Add Department"><i class="fa fa-plus"></i> Add</button>
              </div>
            </div>
          </form>
          
        </div>
      </div>
      <!-- /.box-header -->
      <div class="box-body table-responsive no-padding">
        <table id="tableData" class="table table-hover">
          <thead>
            <tr>
              <td style="width: 20px;"></td>
              <th>Code</th>
              <th>Name</th>
              <th class="hiddexn-xs">Mandate</th>
              <th class="hiddexn-xs">Address</th>
              <th class="hiddexn-xs">Type</th>
              <th class="c"></th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($departments as $department) {
              echo "<tr class='text-left'>";
                echo '<td><img class="logo-small" src="'.public_url() . 'assets/logo/' . $department['Logo'] .'"></td>';
                echo '<td>' . $department['Code'] . '</td>';
                echo '<td>' . $department['Name'] . '</td>';
                echo '<td class="hiddexn-xs">' . $department['FunctionMandate'] . '</td>';
                echo '<td class="hiddexn-xs">' . $department['Address'] . '</td>';
                echo '<td>Main</td>';
                echo '<td>
                        <div class="box-tools">
                          <div class="input-group pull-right" style="width: 10px;">
                            <div class="input-group-btn">' .
                            (
                              count($department['subDepartment'])
                              ? '
                                <button type="button" class="btn btn-xs btn-success" onClick="Department.toggleSubDepartment('.$department['id'].')"><i class="fa fa-level-down"></i> Under</button>'
                              : '' 
                            )
                              . '<button type="button" class="btn btn-xs btn-default" title="Add sub department or office" onClick="Department.addSubDepartment('.$department['id'].')"><i class="fa fa-plus"></i> Add</button>
                              <button type="button" class="btn btn-xs btn-default" onClick="Department.editDepartment('.$department['id'].')"><i class="fa fa-pencil"></i></button>
                              <button type="button" class="btn btn-xs btn-danger" onClick="Department.deleteDepartment('.$department['id'].')"><i class="fa fa-trash"></i></button>
                            </div>
                          </div>
                        </div> 
                      </td>';
              echo '</tr>';
              echo '<tbody id="dept_'.$department['id'].'" class="' . (get_post('searchKeyword') ? '' : 'hidden') . '">';
              foreach ($department['subDepartment'] as $subDept) {
                echo "<tr class='text-left info small'>";
                  echo '<td class="indent-30"><img class="logo-smaller" src="'.public_url() . 'assets/logo/' . $subDept['Logo'] .'"></td>';
                  echo '<td class="indent-30">' . $subDept['Code'] . '</td>';
                  echo '<td class="indent-30">' . $subDept['Name'] . '</td>';
                  echo '<td class="hiddexn-xs">' . $subDept['FunctionMandate'] . '</td>';
                  echo '<td class="hiddexn-xs">' . $subDept['Address'] . '</td>';
                  echo '<td class="">' . lookup('child_department_types', $subDept['Type']) . '</td>';
                  echo '<td>
                          <div class="box-tools">
                            <div class="input-group pull-right" style="width: 10px;">
                              <div class="input-group-btn">
                                <button type="button" class="btn btn-xs btn-default" onClick="Department.editSubDepartment('.$department['id'].','.$subDept['id'].')"><i class="fa fa-pencil"></i> Edit</button>
                                <button type="button" class="btn btn-xs btn-danger" onClick="Department.deleteSubDepartment('.$department['id'].','.$subDept['id'].')"><i class="fa fa-trash"></i></button>
                              </div>
                            </div>
                          </div> 
                        </td>';
                echo '</tr>';
              }
              echo '</tbody>';
            }
            ?>
          </tbody>
        </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>

<?php view('pages/department/modals.php'); ?>

<script type="text/javascript">
  $(document).ready(function(){
    Department.departmentData = <?php echo json_encode($departments, JSON_HEX_TAG); ?>;
    if (typeof(Utils) != 'undefined') {
      Utils.highlightMatch($('#tableData tbody'), $('#searchKeyword').val());
    }
  });
</script>
<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary small">

      <div class="box-header">
        <h3 class="box-title">Services List</h3>
        <div class="box-tools">
          <form action="<?php echo site_url('services') ?>" method="post" class="form-inline">
            <div class="form-group">
              <select id="search_scope" name="search_scope" class="form-control input-sm">
                <option value="">-- any scope --</option>
                <?php
                  foreach (lookup('location_scope') as $k => $v) {
                    echo "<option ". ($search_scope == $k ? 'selected="selected"' : '') ."  value='{$k}'>{$v}</option>";
                  }
                ?>
              </select>
            </div>
            <div class="form-group">
              <input type="text" autocomplete="off" id="search_code" name="search_code" value="<?php echo $search_code ?>" class="form-control input-sm" placeholder="Service Code">
            </div>
            <div class="form-group">
              <input type="text" autocomplete="off" id="search_name" name="search_name" value="<?php echo $search_name ?>" class="form-control input-sm" placeholder="Keyword">
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-sm btn-info">Search</button>
            </div>
          </form>
        </div>
      </div>
      <!-- /.box-header -->

      <div class="box-body table-responsive no-padding">
        <table class="table table-bordered">
          <thead>
            <th>Code</th>
            <th>Name</th>
            <th class="visible-lg">Description</th>
            <th>Scope</th>
            <th>Location</th>
            <th>Type</th>
            <th>Department</th>
            <th class="visible-lg">Date Added</th>
            <th>Status</th>
            <th class="text-right">
              <a href="<?php echo base_url('services/setup'); ?>" class="btn btn-xs btn-success" title="Add Department"><i class="fa fa-plus"></i> Add</a>
            </th>
          </thead>
          <tbody>
            <?php
            foreach ($services as $item) {
              echo "<tr class='text-left'>";
                echo '<td>' . $item['Code'] . '</td>';
                echo '<td>' . $item['Name'] . '</td>';
                echo '<td class="visible-lg" title="'. $item['Description'] .'">' . substr($item['Description'], 0, 60) . (strlen($item['Description']) > 60 ? '...' : '') . '</td>';
                echo '<td>' . $item['Scope'] . '</td>';
                echo '<td>' . strtoupper(preg_replace('~\([^()]*\)~', '', implode(', ', array_reverse(array_slice($item['Location'], -2, 2))))) . '</td>';
                echo '<td>' . $item['Type'] . '</td>';
                echo '<td>' . $item['Department']->Code . ($item['SubDepartment'] ? ' / ' . $item['SubDepartment']->Code : '') . '</td>';
                echo '<td class="visible-lg">' . date('M d, Y', strtotime($item['DateAdded'])) . '</td>';
                // echo '<td>' . lookup('service_status', $item['Status']) . '</td>';
                
                echo '<td>';
                  if ($item['Status'] == 0) {
                    echo 'Pending';
                  } else {
                    echo '<input class="serviceStatusToggle" type="checkbox" '. ($item['Status'] == 1 ? 'checked' : '') .' 
                          data-code="' . $item['Code'] . '"
                          data-toggle="toggle" 
                          data-on="Active" 
                          data-off="Disabled" 
                          data-size="mini" 
                          data-width="70">';
                  }
                echo '</td>';

                echo '<td>
                        <div class="box-tools">
                          <div class="input-group pull-right" style="width: 10px;">
                            <div class="input-group-btn">' .
                            (
                              $item['Status'] == 0
                              ? '
                                <button type="button" class="btn btn-xs btn-success" title="Approve service" onClick="Services.approveService('.$item['id'].')"><i class="fa fa-check"></i><span class="visible-lg-inline"> Approve</span></button>'
                              : '
                                <button class="btn btn-xs btn-info" title="Supports" onClick="Services.showSupports('.$item['id'].')">( '.count($item['Supports']).' ) <i class="fa fa-users"></i><span class="visible-lg-inline"> Supports</span></button>
                              ' 
                            )
                              . '<a href="'.base_url('services/setup/' . $item['Code']).'" class="btn btn-xs btn-default" title="View details"><i class="fa fa-pencil"></i></a>
                              	<button type="button" class="btn btn-xs btn-danger" title="Delete" onClick="Services.deleteService('.$item['id'].')"><i class="fa fa-trash"></i></button>
                            </div>
                          </div>
                        </div> 
                      </td>';
              echo '</tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    <!-- /.box-body -->

      <div class="box-footer clearfix">
        <?php echo $pagination ?>
      </div>
    </div>
  </div>
</div>

<?php view('pages/services/modals.php'); ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" />
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
  	Services.servicesData = <?php echo json_encode($services, JSON_HEX_TAG);?>;
  });
</script>
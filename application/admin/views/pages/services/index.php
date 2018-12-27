<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary small">

      <div class="box-header">
        <h3 class="box-title">Services List</h3>
        <div class="box-tools">
          <a href="<?php echo base_url('services/setup'); ?>" class="btn btn-sm btn-success" title="Add Department"><i class="fa fa-plus"></i> Add</a>
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
            <th>Type</th>
            <th>Department</th>
            <th>Status</th>
            <th class="visible-lg">Date Added</th>
            <th class="c"></th>
          </thead>
          <tbody>
            <?php
            foreach ($services as $item) {
              echo "<tr class='text-left'>";
                echo '<td>' . $item['Code'] . '</td>';
                echo '<td>' . $item['Name'] . '</td>';
                echo '<td class="visible-lg">' . $item['Description'] . '</td>';
                echo '<td>' . $item['Scope'] . '</td>';
                echo '<td>' . $item['Type'] . '</td>';
                echo '<td>' . $item['Department']->Code . '</td>';
                echo '<td>' . lookup('service_status', $item['Status']) . '</td>';
                echo '<td class="visible-lg">' . $item['DateAdded'] . '</td>';
                echo '<td>
                        <div class="box-tools">
                          <div class="input-group pull-right" style="width: 10px;">
                            <div class="input-group-btn">' .
                            (
                              $item['Status'] == 0
                              ? '
                                <button type="button" class="btn btn-xs btn-success" title="Approve service" onClick="Services.approveService('.$item['id'].')"><i class="fa fa-check"></i><span class="visible-lg-inline"> Approve</span></button>'
                              : '' 
                            )
                              . '<button type="button" class="btn btn-xs btn-default hidden" title="View details" onClick="Services.editService('.$item['id'].')"><i class="fa fa-pencil"></i></button>
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

<script type="text/javascript">
  $(document).ready(function(){
  	Services.servicesData = <?php echo json_encode($services, JSON_HEX_TAG);?>;
  });
</script>
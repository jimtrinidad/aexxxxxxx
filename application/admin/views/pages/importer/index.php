<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary small">

      <div class="box-header">
        <h3 class="box-title">Data Importer</h3>
        <div class="box-tools">
          <button type="button" class="btn btn-success btn-sm" onClick="Importer.uploadGroup();" title="Add Group"><i class="fa fa-plus"></i> Upload</button>
        </div>
      </div>
      <!-- /.box-header -->

      <div class="box-body table-responsive no-padding">
        <table class="table table-bordered">
          <thead>
            <th>Name</th>
            <th>Scope</th>
            <th>Location</th>
            <th>Service</th>
            <th>Status</th>
            <th>Count</th>
            <th class="visible-lg">Last Modified</th>
            <th class="c"></th>
          </thead>
          <tbody>
           <?php
              foreach ($groups as $item) {
                echo "<tr class='text-left' id='group_item_".$item['code']."'>";
                echo '<td>' . $item['name'] . '</td>';
                echo '<td>' . $item['scope'] . '</td>';
                echo '<td>' . $item['location'] . '</td>';
                echo '<td>' . $item['service'] . '</td>';
                echo '<td>' . $item['status'] . '</td>';
                echo '<td>' . $item['count'] . '</td>';
                echo '<td class="visible-lg">' . $item['last_update'] . '</td>';
                echo '<td>
                        <div class="box-tools">
                          <div class="input-group pull-right" style="width: 10px;">
                            <div class="input-group-btn">
                                <a href="'. site_url('importer/view/' . $item['code']) .'" class="btn btn-xs btn-info" title="View records"><i class="fa fa-search"></i></a>
                                <button type="button" class="btn btn-xs btn-danger" title="Delete" onClick="Importer.deleteGroup('.$item['code'].')"><i class="fa fa-trash"></i></button>
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

<?php view('pages/importer/modals.php'); ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.8/css/alt/AdminLTE-select2.min.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
    
  });
</script>
<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary small">

      <div class="box-header">
        <h3 class="box-title">Document Templates</h3>
        <div class="box-tools">
          <button type="button" class="btn btn-success btn-sm" onClick="Documents.addDocument();" title="Add Document"><i class="fa fa-plus"></i> Add</button>
        </div>
      </div>
      <!-- /.box-header -->

      <div class="box-body table-responsive no-padding">
        <table class="table table-bordered">
          <thead>
            <th>Code</th>
            <th>Type</th>
            <th>Name</th>
            <th class="visible-lg">Description / Agency</th>
            <th>Department</th>
            <th class="visible-lg">Last Modified</th>
            <th class="c"></th>
          </thead>
          <tbody>
            <?php
              foreach ($documents as $item) {
                echo "<tr class='text-left'>";
                echo '<td>' . $item['Code'] . '</td>';
                echo '<td>' . $item['TypeName'] . '</td>';
                echo '<td>' . $item['Name'] . '</td>';
                echo '<td class="visible-lg">' . $item['Description'] . '</td>';
                echo '<td>' . ($item['SubDepartment'] ? $item['SubDepartment']->Name : $item['Department']->Name ) . '</td>';
                echo '<td class="visible-lg">' . $item['LastUpdate'] . '</td>';
                echo '<td>
                        <div class="box-tools">
                          <div class="input-group pull-right" style="width: 10px;">
                            <div class="input-group-btn">
                                <a href="'. site_url('documents/template/' . $item['Code']) .'" class="btn btn-xs btn-info" title="Template setup"><i class="fa fa-file-code-o"></i></a>
                                <button type="button" class="btn btn-xs btn-default" title="Update" onClick="Documents.editDocument('.$item['id'].')"><i class="fa fa-pencil"></i></button>
                                <button type="button" class="btn btn-xs btn-danger" title="Delete" onClick="Documents.deleteDocument('.$item['id'].')"><i class="fa fa-trash"></i></button>
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

<?php view('pages/documents/modals.php'); ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.8/css/alt/AdminLTE-select2.min.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
    Documents.documentsData = <?php echo json_encode($documents, JSON_HEX_TAG); ?>;
  });
</script>
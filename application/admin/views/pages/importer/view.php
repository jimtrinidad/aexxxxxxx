<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary small">

      <div class="box-header">
        <h3 class="box-title"><?php echo $pageDescription ?></h3>
        <div class="box-tools">
          <a class="btn btn-warning btn-sm" href="<?php echo site_url('importer') ?>">Back to groups</a>
        </div>
      </div>
      <!-- /.box-header -->

      <div class="box-body table-responsive no-padding">
        <table class="table table-bordered">
          <thead>
            <th style="width: 20px;">ControlNo</th>
            <th>Surname</th>
            <th>Firstname</th>
            <th>Middlename</th>
            <th>CityMuni</th>
            <th>Barangay</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Status</th>
          </thead>
          <tbody>
           <?php
              foreach ($items as $item) {
                echo "<tr class='text-left'>";
                echo '<td>' . $item['ControlNumber'] . '</td>';
                echo '<td>' . $item['Surname'] . '</td>';
                echo '<td>' . $item['Firstname'] . '</td>';
                echo '<td>' . $item['Middlename'] . '</td>';
                echo '<td>' . $item['CityMuni'] . '</td>';
                echo '<td>' . $item['Barangay'] . '</td>';
                echo '<td>' . $item['Email'] . '</td>';
                echo '<td>' . $item['MobileNumber'] . '</td>';
                echo '<td>' . lookup('data_imported_item_status', $item['status']) . '</td>';
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
    
  });
</script>
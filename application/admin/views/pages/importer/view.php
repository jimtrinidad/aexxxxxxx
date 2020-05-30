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
            <!-- <th style="width: 20px;"></th> -->
            <th>Fullname</th>
            <th>Test Facility</th>
            <th>Test Date</th>
            <th>Release Date</th>
            <th>Region</th>
            <th>Province</th>
            <th>City</th>
          </thead>
          <tbody>
           <?php
              foreach ($items as $item) {
                echo "<tr class='text-left'>";
                // echo '<td>' . $item['ctr'] . '</td>';
                echo '<td>' . $item['fullname'] . '</td>';
                echo '<td>' . $item['testing_facility'] . '</td>';
                echo '<td>' . $item['testing_date_taken'] . '</td>';
                echo '<td>' . $item['testing_date_release'] . '</td>';
                echo '<td>' . $item['region'] . '</td>';
                echo '<td>' . $item['province'] . '</td>';
                echo '<td>' . $item['city'] . '</td>';
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
<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary small">

      <div class="box-header">
        <h3 class="box-title">
          <ol class="breadcrumb" style="background: none;margin-bottom: 0;">
            <?php
              $last = end($breadcrumbs);
              foreach ($breadcrumbs as $link => $label) {
                if ($label == $last) {
                  echo "<li class='active'>{$label} > {$tableTitle}</li>";
                } else {
                  echo '<li><a href="'. $link .'"> ' . $label . ' </a></li>';
                }
              }
            ?>
          </ol>
        </h3>
        <div class="box-tools">
        </div>
      </div>
      <!-- /.box-header -->
      <div class="box-body table-responsive no-padding">
        <table class="table table-hover table-bordered">
          <thead>
            <?php 
              foreach ($fields as $key => $label) {
                echo "<th>{$label}</th>";
              }
            ?>
            <th class="c"></th>
          </thead>
          <tbody>
            <?php
              foreach ($items as $item) {
                $data_attributes = 'data-zonetype="' . $view . '"';
                foreach ($editData as $key=>$name) {
                  $data_attributes .= 'data-' . $name . '="' . ($key == 'logo' ? logo_filename($item[$key]) : $item[$key]) . '"';
                }
                echo "<tr class='text-left' {$data_attributes}>";
                  foreach ($fields as $key => $label) {
                    echo '<td>' . $item[$key] . '</td>';
                  }
                  echo '<td>
                          <div class="box-tools">
                            <div class="input-group pull-right" style="width: 10px;">
                              <div class="input-group-btn">';
                              if (isset($lowerLink)) {
                                echo '<a class="btn btn-xs btn-success" href="'. $lowerLink['url'] . $item[$lowerLink['key']]. '"><i class="fa fa-level-down"></i> ' . $lowerLink['name'] . '</a>';
                              }
                              if ($view == 'city' || $view == 'province') {
                                echo '<a class="btn btn-xs btn-info" href="'. site_url('zones/office_setup/'. $view .'/' . $item['psgcCode']) .'"><i class="fa fa-cogs"></i> Setup</a>';
                              }
                              
                              echo  '<button type="button" class="btn btn-xs btn-default" title="Update" onClick="Zones.editZone(this, '.$item['id'].')"><i class="fa fa-pencil"></i></button>
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

    </div>
  </div>
</div>

<?php view('pages/zones/modals.php'); ?>

<script type="text/javascript">
  $(document).ready(function(){
  });
</script>
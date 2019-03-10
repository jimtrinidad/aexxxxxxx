<div class="bg-grey padding-10">
   <div class="row gutter-5">
      <div class="col-xs-10 col-sm-8 text-bold text-white padding-top-10 padding-bottom-5"><?php echo $Organization->Name; ?> Projects</div>
      <div class="col-xs-2 col-sm-4 text-right">
        <button class="btn btn-sm btn-success" onclick="Coa.addProject()"><i class="fa fa-plus"></i> Add <span class="hidden-xs">Project</span></button>
      </div>
   </div>
</div>

<div class="padding-20" style="background: #e7edf0;padding-bottom: 10px;">
  <div class="row gutter-5">
    <?php
      if (count($projects)) {
        ?>
        <div class="col-xs-12">
          <div class="row gutter-5 projectcont">
          <?php foreach ($projects as $item) { ?>
            <div class="col-xs-6 col-sm-4 projectitem">
                <div class="busi-box">
                    <div class="product-info">
                      <div class="text-bold product-name"><a class="text-cyan" href="<?php echo site_url('coa/project/'.$item['Code']) ?>"><?php echo $item['Name'] ?></a></div>
                      <div class="product-desc small"><?php echo $item['Description'] ?></div>
                      <div class="product-price text-bold small offset-top-5">
                        <small class="small">
                          Target Date: <span class="text-green"><?php echo $item['TargetDate'] ?></span><br>
                          Categories: <span class="text-orange"><?php echo $item['Categories'] ?></span><br>
                          Items: <span class="text-orange"><?php echo $item['Allocations']['Count'] ?></span><br>
                          Allocation: <span class="text-orange">P <?php echo number_format($item['Allocations']['Allocation']) ?></span>
                        </small>
                      </div>
                      <div class="offset-top-10">
                        <?php
                        echo '<a href="'.site_url('coa/project/'.$item['Code']).'" class=""><i class="fa fa-search text-green"></i></a>
                              <a href="'.site_url('coa/procurementreport/'.$item['Code']).'" class=""><i class="fa fa-bar-chart text-orange"></i></a>
                              <a href="javascript:;" onclick="Coa.editProject('.$item['id'].')" class=""><i class="fa fa-pencil"></i></a>
                              <a href="javascript:;" onclick="Coa.deleteProject('.$item['id'].')" class="text-red"><i class="fa fa-trash"></i></a>';
                        ?>
                      </div>
                    </div>
                </div>
            </div>
            <?php } ?>
          </div>
        </div>
<!--         <table class="hide table table-stripped">
          <thead>
            <tr>
              <th>Name</th>
              <th>Description</th>
              <th>Scope</th>
              <th class="text-center">Categories</th>
              <th class="text-center">Items</th>
              <th class="text-center">Allocation</th>
              <th width=""></th>
            </tr>
          </thead>
          <tbody>
            <?php 
              foreach ($projects as $item) {
                echo '<tr>';
                  echo '<td><a href="'.site_url('coa/project/'.$item['Code']).'">' . $item['Name'] . '</a></td>';
                  echo '<td>' . $item['Description'] . '</td>';
                  echo '<td>' . lookup('location_scope', $item['Scope']) . '</td>';
                  echo '<td class="text-center">' . $item['Categories'] . '</td>';
                  echo '<td class="text-center">' . $item['Allocations']['Count'] . '</td>';
                  echo '<td class="text-center">P' . number_format($item['Allocations']['Allocation']) . '</td>';
                  echo '<td class="text-right">
                          <a href="'.site_url('coa/project/'.$item['Code']).'" class=""><i class="fa fa-search text-green"></i></a>
                          <a href="'.site_url('coa/procurementreport/'.$item['Code']).'" class=""><i class="fa fa-bar-chart text-orange"></i></a>
                          <a href="javascript:;" onclick="Coa.editProject('.$item['id'].')" class=""><i class="fa fa-pencil"></i></a>
                          <a href="javascript:;" onclick="Coa.deleteProject('.$item['id'].')" class="text-red"><i class="fa fa-trash"></i></a>
                        </td>';
                echo '</tr>';
              }
            ?>
          </tbody>
        </table> -->
        <?php
      } else {
        echo '<div class="col-xs-12"><h4 class="h4">No record found.</h4></div>';
      }
    ?>
  </div>
</div>

<?php view('main/coa/modals'); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.2.0/imagesloaded.pkgd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/2.2.2/isotope.pkgd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker3.min.css" />

<script type="text/javascript">
  $(document).ready(function(){
    Coa.projectData = <?php echo json_encode($projects, JSON_HEX_TAG); ?>;

    $('.projectcont').isotope({
      itemSelector : '.projectitem'
    });

    $('.datepicker').datepicker();
  });
</script>
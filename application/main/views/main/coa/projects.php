<div class="bg-grey padding-10 offset-top-10">
   <div class="row gutter-5">
      <div class="col-xs-10 col-sm-8 text-bold text-white padding-top-10 padding-bottom-5"><?php echo $Organization->Name; ?> Projects</div>
      <div class="col-xs-2 col-sm-4 text-right">
        <button class="btn btn-sm btn-success" onclick="Coa.addProject()"><i class="fa fa-plus"></i> Add <span class="hidden-xs">Project</span></button>
      </div>
   </div>
</div>

<div class="bg-white padding-10 offset-top-10 table-responsive">
  <div class="row gutter-5">
    <?php
      if (count($projects)) {
        ?>
          <table class="table table-stripped">
            <thead>
              <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Scope</th>
                <th class="text-center">Categories</th>
                <th class="text-center">Items</th>
                <th class="text-center">Allocation</th>
                <th width="65"></th>
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
                            <a href="javascript:;" onclick="Coa.editProject('.$item['id'].')" class=""><i class="fa fa-pencil"></i></a>
                            <a href="javascript:;" onclick="Coa.deleteProject('.$item['id'].')" class="text-red"><i class="fa fa-trash"></i></a>
                          </td>';
                  echo '</tr>';
                }
              ?>
            </tbody>
          </table>
        <?php
      } else {
        echo '<div class="col-xs-12"><h4 class="h4">No record found.</h4></div>';
      }
    ?>
  </div>
</div>

<?php view('modals/coa'); ?>

<script type="text/javascript">
  $(document).ready(function(){
    Coa.projectData = <?php echo json_encode($projects, JSON_HEX_TAG); ?>;
  });
</script>
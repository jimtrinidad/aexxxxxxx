<style type="text/css">
  .panel {
    border-radius: 0;
  }
  .panel-title a>span {
  	padding-top: 6px;
	display: inline-block;
	font-size: 13px;
  }
  .panel-title a:hover {
  	text-decoration: none;
  }
</style>

<div class="panel panel-default offset-top-10">
  <div class="panel-heading">
    <h3 class="panel-title text-bold"><?php echo $projectData['Name']; ?></h3>
    <a class="btn btn-danger btn-xs pull-right" style="margin-top: -18px;" href="<?php echo site_url('coa/projects') ?>" onclick="if(window.history.length > 1) {window.history.back();return false;}" >Back</a>
  </div>
  <div class="panel-body">
    <span><?php if ($projectData['Description']) {echo $projectData['Description'];} ?></span>
    <div class="padding-top-5 text-cyan small">Total Project Allocation: <b class="text-bold">P<?php echo number_format($projectData['Allocations']['Allocation']) ?></b></div>
    <hr>
    <div class="row gutter-5">
    	<div class="col-xs-9">
    		<b class="text-bold">Categories</b>
    	</div>
    	<div class="col-xs-3 text-right">
    		<button class="btn btn-xs btn-primary" onclick="Coa.addCategory('<?php echo $projectData['Code']; ?>')">Add Category</button>
    	</div>
    </div>
    <div class="row offset-top-5">
    	<div class="col-xs-12">
    		<div class="panel-group" id="accordion">
    			<?php
    			foreach ($projectData['Categories'] as $cat) {
    			?>
    				<div class="panel <?php echo ($cat['Status'] == 1 ? 'panel-info' : 'panel-danger');?>">
					    <div class="panel-heading padding-5">
					      <h4 class="panel-title">
					        <a data-toggle="collapse" data-parent="#accordion" href="#ps-<?php echo $cat['id'] ?>">
					         <img style="width:25px;height: 25px;vertical-align: middle;" src="<?php echo public_url('assets/logo/') . logo_filename($cat['Logo']); ?>">
					         <span><?php echo $cat['Name'] . ($cat['Status'] == 0 ? ' - <b class="text-bold">DISABLED</b>' : '') ?></span>
					        </a>
					        <?php if ($cat['Status'] == 1) { ?>
					        	<a href="javascript:;" class="pull-right" onclick="Coa.disableCategory('<?php echo $projectData['Code']; ?>', <?php echo $cat['id'] ?>)" style="color: red;"><i class="fa fa-trash"></i></a>
					    	<?php } else { ?>
					    		<a href="javascript:;" class="pull-right small" onclick="Coa.activateCategory('<?php echo $projectData['Code']; ?>', <?php echo $cat['id'] ?>)" style="color: green">Activate</a>
					    	<?php } ?>
					      </h4>
					    </div>
					    <div id="ps-<?php echo $cat['id'] ?>" class="panel-collapse collapse">
					      <div class="panel-body">
					      	<?php echo ($cat['Description'] ? $cat['Description'] . '<hr>' : ''); ?>
					      	<div class="row gutter-5">
						    	<div class="col-xs-9">
						    		<b class="text-bold">Items</b>
						    	</div>
						    	<div class="col-xs-3 text-right">
						    		<?php if ($cat['Status'] == 1) { ?>
						    		<button class="btn btn-xs btn-info" onclick="Coa.addCategoryItem('<?php echo $cat['id'] ?>')">Add Item</button>
						    		<?php } ?>
						    	</div>
						    </div>
						    <div class="row">
						    	<div class="col-xs-12">
						    		<div class="table-responsive">
						    			<table class="table table-condensed">
						    				<thead>
						    					<tr>
						    						<th>Name</th>
						    						<th>Description</th>
						    						<th class="text-center">Quantity</th>
						    						<th class="text-center">Allocated</th>
						    						<?php if ($cat['Status'] == 1) { echo '<th width="50"></th>'; }?>
						    					</tr>
						    				</thead>
						    				<tbody>
						    					<?php 
								                foreach ($cat['items'] as $item) {
								                  echo '<tr data-psid="'. $cat['id'] . '" data-id="'.$item['id'].'">';
								                  	echo '<td>' . $item['Name'] . '</td>';
								                    echo '<td>' . $item['Description'] . '</td>';
								                    echo '<td class="text-center">' . $item['Quantity'] . '</td>';
								                    echo '<td data-allocation="'.$item['Allocation'].'" class="text-center">P' . number_format($item['Allocation']) . '</td>';
								                    if ($cat['Status'] == 1) {
								                    	echo '<td class="text-right">
								                            <a href="javascript:;" onclick="Coa.editCategoryItem(this)" class=""><i class="fa fa-pencil"></i></a>
								                            <a href="javascript:;" onclick="Coa.deleteCategoryItem(this)" class="text-red"><i class="fa fa-trash"></i></a>
								                          </td>';
								                    }
								                  echo '</tr>';
								                }
								              ?>
						    				</tbody>
						    			</table>
						    		</div>
						    	</div>
						    </div>
					      </div>
					    </div>
					</div>
				<?php } ?>
    		</div>
    	</div>
    </div>
  </div>
</div>

<?php view('main/coa/modals'); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {

    $("#accordion").on('shown.bs.collapse', function () {
        var active = $("#accordion .in").attr('id');
        $.cookie('activeAccordionGroup', active);
    });
    $("#accordion").on('hidden.bs.collapse', function () {
        $.removeCookie('activeAccordionGroup');
    });
});

  var last = $.cookie('activeAccordionGroup');
  if (last != null) {
      //remove default collapse settings
      $("#accordion .panel-collapse").removeClass('in');
      //show the account_last visible group
      $("#" + last).addClass("in");
  }
</script>
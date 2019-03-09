<style type="text/css">
	td {vertical-align: middle !important;}
</style>
<div class="bg-grey padding-10">
   <div class="row gutter-5">
      <div class="col-xs-9 text-bold text-white padding-5">Procurements</div>
      <div class="col-xs-3 text-right">
      	<?php if ($projectSelected) { ?>
      	<a class="btn btn-xs btn-danger" href="<?php echo site_url('coa/procurement'); ?>" onclick="if(window.history.length > 1) {window.history.back();return false;}">Back</a>
      	<?php } ?>
      </div>
   </div>
</div>
<?php if (!$projectSelected) { ?>
<div class="padding-20" style="background: #e7edf0;padding-bottom: 10px;">
	<?php if (count($projects)) { ?>
	<div class="row">
		<div class="col-xs-12">
			<h3 class="h3 offset-5 text-bold text-orange text-center">Choose a project</h3>
		</div>
		<div class="col-xs-12">
			<div class="row gutter-5">
				<?php foreach ($projects as $item) { ?>
					<div class="col-xs-6 col-sm-4">
						<a href="<?php echo site_url('coa/procurement/' . $item['Code']); ?>" style="text-decoration: none;color: black">
							<div class="busi-box">
									<div class="product-info">
										<div class="text-bold product-name text-cyan"><?php echo $item['Name'] ?></div>
										<div class="product-desc small offset-top-5"><?php echo $item['Description'] ?></div>
										<div class="product-price text-bold small offset-top-5">Allocation: <span class="text-orange">P <?php echo number_format($item['Allocations']['Allocation']) ?></span></div>
									</div>
							</div>
						</a>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php } else {
		echo '<div class="row">
				<div class="col-xs-12">
					<h3 class="h4 offset-5 text-bold text-cyan text-center">No available project at the moment</h3>
				</div>
			</div>';
	} ?>
</div>
<?php } else {?>
<div class="padding-20" style="background: #e7edf0;padding-bottom: 10px;">
	<div class="row">
		<div class="col-xs-12">
			<h3 class="h3 offset-5 text-bold text-cyan text-center">Procurement Plan Selection</h3>
		</div>
	</div>
	<div class="row gutter-5 offset-top-10">
		<div class="col-xs-5 col-sm-8">
			<input type="hidden" name="projectID" id="selectedProject" value="<?php echo $projectData['id'] ?>">
			<div class="text-bold text-green"><?php echo $projectData['Name'] ?></div>
			<div class="small text-gray">
				<?php echo $projectData['Description'] ?>
			</div>
		</div>
		<div class="col-xs-7 col-sm-4 text-right small">
			<div class="row gutter-0">
				<div class="col-xs-6">Total Allocation: </div>
				<div class="col-xs-6"><b class="text-bold">P<?php echo number_format($projectData['Allocations']['Allocation'])?></b></div>
			</div>
			<div class="row gutter-0">
				<div class="col-xs-6">Target Date: </div>
				<div class="col-xs-6"><b class="text-bold"><?php echo date('F d, Y', strtotime('+1 month'))?></b></div>
			</div>
		</div>
	</div>
	<div class="row offset-top-10">
		<div class="col-xs-12">
			<div class="table-responsive bg-white">
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th style="width: 30%">Item</th>
							<th style="width: 23.3%;text-align: center;">Supplier 1</th>
							<th style="width: 23.3%;text-align: center;">Supplier 2</th>
							<th style="width: 23.3%;text-align: center;">Supplier 3</th>
						</tr>
					</thead>
					<tbody>
						<tr><td colspan="4">&nbsp;</td></tr>
						<?php 
						foreach ($projectItems as $i) {
							echo '<tr>';

								echo '<td><div class="text-bold">' . $i['Name'] . '</div></td>';

								foreach ($i['suppliers'] as $r => $sup) {
									echo '<td class="text-center supplier-'.$r.'">';

									if ($sup) {

										?>
										<div class="small" id="accno-<?php echo $sup['SupplierInfo']['AccredicationNo'] ?>">
											<div class="text-cyan"><?php echo $sup['SupplierInfo']['Company Name'] ?></div>
											<span class="text-orange"><?php echo $sup['SupplierInfo']['AccredicationNo'] ?></span>
										</div>
										<img class="img-thumbnail" style="max-width:40px;max-height: 40px;" src="<?php echo public_url('assets/logo/') . logo_filename($sup['SupplierItemInfo']['Image']); ?>">
										<div class="product-info">
											<div class="text-bold product-name"><?php echo $sup['SupplierItemInfo']['Name'] ?></div>
											<div class="product-price">
												<span class="text-bold text-orange small">P <?php echo number_format($sup['SupplierItemInfo']['Price']) ?></span> / 
												<span><?php echo $sup['SupplierItemInfo']['Measurement'] ?></span>
											</div>
											<div class="small">
												<?php echo price_savings($i['Allocation'], ($i['Quantity'] * $sup['SupplierItemInfo']['Price'])) ?>
											</div>
											<div>
												<a href="javascript:;" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?php echo $sup['SupplierItemInfo']['Description'] ?>">Description</a>
											</div>
											<div>
												<a href="javascript:;" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?php echo $sup['Remarks'] ?>">Remarks</a>
											</div>
											<div>
												<a href="javascript:;" class="findSupplier btn btn-success btn-xs" 
													data-matcher='<?php echo $i['Name'] ?>'
													data-item='<?php echo $i['id'] ?>'
													data-rank='<?php echo $r ?>'
													data-project='<?php echo $projectData['id'] ?>'>
													Change
												</a>
												<a href="javascript:;" title="Remove supplier" onclick="Coa.removeAssignedSupplier(<?php echo $sup['id'] ?>)" class="text-danger"><i class="fa fa-trash"></i></a>
											</div>
										</div>
										<?php

									} else {
										?>
										<div>
											<a href="javascript:;" class="findSupplier btn btn-warning btn-xs" 
												data-matcher='<?php echo $i['Name'] ?>'
												data-item='<?php echo $i['id'] ?>'
												data-rank='<?php echo $r ?>'
												data-project='<?php echo $projectData['id'] ?>'>
												Assign
											</a>
										</div>
										<?php 
									}

									echo '</td>';
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
<?php } ?>

<?php  view('main/coa/modals'); ?>

<script type="text/javascript">
	$(document).ready(function(){
	  $('[data-toggle="popover"]').popover(); 
	});
</script>
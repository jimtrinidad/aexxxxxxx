<style type="text/css">
	.table-report td {vertical-align: middle !important;padding: 20px 10px !important;}
	.table-report th.text-cyan {color: #089dc2 !important;}
</style>
<div class="bg-white padding-10 offset-top-10">
	<div class="row gutter-5">
		<div class="col-xs-9">
			<h2 class="h4 offset-5">
				<span class="text-orange text-bold"><?php echo $projectData->Name;?></span>
				<span class="small" style="display: block;"><?php echo $projectData->Description; ?></span>
			</h2>
		</div>
		<div class="col-xs-3 text-right offset-top-10">
			<a class="btn btn-danger btn-xs" onclick="if(window.history.length > 2) {window.history.back();return false;}" href="<?php echo site_url('coa/projects'); ?>">Back</a>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 text-center text-cyan text-bold h4">
			<div><?php echo $address['MuniCity'];?></div>
			<div><?php echo $address['Barangay'];?></div>
		</div>
	</div>
	<div class="row offset-top-10">
		<div class="col-xs-12 text-center ">
			<div class="text-cyan text-bold">
				Procurement Summary Report
			</div>
			<div class="small offset-top-5">
				Target Date of Implementation: <?php echo ($projectData->TargetDate ? date('F d, Y', strtotime($projectData->TargetDate)) : '')?>
			</div>
		</div>
	</div>

	<div class="table-report table-responsive offset-top-20">
		<table class="table table-condensed table-bordered">
			<thead>
				<tr>
					<th width="20"></th>
					<th>Item</th>
					<th>Quantity/Price</th>
					<th class="text-center">Allocated Savings</th>
					<th class="text-center text-bold text-cyan">1st Choice</th>
					<th class="text-center text-bold text-cyan">2nd Choice</th>
					<th class="text-center text-bold text-cyan">3rd Choice</th>
					<th>Remarks</th>
				</tr>
		   </thead>
		   <tbody>
		   	<?php
		   		foreach ($items as $k => $item) {
		   			echo '<tr>';
		   				echo '<td>' . ($k+1) . '</td>';
		   				echo '<td class="text-bold">' . $item['Name'] . '</td>';
		   				echo '<td>
		   							Qty: <span class="text-info">' . $item['Quantity'] . '</span><br>
		   							<span class="text-bold text-orange">₱' . number_format($item['uprice']) . '</span>
		   					  </td>';
		   				if ($item['savings'] > 0) {
		   					echo '<td class="text-center"><span class="text-green">₱' . number_format(abs($item['savings'])) . '</td>';
		   				} else if ($item['savings'] < 0) {
		   					echo '<td class="text-center"><span class="text-red">- ₱' . number_format(abs($item['savings'])) . '</td>';
		   				} else {
		   					echo '<td class="text-center">N/A</td>';
		   				}

		   				foreach ($item['suppliers'] as $sup) {
		   					if ($sup) {
		   						echo '<td class="text-center">
		   								<div class="small">
											<div class="text-cyan">' . $sup['SupplierInfo']['Company Name'] . '</div>
											<span class="text-orange">' . $sup['SupplierInfo']['AccredicationNo'] . '</span>
										</div>
										<div class="product-price">
											<span class="text-bold text-orange small">₱'. number_format($sup['SupplierItemInfo']['Price']) . '</span> / 
											<span>' . $sup['SupplierItemInfo']['Measurement'] . '</span>
										</div>
									</td>';
		   					} else {
		   						echo '<td class="text-center">N/A</td>';
		   					}
		   				}

			   			echo '<td class="small">' . '</td>';

	   				echo '<tr>';
		   		}
		   	?>
		 	</tbody>
		 	<tfoot>
		 	</tfoot>
		</table>
	</div>

	<div class="row gutter-0 small">
		<div class="col-xs-12 col-sm-4">
			<div class="row gutter-5">
				<div class="col-xs-6 small">Date of Aproval:</div>
				<div class="col-xs-6 text-bold"><?php echo date('F d, Y') ?></div>
				<div class="col-xs-6 small">Noted By:</div>
				<div class="col-xs-6 text-bold"><?php echo $owner['FirstName'] . ' ' . $owner['LastName']; ?></div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-4">
			<div class="row gutter-5">
				<div class="col-xs-6 small">Approve By: </div>
				<div class="col-xs-6 text-bold">&nbsp;</div>
				<div class="col-xs-6 small">Received By: </div>
				<div class="col-xs-6 text-bold">&nbsp;</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-4">
			<div class="row gutter-5">
				<div class="col-xs-6 small">Allocation:</div>
				<div class="col-xs-6 text-bold">₱<?php echo number_format($pAllocation['Allocation']) ?></div>
				<div class="col-xs-6 small">Total Amount:</div>
				<div class="col-xs-6 text-bold">₱<?php echo number_format($totalAmount) ?></div>
				<?php 
				$totalSavings = $pAllocation['Allocation'] - $totalAmount;
				if ($totalSavings >= 0) {
					echo '<div class="col-xs-6 small">Total Savings:</div><div class="col-xs-6 text-bold text-green">₱' . number_format(abs($totalSavings)) . '</div>';
				} else {
					echo '<div class="col-xs-6 small">Total Savings:</div><div class="col-xs-6 text-bold"><span class="text-red">- ₱' . number_format(abs($totalSavings)) . '</span></div>';
				}
				?>
			</div>
		</div>
	</div>

</div>
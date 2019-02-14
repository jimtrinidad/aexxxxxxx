<?php 
view('reports/organization/navigation');
$cur = date('Y');
$sel = get_post('year') ?? $cur;
$min = $cur - 5;
$max = $cur + 5;
?>


<div class="bg-white padding-10 offset-top-10">
	<div class="row gutter-5">
		<div class="col-xs-11">
			<h2 class="h4 offset-5">
				<?php echo $serviceData->Name . ' (' . $orgData->MenuName . ')';?>
				<span class="small" style="display: block;"><?php echo $serviceData->Description; ?></span>
			</h2>			
			<div class="offset-left-5 text-bold"><?php echo lookup('months', $this->uri->segment(4)) . ' ' . $this->uri->segment(3); ?></div>
		</div>
		<div class="col-xs-1 text-right offset-top-10">
			<a class="btn btn-danger btn-xs" onclick="if(window.history.length > 2) {window.history.back();return false;}" href="<?php echo site_url('organization/collectionreport/?year=' . $this->uri->segment(3)) ?>">Back</a>
		</div>
	</div>

	<?php
	if (!count($records)) {
		echo '<h3 class="h3">No record found.</h3>';
	} else {
	?>
	<div class="table-report table-responsive offset-top-10">
		<table class="table table-condensed table-bordered">
			<thead>
		      	<tr class="text-bold">
			        <th width="30">No</th>
			        <th style="max-width: 80px;">Report #</th>
			        <?php
			        	foreach ($fields as $k => $l) {
			        		echo '<th style="max-width: 200px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">' . $l . '</th>';
			        	}
			        ?>
			        <th class="text-center">Amount</th>
			        <th class="text-center">Date</th>
			        <th class="text-center">Officer</th>
			        <th class="text-center" width="20"></th>
		   		</tr>
		   </thead>
		   <tbody>
		      <?php
		      $i = 1;
		      $total_amount = 0;
		      foreach ($records as $item) {
		      	$item_total = 0;
		      	$extrafields = json_decode($item['ExtraFields'], true);
		      	$amount = $item['Total'];
		      	$total_amount += $amount;
		      	echo '<tr>';
		      		echo '<td>' . $i . '</td>';
		      		echo '<td>' . $item['appCode'] . '</td>';

		      		foreach ($fields as $k => $l) {
		      			echo '<td>' . ($extrafields[$k] ?? '') . '</td>';
		      		}

					echo '<td class="text-center">' . ($amount ? number_format($amount) : '') . '</td>';
		      		echo '<td class="text-center">' . date('m/d/y', strtotime($item['DateApplied'])) . '</td>';
		      		echo '<td class="text-center">' . substr($item['FirstName'], 0, 1) . '. ' . $item['LastName'] . '</td>';
		      		echo '<td class="text-center"><a href="javascript:;" onClick="Quickserve.paymentReceipt('.$item['PaymentID'].');">Receipt</a></td>';

		      	echo '</tr>';
		      	$i++;
		      }
		      ?>
		 	</tbody>
		 	<tfoot>
		 		<tr>
		 			<td colspan="<?php echo 6 + count($fields); ?>"></td>
		 		</tr>
		 		<tr>
		 			<th colspan="<?php echo 2 + count($fields); ?>"" class="text-bold text-right">Total</th>
			        <td class="text-center text-bold"><?php echo number_format($total_amount) ?></td>
			        <th colspan="3"></th>
		 		</tr>
		 	</tfoot>
		</table>
	</div>
	<?php } ?>
</div>

<?php view('modals/quickserve') ?>
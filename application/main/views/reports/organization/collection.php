<?php 
view('reports/organization/navigation');
$cur = date('Y');
$sel = get_post('year') ?? $cur;
$min = $cur - 5;
$max = $cur + 5;
?>


<div class="bg-white padding-10 offset-top-10">
	<div class="row gutter-5">
		<div class="col-xs-12 col-sm-10">
			<h2 class="h2 offset-5 offset-top-10">
				Collection Report <?php echo $sel?>
			</h2>
			<div class="offset-left-10">As of <?php echo date('F d, Y'); ?></div>
		</div>
		<div class="col-xs-12 col-sm-2 offset-top-5">
			<div class="form-group" style="padding: 0 7px">
				<label>Year</label>
				<select class="form-control" onchange="window.location = window.public_url('organization/collectionreport?year=' + this.value)">
					<?php
					while ($min < $max) {
						echo '<option '.($min == $sel ? 'selected' : '').' >'.$min.'</option>';
						$min++;
					}
					?>
				</select>
			</div>
		</div>
	</div>
	<?php
	if (!count($reportData['items'])) {
		echo '<h3 class="h3">No record found.</h3>';
	} else {
	?>
	<div class="table-report table-responsive offset-top-10">
		<table class="table table-condensed table-bordered">
		    <thead>
		      <tr class="text-bold">
		        <th width="30">No</th>
		        <th>Violation</th>
		        <?php
		        	foreach ($reportData['monthly_total_amount'] as $k => $t) {
		        		echo '<th class="text-center" style="min-width: 50px;">' . substr(lookup('months', $k), 0, 3) . '</th>';
		        	}
		        ?>
		        <th class="text-center">Total</th>
		      </tr>
		    </thead>
		    <tbody>
		      <?php
		      $i = 1;
		      foreach ($reportData['items'] as $k => $item) {
		      	$item_total = 0;
		      	echo '<tr>';
		      		echo '<td>' . $i . '</td>';
		      		echo '<td>' . $item['CommonName'] . '</td>';

		      		foreach ($reportData['monthly_total_amount'] as $m => $t) {
		      			$tt = 0;
		      			if (isset($reportData['per_month_amount'][$k][$m])) {
		      				$tt = $reportData['per_month_amount'][$k][$m];
		      			}
		      			echo '<td class="text-center">' . ($tt ? '<a href="'.site_url("organization/collectiondetails/$sel/$m/{$item['Code']}").'">₱' . number_format($tt) . '</a>' : '') . '</td>';
		      			$item_total += $tt;
		      		}

		      		echo '<td class="text-center">₱' . number_format($item_total) . '</td>';

		      	echo '</tr>';
		      	$i++;
		      }
		      ?>
		 	</tbody>
		 	<tfoot>
		 		<tr>
		 			<td colspan="<?php echo 3 + count($reportData['monthly_total_amount']); ?>"></td>
		 		</tr>
		 		<tr>
		 			<th></th>
		 			<th><b class="text-bold">Totals</b></th>
		 			<?php
		 				$total = 0;
			        	foreach ($reportData['monthly_total_amount'] as $k => $t) {
			        		$total += $t;
			        		echo '<th class="text-center">₱' . number_format($t) . '</th>';
			        	}
			        ?>
			        <td class="text-center text-bold">₱<?php echo number_format($total) ?></td>
		 		</tr>
		 	</tfoot>
		</table>
	</div>
	<?php } ?>
</div>
<?php 
view('reports/cttmo/navigation');
?>


<div class="bg-white padding-10 offset-top-10">
	<div class="row gutter-5">
		<div class="col-xs-12 col-sm-9">
			<h2 class="h2 offset-5 offset-top-10">
				Daily Violations Report
				<div class="small offset-top-5"><?php echo str_replace(' - ', ' to ', $date); ?></div>
			</h2>
		</div>
		<div class="col-xs-12 col-sm-3 offset-top-5">
			<div class="form-group" style="padding: 0 7px">
				<div class="form-group">
					<label>Date</label>
					<input type="text" name="date" class="form-control daterangeinput" value="<?php echo $date; ?>" onchange="">
				</div>
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
		        	foreach ($reportData['daily_total'] as $k => $t) {
		        		echo '<th class="text-center" style="min-width: 50px;">' . date('M d', strtotime($k)) . '</th>';
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

		      		foreach ($reportData['daily_total'] as $m => $t) {
		      			$tt = 0;
		      			if (isset($reportData['per_day_count'][$k][$m])) {
		      				$tt = $reportData['per_day_count'][$k][$m];
		      			}
		      			echo '<td class="text-center">' . ($tt ? number_format($tt) : '') . '</td>';
		      			$item_total += $tt;
		      		}

		      		echo '<td class="text-center">' . number_format($item_total) . '</td>';

		      	echo '</tr>';
		      	$i++;
		      }
		      ?>
		 	</tbody>
		 	<tfoot>
		 		<tr>
		 			<td colspan="<?php echo 3 + count($reportData['daily_total']); ?>"></td>
		 		</tr>
		 		<tr>
		 			<th></th>
		 			<th><b class="text-bold">Totals</b></th>
		 			<?php
		 				$total = 0;
			        	foreach ($reportData['daily_total'] as $k => $t) {
			        		$total += $t;
			        		echo '<th class="text-center">' . number_format($t) . '</th>';
			        	}
			        ?>
			        <td class="text-center text-bold"><?php echo number_format($total) ?></td>
		 		</tr>
		 	</tfoot>
		</table>
	</div>
	<?php } ?>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.3/daterangepicker.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.3/daterangepicker.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.daterangeinput').daterangepicker({
			maxDate: moment(),
			autoApply: true,
			autoUpdateInput: true,
			locale: {
		      format: 'YYYY-MM-DD'
		    },
		    opens: "left",
		    ranges: {
		        'Today': [moment(), moment()],
		        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
		        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
		        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
		        'This Month': [moment().startOf('month'), moment().endOf('month')],
		        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		    }
		}).on('apply.daterangepicker', function(ev, picker) {
			window.location = window.public_url('cttmo/dailyvreports?date=' + $(this).val());
		});
	});
</script>
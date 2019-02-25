<?php 
view('reports/cttmo/navigation');
$cur = date('Y');
$sel = get_post('year') ?? $cur;
$min = $cur - 5;
$max = $cur + 5;
$view = get_post('v');
?>


<div class="bg-white padding-10 offset-top-10">
	<div class="row gutter-5">
		<div class="col-xs-12 col-sm-8">
			<h2 class="h2 offset-5 offset-top-10">
				Yearly <?php echo ($view == 1 ? 'Apprehended' : 'Collection') ?> Report <?php echo $sel?>
			</h2>
			<div class="offset-left-10">As of <?php echo date('F d, Y'); ?></div>
		</div>
		<div class="col-xs-6 col-sm-2 offset-top-5">
			<div class="form-group">
				<label>View</label>
				<select class="form-control" onchange="window.location = window.public_url('cttmo/yearlyreports?year=<?php echo $sel;?>&v=' + this.value)">
					<option value="2" <?php echo ($view == 2 ? 'selected' : '');?>>Collection</option>
					<option value="1" <?php echo ($view == 1 ? 'selected' : '');?>>Count</option>
				</select>
			</div>
		</div>
		<div class="col-xs-6 col-sm-2 offset-top-5">
			<div class="form-group">
				<label>Year</label>
				<select class="form-control" onchange="window.location = window.public_url('cttmo/yearlyreports?year=' + this.value + '&v=<?php echo $view;?>')">
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
	if (!count($categories)) {
		echo '<h3 class="h3">No record found.</h3>';
	} 
	foreach ($categories as $category => $items) { 
	?>
	<div class="table-report table-responsive offset-top-10">
		<h3 class="h4 text-center">
			"<?php echo lookup('service_cttmo_category', $category) ?>"
		</h3>
		<table class="table table-condensed table-bordered rtable">
		    <thead>
		      <tr class="text-bold">
		      	<th>MONTH</th>
		      	<th class="text-center">APPREHENDED</th>
		      	<th class="text-center">SETTLED</th>
		      	<th class="text-center">UNSETTLED</th>
		      	<th class="text-center">CANCELED</th>
		      </tr>
		    </thead>
		    <tbody>
		      <?php
		      $at = $ct = $pt = $dt = 0;
		      foreach ($items as $item) {

		      	$completed = ($view == 1 ? $item['completed'] : $item['completedAmount']);
		      	$pending   = ($view == 1 ? $item['pending'] : $item['pendingAmount']);

		      	$prefix = ($view != 1 ? '₱' : '');

		      	$at += $item['application'];
		      	$ct += $completed;
		      	$pt += $pending;
		      	$dt += $item['canceled'];
		      	echo '<tr>';
		      		echo '<td>' . lookup('months', $item['month']) . '</td>';
		      		echo '<td class="text-center">' . number_format($item['application']) . '</td>';
		      		echo '<td class="text-center">' . $prefix . number_format($completed) . '</td>';
		      		echo '<td class="text-center">' . $prefix . number_format($pending) . '</td>';
		      		echo '<td class="text-center">' . number_format($item['canceled']) . '</td>';
		      	echo '</tr>';
		      }
		      ?>
		 	</tbody>
		 	<tfoot>
		 		<tr>
		 			<td colspan="5"></td>
		 		</tr>
		 		<tr>
		 			<th class="text-bold">Total</th>
		 			<th class="text-center text-bold"><?php echo number_format($at) ?></th>
		 			<th class="text-center"><?php echo  $prefix . number_format($ct) ?></th>
		 			<th class="text-center"><?php echo  $prefix . number_format($pt) ?></th>
		 			<th class="text-center"><?php echo number_format($dt) ?></th>
		 		</tr>
		 	</tfoot>
		</table>
	</div>
	<?php } ?>
</div>
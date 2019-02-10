<?php 
view('reports/organization/navigation');
$cur = date('Y');
$sel = get_post('year') ?? $cur;
$min = $cur - 5;
$max = $cur + 5;
?>


<div class="bg-white padding-10 offset-top-10">
	<div class="row gutter-5 offset-top-5">
		<div class="col-xs-12 col-sm-10">
			<h2 class="h2 offset-5 offset-top-10">
				Yearly Report <?php echo $sel?>
			</h2>
			<div class="offset-left-10">As of <?php echo date('F d, Y'); ?></div>
		</div>
		<div class="col-xs-12 col-sm-2">
			<label>Year</label>
			<div class="form-group">
				<select class="form-control" onchange="window.location = window.public_url('organization/yearlyreports?year=' + this.value)">
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
	<div class="table-report table-responsive">
		<h3 class="h4 text-center">
			"<?php echo lookup('service_organization_category', $category) ?>"
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
		      	$at += $item['application'];
		      	$ct += $item['completed'];
		      	$pt += $item['pending'];
		      	$dt += $item['canceled'];
		      	echo '<tr>';
		      		echo '<td>' . lookup('months', $item['month']) . '</td>';
		      		echo '<td class="text-center">' . number_format($item['application']) . '</td>';
		      		echo '<td class="text-center">' . number_format($item['completed']) . '</td>';
		      		echo '<td class="text-center">' . number_format($item['pending']) . '</td>';
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
		 			<th class="text-center"><?php echo number_format($ct) ?></th>
		 			<th class="text-center"><?php echo number_format($pt) ?></th>
		 			<th class="text-center"><?php echo number_format($dt) ?></th>
		 		</tr>
		 	</tfoot>
		</table>
	</div>
	<?php } ?>
</div>
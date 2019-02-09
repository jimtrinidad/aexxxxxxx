<?php view('reports/organization/navigation'); ?>


<div class="table-report table-responsive bg-white padding-10 offset-top-10">
	<h2 class="h2 offset-top-5">Monthly violation reports</h2>
	<table class="table table-condensed table-bordered">
	    <thead>
	      <tr class="text-bold">
	        <th width="30">No</th>
	        <th>Violation</th>
	        <?php
	        	foreach ($reportData['monthly_total'] as $k => $t) {
	        		echo '<th class="text-center">' . substr(lookup('months', $k), 0, 3) . '</th>';
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

	      		foreach ($reportData['monthly_total'] as $m => $t) {
	      			$tt = 0;
	      			if (isset($reportData['per_month_count'][$k][$m])) {
	      				$tt = $reportData['per_month_count'][$k][$m];
	      			}
	      			echo '<td class="text-center">' . ($tt ? $tt : '') . '</td>';
	      			$item_total += $tt;
	      		}

	      		echo '<td class="text-center">' . $item_total . '</td>';

	      	echo '</tr>';
	      	$i++;
	      }
	      ?>
	 	</tbody>
	 	<tfoot>
	 		<tr>
	 			<td></td>
	 			<th><b class="text-bold">Totals</b></th>
	 			<?php
	 				$total = 0;
		        	foreach ($reportData['monthly_total'] as $k => $t) {
		        		$total += $t;
		        		echo '<th class="text-center">' . $t . '</th>';
		        	}
		        ?>
		        <td class="text-center text-bold"><?php echo $total ?></td>
	 		</tr>
	 	</tfoot>
	 </table>
</div>
<?php 
view('reports/organization/navigation');
?>


<div class="bg-white padding-10 offset-top-10">
	<form>
		<div class="row gutter-5">
			<div class="col-xs-12 col-sm-6 text-center">
				<h2 class="h2 offset-5 offset-top-10">
					<?php echo '<div class="h3 offset-5">' . lookup('service_organization_category', $category) . '</div>Daily Apprehension Report<div class="small offset-top-5">' . str_replace(' - ', ' to ', $date) . '</div>'; ?>
				</h2>
			</div>
			<div class="col-xs-12 col-sm-6">
				<div class="row gutter-5">
					<div class="hidden-xs offset-top-20"></div>
					<div class="col-xs-5 offset-top-5">
						<div class="form-group">
							<label>Category</label>
							<select class="form-control" name="category">
								 <?php
				                foreach (lookup('service_organization_category') as $k => $v) {
				                    echo "<option ".($k == $category ? 'selected' : '')." value='{$k}'>{$v}</option>";
				                }
				                ?>
							</select>
						</div>
					</div>
					<div class="col-xs-5 offset-top-5">
						<div class="form-group">
							<label>Date</label>
							<input type="text" name="date" class="form-control daterangeinput" value="<?php echo $date; ?>">
						</div>
					</div>
					<div class="col-xs-2 offset-top-5">
						<div class="form-group">
							<label>&nbsp;</label>
							<button type="submit" name="find" class="form-control btn btn-success">Find</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	<?php

	if (!count($records)) {
		echo '<h3 class="h3 text-center">No record found.</h3>';
	} else {
	?>
	<div class="table-report table-responsive offset-top-10">
		<table class="table table-condensed table-bordered">
		    <thead>
		      <tr class="text-bold">
		        <th width="30">No</th>
		        <th style="max-width: 80px;">Report #</th>
		        <th>Violation</th>
		        <?php
		        	foreach ($fields as $k => $l) {
		        		echo '<th style="max-width: 200px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">' . $l . '</th>';
		        	}
		        ?>
		        <th class="text-center">Date</th>
		        <th class="text-center">Officer</th>
		      </tr>
		    </thead>
		    <tbody>
		      <?php
		      $i = 1;
		      foreach ($records as $item) {
		      	$item_total = 0;
		      	echo '<tr>';
		      		echo '<td>' . $i . '</td>';
		      		echo '<td>' . $item['reportid'] . '</td>';
		      		echo '<td>' . $item['MenuName'] . '</td>';

		      		foreach ($fields as $k => $l) {
		      			echo '<td>' . ($item[$k] ?? '') . '</td>';
		      		}

		      		echo '<td class="text-center">' . date('m/d/y', strtotime($item['dateapplied'])) . '</td>';
		      		echo '<td class="text-center">' . substr($item['FirstName'], 0, 1) . '. ' . $item['LastName'] . '</td>';

		      	echo '</tr>';
		      	$i++;
		      }
		      ?>
		 	</tbody>
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
		    }
		});
	});
</script>
<!-- Filter Services-->
<div class="bg-grey padding-top-10 padding-left-10 padding-right-10 offset-bottom-10">
	<div class="row gutter-5">
		<form id="service_summary_search_form">
			<div class="col-sm-5 text-bold text-white padding-top-10 padding-bottom-5">Service Summary Report</div>
			<div class="col-sm-3 padding-bottom-5">
				<select id="service_category_selector" class="form-control" name="service_category">
					<option value="">Service Category</option>
					<!-- <option value="null">Uncategorized</option> -->
					<?php 
						foreach (lookup('service_categories') as $k => $v) {
							echo '<option value="'.$k.'">' . $v . '</option>';
						}
					?>
				</select>
			</div>
			<div class="col-sm-3 padding-bottom-10">
				<input type="text" class="form-control daterangeinput" name="service_date_range" id="service_range_date" placeholder="Date">
			</div>
			<div class="col-sm-1 padding-bottom-10">
				<button type="submit" id="service_summary_search_button" class="btn btn-success">Search</button>
			</div>
		</form>
	</div>
</div>
<!-- Government Table Report -->
<div class="table-report table-responsive bg-white padding-10" id="gov_performance_report_cont">
	<table class="table">
		<thead>
			<tr>
				<th>Service Name</th>
				<th>Services</th>
				<th>Transactions</th>
				<th>Processed</th>
				<th>Approved</th>
				<th>Denied</th>
				<th>Pending</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-treegrid/0.2.0/css/jquery.treegrid.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-treegrid/0.2.0/js/jquery.treegrid.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-treegrid/0.2.0/js/jquery.treegrid.bootstrap3.min.js"></script>


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
		});
	});

    /**
    * get provice gov report
    */
    $('#service_summary_search_form').submit(function(e){
    	e.preventDefault();
        Mgovph.get_service_summary_report(this);
    });

</script>
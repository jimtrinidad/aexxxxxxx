<!-- Filter Services-->
<div class="bg-grey padding-top-10 padding-left-10 padding-right-10 offset-bottom-10">
	<div class="row">
		<div class="col-sm-6 text-bold text-white padding-top-10 padding-bottom-5">Government Performance Report</div>
		<div class="col-sm-3 padding-bottom-5">
			<select id="gov_report_region_selector" class="form-control">
				<option value="">Select Region</option>
				<?php 
					foreach (lookup_all('UtilLocRegion', false, 'regDesc', false) as $item) {
						echo '<option value="'.$item['regCode'].'" '. ($accountInfo->RegionalID == $item['regCode'] ? 'selected="selected"' : '') .'>' . $item['regDesc'] . '</option>';
					}
				?>
			</select>
		</div>
		<div class="col-sm-3 padding-bottom-10">
			<select id="gov_report_province_selector" disabled="disabled" class="form-control">
				<option value="">Select Province</option>
			</select>
		</div>
	</div>
</div>
<!-- Government Table Report -->
<div class="table-report table-responsive bg-white padding-10" id="gov_performance_report_cont">
	<table class="table">
		<thead>
			<tr>
				<th>Province</th>
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

<script type="text/javascript">
$(document).ready(function(){

	Mgovph.set_gov_report_province_selector('#gov_report_region_selector', function(){
		$('#gov_report_province_selector').val('<?php echo $accountInfo->ProvincialID ?>').change();
	});

    /* load provinces
    */
    $('#gov_report_region_selector').change(function(){
        Mgovph.set_gov_report_province_selector(this);
    });

    /**
    * get provice gov report
    */
    $('#gov_report_province_selector').change(function(){
        Mgovph.get_gov_province_performance_report(this);
    });

});
</script>
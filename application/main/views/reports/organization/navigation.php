<style type="text/css">
	.report-nav ul li a {
		color: orange;
	}
	.report-nav ul li a:hover,.report-nav ul li a.active {
		color: white;
	}
	.rtable td {padding: 3px 5px !important;}
</style>
<div class="bg-grey seconday-nav report-nav offset-top-10" style="padding: 7px;">
  <ul>
    <!-- <li><a href="javascript:;">Daily Apprehension</a></li> -->
    <!-- <li><a href="javascript:;">Accident Statistics</a></li> -->
    <li><a href="<?php echo site_url('organization/monthlyreports'); ?>" class="<?php echo current_method() == 'monthlyreports' ? 'active' : '';?>">Violation Report</a></li>
    <li><a href="<?php echo site_url('organization/yearlyreports'); ?>" class="<?php echo current_method() == 'yearlyreports' ? 'active' : '';?>">Yearly Reports</a></li>
  </ul>
</div>
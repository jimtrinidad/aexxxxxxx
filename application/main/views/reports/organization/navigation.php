<style type="text/css">
	.report-nav ul li a {
		color: orange;
	}
	.report-nav ul li a:hover,.report-nav ul li a.active {
		color: white;
	}
	.rtable td {padding: 3px 5px !important;}
</style>
<div class="bg-grey seconday-nav report-nav offset-top-10" style="padding: 7px;overflow: visible;">
  <ul>
    <li><a href="<?php echo site_url('organization/dailyreport'); ?>" class="<?php echo current_method() == 'dailyreport' ? 'active' : '';?>">Apprehension Report</a></li>
    <!-- <li><a href="javascript:;">Accident Statistics</a></li> -->
    <!-- <li><a href="<?php echo site_url('organization/monthlyvreports'); ?>" class="<?php echo current_method() == 'monthlyvreports' ? 'active' : '';?>">Violation Report</a></li> -->
    <li>
    	<div class="dropdown">
		  <a class="dropbtn <?php echo in_array(current_method(), array('monthlyvreports', 'dailyvreports')) ? 'active' : '';?>" href="javascript:;">Violation Report</a>
		  <div class="dropdown-content">
		    <a href="<?php echo site_url('organization/monthlyvreports'); ?>">Monthly Violation</a>
		    <a href="<?php echo site_url('organization/dailyvreports'); ?>">Daily Violation</a>
		  </div>
		</div>
	</li>
    <li><a href="<?php echo site_url('organization/yearlyreports'); ?>" class="<?php echo current_method() == 'yearlyreports' ? 'active' : '';?>">Yearly Reports</a></li>
    <li><a href="<?php echo site_url('organization/collectionreport'); ?>" class="<?php echo in_array(current_method(), array('collectionreport', 'collectiondetails')) ? 'active' : '';?>">Collection Report</a></li>
  </ul>
</div>
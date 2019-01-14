<style type="text/css">

	.dashboard-head {
		background: url('<?php echo public_url(); ?>resources/images/admin/dashboard-bg.png') repeat;
		margin-top: -15px;
		margin-bottom: 20px;
    	padding: 20px 0;
	}

	.dashboard-content {
		margin-top: 150px;
	}

	.style4 {min-height: 190px;margin-bottom: 20px;margin-top: 20px;}
	.style5 {width: 80px;height: 80px;}
	.style6 {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 24px; color: #FF9900; padding: 0;line-height: 1.2}
	.style9 {font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #FFFFFF; text-align: center;margin: 0 auto;padding: 10px;}

	/*small*/
	@media (max-width: 768px) {
	  	.dashboard-content {
			margin-top: 50px;
		}
		.style4 {min-height: 0;}
	}
</style>

<div class="row dashboard-head">
	<div class="col-xs-6">
		<img style="width: 100%;max-width: 550px;" src="<?php echo public_url(); ?>resources/images/admin/dashboard-logo.png"/>
	</div>
	<div class="col-xs-6 text-right">
		<img style="width: 100%;max-width: 200px;" src="<?php echo public_url(); ?>resources/images/admin/dashboard-name.png"/>
	</div>
</div>

<div class="row dashboard-content">
	<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 text-center">
		<img src="<?php echo public_url(); ?>resources/images/admin/icon-accounts.png" class="style5">
		<div class="style4">
			<div class="style6">Accounts</div>
			<div class="style9">
				# Citizens<br>
				# Public Servants<br>
				# Unique Emails<br>
				# Online Now<br>
				# Chatting Now
			</div>
		</div>
	</div>
	<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 text-center">
		<img src="<?php echo public_url(); ?>resources/images/admin/icon-gov.png" class="style5">
		<div class="style4">
			<div class="style6">Government Performance</div>
			<div class="style9">
				# Services<br>
				# Services Rendered All Time<br>
				# Services Rendered Today
			</div>
		</div>
	</div>
	<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 text-center">
		<img src="<?php echo public_url(); ?>resources/images/admin/icon-services.png" class="style5">
		<div class="style4">
			<div class="style6">Service Status</div>
			<div class="style9">
				# Service Request<br>
				# Transactions<br>
				# Pending<br>
				# Approve<br>
				# Denied
			</div>
		</div>
	</div>
	<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 text-center">
		<img src="<?php echo public_url(); ?>resources/images/admin/icon-manage.png" class="style5">
		<div class="style4">
			<div class="style6">Manage Information</div>
			<div class="style9">
				# Zone Banners<br>
				# Announcements<br>
				# Events<br>
				# May Trabaho<br>
				# May Negosyo
			</div>
		</div>
	</div>
	<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 text-center">
		<img src="<?php echo public_url(); ?>resources/images/admin/icon-server.png" class="style5">
		<div class="style4">
			<div class="style6">Server Information</div>
			<div class="style9">
				# Total Webspace<br>
				# Used<br>
				# Available<br>
				# Total Bandwidth<br>
				# Used<br>
				# Available
			</div>
		</div>
	</div>
	<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 text-center">
		<img src="<?php echo public_url(); ?>resources/images/admin/icon-terms.png" class="style5">
		<div class="style4">
			<div class="style6">Terms</div>
			<div class="style9">
				# Privacy Policy<br>
				# Terms and Condition<br>
				# Disclaimer<br>
				# Copyright
			</div>
		</div>
	</div>
</div>
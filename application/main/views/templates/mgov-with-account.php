<!DOCTYPE html>
<html lang="en" class="bg-dark js no-touch no-android chrome no-firefox no-iemobile no-ie no-ie10 no-ie11 no-ios no-ios7 ipad">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<link rel="shortcut icon" href="<?php echo public_url(); ?>resources/images/favicon.ico" type="image/x-icon">
		<link rel="icon" href="<?php echo public_url(); ?>resources/images/favicon.ico" type="image/x-icon">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php echo TITLE_PREFIX . $pageTitle ?></title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css" />

		<link rel="stylesheet" href="<?php echo public_url(); ?>resources/css/style.css" rel="stylesheet">
		<link rel="stylesheet" href="<?php echo public_url(); ?>resources/css/site.css" type="text/css"> 

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	</head>
	<body>

		<!-- HEADER -->
		<div id="header-wrapper">
			<?php view('templates/mgov_header');?>
		</div>
		<!-- HEADER END -->		

		<!-- MAIN CONTENT -->
		<div id="main-wrapper" class="wrapper">
	        <div class="row padding-top-15 padding-bottom-20 gutter-5">

	        	<!-- My Account Profile -->
				<div class="col-md-3 visible-lg visible-md col-xs-6">
					<div class="text-center padding-bottom-10">
						<strong class="text-white text-bold"><?php echo user_full_name($accountInfo, 1); ?></strong>
					</div>
					<!-- Profile Menu-->
					<div class="profile-img">
						<img style="max-width: 234px;margin: 0 auto;" src="<?php echo public_url(); ?>assets/profile/<?php echo $accountInfo->Photo ?>" class="img-responsive"/>
						<button class="btn btn-sm bg-green text-white btn-block offset-top-10">UPDATE PROFILE</button>
					</div>
					
					<!-- Profile Data -->
					<div class="padding-top-10 text-white">
						<p class="lh-20"><?php echo user_full_address($accountInfo, true, true) ?></p>
						<p class="text-bold offset-top-10 lh-20"><?php echo lookup('education', $accountInfo->EducationalAttainmentID) ?></p>
						<p class="text-bold lh-20"><?php echo $accountInfo->EmailAddress ?></p>
						<p class="text-bold lh-20"><?php echo $accountInfo->ContactNumber ?></p>
					</div>

					<div class="padding-top-10">
						<img src="<?php echo public_url() . 'assets/qr/' . $accountInfo->QR ?>" style="width: 100%;max-width: 60px;background: white;">
					</div>
					
					<!-- Mak ID -->
					<div>
						<!-- <img src="<?php echo public_url(); ?>resources/images/mak-id-2.png" class="img-responsive" /> -->
					</div>
					
				</div>
				<!-- END ACCOUNT -->

	        	<!-- SUB CONTENT -->
	            <div class="col-md-9 col-sm-12 col-xs-12 padding-bottom-10">

	            	<!-- NAVIGATION -->
	            	<?php view('templates/mgov_navigation');?>
	            	<!-- END NAVIGATION -->

	            	<!-- PAGE VIEW CONTENT -->
	            	<?php echo $templateContent;?>
	            	<!-- END PAGE VIEW CONTENT -->

	            </div>
	            <!-- END SUB CONTENT -->

	        </div>
	    </div>
	    <!-- END MAIN CONTENT -->

	    <!-- FOOTER -->
	    <div id="footer-wrapper">
	    	<?php view('templates/mgov_footer');?>
	    </div>
	    <!-- END FOOTER -->

	    <?php view('templates/mabuhay_id'); ?>
	    <?php view('snippets/chat'); ?>
	    <?php view('modals/changepassword'); ?>

	</body>

	<?php view('templates/js_constants'); ?>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-loading-overlay/2.1.6/loadingoverlay.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>

	<script type="text/javascript" src="<?php echo public_url(); ?>resources/js/scripts.js?<?php echo time()?>"></script>
	<script type="text/javascript" src="<?php echo public_url(); ?>resources/js/modules/utils.js?<?php echo time()?>"></script>
	<script type="text/javascript" src="<?php echo public_url(); ?>resources/js/modules/mgovph.js?<?php echo time()?>"></script>
	<script type="text/javascript" src="<?php echo public_url(); ?>resources/js/modules/account.js?<?php echo time()?>"></script>

	<script type="text/javascript">
		$(document).ready(function(){
			bootbox.setDefaults({
				size: 'small'
			});
		});
	</script>

</html>
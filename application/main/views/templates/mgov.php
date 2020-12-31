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
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css" />

		<link rel="stylesheet" href="<?php echo public_url(); ?>resources/css/style.css?<?php echo time()?>" rel="stylesheet">
		<link rel="stylesheet" href="<?php echo public_url(); ?>resources/css/site.css?<?php echo time()?>" type="text/css"> 

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	</head>
	<body>

		<!-- HEADER -->
		<div id="header-wrapper">
			<?php view('templates/mgov_header');?>
		</div>
		<!-- HEADER END -->

		<!-- MAIN CONTENT -->
		<div id="main-wrapper" class="wrapper <?php echo (isset($fullwidth) && $fullwidth) ? 'big-wrapper' : '' ?>">
	        <div class="row padding-top-15 padding-bottom-20 gutter-5">

	        	<?php if (isset($nosidebar) && $nosidebar): ?>
	        		<div class="col-xs-12 padding-bottom-20">

	        			<!-- NAVIGATION -->
	        			<?php if (isset($shownav) && $shownav) {
		            		view('templates/mgov_navigation');
		            	}
		            	?>
		            	<!-- END NAVIGATION -->


		        		<!-- PAGE VIEW CONTENT -->
		            	<?php echo $templateContent;?>
		            	<!-- END PAGE VIEW CONTENT -->
		            </div>
	            <?php else: ?>

		        	<!-- SUB CONTENT -->
		            <div class="col-md-8 col-xs-12 padding-bottom-10">

		            	<!-- NAVIGATION -->
		            	<?php view('templates/mgov_navigation');?>
		            	<!-- END NAVIGATION -->

		            	<!-- PAGE VIEW CONTENT -->
		            	<?php echo $templateContent;?>
		            	<!-- END PAGE VIEW CONTENT -->

		            </div>
		            <!-- END SUB CONTENT -->

		            <!-- SIDE BAR -->
		            <div class="col-md-4 col-xs-12 side-bar">

		            	<?php view('main/right_sidebar');?>

		            </div>
		            <!-- END SIDE BAR -->

	        	<?php endif ?>

	        </div>
	    </div>
	    <!-- END MAIN CONTENT -->

	    <!-- FOOTER -->
	    <div id="footer-wrapper">
	    	<?php view('templates/mgov_footer');?>
	    </div>
	    <!-- END FOOTER -->

	    <?php
	    if ($accountInfo) {
	    	view('templates/mabuhay_id');	
	    	view('snippets/chat');
	    }

	    view('modals/privacy-policy');	
	    ?>

	</body>

	<?php view('templates/js_constants'); ?>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-loading-overlay/2.1.6/loadingoverlay.min.js"></script>
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/iScroll/5.2.0/iscroll.min.js"></script> -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function(){

			bootbox.setDefaults({
				size: 'small'
			});

			$.LoadingOverlaySetup({
				zIndex : 99999
			});

		});
	</script>

	<script type="text/javascript" src="<?php echo public_url(); ?>resources/js/scripts.js?<?php echo time()?>"></script>
	<script type="text/javascript" src="<?php echo public_url(); ?>resources/js/modules/utils.js?<?php echo time()?>"></script>
	<script type="text/javascript" src="<?php echo public_url(); ?>resources/js/modules/mgovph.js?<?php echo time()?>"></script>

	<?php
      if (isset($jsModules)) {
        foreach ($jsModules as $jsModule) {
          echo '<script src="'. public_url() .'resources/js/modules/'. $jsModule .'.js?'. time() .'"></script>';
        }
      }
    ?>

</html>
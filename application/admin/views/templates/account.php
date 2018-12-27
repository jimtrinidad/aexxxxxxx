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
		
		<link rel="stylesheet" href="<?php echo public_url(); ?>resources/libraries/flatfull/css/font.css" type="text/css"> 
		<link rel="stylesheet" href="<?php echo public_url(); ?>resources/libraries/flatfull/css/app.v1.css" type="text/css">

		<link rel="stylesheet" href="<?php echo public_url(); ?>resources/css/site.css" type="text/css"> 

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	</head>
	<body>
		<?php echo $templateContent;?>
	</body>

	<?php view('templates/js_constants'); ?>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-loading-overlay/2.1.6/loadingoverlay.min.js"></script>
	<script type="text/javascript" src="<?php echo public_url(); ?>resources/js/modules/account.js""></script>
	<script type="text/javascript" src="<?php echo public_url(); ?>resources/js/modules/utils.js""></script>

	<script type="text/javascript">
		$(document).ready(function(){
			bootbox.setDefaults({
				size: 'small'
			});
		});
	</script>

</html>
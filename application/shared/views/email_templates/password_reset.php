<!DOCTYPE html>
<html>
	<head>
		<title></title>
	</head>
	<body style="width: 1024px;margin: 0 auto;">
		<br>
		<a href="https://platform.mgov.ph"><img src="<?php echo public_url(); ?>resources/images/welcome-header.png"></a>
		<div style="padding: 30px 40px 30px 70px;color: #756f6f;font-size: 20px;font-family: arial;line-height: 1.2">
			Hi <b><?php echo $account->FirstName . ' ' . $account->LastName; ?></b>,
			<br>
			<br>
			<p>
				Your password has been reset upon your request.
			</p>
			<p>
				Your Mabuhay ID is: <b><?php echo $account->MabuhayID ?></b>, and your new password is: <i><?php echo $password ?></i>
			</p>
			<p>
				Please change your new password when you log in.
			</p>
		</div>
		<img src="<?php echo public_url(); ?>resources/images/welcome-footer.png">
	</body>
</html>
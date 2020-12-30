<!DOCTYPE html>
<html>
	<head>
		<title></title>
	</head>
	<body style="width: 1024px;margin: 0 auto;">
		<br>
		<a href="https://mgov.cloud/"><img src="<?php echo public_url(); ?>resources/images/email-header.png"></a>
		<div style="padding: 30px 40px 30px 70px;color: #756f6f;font-size: 20px;font-family: arial;line-height: 1.2">
			Hi <b><?php echo $account->FirstName . ' ' . $account->LastName; ?></b>,
			<br>
			<p>
				<a style="font-size: 30px;color: #03438b;font-weight: bold;" href="https://mgov.cloud/">>> Tap here to access your MGOV Account</a>
			</p>
			<p>
				MGOV is a cloud-based eGovernment Service Platform that connects citizens and government through internet data that process millions and billions of government transactions real-time.  By just only two taps, MGOV App provides every citizen direct access and processes via mobile, thousands of services the government provide based on the citizens’ needed assistance and support.
			</p>
			<p>
				MGOV App full-packed system features will also make sure that each municipal and barangay services provided is carried and reported through real-time statistics and live-feed, synchronized and standardized nationwide.
			</p>
			<br>
			<p>Your account application has been appproved.</p>
			<p>Your MGOV ID is: <b><?php echo $account->MabuhayID ?></b>, and your password is: <i><?php echo $password ?></i></p>
		</div>
		<img src="<?php echo public_url(); ?>resources/images/email-footer.png">
	</body>
</html>
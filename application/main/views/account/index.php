<!-- My Account Profile -->
<div class="col-sm-12 visible-sm visible-xs padding-15">
	<div class="row gutter-5">
		<div class="col-xs-3">
			<!-- Profile Menu-->
			<div class="profile-img">
				<img style="width: 100%;max-width: 170px;margin: 0 auto;" src="<?php echo public_url(); ?>assets/profile/<?php echo $accountInfo->Photo ?>" class="img-responsive i-profile"/>
				<img class="visible-xs" src="<?php echo public_url() . 'assets/qr/' . $accountInfo->QR ?>" style="width: 100%;max-width: 60px;background: white;margin-top: 10px;"/>
			</div>
		</div>
		<div class="col-xs-9">
			<div class="padding-bottom-5">
				<strong class="text-white text-bold"><?php echo user_full_name($accountInfo, 1); ?></strong>
			</div>
			<!-- Profile Data -->
			<div class="padding-top-10 text-white">
				<p class="lh-20"><?php echo user_full_address($accountInfo, true, true) ?></p>
				<div style="position: relative;min-height: 60px;">
					<p class="text-bold offset-top-10 lh-20"><?php echo lookup('education', $accountInfo->EducationalAttainmentID) ?></p>
					<p class="text-bold lh-20"><?php echo $accountInfo->EmailAddress ?></p>
					<p class="text-bold lh-20"><?php echo $accountInfo->ContactNumber ?></p>
					<img class="visible-sm" src="<?php echo public_url() . 'assets/qr/' . $accountInfo->QR ?>" style="width: 100%;max-width: 60px;background: white; position: absolute;bottom: 0;right: 0;"/>
				</div>
			</div>

			<!-- Mak ID -->
			<div>
				<!-- <img src="<?php echo public_url(); ?>resources/images/mak-id-2.png" class="img-responsive" /> -->
			</div>

			<button class="btn btn-sm bg-green text-white btn-block offset-top-10" onclick="Account.changeProfileOpen()">UPDATE PROFILE</button>

		</div>
	</div>
	
</div>

<!-- My Account Details -->
<div class="row gutter-5">
	<div class="col-sm-3 offset-top-10">
		<div class="bg-light-gray">
			<div class="bg-cyan text-white padding-10 text-bold">Secure My Account</div>
			<div class="bg-white padding-10 text-blue">
				<p>My ID: <a href="javascript:;" class="open-mak-id"><?php echo $accountInfo->MabuhayID ?></a></p>
				<p class="lh-20"><a href="javascript:;" onclick="Account.changePasswordOpen()">Change Password</a></p>
				<!-- <p>My Connections</p> -->
			</div>
		</div>
	</div>
	<div class="col-sm-9 offset-top-10">
		<div class="bg-light-gray">
			<div class="bg-red text-white padding-10 text-bold">Pending Applications</div>
			<div class="bg-white padding-10">
				<?php
					if (count($pending_applications)) {
						foreach ($pending_applications as $item) {
							echo '<div class="row offset-bottom-5">
									<div class="col-xs-7 text-bold text-blue">
										'. $item['ServiceName'] .'
									</div>
									<div class="col-xs-2 text-italic small-text">
										'. lookup('service_application_status', $item['Status']) .'
									</div>
									<div class="col-xs-3 text-italic small-text">
										'.date('M d, Y', strtotime($item['DateApplied'])).'
									</div>
									<div class="hide col-xs-2 text-bold text-red">
										Follow Up
									</div>
								</div>';
						}
					} else {
						echo '<div class="row offset-bottom-5">
								<div class="col-sm-12 text-orange text-bold">
									You have no pending applications at the moment.
								</div>
							</div>';
					}
				?>
				
			</div>
		</div>
	</div>
</div>
<!-- My Documents -->
<div class="row offset-top-10 offset-bottom-10">
	<div class="col-sm-12">
		<div class="bg-light-gray">
			<div class="bg-yellow text-white text-bold padding-10">
				My Completed Documents, Credentials and Applications
			</div>
			<div class="bg-white padding-10">
				
				<div class="row gutter-5">

					<?php
						if (count($completed_applications)) {
							foreach ($completed_applications as $item) {
								echo '<div class="col-sm-6">
										<div class="row offset-bottom-5 gutter-5">
											<div class="col-xs-6 text-bold text-blue">
												'. $item['ServiceName'] .'
											</div>
											<div class="col-xs-3 text-italic small-text">
												Completed
											</div>
											<div class="col-xs-3 text-italic small-text">
												'.date('M d, Y', strtotime($item['DateCompleted'])).'
											</div>
										</div>
									</div>';
							}
						} else {
							echo '<div class="row offset-bottom-5">
									<div class="col-sm-12 text-orange text-bold">
										You currently dont have any completed applications.
									</div>
								</div>';
						}
					?>
					
				</div>
				
				<!-- Logo -->
				<?php
					if (count($completed_applications)) {
						echo '<div class="row equal offset-bottom-20 gutter-5 offset-top-10">';
						foreach ($completed_applications as $item) {
							$logo  	 	= $item['ddLogo'];
							$deptName 	= $item['ddName'];
							if (!empty($item['dcID'])) {
								$logo  	 	= $item['dcLogo'];
								$deptName 	= $item['dcName'];
							}
							echo '<div class="col-sm-3 col-xs-6 text-center offset-top-10" style="min-height: 110px;">
									<img src="' . public_url() . 'assets/logo/' . logo_filename($logo) . '" class="center-block" width="60"/>
									<p class="text-bold text-blue small offset-top-5">'.$deptName.' - ' . $item['ServiceName'] . '</p>
								</div>';
						}
						echo '</div>';
					}
				?>
				
			</div>
		</div>
	</div>
</div>
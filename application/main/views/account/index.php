<!-- My Account Profile -->
<div class="col-sm-12 visible-sm visible-xs padding-15">
	<div class="row">
		<div class="col-xs-3">
			<!-- Profile Menu-->
			<div class="profile-img">
				<img style="width: 100%;max-width: 170px;margin: 0 auto;" src="<?php echo public_url(); ?>assets/profile/<?php echo $accountInfo->Photo ?>" class="img-responsive"/>
			</div>
		</div>
		<div class="col-xs-9">
			<div class="padding-bottom-5">
				<strong class="text-white text-bold"><?php echo user_full_name($accountInfo, 1); ?></strong>
			</div>
			<!-- Profile Data -->
			<div class="padding-top-10 text-white">
				<p class="lh-20"><?php echo user_full_address($accountInfo, true, true) ?></p>
				<p class="text-bold offset-top-10 lh-20"><?php echo lookup('education', $accountInfo->EducationalAttainmentID) ?></p>
				<p class="text-bold lh-20"><?php echo $accountInfo->EmailAddress ?></p>
				<p class="text-bold lh-20"><?php echo $accountInfo->ContactNumber ?></p>
			</div>

			<!-- Mak ID -->
			<div>
				<!-- <img src="<?php echo public_url(); ?>resources/images/mak-id-2.png" class="img-responsive" /> -->
			</div>

			<button class="btn btn-sm bg-green text-white btn-block offset-top-10">UPDATE PROFILE</button>

		</div>
	</div>
	
</div>

<!-- My Account Details -->
<div class="row gutter-5">
	<div class="col-sm-3 offset-top-10">
		<div class="bg-light-gray">
			<div class="bg-cyan text-white padding-10 text-bold">Secure My Account</div>
			<div class="padding-10 text-blue">
				<p>My ID: <?php echo $accountInfo->MabuhayID ?></p>
				<p class="lh-20"><a href="javascript:;" onclick="Account.changePasswordOpen()">Change Password</a></p>
				<!-- <p>My Connections</p> -->
			</div>
		</div>
	</div>
	<div class="col-sm-9 offset-top-10">
		<div class="bg-light-gray">
			<div class="bg-red text-white padding-10 text-bold">Pending Applications</div>
			<div class="padding-10">
				<div class="row offset-bottom-5">
					<div class="col-sm-4 text-bold text-blue">
						HLURB Housing
					</div>
					<div class="col-sm-2 text-italic small-text">
						Pending
					</div>
					<div class="col-sm-4 text-italic small-text">
						Applied:  July 27, 2016
					</div>
					<div class="col-sm-2 text-bold text-red">
						Follow Up
					</div>
				</div>
				
				<div class="row offset-bottom-5">
					<div class="col-sm-4 text-bold text-blue">
						Livelihood Loan
					</div>
					<div class="col-sm-2 text-italic small-text">
						Pending
					</div>
					<div class="col-sm-4 text-italic small-text">
						Applied:  July 27, 2016
					</div>
					<div class="col-sm-2 text-bold text-red">
						Follow Up
					</div>
				</div>
				
				<div class="row offset-bottom-5">
					<div class="col-sm-4 text-bold text-blue">
						Vitamins & Supplements
					</div>
					<div class="col-sm-2 text-italic small-text">
						Pending
					</div>
					<div class="col-sm-4 text-italic small-text">
						Applied:  July 27, 2016
					</div>
					<div class="col-sm-2 text-bold text-red">
						Follow Up
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>
<!-- My Documents -->
<div class="row offset-top-10 offset-bottom-10">
	<div class="col-sm-12">
		<div class="bg-light-gray">
			<div class="bg-yellow text-white text-bold padding-10">
				My Completed Documents and Credentials
			</div>
			<div class="padding-10">
				
				<div class="row gutter-5">
					<div class="col-sm-6">
						<div class="row offset-bottom-5 gutter-5">
							<div class="col-sm-4 text-bold text-blue">
								Mabuhay ID
							</div>
							<div class="col-sm-4 text-italic small-text">
								Completed
							</div>
							<div class="col-sm-4 text-italic small-text">
								Applied:  July 27, 2016
							</div>
						</div>
						
						<div class="row offset-bottom-5 gutter-5">
							<div class="col-sm-4 text-bold text-blue">
								Brgy. Clearance
							</div>
							<div class="col-sm-4 text-italic small-text">
								Completed
							</div>
							<div class="col-sm-4 text-italic small-text">
								Applied:  July 27, 2016
							</div>
						</div>
						
						<div class="row offset-bottom-5 gutter-5">
							<div class="col-sm-4 text-bold text-blue">
								Police Clearance
							</div>
							<div class="col-sm-4 text-italic small-text">
								Completed
							</div>
							<div class="col-sm-4 text-italic small-text">
								Applied:  July 27, 2016
							</div>
						</div>
						
						<div class="row offset-bottom-5 gutter-5">
							<div class="col-sm-4 text-bold text-blue">
								Business Permit
							</div>
							<div class="col-sm-4 text-italic small-text">
								Completed
							</div>
							<div class="col-sm-4 text-italic small-text">
								Applied:  July 27, 2016
							</div>
						</div>
					</div>
					
					<div class="col-sm-6">
						<div class="row offset-bottom-5 gutter-5">
							<div class="col-sm-4 text-bold text-blue">
								Drivers License
							</div>
							<div class="col-sm-4 text-italic small-text">
								Completed
							</div>
							<div class="col-sm-4 text-italic small-text">
								Applied:  July 27, 2016
							</div>
						</div>
						
						<div class="row offset-bottom-5 gutter-5">
							<div class="col-sm-4 text-bold text-blue">
								SSS ID
							</div>
							<div class="col-sm-4 text-italic small-text">
								Completed
							</div>
							<div class="col-sm-4 text-italic small-text">
								Applied:  July 27, 2016
							</div>
						</div>
						
						<div class="row offset-bottom-5 gutter-5">
							<div class="col-sm-4 text-bold text-blue">
								Philhealth ID
							</div>
							<div class="col-sm-4 text-italic small-text">
								Completed
							</div>
							<div class="col-sm-4 text-italic small-text">
								Applied:  July 27, 2016
							</div>
						</div>
						
						<div class="row offset-bottom-5 gutter-5">
							<div class="col-sm-4 text-bold text-blue">
								GSIS ID
							</div>
							<div class="col-sm-4 text-italic small-text">
								Completed
							</div>
							<div class="col-sm-4 text-italic small-text">
								Applied:  July 27, 2016
							</div>
						</div>
					</div>
					
				</div>
				
				<!-- Logo -->
				<div class="row offset-bottom-20 gutter-5">
					<div class="col-sm-3 text-center">
						<img src="<?php echo public_url(); ?>resources/images/LTO.png" class="center-block" width="80"/>
						<p class="text-bold text-blue">Land Transportation Office Driver’s License</p>
					</div>
					<div class="col-sm-3 text-center">
						<img src="<?php echo public_url(); ?>resources/images/BIR.png" class="center-block" width="80" />
						<p class="text-bold text-blue">Bureau of Internal Revenue - TIN ID</p>
					</div>
					<div class="col-sm-3 text-center">
						<img src="<?php echo public_url(); ?>resources/images/NBI.png" class="center-block" width="80" />
						<p class="text-bold text-blue">National Bureau of Investigation - NBI Clearance</p>
					</div>
					<div class="col-sm-3 text-center">
						<img src="<?php echo public_url(); ?>resources/images/PSA.png" class="center-block" width="80" />
						<p class="text-bold text-blue">National Statistics Office - Certificate of Live Birth</p>
					</div>
				</div>
				
				<div class="row offset-bottom-20 gutter-5">
					<div class="col-sm-3 text-center">
						<img src="<?php echo public_url(); ?>resources/images/DFA.png" class="center-block" width="80"/>
						<p class="text-bold text-blue">Department of Foreign Affairs - Passport</p>
					</div>
					<div class="col-sm-3 text-center">
						<img src="<?php echo public_url(); ?>resources/images/city-of-mandaluyong.png" class="center-block" width="80" />
						<p class="text-bold text-blue">Department of Interior and Local Government Revenue - Brgy. Clearance</p>
					</div>
					<div class="col-sm-3 text-center">
						<img src="<?php echo public_url(); ?>resources/images/logo-1.png" class="center-block" width="80" />
						<p class="text-bold text-blue">Office of the President - Scholarship Grant</p>
					</div>
					<div class="col-sm-3 text-center">
						<img src="<?php echo public_url(); ?>resources/images/PNP.png" class="center-block" width="80" />
						<p class="text-bold text-blue">National Police Commission - Police Clearance</p>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>
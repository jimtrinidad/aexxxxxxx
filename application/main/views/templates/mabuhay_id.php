<div class="id-overlay"></div>
<div id="mak-id">
	<div class="left-logo">
		<img src="<?php echo public_url(); ?>resources/images/mabuhay-logo-id.png" width="200" />
	</div>
	<div class="right-logo">
		<img src="<?php echo public_url() . (file_exists(LOGO_DIRECTORY . $accountInfo->CityData->logo) ? 'assets/logo/' . $accountInfo->CityData->logo : 'resources/images/republic.png') ?>" style="max-width: 70px;" /><br />
		<p class="text-bold"><?php echo ucwords(strtolower($accountInfo->regDesc)) ?><br /> <?php echo ucwords(strtolower($accountInfo->citymunDesc)) ?></p>
	</div>
	
	<div class="profile-photo">
		<img class="avatar" src="<?php echo public_url(); ?>assets/profile/<?php echo $accountInfo->Photo ?>" width="100" height="100" />
		<div><img src="<?php echo public_url(); ?>resources/images/id-label.png" width="100" /></div>
	</div>
	
	<div class="mak-id-data">
		<strong class="main-data">
			<?php echo strtoupper($accountInfo->LastName . ' ' . $accountInfo->FirstName . ' ' . $accountInfo->MiddleName) ?> <br />
			<?php echo $accountInfo->MabuhayID ?></strong>
		<p>
			Birthday: <strong class="text-bold"><?php echo date('M d, Y', strtotime($accountInfo->BirthDate)) ?></strong>    
			Gender: <strong class="text-bold"><?php echo lookup('gender', $accountInfo->GenderID) ?></strong>  <br /> 
			Status: <strong class="text-bold"><?php echo lookup('marital_status', $accountInfo->MaritalStatusID) ?></strong>  
			Address:  <strong class="text-bold">Brgy <?php echo ucwords(strtolower($accountInfo->brgyDesc)) ?>, <?php echo ucwords(strtolower($accountInfo->citymunDesc)) ?></strong> <br />
			Organization: <strong class="text-bold"><?php echo ($accountInfo->OrganizationID ? lookup_db('Dept_ChildDepartment', 'Name', $accountInfo->OrganizationID) : 'n/a') ?></strong>
		</p>
	</div>
	
	<div class="qr-code">
		<!-- <img src="<?php echo public_url(); ?>resources/images/qr-code.png" width="75" /> -->
		<img src="<?php echo public_url() . 'assets/qr/' . $accountInfo->QR ?>" width="75" />
	</div>
	
</div>
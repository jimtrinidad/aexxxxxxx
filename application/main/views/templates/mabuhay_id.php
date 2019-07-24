<div class="id-overlay"></div>
<div id="mak-id">
	<div class="left-logo">
		<img src="<?php echo public_url(); ?>resources/images/rp.png" width="200" />
		<span class="txt">
			<span class="m">MABUHAY</span>
			<span class="c">
			<?php 
				echo $accountInfo->PublicOffice ? $accountInfo->PublicOffice->Name : $accountInfo->citymunDesc
			?>
			</span>
			<span class="i">INTEGRATED GOVERNMENT SYSTEM IDENTIFICATION CARD</span>
		</span>
	</div>
	<div class="right-logo">
		<img src="<?php echo public_url() . (file_exists(LOGO_DIRECTORY . $accountInfo->CityData->logo) ? 'assets/logo/' . $accountInfo->CityData->logo : 'resources/images/republic.png') ?>" style="max-width: 45px;" /><br />
		<p class="text-bold">
			<?php

				$wordlist = array('city', 'of', 'municipal', 'municipality');
				foreach ($wordlist as &$word) {
				    $word = '/\b' . preg_quote($word, '/') . '\b/i';
				}

				$string = preg_replace($wordlist, '', $accountInfo->citymunDesc);

				if ($accountInfo->CityData->type == 2) {
					echo 'City Government of ' . ucwords(strtolower($string));
				} else {
					echo 'Municipal Government of ' . ucwords(strtolower($string));
				}
			?>
		</p>
	</div>
	
	<div class="profile-photo">
		<img class="avatar" src="<?php echo public_url(); ?>assets/profile/<?php echo $accountInfo->Photo ?>" width="80" height="80" />
	</div>
	
	<div class="mak-id-data">
		<strong class="main-data">
			<?php echo $accountInfo->MabuhayID ?><br />
			<?php echo strtoupper($accountInfo->LastName . ' ' . $accountInfo->FirstName . ' ' . $accountInfo->MiddleName) ?> 
		</strong>
		<p class="sub-data">
			Birthday: <strong class="text-bold"><?php echo date('M d, Y', strtotime($accountInfo->BirthDate)) ?></strong>    
			Gender: <strong class="text-bold"><?php echo lookup('gender', $accountInfo->GenderID) ?></strong>  <br /> 
			Status: <strong class="text-bold"><?php echo lookup('marital_status', $accountInfo->MaritalStatusID) ?></strong>  
			Address:  <strong class="text-bold"><?php echo ucwords(strtolower($accountInfo->brgyDesc)) ?>, <?php echo ucwords(strtolower($accountInfo->citymunDesc)) ?></strong> <br />
			Organization: <strong class="text-bold"><?php echo ($accountInfo->OrganizationID ? lookup_db('Dept_ChildDepartment', 'Name', $accountInfo->OrganizationID) : 'n/a') ?></strong>
		</p>
	</div>
	
	<div class="qr-code">
		<!-- <img src="<?php echo public_url(); ?>resources/images/qr-code.png" width="75" /> -->
		<img src="<?php echo public_url() . 'assets/qr/' . $accountInfo->QR ?>" width="75" class="qr-img" />
	</div>
	
</div>
<?php 
// print_data($accountInfo, true);
?>
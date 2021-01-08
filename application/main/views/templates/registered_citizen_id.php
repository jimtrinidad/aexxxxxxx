<div id="rc-id">
	<div class="left-logo">
		
	</div>
	<div class="right-logo">
		<img src="<?php echo public_url() . (file_exists(LOGO_DIRECTORY . $accountInfo->CityData->logo) ? 'assets/logo/' . $accountInfo->CityData->logo : 'resources/images/republic.png') ?>" width="60" /><br />
	</div>
	
	<div class="center-header-title">
		<h1>Republic of the Philippines</h1>
		<h2>
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
		</h2>
		
		<h3>Registered Citizen ID</h3>
	</div>
	
	<div class="profile-photo">
		<img class="avatar" src="<?php echo public_url(); ?>assets/profile/<?php echo $accountInfo->Photo ?>" width="100" height="100" />
		<div><strong class="pre-label">Mamamayan ng</strong><br /><strong class="prov-label"><?php echo $accountInfo->provDesc ?></strong></div>
	</div>
	
	<div class="rc-id-main-data">
		<strong class="main-data">
			<?php echo strtoupper($accountInfo->LastName . ' ' . $accountInfo->FirstName . ' ' . $accountInfo->MiddleName) ?>  <br />
			<span>ID# <?php echo $accountInfo->MabuhayID ?></span>
		</strong>
	</div>
	
	<div class="rc-id-data">
		<p>Birthday: <strong class="text-bold"><?php echo date('M d, Y', strtotime($accountInfo->BirthDate)) ?></strong> <br />
			Gender: <strong class="text-bold"><?php echo lookup('gender', $accountInfo->GenderID) ?></strong>  <br /> 
			Status: <strong class="text-bold"><?php echo lookup('marital_status', $accountInfo->MaritalStatusID) ?></strong><br />  
			Address:  <strong class="text-bold"><?php echo ucwords(strtolower($accountInfo->brgyDesc)) ?>, <?php echo ucwords(strtolower($accountInfo->citymunDesc)) ?></strong></p>
	</div>
	
	<div class="qr-code">
		<img src="<?php echo public_url() . 'assets/qr/' . $accountInfo->QR ?>" width="75" />
	</div>
</div>
<?php 
// print_data($accountInfo, true);
?>
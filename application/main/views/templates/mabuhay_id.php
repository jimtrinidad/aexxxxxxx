<?php

    $wordlist = array('city', 'of', 'municipal', 'municipality');
    foreach ($wordlist as &$word) {
        $word = '/\b' . preg_quote($word, '/') . '\b/i';
    }

    $string = preg_replace($wordlist, '', $accountInfo->citymunDesc);

    if ($accountInfo->CityData->type == 2) {
        $citymuni = ucwords(strtolower($string)) . ' City';
    } else {
        $citymuni = ucwords(strtolower($string));
    }
?>

<div class="id-overlay"></div>
	
<!-- Update This Section -->
<div id="mak-id">
    <div class="left-logo">
        <img src="<?php echo public_url(); ?>resources/images/rp.png" width="60" /><br />	
    </div>
    <div class="right-logo">
        <img src="<?php echo public_url() . (file_exists(LOGO_DIRECTORY . $accountInfo->CityData->logo) ? 'assets/logo/' . $accountInfo->CityData->logo : 'resources/images/republic.png') ?>" width="60" /><br />
    </div>
    
    <div class="center-header-title">
        <h1>republika ng pilipinas</h1>
        <h2>republic of the philippines</h2>
        <h3>pambansang pagkakakilanlan</h3>
    </div>
    
    <div class="profile-photo">
        <img class="avatar" src="<?php echo public_url(); ?>assets/profile/<?php echo $accountInfo->Photo ?>" width="100" height="100" />
        <div><strong class="prov-label">FEBRUARY 28, 1945</strong><br /><strong class="pre-label"><?php echo $citymuni ?>, <?php echo $accountInfo->provDesc ?></strong></div>
    </div>
    
    <div class="watermark-profile-photo">
        <img class="avatar" src="<?php echo public_url(); ?>assets/profile/<?php echo $accountInfo->Photo ?>" width="45"/>
    </div>
    
    <div class="mak-id-main-data">
        <span>ID#</span><br />
        <strong class="main-data"><?php echo $accountInfo->MabuhayID ?></strong>
    </div>
    
    <div class="mak-id-data">
        <p>Apelyido / Surname:<br /><strong class="text-bold"><?php echo $accountInfo->LastName ?></strong></p>
        <p>Pangalan / Given name:<br /><strong class="text-bold"><?php echo $accountInfo->FirstName ?></strong></p>
        <p>Apelyido sa Ina / Middle Name:<br /><strong class="text-bold"><?php echo $accountInfo->MiddleName ?></strong></p>
        <p>Araw ng Kapanganakan / Birthday:<br /><strong class="text-bold"><?php echo date('M d, Y', strtotime($accountInfo->BirthDate)) ?></strong></p>
        <p class="mak-id-address"><strong class="text-bold"><?php echo $citymuni ?>,<br /><?php echo $accountInfo->provDesc ?></strong></p>
    </div>
    
    <div class="qr-code">
        <img src="<?php echo public_url() . 'assets/qr/' . $accountInfo->QR ?>" width="100" />
    </div>
</div>
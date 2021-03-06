<!-- Main Header -->
<div class="wrapper padding-top-20 padding-bottom-20">
  <?php if ($accountInfo && $accountInfo->PublicOffice): ?>
    <div class="row">
      <div class="col-sm-6 logo-holder">
        <a href="<?php echo site_url()?>"><img style="width: 40%;margin-left: 10px;" src="<?php echo public_url(); ?>resources/images/mak-logo.png"/></a>
        <div id="header-office-title">
          <div class="row gutter-5">
            <div class="col-xs-2">
              <img src="<?php echo public_url() . (file_exists(LOGO_DIRECTORY . $accountInfo->CityData->logo) ? 'assets/logo/' . $accountInfo->CityData->logo : 'resources/images/republic.png') ?>"/>
            </div>
            <div class="col-xs-10 text-left">
              <h1><?php echo $accountInfo->PublicOffice->Name ?></h1>
              <h4>GOVERNMENT INTEGRATED SYSTEM</h4>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 tagline-holder hidden-xs">
        <a href="<?php echo site_url()?>"><img style="max-width: 45%" src="<?php echo public_url(); ?>resources/images/tagline.png"/></a>
        <div id="header-office-contact">
          <span class="text-yellow contact-label"><b>Citizen Support</b></span>&nbsp;&nbsp;<span class="contact-value"><?php echo $accountInfo->PublicOffice->Contact ?></span><br>
          <span class="address-value"><?php echo $accountInfo->PublicOffice->Address ?></span>
        </div>
      </div>
    </div>
  <?php else :?>
    <div class="row">
      <div class="col-sm-6 logo-holder">
        <a href="<?php echo site_url()?>"><img src="<?php echo public_url(); ?>resources/images/mak-logo.png"/></a>
      </div>
      <div class="col-sm-6 tagline-holder">
        <a href="<?php echo site_url()?>"><img src="<?php echo public_url(); ?>resources/images/tagline.png"/></a>
      </div>
    </div>
  <?php endif;?>
</div>
<div class="header-bar bg-gold">
  <div class="wrapper">
    <div class="row">
      <div class="col-md-7 col-sm-6">
        <h1>Enjoy Mobile Government One Touch Processing</h1>
      </div>
      <div class="col-md-5 col-sm-6">
        <div class="row gutter-0">
          <?php if ($accountInfo) { ?>
          <div class="mak-id col-sm-8 col-xs-8">
            <div class="col-md-4 hidden-xs hidden-sm text-right" style="padding: 0;"><span>MGOV ID</span></div>
            <div class="col-md-8 col-xs-12 text-left open-mak-id" style="cursor: pointer;padding: 0 0 0 10px;">
              <span class="visible-xs-inline visible-sm-inline" style="padding-right: 20px;">ID</span>
              <?php echo $accountInfo->MabuhayID; ?> <!--16-000-000-106--> 
            </div>
          </div>
          <?php } else { ?>
            <div class="col-sm-8 col-xs-8">
              <a class="text-bold" href="<?php echo site_url('account/signin') ?>">Sign in</a> your account or
              <a class="text-bold" href="<?php echo site_url('account/signup') ?>">Register</a>.
            </div>
          <?php } ?>
          <div class="col-sm-4 col-xs-4 mak-settings">
            <a href="<?php echo site_url() ?>"><i class="fa fa-university text-blue" aria-hidden="true"></i></a>
            <?php
              if ($accountInfo && in_array($accountInfo->AccountTypeID, array(2,3,4))) {
                // if ($accountInfo->id == 66) {
                  echo '<a class="text-bold" onclick="Mgovph.verifyDocument()" href="javascript:;"><i class="fa fa-search text-blue" aria-hidden="true"></i></a>';
                // }
                echo '<a class="text-bold" href="' . site_url('quickserve') .'">QS</a>';
              }
            ?>
            <?php
              if ($accountInfo && in_array($accountInfo->AccountTypeID, array(2,3,4)) && isset($accountInfo->OrganizationID) && in_array($accountInfo->OrganizationID, lookup('cttmo_organizations'))) {
                echo '<a class="text-bold" href="' . site_url('cttmo/monthlyvreports') .'"><i class="fa fa-bar-chart text-blue" aria-hidden="true"></i></a>';
              }
              if ($accountInfo && in_array($accountInfo->AccountTypeID, array(2,3,4)) && isset($accountInfo->OrganizationID) && in_array($accountInfo->OrganizationID, lookup('coa_organizations'))) {
                echo '<a class="text-bold" href="' . site_url('coa/report') .'"><i class="fa fa-bar-chart text-blue" aria-hidden="true"></i></a>';
              }
            ?>
            <!-- <a href="<?php echo site_url('statistics/govt_performance') ?>"><i class="fa fa-bar-chart text-blue" aria-hidden="true"></i></a> -->
            <!-- <i class="fa fa-wrench text-blue offset-right-5 hide" aria-hidden="true"></i> -->
            <?php if ($accountInfo) { ?>
              <a href="<?php echo site_url('account/logout') ?>"><i class="fa fa-power-off text-blue" aria-hidden="true"></i></a>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Main Header End-->

<?php
  // if ($accountInfo->id == 66) {
    if ($accountInfo && in_array($accountInfo->AccountTypeID, array(2,3,4))) {
      view('modals/verify-document-modal');
    }
  // }
?>
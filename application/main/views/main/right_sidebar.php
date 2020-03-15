<div class="clearfix offset-bottom-10" id="side-bar-content">

  <div class="col-md-12 small" style="background: #95c8eb;padding: 5px;" >
    <p>
      <b style="color: #094d91;font-weight: bold;line-height: 1.4">Philippine Standard Time:</b><br>
      <span style="color: #3c718a;line-height: 1.4" id="system-time"><?php echo date('l, F d, Y, h:i:s A') ?></span>
    </p>
  </div>
  <?php 
  if ($accountInfo->PublicOffice) {
    $servants = json_decode($accountInfo->PublicOffice->PublicServants, true);
    $firstOfficer = reset($servants);
    if ($firstOfficer && $accountInfo->PublicOffice->Message) {
  ?>
  <div class="col-md-12 offset-top-10 bg-white" >
    <div class="row gutter-5">
      <div class="col-xs-12 text-blue" style="text-transform: uppercase;font-weight: bold;padding: 4px;">
        <small class="small">WELCOME REMARKS FROM</small><br><?php echo $firstOfficer['Position'] . ' ' . $firstOfficer['Firstname'] . ' ' . $firstOfficer['Lastname'] ?>
      </div>
      <hr style="border: 1px solid gray;opacity: 0.3;margin: 5px 0;">
      <div class="col-xs-12 side-message">
        <img src="<?php echo public_url() . 'assets/etc/' . etc_filename($firstOfficer['Photo']) ?>">
        <?php
          $message = explode(PHP_EOL, $accountInfo->PublicOffice->Message);
          foreach ($message as $line) {
            echo '<p>' . $line . '</p>';
          }
        ?>
      </div>
    </div>
  </div>
  <?php }} ?>
  <div class="col-md-12 top-gov-services offset-top-10 hide" id="trending-service-cont">
    <h2>COVID-19 UPDATES</h2>
    <iframe class="embed-responsive-item" src="https://dohph.maps.arcgis.com/apps/opsdashboard/index.html#/a21336cf215744e198ae5e90df7264af" style="min-height: 500px;width: 100%"></iframe>
  </div>
  <div class="col-md-12 top-gov-services offset-top-10 hide" id="trending-service-cont">
    <h2>Trending: TOP GOV SERVICES</h2>
    <ul id="trending-service-items">
    </ul>
  </div>
  <div class="col-md-12 top-performance-services offset-top-10" id="govt-ranking-cont">
    <h2>GOVERNMENT PERFORMANCE</h2>
    <div class="row">
      <div class="col-xs-6">
        <ul id="govt-ranking-cont-dept">
        </ul>
      </div>
      <div class="col-xs-6">
        <ul id="govt-ranking-cont-city">
        </ul>
      </div>
    </div>
  </div>
  <!-- View All Rank Based-->
  <div class="view-all hide">
    <!-- <p>Ranking Based on completed Services</p> -->
    <a href="<?php echo site_url('statistics/govt_performance') ?>" class="btn btn-sm bg-green text-white text-center">View More</a>
  </div>

  <div class="col-md-12 top-performance-services offset-top-10 hide" id="org-ranking-cont">
    <h2 class="org-ranking-title">Organization Performance</h2>
    <div class="row offset-bottom-10">
      
    </div>
    <a style="position: absolute;bottom: 2px;right: 5px;text-decoration: none;" class="text-bold org-ranking-view-button" data-view="partial" href="javascript:;" onclick="Mgovph.load_organization_ranking()">Ranked</a>
  </div>

  <div class="col-md-12 top-performance-services offset-top-10" id="currency-cont">
    <h2 class="org-ranking-title">Philippine Peso Conversion <span class="pull-right text-orange date-rate">02/01/2019</span></h2>
    <div class="row">
      <div class="col-xs-12">
        <table class="table small table-condensed">
          <thead>
            <tr>
              <td width="50%">Currencies</td>
              <td width="25%">Rate</td>
              <td>Pesos</td>
            </tr>
          </thead>
          <tbody id="currency-data">
            
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <?php if (current_controller() == 'organization') { ?>
  <div class="col-md-12 offset-top-10">
    <div class="row">
       <div class="col-sm-12">
         <table border="0">
           <tr>
             <td>
               <?php if ($Organization->Logo && file_exists(LOGO_DIRECTORY . $Organization->Logo)) {?>
               <img src="<?php echo public_url() . 'assets/logo/' . $Organization->Logo ?>" class="organization-logo" />
               <?php }?>
               <?php if ($accountInfo->CityData->logo && file_exists(LOGO_DIRECTORY . $accountInfo->CityData->logo)) {?>
                <img src="<?php echo public_url() . 'assets/logo/' . $accountInfo->CityData->logo; ?>" class="organization-partner-logo" />
               <?php }?>
            <!--    <img src="<?php echo public_url() . 'resources/images/dotr-logo.png' ?>" class="organization-partner-logo" />
               <img src="<?php echo public_url() . 'resources/images/LTO.png' ?>" class="organization-partner-logo" /> -->

               <?php
                if ($Organization->Setup) {
                  $partners = json_decode($Organization->Setup->Partners, true);
                  foreach ($partners as $partner) {
                    if (file_exists(PUBLIC_DIRECTORY . 'assets/etc/' . $partner['Photo'])) {
                      echo '<a href="'.$partner['URL'].'" target="_blank">';
                      echo '<img src="'.public_url() . 'assets/etc/'. $partner['Photo'] .'" class="organization-partner-logo" title="'.$partner['Name'].'" />';
                      echo '</a>';
                    }
                  }
                }
               ?>
             </td>
           </tr>
         </table>
       </div>
     </div>
  </div>
  <?php }?>

  <div class="col-md-12 sidebar-ads offset-top-10 hide">
    <img src="<?php echo public_url(); ?>resources/images/malacanang-banner.png" class="img-responsive" />
    <img src="<?php echo public_url(); ?>resources/images/one-stop-service.png" class="img-responsive offset-top-10" />
  </div>

</div>
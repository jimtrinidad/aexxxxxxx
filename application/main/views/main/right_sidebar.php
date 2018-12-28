<div class="clearfix">
  <div class="col-md-12 offset-bottom-10 small" style="background: #95c8eb;padding: 5px;" >
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
  <div class="col-md-12 offset-bottom-10 bg-white" >
    <div class="row gutter-5">
      <div class="col-xs-12 text-blue" style="text-transform: uppercase;font-weight: bold;padding: 4px;">
        WELCOME REMARKS FROM <?php echo $firstOfficer['Position'] . ' ' . $firstOfficer['Firstname'] . ' ' . $firstOfficer['Lastname'] ?>
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
  <div class="col-md-12 top-gov-services offset-bottom-10" id="trending-service-cont">
    <h2>Trending: TOP GOV SERVICES</h2>
    <ul id="trending-service-items">
    </ul>
  </div>
  <div class="col-md-12 top-performance-services offset-bottom-5" id="govt-ranking-cont">
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
  <div class="view-all">
    <!-- <p>Ranking Based on completed Services</p> -->
    <a href="<?php echo site_url('statistics/govt_performance') ?>" class="btn btn-sm bg-green text-white text-center">View More</a>
  </div>
  <div class="sidebar-ads offset-top-10 hide">
    <img src="<?php echo public_url(); ?>resources/images/malacanang-banner.png" class="img-responsive" />
    <img src="<?php echo public_url(); ?>resources/images/one-stop-service.png" class="img-responsive offset-top-10" />
  </div>
</div>
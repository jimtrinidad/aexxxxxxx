 <!-- BANNERS -->
<?php 
  
  $banners = array();
  if (current_controller() == 'organization' && $Organization->Setup) {
      $banners = json_decode($Organization->Setup->Banners, true);
  }

  // if all else fails
  if ($accountInfo->PublicOffice && count($banners) == 0) {
    $banners = json_decode($accountInfo->PublicOffice->Banners, true);
  }

  if (count($banners)) {
?>
<div id="carouselFade" class="carousel slide carousel-fade" data-ride="carousel" style="margin-top: -15px;">
    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
      <?php 
        $i = 0;
        foreach ($banners as $item) {
          $img = '<img class="d-block w-100" src="'. public_url() . 'assets/etc/' . (isset($item['Photo']) && $item['Photo'] ? $item['Photo'] : 'placeholder-banner.png') .'">';
          echo '<div class="item '.($i == 0 ? 'active' : '').'">';
            if (isset($item['URL']) && !empty($item['URL'])) {
              echo '<a href="'.$item['URL'].'">' . $img . '</a>';
            } else {
              echo $img;
            }
          echo '</div>';
          $i++;
        }
      ?>
    </div>

    <!-- Controls -->
    <a class="left carousel-control" href="#carouselFade" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#carouselFade" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
<?php } ?>
<!-- Banners end -->

<!-- Primary Navigation -->
<div class="bg-blue primary-nav">
    <ul id="horizontal">
      <li><a href="<?php echo site_url('dashboard'); ?>"><i class="fa fa-rss bg-orange" aria-hidden="true"></i> Livefeed</a></li>
      <li><a href="<?php echo site_url('services'); ?>"><i class="fa fa-arrow-right bg-yellow" aria-hidden="true"></i> Services</a></li>
      <?php if (isset($accountInfo->OrganizationID) && $accountInfo->OrganizationID): ?>
        <li><a href="<?php echo site_url('organization'); ?>"><i class="fa fa-sitemap bg-gray" aria-hidden="true"></i> My Organization</a></li>
      <?php endif;?>
      <!-- <li><a href="#"><i class="fa fa-volume-up bg-green" aria-hidden="true"></i> Announcements</a></li> -->
      <li><a href="<?php echo site_url('statistics/govt_performance'); ?>"><i class="fa fa-bar-chart-o bg-green" aria-hidden="true"></i> Government Performance</a></li>
      <li><a href="javascript:;" onclick="Chatbox.openChatWindow('support')"><i class="fa fa-comments-o bg-cyan" aria-hidden="true"></i> Live Support</a></li>
      <li><a href="<?php echo site_url('trabaho'); ?>"><i class="fa fa-clock-o bg-red" aria-hidden="true"></i> My Trabaho</a></li>
      <!-- <li><a href="#"><i class="fa fa-flag bg-violet" aria-hidden="true"></i> Events</a></li> -->
      <!-- <li><a href="#"><i class="fa fa-search bg-orange" aria-hidden="true"></i> Community</a></li> -->
      <!-- <li><a href="<?php echo site_url('account'); ?>"><i class="fa fa-user bg-green" aria-hidden="true"></i> My Account</a></li> -->
      <!-- <li><a href="<?php echo site_url('account/mywallet'); ?>"><i class="fa fa-google-wallet bg-cyan" aria-hidden="true"></i> My GovWallet</a></li> -->
    </ul>
</div>
<!-- Primary Navigation End-->
<!-- Secondary Navigation -->
<div class="bg-light-gray seconday-nav">
  <ul>
    <li><a href="<?php echo site_url('account'); ?>">My Account <!-- <i class="fa fa-caret-down" aria-hidden="true"></i> --></a></li>
    <li><a href="<?php echo site_url('trabaho'); ?>">Profile <!-- <i class="fa fa-caret-down" aria-hidden="true"></i> --></a></li>
    <li><a href="#">Mobile Wallet <!-- <i class="fa fa-caret-down" aria-hidden="true"></i> --></a></li>
  </ul>
</div>
<!-- Secondary Navigation End
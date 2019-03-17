<!-- Form Search -->
<form id="searchForm" onSubmit="return false;">
  <input type="hidden" id="lastFeed">
   <div class="row">
      <div class="col-md-12">
         <div class="bg-white padding-5">
            <input type="text" id="keyword" autocomplete="off" class="form-control" placeholder="Search Government Services">
         </div>
      </div>
   </div>
   <div class="row gutter-5 padding-bottom-5">

      <div class="col-sm-2 padding-top-5 visible-xs">
         <button class="btn bg-cyan text-white btn-block text-bold" onClick="Mgovph.getServices()">Search</button>
      </div>

      <div class="col-sm-9 padding-top-5">
         <!--input type="text" name="" class="form-control bg-green text-white" value="Department"-->
         <select class="form-control bg-green text-white text-bold" style="text-align-last: center;" id="DepartmentID" name="DepartmentID" onChange="Mgovph.getServices()">
            <!-- input-sm GetAgencyOffice();-->
            <option value="">Search By Department</option>
            <?php
               foreach(lookup_trending_departments() as $item) {
                echo '<option value="' . $item['id'] . '">' . $item['Name'] . '</option>';
               }
            ?>
         </select>
      </div>
      <div class="col-sm-3 padding-top-5 hide">
         <select class="form-control bg-green text-white" id="LocationScopeID" name="LocationScopeID" onchange="Mgovph.getServices()">
            <!-- input-sm-->
            <option value="">Search By Location</option>
            <?php
              foreach (lookup('location_scope') as $k => $v) {
                echo "<option value='{$k}'>{$v}</option>";
              }
            ?>
         </select>
      </div>
      <div class="col-sm-3 padding-top-5 hidden-xs">
         <button class="btn bg-cyan text-white btn-block text-bold" onClick="Mgovph.getServices()">Search</button>
      </div>
   </div>
</form>
<!-- Form Search End-->

<div id="LoadMainBody">
  
</div>
<div id="ServiceDetailsBody"></div>

<div class="row" id="dashboard-menu">
  <div class="col-xs-12 text-center">

    <?php
      $menu_items = array(
        array(
          'icon'  => 'peace-order.png',
          'bg'    => '#fd423e',
          'label' => 'Peace & Order',
          'href'  => site_url('services/?c=1')
        ),
        array(
          'icon'  => 'environment.png',
          'bg'    => '#ffffff',
          'label' => 'Environment',
          'href'  => site_url('services/?c=2')
        ),
        array(
          'icon'  => 'health.png',
          'bg'    => '#ca3435',
          'label' => 'Health',
          'href'  => site_url('services/?c=3')
        ),
        array(
          'icon'  => 'education.png',
          'bg'    => '#03585e',
          'label' => 'Education',
          'href'  => site_url('services/?c=4')
        ),
        array(
          'icon'  => 'social services.png',
          'bg'    => '#00508d',
          'label' => 'Social Services',
          'href'  => site_url('services/?c=5')
        ),
        array(
          'icon'  => 'shelter.png',
          'bg'    => '#003554',
          'label' => 'Shelter',
          'href'  => site_url('services/?c=6')
        ),
        array(
          'icon'  => 'livelihood.png',
          'bg'    => '#9bb31c',
          'label' => 'Livelihood & Employment',
          'href'  => site_url('services/?c=7')
        ),
        array(
          'icon'  => 'infra.png',
          'bg'    => '#005688',
          'label' => 'Infrastructure & Utilities',
          'href'  => site_url('services/?c=8')
        ),
        array(
          'icon'  => 'agriculture.png',
          'bg'    => '#308c32',
          'label' => 'Agriculture & Fishery',
          'href'  => site_url('services/?c=9')
        ),
        array(
          'icon'  => 'investment-tourism.png',
          'bg'    => '#ddd9d9',
          'label' => 'Investment & Tourism',
          'href'  => site_url('services/?c=10')
        ),
        array(
          'icon'  => 'eloading.png',
          'bg'    => '#fab33b',
          'label' => 'eLoading',
        ),
        array(
          'icon'  => 'remittance.png',
          'bg'    => '#005f96',
          'label' => 'Remittance',
        ),
        array(
          'icon'  => 'mywallet-rewards.png',
          'bg'    => '#fe6b3e',
          'label' => 'MyWallet & Rewards',
        ),
        array(
          'icon'  => 'bills-payment.png',
          'bg'    => '#b43343',
          'label' => 'Bills Payment',
        ),
        array(
          'icon'  => 'ticketing.png',
          'bg'    => '#8c9ca1',
          'label' => 'Ticketing',
        ),
        array(
          'icon'  => 'market.png',
          'bg'    => '#fd423e',
          'label' => 'Marketplace',
          'href'  => site_url('marketplace')
        ),
        array(
          'icon'  => 'payment-services.png',
          'bg'    => '#004b68',
          'label' => 'Payment Services',
        ),
      );

      foreach ($menu_items as $i) {
        echo '<div class="menu-box">';

        if (isset($i['href']) && $i['href']) {
          echo '<a href="' . $i['href'] . '" style="text-decoration: none;">';
        }

          echo '<div class="menu-img-box" style="background: '. $i['bg'] .'">
                  <span class="helper"></span>
                  <img src="' . public_url() . 'resources/images/dashboard/' . $i['icon'] . '">
                </div>
                <div class="menu-box-label">
                  '. $i['label'] .'
                </div>';

        if (isset($i['href']) && $i['href']) {
          echo '</a>';
        }

        echo  '</div>';
      }
    ?>

  </div>
</div>

<div class="hide templates-container">
  <?php view('snippets/service-item'); ?>
</div>

<?php view('modals/service-application'); ?>

<style type="text/css">
  .menu-box {
    width: 80px;
    height: 110px;
    display:inline-block; 
    margin: 20px 5px;
    cursor: pointer;
  }
  .menu-box .menu-img-box {
    width: 75px;
    height: 90px;
    white-space: nowrap;
    border-radius: 5px;
    -moz-border-radius: 5px;
    text-align: center; 
  }
  .menu-box .helper {
    display: inline-block;
    height: 100%;
    vertical-align: middle;
  }

  .menu-box img {
    vertical-align: middle;
    max-height: 55px;
    max-width: 60px;
  }

  .menu-box .menu-box-label {
    color: #fafafa;
    margin-top: 10px;
    text-align: center;
    font-size: 12px;
    display: inline-flex;
  }
</style>
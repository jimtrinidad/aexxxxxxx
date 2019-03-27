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
   <div class="row gutter-5">

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
  <div class="row gutter-5 categories-cont">
    <?php foreach ($categories as $cat) { ?>
    <div class="categorybox col-xs-12 col-sm-6 offset-top-10">
      <div class="bg-white">
        <div class="org-category"><?php echo $cat['category']?></div>
          <div class="categoryitemcont row gutter-5">
            <?php foreach ($cat['items'] as $item) { ?>
            <div class="col-xs-3 categoryitem">
                <div class="org-item" onclick="Mgovph.openServiceDetails(this)" data-code="<?php echo $item['Code'] ?>">
                    <div class="image" style="background-image: url('<?php echo base_url('assets/logo/') . $item['Logo'] ?>');"></div>
                    <div class="name"><?php echo ($item['ShortName'] ? $item['ShortName'] : $item['Name']) ?></div>
                </div>
            </div>
            <?php } ?>
        </div>
      </div>
    </div>
    <?php } ?>
  </div>

</div>
<div id="ServiceDetailsBody"></div>

<div class="hide templates-container">
  <?php view('snippets/service-item'); ?>
</div>

<?php view('modals/service-application'); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.2.0/imagesloaded.pkgd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/2.2.2/isotope.pkgd.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css" />

<script type="text/javascript">
  $(document).ready(function(){
    $('#LoadMainBody').imagesLoaded( function(){
        $('.categoryitemcont').isotope({
            itemSelector : '.categoryitem'
        });
        setTimeout(function() {
          var $grid = $('.categories-cont').isotope({
            itemSelector : '.categorybox'
          });
        }, 1);
    });

    // $('.categories-cont').slick({
    //   dots: true,
    //   infinite: true,
    //   speed: 300,
    //   slidesToShow: 1,
    //   adaptiveHeight: true
    // });
      
  })
</script>
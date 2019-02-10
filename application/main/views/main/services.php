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

<div class="bg-grey padding-10 offset-bottom-10">
   <div class="row">
      <div class="col-sm-12 text-bold text-white padding-top-10 padding-bottom-5">Government and Citizens Services</div>
      
   </div>
</div>

<div id="LoadMainBody">

</div>
<div id="ServiceDetailsBody"></div>

<div class="hide templates-container">
  <?php view('snippets/service-item'); ?>
</div>
<?php view('modals/service-application'); ?>

<script type="text/javascript">
  $(document).ready(function(){
    Mgovph.getServices(<?php echo get_post('v') ?>);
  });
</script>
<!-- Form Search -->
<form id="searchForm" onSubmit="return false;">
    <div class="row">
      <div class="col-md-12">
         <div class="bg-white padding-5">
            <input type="text" id="keyword" autocomplete="off" class="form-control" placeholder="Search Keyword">
         </div>
      </div>
    </div>
    <div class="row padding-top-10 padding-bottom-10">
      <!--div class="col-sm-4">
         <button class="btn bg-blue text-white btn-block">Search by Category</button>
         </div-->
      <div class="col-sm-7">
         <!--input type="text" name="" class="form-control bg-green text-white" value="Department"-->
         <select class="form-control bg-green text-white" id="DepartmentID" name="DepartmentID" onChange="">
            <!-- input-sm GetAgencyOffice();-->
            <option value="">--Select Department--</option>
            <?php
               foreach(lookup_all('Dept_Departments', false, 'Name') as $item) {
                echo '<option value="' . $item['id'] . '">' . $item['Name'] . '</option>';
               }
            ?>
         </select>
      </div>
      <div class="col-sm-3">
         <select class="form-control bg-green text-white" id="LocationScopeID" name="LocationScopeID">
            <!-- input-sm-->
            <option value="">--</option>
            <?php
              foreach (lookup('location_scope') as $k => $v) {
                echo "<option value='{$k}'>{$v}</option>";
              }
            ?>
         </select>
      </div>
      <div class="col-sm-2">
         <button class="btn bg-cyan text-white btn-block" onClick="Mgovph.getServices()">Search</button>
      </div>
    </div>
    <!-- Form Search End-->
    <!-- Filter Services-->
    <div class="bg-grey padding-10 offset-bottom-10">
       <div class="row">
          <div class="col-sm-12 text-bold text-white padding-top-10 padding-bottom-5">Government and Citizens Services</div>
       </div>
    </div>
</form>

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
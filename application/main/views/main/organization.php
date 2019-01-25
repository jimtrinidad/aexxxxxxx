<!-- Form Search -->
<form id="searchForm" onSubmit="return false;">
   <div class="row">
      <div class="col-md-12">
         <div class="padding-5 bg-white">
            <div class="row gutter-5">
              <div class="col-md-10">
                <input type="text" id="keyword" autocomplete="off" class="form-control" placeholder="Search Keyword of Organization Services">
              </div>
              <div class="col-md-2">
                <button class="btn bg-cyan text-white btn-block text-bold" onClick="Organization.getServices()">Search</button>
              </div>
            </div>
         </div>
      </div>
   </div>
</form>

<div class="bg-grey padding-10 offset-top-10">
   <div class="row">
      <div class="col-sm-12 text-bold text-white padding-top-10 padding-bottom-5"><?php echo $Organization->Name; ?></div>
   </div>
</div>

<div id="LoadMainBodyCont">
  <div id="LoadMainBody" class="row gutter-5">
    
  </div>
</div>

<?php view('modals/report-service-application'); ?>

<script type="text/javascript">
  $(document).ready(function(){
    Organization.getServices();
  });
</script>
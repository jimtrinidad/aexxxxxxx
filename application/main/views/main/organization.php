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

<div class="bg-grey padding-10 offset-top-10 offset-bottom-10">
   <div class="row">
      <div class="col-sm-12 text-bold text-white padding-top-10 padding-bottom-5"><?php echo $Organization->Name; ?></div>
   </div>
</div>

<div class="offset-bottom-10" style="background: #dcdfe1">
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
             <img src="<?php echo public_url() . 'resources/images/dotr-logo.png' ?>" class="organization-partner-logo" />
             <img src="<?php echo public_url() . 'resources/images/LTO.png' ?>" class="organization-partner-logo" />
           </td>
         </tr>
       </table>
     </div>
   </div>
</div>

<div class="bg-white">
  <div id="LoadMainBody" class="row">
    
  </div>
</div>

<?php view('modals/report-service-application'); ?>

<script type="text/javascript">
  $(document).ready(function(){
    Organization.getServices();
  });
</script>
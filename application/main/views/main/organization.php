
<!-- Form Search -->
<form id="searchForm" onSubmit="return false;">
   <div class="row">
      <div class="col-md-12">
         <div class="bg-white padding-bottom-5 padding-left-5 padding-right-5">
            <div class="row gutter-5">
              <div class="col-sm-10 padding-top-5">
                <input type="text" id="keyword" autocomplete="off" class="form-control" placeholder="Search Keyword of Organization Services">
              </div>
              <div class="col-sm-2 padding-top-5">
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.2.0/imagesloaded.pkgd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/2.2.2/isotope.pkgd.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.8/css/alt/AdminLTE-select2.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
    Organization.getServices();

    $('#violation-list-items').select2({
        width: '100%'
    });

  });
</script>
<style type="text/css">
  .select2-selection--single {
    height: 100% !important;
    min-height: 34px;
  }
  .select2-selection__rendered{
    word-wrap: break-word !important;
    text-overflow: inherit !important;
    white-space: normal !important;
  }
</style>
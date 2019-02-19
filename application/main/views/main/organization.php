<style type="text/css">
  /* Isotope Transitions
------------------------------- */
.isotope,
.isotope .item {
  -webkit-transition-duration: 0.8s;
     -moz-transition-duration: 0.8s;
      -ms-transition-duration: 0.8s;
       -o-transition-duration: 0.8s;
          transition-duration: 0.8s;
}

.isotope {
  -webkit-transition-property: height, width;
     -moz-transition-property: height, width;
      -ms-transition-property: height, width;
       -o-transition-property: height, width;
          transition-property: height, width;
}

.isotope .item {
  -webkit-transition-property: -webkit-transform, opacity;
     -moz-transition-property:    -moz-transform, opacity;
      -ms-transition-property:     -ms-transform, opacity;
       -o-transition-property:         top, left, opacity;
          transition-property:         transform, opacity;
}
  
  
/* responsive media queries */

@media (max-width: 768px) {
  header h1 small {
    display: block;
  }

  header div.description {
    padding-top: 9px;
    padding-bottom: 4px;
  }

  .isotope .item {
    position: static ! important;
    -webkit-transform: translate(0px, 0px) ! important;
       -moz-transform: translate(0px, 0px) ! important;
            transform: translate(0px, 0px) ! important;
  }
}
</style>

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

<script type="text/javascript">
  $(document).ready(function(){
    Organization.getServices();
  });
</script>
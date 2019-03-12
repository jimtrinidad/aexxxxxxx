<div class="bg-grey padding-10">
   <div class="row gutter-5">
      <div class="col-xs-10 col-sm-8 text-bold text-white padding-top-10 padding-bottom-5"><?php echo $businessName ?> Products</div>
      <div class="col-xs-2 col-sm-4 text-right">
        <button class="btn btn-sm btn-success" onclick="Business.addItem()"><i class="fa fa-plus"></i> Add <span class="hidden-xs">Product</span></button>
      </div>
   </div>
</div>
<div class="padding-20" style="background: #e7edf0;padding-bottom: 5px;">
	<div class="row">
		<div class="col-xs-12 product-listing">
			<?php if (count($items)) { ?>
			<div class="row gutter-5 itemcont">
				<?php foreach ($items as $item) { ?>
					<div class="col-xs-6 col-sm-4 item">
						<div class="product-box">
							<div class="row gutter-5">
								<div class="col-xs-4">
									<img class="product-image" src="<?php echo public_url('assets/logo/') . logo_filename($item['Image']) ?>">
								</div>
								<div class="col-xs-8 product-info">
									<div class="text-bold product-name"><?php echo $item['Name'] ?></div>
									<div class="product-desc small"><?php echo $item['Description'] ?></div>
									<div class="product-price text-bold text-orange small">P <?php echo number_format($item['Price']) ?></div>
									<div class="offset-top-5">
										<a href="javascript:;" onclick="Business.editItem(<?php echo $item['id'] ?>)" class="text-blue"><i class="fa fa-pencil"></i></a>
										<a href="javascript:;" onclick="Business.deleteItem(<?php echo $item['id'] ?>)" class="text-red"><i class="fa fa-trash"></i></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
			<?php } else { echo '<h4 class="text-bold padding-bottom-15">No item found.</h4>'; } ?>
		</div>
	</div>
</div>

<?php view('main/businesses/modals'); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.2.0/imagesloaded.pkgd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/2.2.2/isotope.pkgd.min.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
    Business.itemData = <?php echo json_encode($items, JSON_HEX_TAG); ?>;
    $('.product-listing').imagesLoaded(function(){
	    $('.itemcont').isotope({
	      itemSelector : '.item'
	    });
	});

  });
</script>
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
		<div class="col-xs-12">
			<?php if (count($items)) { ?>
			<div class="row gutter-5">
				<?php foreach ($items as $item) { ?>
					<div class="col-xs-6 col-sm-4">
						<div class="product-box">
							<div class="row gutter-5">
								<div class="col-xs-4">
									<img class="product-image" src="<?php echo public_url('assets/logo/') . logo_filename($item['Image']) ?>">
								</div>
								<div class="col-xs-8 product-info">
									<div class="text-bold product-name"><?php echo $item['Name'] ?></div>
									<div class="product-desc small"><?php echo $item['Description'] ?></div>
									<div class="product-price text-bold text-orange small">P <?php echo number_format($item['Price']) ?></div>
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
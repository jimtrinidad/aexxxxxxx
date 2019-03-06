<div class="bg-grey padding-10">
   <div class="row gutter-5">
      <div class="col-xs-12 col-sm-8 text-bold text-white padding-5">Accredited Businesses</div>
   </div>
</div>
<div class="padding-20" style="background: #e7edf0;padding-bottom: 5px;">
	<div class="row">
		<div class="col-xs-12">
			<div class="row gutter-5">
				<!-- <div class="col-xs-6 col-sm-4">
					<div class="busi-box">
						business 1
					</div>
				</div> -->
				<?php foreach ($businesses as $busi) { ?>
					<div class="col-xs-6 col-sm-4">
						<div class="busi-box">
							<a href="<?php echo site_url('businesses/view/' . $busi['Code']); ?>">
								<?php echo ($busi['Name'] ? $busi['Name'] : 'Business') . ' - ' . $busi['Code']; ?>
							</a>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
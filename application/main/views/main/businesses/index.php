<style type="text/css">
	.busi-box a, .busi-box a:hover {text-decoration: none;}
	.padding-2 {padding: 2px;}
</style>
<div class="bg-grey padding-10">
   <div class="row gutter-5">
      <div class="col-xs-12 col-sm-8 text-bold text-white padding-2">Accredited Businesses</div>
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
				<?php 
				foreach ($businesses as $busi) { 
					$bData = lookup_business_data($busi['id']);
					// print_data($bData);
				?>
					<div class="col-xs-6 col-sm-6">
						<div class="busi-box">
							<a href="<?php echo site_url('businesses/view/' . $busi['Code']); ?>">
								<?php //echo ($busi['Name'] ? $busi['Name'] : 'Business') . ' - ' . $busi['Code']; ?>
								<dl class="dl-horizontal items small">
									<?php
									foreach ($bData as $k => $v) {
										echo '<dt class="text-bold padding-2">'.$k.'</dt><dd class="padding-2">'.$v.'</dd>';
									}
									?>
								</dl>
							</a>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="modal-overs">
			<div class="hidden-sm hidden-xs" style="min-height: 120px;">&nbsp;</div>
			<div class="modal-dialog animated fadeInUp" style="max-width: 400px;min-width: 300px;margin: 30px auto 20px;">
				<div class="box-content modal-content" style="background-color:transparent;">
					<div class="modal-body">
						<div class="row">
							<div class="col-sm-12 padding-top-10">
								<?php
								if (SUBDOMAIN == 'davaocity') {
									echo '<div class="row gutter-5 padding-bottom-5 login-header">
											<div class="col-xs-3">
												<img src="' . public_url() . 'resources/images/davaocity-logo.png" class="img-responsive" />
											</div>
											<div class="col-xs-9 text-left login-header-cont">
								              <h1>DAVAO CITY</h1>
								              <h4>GOVERNMENT INTEGRATED SYSTEM</h4>
								            </div>
										</div>';
									// echo '<img src="' . public_url() . 'resources/images/davaocity-logo.png" class="img-responsive" />';
								} else {
									echo '<p class="text-muted text-center">';
										echo '<img src="' . public_url() . 'resources/images/mak-logo.png" class="img-responsive" />';
									echo '<p>';
								}
								?>
								<h4 class="text-white margin-bottom-15">Forgot Password</h4>
								<form id="forgotPasswordForm" action="<?php echo site_url('account/forgot_password') ?>" autocomplete="off" >
									<div id="error_message_box" class="hide row margin-top-20">
					                    <div class="error_messages alert alert-danger" role="alert"></div>
					                  </div>
									<div class="form-group">
										<label style="color:#FFF">Email Address</label>
										<input type="text" name="account_email" id="account_email" class="form-control" placeholder="Email Address">
									</div>
									<div class="form-group">
										<label style="color:#FFF">Mabuhay ID</label>
										<input type="text" name="acount_id" id="acount_id" class="form-control" placeholder="Mabuhay ID">
										<span class="help-block">Required if multiple accounts bind on email address.</span>
									</div>

									<div class="checkbox m-t-lg">
										<button type="submit" name="reset_password" class="btn btn-sm btn-danger pull-right text-uc m-t-n-xs">
										<i class="fa fa-sign-in"></i> <strong>Reset Password</strong>
										</button>
										
										<a class="btn btn-info btn-sm text-uc m-t-n-xs " href="<?php echo site_url('account/signin') ?>">Back</a>

									</div>
									
								</form>
								<div class="visible-sm visible-xs padding-top-15">
									<img src="<?php echo public_url(); ?>resources/images/login-min.png" class="img-responsive" />
								</div>
								<p class="text-muted text-center padding-top-15" style="padding-left:20px; padding-right:20px;margin-top: 10px;">
									<img src="<?php echo public_url(); ?>resources/images/tagline.png" class="img-responsive" />
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6 hidden-sm hidden-xs">
		<img style="margin-top: 130px;width: 85%;max-width: 850px" src="<?php echo public_url(); ?>resources/images/login-min.png" class="img-responsive" />
	</div>
</div>
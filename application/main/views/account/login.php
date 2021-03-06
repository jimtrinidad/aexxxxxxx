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
										echo '<a href="'. site_url() .'"><img src="' . public_url() . 'resources/images/mak-logo.png" class="img-responsive" /></a>';
									echo '<p>';
								}
								?>
								<form id="loginForm" action="<?php echo site_url('account/login') ?>" autocomplete="off" >
									<div id="error_message_box" class="hide alert alert-danger" role="alert"></div>
									<div class="form-group">
										<label style="color:#FFF">Mabuhay ID</label>
										<input type="text" name="username" id="username" class="form-control" placeholder="Mabuhay ID">
									</div>
									<div class="form-group">
										<label style="color:#FFF">Password</label>
										<input type="password" name="password" id="password" class="form-control" placeholder="Password">
									</div>
									<div class="checkbox m-t-lg">
										<button type="submit" class="btn btn-sm btn-success pull-right text-uc m-t-n-xs">
										<i class="fa fa-sign-in"></i> <strong>Log in</strong>
										</button>
										
										<label> <input type="checkbox"> Remember me </label>
										
										<a href="<?php echo site_url('account/signup')?>" class="btn btn-default btn-sm text-uc m-t-n-xs hide">
										<i class="fa fa-plus"></i> Create an account</a>
									</div>
									
								</form>
								<div class="text-center padding-top-5 text-yellow">
									<a href="<?php echo site_url('account/forgot')?>"><b>Forgot Password</b></a> | <a href="<?php echo site_url('account/signup')?>"><b>Create New Account</b></a>
								</div>
								<div class="visible-sm visible-xs padding-top-15">
									<img src="<?php echo public_url(); ?>resources/images/login-min.png" class="img-responsive" />
								</div>
								<p class="text-muted text-center" style="padding-left:20px; padding-right:20px;margin-top: 10px;">
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
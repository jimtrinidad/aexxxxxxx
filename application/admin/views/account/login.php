<div class="modal-overs">
	<div class="modal-dialog animated fadeInUp">
		<div class="col-sm-offset-2 col-sm-8 modal-content" style="background-color:transparent;">
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<h3 class="m-t-none m-b">Sign in</h3>
						<p class="text-muted text-center">
							<img src="<?php echo public_url(); ?>resources/images/mak-logo.png" class="img-responsive" />
						</p>
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
								
							</div>
							
						</form>
						
						<p class="text-muted text-center" style="padding-left:20px; padding-right:20px;">
							<img src="<?php echo public_url(); ?>resources/images/tagline.png" class="img-responsive" />
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
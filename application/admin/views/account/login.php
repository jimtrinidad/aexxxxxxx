
		<div class="modal-overs">
			<div class="modal-dialog animated fadeInUp" style="max-width: 400px;min-width: 300px;margin: 30px auto 20px;">
				<div class="box-content modal-content" style="background-color:transparent;">
					<div class="modal-body">
						<div class="row">
							<div class="col-sm-12 padding-top-10">
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
								<div class="text-left padding-top-5 text-yellow">
									<a href="<?php echo site_url('account/forgot')?>"><b>Forgot Password</b></a>
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
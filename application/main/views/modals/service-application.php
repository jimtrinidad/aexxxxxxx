<div class="modal fade" id="serviceApplicationModal" tabindex="-1" role="dialog" aria-labelledby="serviceApplicationModal">
	<div class="modal-dialog modal-lg">
		<form id="ServiceApplicationForm" name="ServiceApplicationForm" action="<?php echo site_url('services/save_application') ?>" enctype="multipart/form-data">
			<div class="modal-content" id="modal-content">
				<div class="modal-header bg-cyan text-white">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" style="font-family:Trebuchet MS; font-size:16px;"><b>Application</b> | Form </h4>
				</div>
				<div class="modal-body bg-light-gray" id="modal-body">
					<!-- Post Items -->
					<div class="post-items bg-white padding-10 serviceDetails">
						<div class="row gutter-5">
					         <div class="col-sm-2 col-xs-3">
					            <img src="" class="img-responsive DepartmentLogo" style="margin: 10px auto 0;max-height: 70px;max-width: 70px;">
					         </div>
					         <div class="col-sm-8 col-xs-9">
					         	<div class="row gutter-0">
					         		<div class="col-xs-12">
							         	<h2><span class="text-bold DeptName">Department name</span></h2>
							            <h2><span class="text-bold text-green ServiceName">Service name</span></h2>
							            <span class="ServiceDesc" style="font-family:Trebuchet MS; font-size:12px;">Service description</span>
							            <span class="ServiceFee" style="font-family:Trebuchet MS; font-size:12px;"><br>Fee: <span class="fee-amount text-orange"></span><br></span>
							            <h5><span class="text-blue ServiceZone" style="font-family:Trebuchet MS; font-size:10px;">Zone</span></h5>
						            </div>
						            <div class="col-xs-8 col-sm-12">
							            <div style="margin-top: 5px;" class="transaction-counter">
							            	Service Code: <span class="ServiceCode">ServiceCode</span>  <br class="visible-xs">
							            	Total Services Provided:  <span class="serviceProvided">2,005,009,997</span>
							            </div>
							            <div class="ServiceTags small padding-top-5"></div>
					            	</div>
					            	<dir class="col-xs-4 visible-xs" style="margin: 0;">
					            		<img src="" class="img-responsive ServiceLogo" style="float: right;max-height: 50px;">
					            	</dir>
					            </div>
					         </div>
					         <div class="col-sm-2 hidden-xs">
					            <img src="" class="img-responsive ServiceLogo" style="float: right;margin-top: 10px;max-height: 70px;max-width: 70px;">
					         </div>
					    </div>
					</div>
					<!-- Post Items End-->

					<div id="error_message_box" class="hide row small" style="margin-bottom: -10px;margin-top: -10px;">
			            <br>
			            <div class="error_messages no-border-radius alert alert-danger small" role="alert"></div>
			         </div>
					
					<div class="post-items bg-white padding-10">
						<h2 class="text-cyan text-bold offset-bottom-10">Application Form</h2>
						<div class="row">
							<div class="col-xs-12">
								<div class="box">
									<!-- /.box-header -->
									<div class="box-body table-responsive no-padding">
										<table class="table" style="font-size:12px;">
											<thead>
												<tr>
													<td align="left" rowspan="3">
														<img style="height: 80px;width: 80px;" src="<?php echo public_url(); ?>assets/profile/<?php echo $accountInfo->Photo ?>">
													</td>
													<th style="width:120px;">Mabuhay ID</th>
													<td colspan="2" style="width:300px;"><?php echo $accountInfo->MabuhayID ?></td>
													<td></td>
												</tr>
												<tr>
													<th>Name</th>
													<td colspan="2"><?php echo user_full_name($accountInfo, false); ?></td>
													<td></td>
													<td></td>
												</tr>
												<tr>
													<th>Birth Date</th>
													<td colspan="2"><?php echo $accountInfo->BirthDate ?></td>
													<td></td>
													<td></td>
												</tr>
												<tr>
													<th>Civil Status</th>
													<td>:</td>
													<td><?php echo lookup('marital_status', $accountInfo->MaritalStatusID) ?></td>
													<th>Gender</th>
													<td>:</td>
													<td><?php echo lookup('gender', $accountInfo->GenderID) ?></td>
												</tr>
												<tr>
													<th>Email Address</th>
													<td>:</td>
													<td><?php echo $accountInfo->EmailAddress ?></td>
													<th>Contact Number</th>
													<td>:</td>
													<td><?php echo $accountInfo->ContactNumber ?></td>
												</tr>
												<tr>
													<th>Educational&nbsp;Attainment</th>
													<td>:</td>
													<td><?php echo lookup('education', $accountInfo->EducationalAttainmentID) ?></td>
													<th>Nature&nbsp;of&nbsp;Livelihood</th>
													<td>:</td>
													<td><?php echo lookup('livelihood', $accountInfo->LivelihoodStatusID) ?></td>
												</tr>
												<tr>    
													<th>Address</th>
													<td style="border-bottom: 1px solid #ddd;">:</td>
													<td style="border-bottom: 1px solid #ddd;" colspan="4">
														<?php echo ucwords(strtolower(user_full_address($accountInfo, true))); ?>
													</td>
												</tr>
												
											</thead>
										</table>
									</div>
									<!-- /.box-body -->
								</div>
								<!-- /.box -->
							</div>
						</div>
						
					</div>
					
					<div id="serviceAdditionalFieldsCont" style="font-size:12px;"></div>
					
					<div id="documentAdditionalFieldsCont" style="font-size:12px;"></div>
					
					<input type="hidden" id="ServiceCode" name="ServiceCode" value="">
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn bg-cyan btn-sm text-white" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
					<button type="submit" class="btn bg-green btn-sm text-white"><i class="fa fa-save"></i> Submit</button>
				</div>
			</div>

		</form>
	</div>
</div>
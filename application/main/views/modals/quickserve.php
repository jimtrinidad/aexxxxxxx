<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" id="modal-content">
			<div class="modal-header bg-light-green text-white">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" style="font-family:Trebuchet MS; font-size:16px;">Details</h4>
			</div>
			<div class="modal-body bg-light-gray" id="modal-body">
				<!-- Post Items -->
				<div class="post-items bg-white padding-10 serviceDetails">
					<div class="row gutter-5">
				         <div class="col-sm-2 col-xs-6">
				            <img src="" class="img-responsive DepartmentLogo" style="margin: 10px auto 0;max-height: 100px;">
				         </div>
				         <div class="visible-xs col-xs-6">
				            <img src="" class="img-responsive ServiceLogo" style="margin: 0 auto;max-height: 100px;">
				         </div>
				         <div class="col-sm-8 col-xs-12">
				         	<h2><span class="text-bold text-bold DeptName">Department name</span></h2>
				            <h2><span class="text-bold text-green ServiceName">Service name</span></h2>
				            <span class="ServiceDesc" style="font-family:Trebuchet MS; font-size:12px;">Service description</span>
				            <h5><span class="text-blue ServiceZone" style="font-family:Trebuchet MS; font-size:10px;">Zone</span></h5>
				            <div class="ServiceTags small padding-top-5"></div>
				         </div>
				         <div class="col-sm-2 hidden-xs">
				            <img src="" class="img-responsive ServiceLogo" style="float: right;max-height: 100px;">
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
												<th style="width:120px;">Mabuhay ID</th>
												<td style="width:1px;">:</td>
												<td style="width:300px;"><span class="info-mabuhayID"></span></td>
												<td></td>
												<td></td>
												<td align="right" rowspan="3">
													<img class="info-photo" style="height: 80px;width: 80px;" src="<?php echo public_url(); ?>assets/profile/avatar_default.jpg">
												</td>
											</tr>
											<tr>
												<th>Name</th>
												<td>:</td>
												<td><span class="info-name"></span></td>
												<td></td>
												<td></td>
											</tr>
											<tr>
												<th>Birth Date</th>
												<td>:</td>
												<td><span class="info-birthday"></span></td>
												<td></td>
												<td></td>
											</tr>
											<tr>
												<th>Civil Status</th>
												<td>:</td>
												<td><span class="info-civil"></span></td>
												<th>Gender</th>
												<td>:</td>
												<td><span class="info-gender"></span></td>
											</tr>
											<tr>
												<th>Email Address</th>
												<td>:</td>
												<td><span class="info-email"></span></td>
												<th>Contact Number</th>
												<td>:</td>
												<td><span class="info-contact"></span></td>
											</tr>
											<tr>
												<th>Educational&nbsp;Attainment</th>
												<td>:</td>
												<td><span class="info-education"></span></td>
												<th>Nature&nbsp;of&nbsp;Livelihood</th>
												<td>:</td>
												<td><span class="info-livelihood"></span></td>
											</tr>
											<tr>    
												<th>Address</th>
												<td style="border-bottom: 1px solid #ddd;">:</td>
												<td style="border-bottom: 1px solid #ddd;" colspan="4">
													<span class="info-address"></span>
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

				<div id="otherDataCont" class="post-items bg-white padding-10">
					<h2 class="text-cyan text-bold offset-bottom-10">Other Data</h2>
					<div class="row">
						<div class="col-xs-12">
							<dl class="dl-horizontal items">
				                <dt class="text-bold padding-5">Description lists</dt>
				                <dd class="padding-5">A description list is perfect for defining terms.</dd>
				                <dt class="text-bold padding-5">sigsadasdgy</dt>
				                <dd><img src="http://localhost/Projects/Aexponents/mgovphV2/public/assets/logo/821a5b398af5adfecce0ef539a82de86.png" class="img-responsive" style="max-width: 100px;"></dd>
				            </dl>
						</div>
					</div>
				</div>
					
				<div id="requirementsCont" class="post-items bg-white padding-10">
					<h2 class="text-cyan text-bold offset-bottom-10">Required Documents</h2>
					<div class="row">
						<div class="col-xs-12">
							<div class="table-responsive">
								<table class="table">
									<thead>
										<tr>
											<th style="width: 100px;"></th>
											<th>Name</th>
											<th>Description</th>
											<th>Status</th>
											<th>LastUpdate</th>
											<th style="width: 100px"></th>
										</tr>
									</thead>
									<tbody class="items">
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<div class="pull-right">
					<button type="button" class="btn bg-orange btn-sm text-white" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
				</div>

				<div class="clearfix"></div>
				
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModal">
	<div class="modal-dialog modal-md">
		<form id="approveForm" name="approveForm" action="<?php echo site_url('quickserve/approve') ?>">
			<div class="modal-content" id="modal-content">
				<div class="modal-header bg-cyan text-white">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" style="font-family:Trebuchet MS; font-size:16px;"><b>Complete Task</b> | <span class="taskName"></span> </h4>
				</div>
				<div class="modal-body" id="modal-body">

					<div id="error_message_box" class="hide row">
			            <div class="error_messages no-border-radius alert alert-danger" role="alert"></div>
			        </div>
					
					<div class="form-group">
	                  <label class="control-label text-bold" for="Remarks">Remarks</label>
	                  <textarea class="form-control" id="Remarks" name="Remarks" placeholder=""></textarea>
	                  <span class="help-block hidden"></span>
	                </div>
					<input type="hidden" id="safID" name="safID" value="">

					<div class="pull-right">
						<button type="button" class="btn bg-cyan btn-sm text-white" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
						<button type="submit" class="btn bg-green btn-sm text-white"><i class="fa fa-check"></i> Process</button>
					</div>

					<div class="clearfix"></div>
					
				</div>
			</div>

		</form>
	</div>
</div>

<div class="modal fade" id="declineModal" tabindex="-1" role="dialog" aria-labelledby="declineModal">
	<div class="modal-dialog modal-md">
		<form id="declineForm" name="declineForm" action="<?php echo site_url('quickserve/decline') ?>">
			<div class="modal-content" id="modal-content">
				<div class="modal-header bg-orange text-white">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" style="font-family:Trebuchet MS; font-size:16px;"><b>Decline/Cancel Task</b> | <span class="taskName"></span> </h4>
				</div>
				<div class="modal-body" id="modal-body">

					<div id="error_message_box" class="hide row">
			            <div class="error_messages no-border-radius alert alert-danger" role="alert"></div>
			        </div>

			        <div class="form-group">
	                  <select class="form-control" name="Status">
	                  	<option value="3">Decline</option>
	                  	<option value="4">Cancel</option>
	                  </select>
	                  <span class="help-block hidden"></span>
	                </div>
					
					<div class="form-group">
	                  <label class="control-label text-bold" for="Remarks">Remarks</label>
	                  <textarea class="form-control" id="Remarks" name="Remarks" placeholder=""></textarea>
	                  <span class="help-block hidden"></span>
	                </div>
					<input type="hidden" id="safID" name="safID" value="">

					<div class="pull-right">
						<button type="button" class="btn bg-cyan btn-sm text-white" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
						<button type="submit" class="btn bg-orange btn-sm text-white"><i class="fa fa-check"></i> Decline</button>
					</div>

					<div class="clearfix"></div>
					
				</div>
			</div>

		</form>
	</div>
</div>



<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModal">
	<div class="modal-dialog modal-md">
		<form id="paymentForm" name="paymentForm" action="<?php echo site_url('quickserve/payment') ?>">
			<div class="modal-content" id="modal-content">
				<div class="modal-header bg-cyan text-white">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" style="font-family:Trebuchet MS; font-size:16px;"><span class="taskName"></span> </h4>
				</div>
				<div class="modal-body" id="modal-body">

					<div id="error_message_box" class="hide row">
			            <div class="error_messages no-border-radius alert alert-danger" role="alert"></div>
			        </div>

			        <div class="row gutter-5">
			        	
			        	<div class="col-xs-6">
		                	<div class="form-group">
			               		<label class="control-label text-bold" for="date">Payment Type</label>
			               		<select class="form-control input-sm" id="type" name="type">
			               			<option>Cash</option>
			               			<option>Treasury Warrant</option>
			               			<option>Check</option>
			               			<option>Money Order</option>
			               		</select>
			               	</div>
		                </div>

			        	<div class="col-xs-6">
			               	<div class="form-group">
			               		<label class="control-label text-bold" for="date">Payment Date</label>
			               		<input type="text" name="date" id="date" class="form-control input-sm" data-provide="datepicker" data-date-format="mm/dd/yyyy">
			               	</div>
		                </div>

		                <div class="col-xs-12">
			               	<div class="form-group">
			               		<label class="control-label text-bold" for="date">Payor</label>
			               		<input type="text" id="payor" class="form-control input-sm" name="payor">
			               	</div>
		                </div>

		                <div class="col-xs-12">
		                	<div class="table-responsive">
			                	<table class="table table-condensed">
			                		<thead>
			                			<th width="40%">Nature of Collection</th>
			                			<th width="25%">Fund and Account Code</th>
			                			<th>Amount</th>
			                			<th style="width: 10px;">
			                				<button type="button" class="btn btn-sm btn-success" onclick="Quickserve.addCollectionRow()"><i class="fa fa-plus"></i></button>
			                			</th>
			                		</thead>
			                		<tbody id="collectionBody">
			                			<tr>
			                				<td><input type="text" autocomplete="off" name="collectionName[]" class="form-control input-sm"></td>
			                				<td><input type="text" autocomplete="off" name="collectionCode[]" class="form-control input-sm"></td>
			                				<td><input type="number" step=".01" autocomplete="off" name="collectionAmount[]" class="form-control input-sm"></td>
			                				<td style="vertical-align:middle;"></td>
			                			</tr>
			                		</tbody>
			                	</table>
			                </div>
		                </div>

		                <div class="col-xs-12 col-sm-6">
			        		<div class="form-group">
			                  <label class="control-label text-bold" for="Scope">Scope</label>
			                  <select class="form-control input-sm" id="scope" name="scope">
			                  	<option>Department</option>
					            <?php
					              foreach (lookup('location_scope') as $k => $v) {
					                echo "<option>{$v}</option>";
					              }
					            ?>
					         </select>
			                  <span class="help-block hidden"></span>
			                </div>
			        	</div>

		                <div class="col-xs-12 col-sm-6">
			               	<div class="form-group">
			               		<label class="control-label text-bold" for="treasurer">Treasurer Name</label>
			               		<input type="text" name="treasurer" id="treasurer" class="form-control input-sm">
			               	</div>
		                </div>
			        </div>
					

					<input type="hidden" id="safID" name="safID" value="">

					<div class="pull-left hide" id="paymentPreviewButtonCont">
						<button type="button" data-id="" class="paymentPreviewButton btn bg-orange btn-sm text-white"><i class="fa fa-file-text"></i> Receipt</button>
					</div>

					<div class="pull-right">
						<button type="button" class="btn bg-cyan btn-sm text-white" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
						<button type="submit" class="btn bg-green btn-sm text-white"><i class="fa fa-save"></i> Save</button>
					</div>

					<div class="clearfix"></div>
					
				</div>
			</div>

		</form>
	</div>
</div>

<div class="modal fade" id="receiptModal" tabindex="-1" role="dialog" aria-labelledby="receiptModal">
	<div class="modal-dialog modal-md">
		<div class="modal-content" id="modal-content">
			<div class="modal-header text-white">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>
			<div class="modal-body text-center" id="modal-body"style="overflow: auto;padding: 0;height: 810px;">
				<iframe frameborder="0" style="width: 500px;min-height: 800px;margin: 0 auto;"></iframe>
			</div>
			<div class="modal-footer">
				<div class="pull-right">
					<button type="button" class="btn bg-cyan btn-sm text-white" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="feedbackModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content" id="modal-content">
			<div class="modal-header bg-cyan text-white">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" style="font-family:Trebuchet MS; font-size:16px;">Feedbacks | <span class="userName text-bold"></span> </h4>
			</div>
			<div class="modal-body" id="modal-body">
				<b class="h2"><small class="pull-right padding-top-10 feedNote hide">45 feedbacks</small> About <span class="userName"></span></b>
				<form id="feedbackForm" action="<?php echo site_url('quickserve/add_feedback') ?>" enctype="multipart/form-data" >
					<input type="hidden" name="mID" id="mID">
					<div class="row padding-top-10">
						<div id="error_message_box" class="hide">
				            <div class="error_messages no-border-radius alert alert-danger" role="alert"></div>
				        </div>
						<div class="col-xs-12">
							<div class="form-group">
								<label class="control-label text-bold" for="Message">Message</label>
								<textarea class="form-control" name="Message" id="Message" placeholder="Message"></textarea>
							</div>
						</div>
						<div class="col-xs-8">
							<div class="form-group" style="margin-top: -10px;">
								<label class="control-label text-bold" for="Attachment">Attachment</label>
								<input type="file" name="Attachment" id="Attachment">
							</div>
						</div>
						<div class="col-xs-4 text-right">
							<button type="submit" class="btn bg-green btn-sm text-white"><i class="fa fa-send"></i> Send</button>
						</div>
					</div>
				</form>
				<hr style="margin-top: 10px;margin-bottom: 10px;">
				<div class="row">
				  	<div class="col-xs-12">
					    <h2 class="norecord hide">No record found.</h2>
					    <div class="comments-list">
					    </div>
				  	</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="pull-right">
					<button type="button" class="btn bg-orange btn-sm text-white" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
$applicantData = false;
if ($accountInfo) {
	 $applicantData = (object) array(
        'mid'           => $accountInfo->MabuhayID,
        'profile'       => public_url() . 'assets/profile/' . $accountInfo->Photo,
        'name'          => user_full_name($accountInfo, false),
        'birthday'      => $accountInfo->BirthDate,
        'martial'       => lookup('marital_status', $accountInfo->MaritalStatusID),
        'gender'        => lookup('gender', $accountInfo->GenderID),
        'email'         => $accountInfo->EmailAddress,
        'contact'       => $accountInfo->ContactNumber,
        'education'     => lookup('education', $accountInfo->EducationalAttainmentID),
        'livelihood'    => lookup('livelihood', $accountInfo->LivelihoodStatusID),
        'address'       => ucwords(strtolower(user_full_address($accountInfo, true)))
    );
}
?>

<style type="text/css">
	#serviceApplicationModal .text-white {
		color: #aaa;
	}
</style>
<div class="modal fade" id="serviceApplicationModal" role="dialog" aria-labelledby="serviceApplicationModal">
	<div class="modal-dialog modal-lg">
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

					<div id="ServiceApplicationContainer" class="post-items <?php echo $accountInfo || $tmpUser ? '' : 'hide' ?>">
						<form id="ServiceApplicationForm" name="ServiceApplicationForm" action="<?php echo site_url('services/save_application') ?>" enctype="multipart/form-data">
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
																<img class="s_info_photo" style="height: 80px;width: 80px;" src="<?php echo $applicantData->profile ?? 'avatar_default.jpg' ?>">
															</td>
															<th style="width:120px;">MGOV ID</th>
															<td colspan="2" class="s_info_mid" style="width:300px;"><?php echo $applicantData->mid ?? '' ?></td>
															<td></td>
														</tr>
														<tr>
															<th>Name</th>
															<td colspan="2" class="s_info_name"><?php echo $applicantData->name ?? ''; ?></td>
															<td></td>
															<td></td>
														</tr>
														<tr>
															<th>Birth Date</th>
															<td colspan="2" class="s_info_bday"><?php echo $applicantData->birthday ?? '' ?></td>
															<td></td>
															<td></td>
														</tr>
														<tr>
															<th>Civil Status</th>
															<td>:</td>
															<td class="s_info_civil"><?php echo $applicantData->martial ?? '' ?></td>
															<th>Gender</th>
															<td>:</td>
															<td class="s_info_gender"><?php echo $applicantData->gender ?? '' ?></td>
														</tr>
														<tr>
															<th>Email Address</th>
															<td>:</td>
															<td class="s_info_email"><?php echo $applicantData->email ?? '' ?></td>
															<th>Contact Number</th>
															<td>:</td>
															<td class="s_info_contact"><?php echo $applicantData->contact ?? '' ?></td>
														</tr>
														<tr>
															<th>Educational&nbsp;Attainment</th>
															<td>:</td>
															<td class="s_info_education"><?php echo $applicantData->education?? '' ?></td>
															<th>Nature&nbsp;of&nbsp;Livelihood</th>
															<td>:</td>
															<td class="s_info_livelihood"><?php echo $applicantData->livelihood ?? '' ?></td>
														</tr>
														<tr>    
															<th>Address</th>
															<td style="border-bottom: 1px solid #ddd;">:</td>
															<td style="border-bottom: 1px solid #ddd;" colspan="4" class="s_info_address">
																<?php echo $applicantData->address ?? '' ?>
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

							<div id="error_message_box" class="hide row small" style="margin-bottom: -10px;margin-top: -10px;">
					            <br>
					            <div class="error_messages no-border-radius alert alert-danger small" role="alert"></div>
					         </div>

							<div id="serviceAdditionalFieldsCont" style="font-size:12px;"></div>
							
							<div id="documentAdditionalFieldsCont" style="font-size:12px;"></div>
							
							<input type="hidden" id="ServiceCode" name="ServiceCode" value="">
							<input type="hidden" id="MID" name="MID" value="<?php echo $applicantData->mid ?? '' ?>">
							
							<div class="modal-footer padding-bottom-5">
								<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
								<button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Submit</button>
							</div>
						</form>
					</div>
					<?php if (!$accountInfo && !$tmpUser) { ?>
						<div class="post-items bg-white padding-20" id="ServiceRegistrationContainer">
							<div class="row">
								<div class="col-xs-12">
									<div class="box text-center">
										<p class="text-orange text-bold">You'll have to login your account first to apply.</p>
										<br>
										<p>
											<a class="btn btn-success sign_in_url">Signin</a>
										</p>
										<br>
										<p class="text-orange">or fill up registration form</p>
										<br>
										<p>
											<!-- <a class="btn btn-info sign_up_url">Create a new account.</a> -->
											<form id="RegistrationForm" action="<?php echo site_url('account/register') ?>" enctype="multipart/form-data" >
							                  <div class="row gutter-5 padding-top-10">

								                  <div id="error_message_box" class="hide row margin-top-20">
								                    <br>
								                    <div class="error_messages alert alert-danger" role="alert"></div>
								                  </div>

								                <input type="hidden" id="RegistrationID" name="RegistrationID" class="form-control" value="<?php echo microsecID(); ?>">

							                    <div class="col-md-3 col-md-push-9 col-xs-6 col-xs-push-6">
							                      <div class="image-upload-container padding-top-20">
							                        <img class="image-preview" src="<?php echo public_url(); ?>assets/profile/avatar_default.jpg" alt="...">
							                        <span class="hiddenFileInput hide">
							                          <input type="file" accept="image/*" capture="camera" class="image-upload-input" id="avatarFile" name="avatarFile"/>
							                        </span>
							                      </div>
							                    </div>

							                    <div class="col-md-9 col-md-pull-3 col-xs-6 col-xs-pull-6">
							                        <div class="row gutter-5">
							                          <div class="col-md-4 col-xs-12">
							                            <label class="text-white padding-bottom-5">First Name</label>
							                            <input type="text" id="FirstName" name="FirstName" class="form-control" placeholder="">
							                          </div>
							                          <div class="col-md-4 col-xs-12">
							                            <label class="text-white padding-bottom-5">Middle Name</label>
							                            <input type="text" id="MiddleName" name="MiddleName" class="form-control has-error" placeholder="">
							                          </div>
							                          <div class="col-md-4 col-xs-12">
							                            <label class="text-white padding-bottom-5">Last Name</label>
							                            <input type="text" id="LastName" name="LastName" class="form-control" placeholder="">
							                          </div>
							                        </div>

							                        <div class="row gutter-5 hidden-xs hidden-sm showonmd">

							                          <div class="col-md-4 col-xs-6">
							                            <label class="text-white padding-bottom-5">Gender</label>
							                            <select id="GenderID" name="GenderID" class="form-control GenderID">
							                              <option value="">--</option>
							                              <?php
							                                foreach (lookup('gender') as $k => $v) {
							                                  echo "<option value='{$k}'>{$v}</option>";
							                                }
							                              ?>
							                            </select>
							                          </div>

							                          <div class="col-md-4 col-xs-6">
							                            <label class="text-white padding-bottom-5">Birth Date</label>
							                            <input type="text" autocomplete="off" id="BirthDate" name="BirthDate" class="form-control BirthDate" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask>
							                          </div>

							                          <div class="col-md-4 col-xs-6">
							                            <label class="text-white padding-bottom-5">Contact Number</label>
							                            <input type="text" id="ContactNumber" name="ContactNumber" class="form-control ContactNumber" placeholder="">
							                          </div>

							                          <div class="col-md-12 col-xs-6">
							                            <label class="text-white padding-bottom-5">Email Address</label>
							                            <input type="text" id="EmailAddress" name="EmailAddress" class="form-control EmailAddress" placeholder="">
							                          </div>

							                        </div>

							                    </div>

							                  </div>

							                  <div class="row gutter-5 hidden-md hidden-lg">
							                    <div class="col-md-9 col-xs-12">
							                        <div class="row gutter-5 showonsm">

							                          <div class="col-md-4 col-xs-6">
							                            <label class="text-white padding-bottom-5">Gender</label>
							                            <select id="GenderID" name="GenderID" class="form-control GenderID">
							                              <option value="">--</option>
							                              <?php
							                                foreach (lookup('gender') as $k => $v) {
							                                  echo "<option value='{$k}'>{$v}</option>";
							                                }
							                              ?>
							                            </select>
							                          </div>

							                          <div class="col-md-4 col-xs-6">
							                            <label class="text-white padding-bottom-5">Birth Date</label>
							                            <input type="text" autocomplete="off" id="BirthDate" name="BirthDate" class="form-control BirthDate" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask>
							                          </div>

							                          <div class="col-md-4 col-xs-6">
							                            <label class="text-white padding-bottom-5">Contact Number</label>
							                            <input type="text" id="ContactNumber" name="ContactNumber" class="form-control ContactNumber" placeholder="">
							                          </div>

							                          <div class="col-md-12 col-xs-6">
							                            <label class="text-white padding-bottom-5">Email Address</label>
							                            <input type="text" id="EmailAddress" name="EmailAddress" class="form-control EmailAddress" placeholder="">
							                          </div>

							                        </div>
							                    </div>
							                  </div>

							                  <div class="row gutter-5">
							                    <div class="col-md-4 col-xs-6">
							                      <label class="text-white padding-bottom-5">Marital Status</label>
							                      <select id="MaritalStatusID" name="MaritalStatusID" class="form-control">
							                        <option value="">--</option>
							                        <?php
							                          foreach (lookup('marital_status') as $k => $v) {
							                            echo "<option value='{$k}'>{$v}</option>";
							                          }
							                        ?>
							                      </select>
							                    </div>
							                    <div class="col-md-4 col-xs-6">
							                      <label class="text-white padding-bottom-5">Educational Attainment</label>
							                      <select id="EducationalAttainmentID" name="EducationalAttainmentID" class="form-control">
							                        <option value="">--</option>
							                        <?php
							                          foreach (lookup('education') as $k => $v) {
							                            echo "<option value='{$k}'>{$v}</option>";
							                          }
							                        ?>
							                      </select>
							                    </div>
							                    <div class="col-md-4 col-xs-6">
							                      <label class="text-white padding-bottom-5"><span class="hidden-xs">Present</span> Livelihood Status</label>
							                      <select id="LivelihoodStatusID" name="LivelihoodStatusID" class="form-control">
							                        <option value="">--</option>
							                        <?php
							                          foreach (lookup('livelihood') as $k => $v) {
							                            echo "<option value='{$k}'>{$v}</option>";
							                          }
							                        ?>
							                      </select>
							                    </div>
							                    <div class="col-md-12 col-xs-6">
							                      <label class="text-white padding-bottom-5">City or Municipality</label>
							                      <select id="MunicipalityCityID" name="MunicipalityCityID" class="form-control" onChange="Account.loadBarangayOptions(BarangayID, this)">
							                        <option value="">--</option>
							                        <?php
							                          foreach (lookup_muni_city(null, false) as $v) {
							                            echo "<option value='" . $v['citymunCode'] . "'>" . $v['provDesc'] . ' | ' . $v['citymunDesc'] . "</option>";
							                          }
							                        ?>
							                      </select>
							                    </div>
							                    <div class="col-md-12 col-xs-6" id="LoadBarangay">
							                      <label class="text-white padding-bottom-5">Barangay</label>
							                      <select id="BarangayID" disabled="disabled" name="BarangayID" class="form-control">
							                        <option value="">--</option>
							                      </select>
							                    </div>
							                    <div class="col-md-12 col-xs-6">
							                      <label class="text-white padding-bottom-5">Building, Street, etc..</label>
							                      <input type="text" id="StreetPhase" name="StreetPhase" class="form-control">
							                    </div>
							                    <div class="col-md-12 col-xs-12">
							                      <label class="text-white padding-bottom-5">Organization</label>
							                      <select id="OrganizationID" name="OrganizationID" class="form-control">
							                        <option value="">--</option>
							                        <?php
							                           foreach(lookup_all('Dept_Departments', false, 'Name') as $item) {
							                            $orgs = lookup_all('Dept_ChildDepartment', array('Type' => 3, 'DepartmentID' => $item['id']), 'Name');
							                            if (count($orgs)) {
							                              echo '<optgroup label="'.$item['Name'].'">';
							                              foreach ($orgs as $org) {
							                                echo '<option value="'. $org['id'] .'" data-logo="'. logo_filename($org['Logo']) .'">' . $org['Name'] . '</option>';
							                              }
							                              echo '</optgroup>';
							                            }
							                           }
							                        ?>
							                      </select>
							                    </div>
							                  </div>
							                  <div class="row padding-top-10">
							                    <div class="col-md-12">
							                      <strong class="text-cyan" style="color: cyan;">Please upload 2 Valid ID's to process your registration.</strong>

							                      <div class="row">
							                        <div class="col-xs-12 col-sm-6">
							                          <label class="text-white">Valid Government ID.</label>
							                          <div class="input-group mb-3">
							                            <div class="custom-file padding-5">
							                              <input type="file" class="custom-file-input text-white" id="valid_id_one" name="file[valid_id_one]" accept="image/*">
							                            </div>
							                          </div>
							                        </div>
							                        <div class="col-xs-12 col-sm-6">
							                          <label class="text-white">Any Primary ID.</label>
							                          <div class="input-group mb-3">
							                            <div class="custom-file padding-5">
							                              <input type="file" class="custom-file-input text-white" id="valid_id_two" name="file[valid_id_two]" accept="image/*">
							                            </div>
							                          </div>
							                        </div>
							                      </div>

							                    </div>
							                    
							                  </div>

							                  	<input type="hidden" id="ServiceCode" name="ServiceCode" value="">

							                  	<div class="modal-footer padding-bottom-5">
													<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
													<button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Continue</button>
												</div>

							              </form>
										</p>
									</div>
								</div>
							</div>
						

							<!-- Select2 -->
							<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" />
							<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.8/css/alt/AdminLTE-select2.min.css" />
							<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
							<!-- InputMask -->
							<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
							<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/bindings/inputmask.binding.min.js"></script>

							<script type="text/javascript" src="<?php echo public_url(); ?>resources/js/modules/account.js?<?php echo time()?>"></script>

							<script type="text/javascript">
							  $(document).ready(function(){
							    $('#MunicipalityCityID').select2({
							        width: '100%'
							    });
							  });
							</script>

						</div>

					<?php } ?>
					
				</div>
				
			</div>

	</div>
</div>
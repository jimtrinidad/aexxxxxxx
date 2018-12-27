<div class="modal-overs">
  <div class="modal-dialog modal-lg animated fadeInUp">
    
    <div class="modal-content" style="background-color:transparent;">
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <h3 class="m-t-none m-b">Register</h3>
              <div class="login-container col-md-12">
                <div class="row">
                  <div class="col-sm-2"></div>
                  <div class="col-sm-8 text-center center-block float-none">
                    <a href="#"><img src="<?php echo public_url(); ?>resources/images/mak-logo.png" class="img-responsive" /></a>
                  </div>
                </div>
                <form id="RegistrationForm" action="<?php echo site_url('account/register') ?>" enctype="multipart/form-data" >
                  <div id="error_message_box" class="hide row margin-top-20">
                    <br>
                    <div class="error_messages alert alert-danger" role="alert"></div>
                  </div>
                  <input type="hidden" id="RegistrationID" name="RegistrationID" class="form-control" value="<?php echo $RegistrationID; ?>">
                  <div class="row padding-top-10">

                    <div class="col-md-3 col-md-push-9">
                      <div class="image-upload-container padding-top-20">
                        <img class="image-preview" src="<?php echo public_url(); ?>assets/profile/avatar_default.jpg" alt="...">
                        <span class="hiddenFileInput">
                          <input type="file" accept="image/*" class="image-upload-input" id="avatarFile" name="avatarFile"/>
                        </span>
                      </div>
                    </div>

                    <div class="col-md-9 col-md-pull-3">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="text-white padding-bottom-5">First Name</label>
                            <input type="text" id="FirstName" name="FirstName" class="form-control" placeholder="">
                          </div>
                          <div class="col-md-4">
                            <label class="text-white padding-bottom-5">Middle Name</label>
                            <input type="text" id="MiddleName" name="MiddleName" class="form-control has-error" placeholder="">
                          </div>
                          <div class="col-md-4">
                            <label class="text-white padding-bottom-5">Last Name</label>
                            <input type="text" id="LastName" name="LastName" class="form-control" placeholder="">
                          </div>
                        </div>
                        <div class="row padding-top-10">
                          <div class="col-md-4">
                            <label class="text-white padding-bottom-5">Gender</label>
                            <select id="GenderID" name="GenderID" class="form-control">
                              <option value="">--</option>
                              <?php
                                foreach (lookup('gender') as $k => $v) {
                                  echo "<option value='{$k}'>{$v}</option>";
                                }
                              ?>
                            </select>
                          </div>

                          <div class="col-md-4">
                            <label class="text-white padding-bottom-5">Birth Date</label>
                            <input type="text" autocomplete="off" id="BirthDate" name="BirthDate" class="form-control" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask>
                          </div>

                          <div class="col-md-4">
                            <label class="text-white padding-bottom-5">Contact Number</label>
                            <input type="text" id="ContactNumber" name="ContactNumber" class="form-control" placeholder="">
                          </div>
                        </div>
                        <div class="row padding-top-10">
                          <div class="col-md-12">
                            <label class="text-white padding-bottom-5">Email Address</label>
                            <input type="text" id="EmailAddress" name="EmailAddress" class="form-control" placeholder="">
                          </div>

                        </div>
                    </div>
                  </div>

                  <div class="row padding-top-10">
                    <div class="col-md-4">
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
                    <div class="col-md-4">
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
                    <div class="col-md-4">
                      <label class="text-white padding-bottom-5">Present Livelihood Status</label>
                      <select id="LivelihoodStatusID" name="LivelihoodStatusID" class="form-control">
                        <option value="">--</option>
                        <?php
                          foreach (lookup('livelihood') as $k => $v) {
                            echo "<option value='{$k}'>{$v}</option>";
                          }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="row padding-top-10">
                    <div class="col-md-12">
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
                  </div>
                  <div class="row padding-top-10">
                    <div class="col-md-12" id="LoadBarangay">
                      <label class="text-white padding-bottom-5">Barangay</label>
                      <select id="BarangayID" disabled="disabled" name="BarangayID" class="form-control">
                        <option value="">--</option>
                      </select>
                    </div>
                  </div>
                  <div class="row padding-top-10">
                    <div class="col-md-12">
                      <label class="text-white padding-bottom-5">Building, Street, etc..</label>
                      <input type="text" id="StreetPhase" name="StreetPhase" class="form-control">
                    </div>
                  </div>
                  <div class="row padding-top-10">
                    <div class="col-md-12">
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
                      <strong class="text-cyan">Please enter checked two valid government ID to be presented in your barangay to validate your registration.</strong>
                      <div class="row">
                        <div class="col-md-3">
                          <div class="checkbox text-white">
                            <label>
                              <input name="GovernmentID[]" value="1" type="checkbox"> Voters ID
                            </label>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="checkbox text-white">
                            <label>
                              <input name="GovernmentID[]" value="2" type="checkbox"> BIR
                            </label>
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="checkbox text-white">
                            <label>
                              <input name="GovernmentID[]" value="3" type="checkbox"> SSS
                            </label>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="checkbox text-white">
                            <label>
                              <input name="GovernmentID[]" value="4" type="checkbox"> DRIVERS
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                  </div>

                <div class="row">
                  <div class="col-xs-6" style="padding-right:1px;">
                    <a class="btn btn-sm btn-danger btn-block" href="<?php echo site_url('account/signin')?>" ><i class="fa fa-ban"></i> CANCEL</a>
                  </div>
                  <div class="col-xs-6" style="padding-left:1px;">
                    <button type="submit" class="btn btn-sm btn-success btn-block"><i class="fa fa-save"></i> REGISTER</button>
                  </div>
                </div>
              
              </form>
                
              <div class="row" style="padding-top:5px;">
                <div class="col-sm-3"></div>
                <div class="col-sm-6 text-center float-none center-block">
                  <img src="<?php echo public_url(); ?>resources/images/tagline.png" class="img-responsive" />
                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>
      
      <!-- /.modal-content -->
    </div>
  </div>

  <!-- Select2 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.8/css/alt/AdminLTE-select2.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/bindings/inputmask.binding.min.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
    $('#MunicipalityCityID').select2({
        width: '100%'
    });
  });
</script>
<div class="modal fade" id="approveAccountModal" tabindex="-1" role="dialog" aria-labelledby="approveAccountModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="AccountApprovalForm" name="AccountApprovalForm" action="<?php echo site_url('accounts/approve_account') ?>">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><b>Accounts</b> | Approval</h4>
        </div>
        <div class="modal-body">
          <div id="error_message_box" class="hide row">
            <br>
            <div class="error_messages no-border-radius alert alert-danger" role="alert"></div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="accountInfoCont hidden col-xs-12 col-sm-3 padding-top-10 text-center">
                <img style="width: 100px;height: 100px;margin: 10px auto" class="photo" src="">
              </div>
              <div class="accountInfoCont accountInfo hidden col-xs-12 col-sm-9 padding-top-10"></div>
            </div>
            <div class="row padding-top-20">
              <div class="col-xs-6">
                <div class="form-group">
                  <label class="control-label" for="AccountTypeID">Account Type</label>
                  <select id="AccountTypeID" name="AccountTypeID" onChange="Accounts.setLevelOptions(this)" class="form-control">
                    <?php
                    foreach (lookup('account_type') as $k => $v) {
                    echo "<option value='{$k}'>{$v}</option>";
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="col-xs-6">
                <div class="form-group">
                  <label class="control-label" for="AccountLevelID">Account Level</label>
                  <select id="AccountLevelID" name="AccountLevelID" class="form-control">
                    <!-- default -->
                    <option value="1">Citizen</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row padding-top-10">
              <div class="col-xs-12">
                <label class="control-label">Provided ID</label>
                <div class="row accountRequirements">
                  
                </div>
              </div>
            </div>
            <input type="hidden" id="id" name="id">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Approve</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="accountModal" role="dialog" aria-labelledby="accountModal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="AccountForm" name="AccountForm" action="<?php echo site_url('accounts/update_account') ?>" enctype="multipart/form-data">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><b>Account</b> | Details <span class="mabuhay-id padding-right-10 pull-right text-red"></span></h4>
        </div>
        <div class="modal-body">
          <div id="error_message_box" class="hide row">
            <br>
            <div class="error_messages no-border-radius alert alert-danger" role="alert"></div>
          </div>
          <div class="row padding-top-10">
            <div class="col-md-3 col-md-push-9">
              <div class="image-upload-container padding-top-10">
                <img class="image-preview" src="<?php echo public_url(); ?>assets/profile/avatar_default.jpg" alt="...">
                <span class="hiddenFileInput">
                  <input type="file" accept="image/*" class="image-upload-input" id="avatarFile" name="avatarFile"/>
                </span>
              </div>
            </div>
            <div class="col-md-9 col-md-pull-3">
              <div class="row">
                <div class="col-md-4">
                  <label class="text-white s">First Name</label>
                  <input type="text" id="FirstName" name="FirstName" class="form-control" placeholder="">
                </div>
                <div class="col-md-4">
                  <label class="text-white ">Middle Name</label>
                  <input type="text" id="MiddleName" name="MiddleName" class="form-control has-error" placeholder="">
                </div>
                <div class="col-md-4">
                  <label class="text-white ">Last Name</label>
                  <input type="text" id="LastName" name="LastName" class="form-control" placeholder="">
                </div>
              </div>
              <div class="row padding-top-10">
                <div class="col-md-4">
                  <label class="text-white ">Gender</label>
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
                  <label class="text-white ">Birth Date</label>
                  <input type="text" autocomplete="off" id="BirthDate" name="BirthDate" class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask>
                </div>
                <div class="col-md-4">
                  <label class="text-white ">Contact Number</label>
                  <input type="text" id="ContactNumber" name="ContactNumber" class="form-control" placeholder="">
                </div>
              </div>
              <div class="row padding-top-10">
                <div class="col-md-12">
                  <label class="text-white ">Email Address</label>
                  <input type="text" id="EmailAddress" name="EmailAddress" class="form-control" placeholder="">
                </div>
              </div>
            </div>
          </div>
          <div class="row padding-top-20">
            <div class="col-md-4">
              <label class="text-white ">Marital Status</label>
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
              <label class="text-white ">Educational Attainment</label>
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
              <label class="text-white ">Present Livelihood Status</label>
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
              <label class="text-white ">City or Municipality</label>
              <select id="MunicipalityCityID" name="MunicipalityCityID" class="form-control" onChange="Accounts.loadBarangayOptions(BarangayID, this)">
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
              <label class="text-white ">Barangay</label>
              <select id="BarangayID" name="BarangayID" class="form-control">
                <option value="">--</option>
              </select>
            </div>
          </div>
          <div class="row padding-top-10">
            <div class="col-md-12">
              <label class="text-white ">Building, Street, etc..</label>
              <input type="text" id="StreetPhase" name="StreetPhase" class="form-control">
            </div>
          </div>
          <div class="row padding-top-10">
            <div class="col-md-12">
              <label class="text-white padding-bottom-5">Orginazation</label>
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
          <div class="row padding-top-20" id="account-level-container">
            <div class="col-xs-4">
              <div class="form-group">
                <label class="control-label" for="AccountTypeID">Account Type</label>
                <select id="AccountTypeID" name="AccountTypeID" onChange="Accounts.setLevelOptions(this)" class="form-control">
                  <?php
                  foreach (lookup('account_type') as $k => $v) {
                  echo "<option value='{$k}'>{$v}</option>";
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="col-xs-4">
              <div class="form-group">
                <label class="control-label" for="AccountLevelID">Account Level</label>
                <select id="AccountLevelID" name="AccountLevelID" class="form-control">
                  <!-- default -->
                  <option value="1">Citizen</option>
                </select>
              </div>
            </div>
            <div class="col-xs-4">
              <div class="form-group">
                <label class="control-label" for="StatusID">Account Status</label>
                <select id="StatusID" name="StatusID" class="form-control">
                  <?php
                  foreach (lookup('account_status') as $k => $v) {
                  echo "<option value='{$k}'>{$v}</option>";
                  }
                  ?>
                </select>
              </div>
            </div>
          </div>
          <input type="hidden" name="id" id="id">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-info">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
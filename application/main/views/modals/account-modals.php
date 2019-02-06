<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModal">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <form id="ChangePasswordForm" name="ChangePasswordForm" action="<?php echo site_url('account/changep') ?>">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><b class="text-bold">Account</b> | Change Password</h4>
        </div>
        <div class="modal-body">
          <div id="error_message_box" class="hide row">
            <br>
            <div class="error_messages no-border-radius alert alert-danger small" role="alert"></div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-xs-12">
                <div class="form-group">
                  <label class="control-label" for="currentp">Current Password</label>
                  <input type="password" class="form-control" id="currentp" name="currentp" placehoder="Current password">
                  <span class="help-block hidden"></span>
                </div>
              </div>
              <div class="col-xs-12">
                <div class="form-group">
                  <label class="control-label" for="newp">New Password</label>
                  <input type="password" class="form-control" id="newp" name="newp" placehoder="New password">
                  <span class="help-block hidden"></span>
                </div>
              </div>
              <div class="col-xs-12">
                <div class="form-group">
                  <label class="control-label" for="confirmp">Confirm Password</label>
                  <input type="password" class="form-control" id="confirmp" name="confirmp" placehoder="Confirm password">
                  <span class="help-block hidden"></span>
                </div>
              </div>
            </div>
            <input type="hidden" name="mid" value="<?php echo $accountInfo->MabuhayID?>">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Change</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="changeProfileModal" tabindex="-1" role="dialog" aria-labelledby="changeProfileModal">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <form id="ChangeProfileForm" name="ChangeProfileForm" action="<?php echo site_url('account/changeprofile') ?>" enctype="multipart/form-data">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"><b class="text-bold">Account</b> | Change Profile</h4>
        </div>
        <div class="modal-body">
          <div id="error_message_box" class="hide row">
            <br>
            <div class="error_messages no-border-radius alert alert-danger small" role="alert"></div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-xs-12 text-center">
                <div class="bigger image-upload-container padding-top-10">
                  <img class="image-preview" src="<?php echo public_url(); ?>assets/profile/<?php echo $accountInfo->Photo?>" alt="...">
                  <span class="hiddenFileInput hide">
                    <input type="file" accept="image/*" class="image-upload-input" id="avatarFile" name="avatarFile"/>
                  </span>
                </div>
                <span class="help-block">Click on the photo to choose new one</span>
              </div>
            </div>
            <input type="hidden" name="mid" value="<?php echo $accountInfo->MabuhayID?>">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
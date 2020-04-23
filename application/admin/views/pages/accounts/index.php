<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary small">

      <div class="box-header">
        <h3 class="box-title">Accounts List</h3>
        <div class="box-tools">
          <form action="<?php echo site_url('accounts') ?>" method="post" class="form-inline">
            <div class="form-group">
              <select class="form-control input-sm" id="search_account_status" name="search_account_status">
                <option value="">Account Status</option>
                <?php
                  foreach (array(100 => 'Pending') + lookup('account_status') as $k => $v) {
                    echo "<option ". ($search_account_status == $k ? 'selected="selected"' : '') ." value='{$k}'>{$v}</option>";
                  }
                ?>
              </select>
            </div>
            <div class="form-group">
              <select class="form-control input-sm" id="search_account_level" name="search_account_level" style="min-width: 150px;">
                <option value="">Account Level</option>
                <?php
                  foreach ($account_levels as $v) {
                    echo "<option ". ($search_account_level == $v['id'] ? 'selected="selected"' : '') ."  value='". $v['id'] ."'>". $v['LevelName'] ."</option>";
                  }
                ?>
              </select>
            </div>
            <?php if ($this->session->userdata('alevel') >= 13) { ?>
            <div class="form-group">
              <select id="search_account_city" name="search_account_city" class="form-control input-sm">
                  <option value="">City Or Municipal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                  <?php
                  foreach (lookup_muni_city(null, false) as $v) {
                  echo "<option ". ($search_account_city == $v['citymunCode'] ? 'selected="selected"' : '') ." value='" . $v['citymunCode'] . "'>" . $v['provDesc'] . ' | ' . $v['citymunDesc'] . "</option>";
                  }
                  ?>
              </select>
            </div>
            <?php } ?>
            <div class="form-group">
              <input type="text" autocomplete="off" id="search_mid" name="search_mid" value="<?php echo $search_mid ?>" class="form-control input-sm" placeholder="Mabuhay ID">
            </div>
            <div class="form-group">
              <input type="text" autocomplete="off" id="search_name" name="search_name" value="<?php echo $search_name ?>" class="form-control input-sm" placeholder="Name">
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-sm btn-success">Search</button>
            </div>
          </form>
        </div>
      </div>
      <!-- /.box-header -->

      <div class="box-body table-responsive">
        <table class="table table-bordered">
          <thead>
            <th>Mabuhay ID</th>
            <th>Name</th>
            <th class="hidden-xs hidden-sm">Email</th>
            <th class="hidden-xs">Contact</th>
            <th>Location</th>
            <th class="hidden-xs">Type</th>
            <th>Level</th>
            <th>Status</th>
            <th class="visible-lg">Regisration Date</th>
            <th class="c"></th>
          </thead>
          <tbody>
            <?php
            foreach ($accounts as $account) {
              echo "<tr class='text-left'>";
                echo '<td>' . $account['mabuhay_id'] . '</td>';
                echo '<td>' . $account['fullname'] . '</td>';
                echo '<td class="hidden-xs hidden-sm">' . $account['email'] . '</td>';
                echo '<td class="hidden-xs">' . $account['contact'] . '</td>';
                echo '<td>' . strtoupper(preg_replace('~\([^()]*\)~', '', implode(', ', array_reverse(array_slice($account['address'], -2, 2))))) . '</td>';
                echo '<td class="hidden-xs">' . $account['account_type'] . '</td>';
                echo '<td>' . $account['account_level'] . '</td>';
                echo '<td>' . $account['account_status'] . '</td>';
                echo '<td class="visible-lg">' . $account['reg_date'] . '</td>';
                echo '<td>
                        <div class="box-tools">
                          <div class="input-group pull-right" style="width: 10px;">
                            <div class="input-group-btn">' .
                            (
                              $account['a_status_id'] == 0
                              ? '
                                <button type="button" class="btn btn-xs btn-success" title="Approve account" onClick="Accounts.prepareAccountApproval('.$account['id'].')"><i class="fa fa-check"></i><span class="visible-lg-inline"> Approve</span></button>'
                              : '' 
                            )
                              . '
                              <button type="button" class="btn btn-xs btn-default" title="View details" onClick="Accounts.editAccount('.$account['id'].')"><i class="fa fa-pencil"></i><span class="visible-lg-inline"> Details</span></button>
                              <button type="button" class="btn btn-xs btn-warning" title="Generate new password." onClick="Accounts.resetPassword('.$account['id'].')"><i class="fa fa-key"></i></button>
                              <button type="button" class="btn btn-xs btn-danger" title="Delete" onClick="Accounts.deleteAccount('.$account['id'].')"><i class="fa fa-trash"></i></button>
                            </div>
                          </div>
                        </div> 
                      </td>';
              echo '</tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    <!-- /.box-body -->

      <div class="box-footer clearfix">
        <?php echo $pagination ?>
      </div>
    </div>
  </div>
</div>

<?php view('pages/accounts/modals.php'); ?>

  <!-- Select2 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.8/css/alt/AdminLTE-select2.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/bindings/inputmask.binding.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js"></script>

<script type="text/javascript">
  $(document).ready(function(){
    Accounts.accountData = <?php echo json_encode($accounts, JSON_HEX_TAG); ?>;
    Accounts.accountLevels = <?php echo json_encode($account_levels, JSON_HEX_TAG); ?>;

    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });

  });
</script>

<style type="text/css">
  .box-tools .select2-container--default .select2-selection--single, .box-tools .select2-selection .select2-selection--single {
      border: 1px solid #d2d6de;
      border-radius: 0;
      padding: 4px 12px;
      height: 30px;
  }

  .box-tools .select2-container--default .select2-selection--single .select2-selection__arrow {
      top: -1px;
  }
</style>
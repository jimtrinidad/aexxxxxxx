<div class="modal fade" id="depositModal" tabindex="-1" role="dialog" aria-labelledby="depositModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="depositForm" name="depositForm" action="<?php echo site_url('wallet/add_deposit') ?>">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <div id="error_message_box" class="hide row">
            <br>
            <div class="error_messages no-border-radius alert alert-danger small" role="alert"></div>
          </div>
          <div class="row gutter-5">
            <div class="col-xs-12">
              <div class="form-group">
                <label class="control-label" for="Bank">Bank</label>
                <input class="form-control" type="text" name="Bank" id="Bank" placeholder="Bank">
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-xs-12">
              <div class="form-group">
                <label class="control-label" for="Branch">Branch</label>
                <input class="form-control"  type="text" name="Branch" id="Branch" placeholder="Branch">
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-xs-12">
              <div class="form-group">
                <label class="control-label" for="ReferenceNo">Reference Number</label>
                <input class="form-control"  type="text" name="ReferenceNo" id="ReferenceNo" placeholder="Reference Number">
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                <label class="control-label" for="Date">Deposit Date</label>
                <input class="form-control"  type="date" name="Date" id="Date" placeholder="Deposit Date">
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6">
              <div class="form-group">
                <label class="control-label" for="Amount">Deposit Amount</label>
                <input class="form-control"  type="number" step=".01" name="Amount" id="Amount" placeholder="Deposit Amount">
                <span class="help-block hidden"></span>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Send Deposit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="paymentModal" role="dialog" aria-labelledby="paymentModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="paymentForm" name="paymentForm" action="<?php echo site_url('wallet/add_payment') ?>">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <div id="error_message_box" class="hide row">
            <br>
            <div class="error_messages no-border-radius alert alert-danger small" role="alert"></div>
          </div>
          <div class="row gutter-5">
            <div class="col-xs-12">
              <div class="form-group">
                <label class="control-label" for="Biller">Biller/Merchant</label>
                <select class="form-control" id="Biller" name="Biller">
                  <option value="">--Biller/Merchant--</option>
                  <?php
                  $billers = lookup_dbp_billers();
                  if ($billers) {
                    foreach ($billers as $v) {
                      echo '<optgroup label="'.$v['category'].'">';
                      foreach ($v['items'] as $biller) {
                        echo '<option value="' . $biller['Code'] . '" data-logo="'. $biller['Logo'] .'">' . $biller['Name'] . '</option>';
                      }
                      echo '</optgroup>';
                    }
                  }
                  ?>
                </select>
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-xs-12">
              <div class="form-group">
                <label class="control-label" for="ReferenceNo">Reference No/Account No</label>
                <input class="form-control" type="text" name="ReferenceNo" id="ReferenceNo" placeholder="Reference No/Account No">
                <span class="help-block hidden"></span>
              </div>
            </div>
<!--             <div class="col-xs-12">
              <div class="form-group">
                <label class="control-label" for="Description">Description</label>
                <input class="form-control"  type="text" name="Description" id="Description" placeholder="Description">
                <span class="help-block hidden"></span>
              </div>
            </div> -->
            <div class="col-xs-12">
              <div class="form-group">
                <label class="control-label" for="Amount">Amount</label>
                <input class="form-control"  type="number" step=".01" name="Amount" id="Amount" placeholder="Amount">
                <span class="help-block hidden"></span>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Send Payment</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="eLoadModal" tabindex="-1" role="dialog" aria-labelledby="eLoadModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="eLoadForm" name="eLoadForm" action="<?php echo site_url('wallet/eload') ?>">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <div id="error_message_box" class="hide row">
            <br>
            <div class="error_messages no-border-radius alert alert-danger small" role="alert"></div>
          </div>
          <div class="row gutter-5">
            <div class="col-xs-12">
              <div class="form-group">
                <label class="control-label" for="ServiceProvider">Service Provider</label>
                <select class="form-control" id="ServiceProvider" name="ServiceProvider">
                  <option value="">--Service Provider--</option>
                  <?php
                    foreach (lookup('mobile_service_provider') as $k => $v) {
                      echo "<option value='{$k}'>{$v}</option>";
                    }
                  ?>
                </select>
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-xs-12">
              <div class="form-group">
                <label class="control-label" for="Number">11 Digits Number</label>
                <input class="form-control"  type="text" name="Number" id="Number" placeholder="11 Digits Number">
                <span class="help-block hidden"></span>
              </div>
            </div>
            <div class="col-xs-12">
              <div class="form-group">
                <label class="control-label" for="Amount">Amount</label>
                <input class="form-control"  type="number" step=".01" name="Amount" id="Amount" placeholder="Amount">
                <span class="help-block hidden"></span>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Send a Load</button>
        </div>
      </form>
    </div>
  </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.8/css/alt/AdminLTE-select2.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>

<style type="text/css">
  .select2-container--default .select2-selection--single, .select2-selection .select2-selection--single {
    border-radius: 4px !important;
    border-color: #ccc !important;
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
  }

  .select2-container--default .select2-results>.select2-results__options{
    max-height: 300px;
  }
</style>
<script type="text/javascript">
  $(document).ready(function(){
    $('#Biller').select2({
        width: '100%',
        templateResult: function (selection) {
            if (!selection.id) { return selection.text; }
            var logo = $(selection.element).data('logo');
            if(!logo){
                return selection.text;
            } else {
                return $('<img style="width: 30px;height: 30px;vertical-align: middle;" src="' + window.public_url() + 'assets/logo/' + logo + '" alt=""> \
                                        <span class="img-changer-text"> ' + $(selection.element).text() + '</span>');
            }
        }
    });
  });
</script>
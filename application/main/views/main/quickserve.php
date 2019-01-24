<div class="quickserve row padding-top-20">
  <div class="col-md-12">
    <h1>Mobile Government Integrated System QuickServe Platform</h1>
    
    <!-- Form -->
    <form method="">
      <div class="form offset-top-10">
        <div class="row">
          <div class="col-md-3">
            <label class="text-white text-bold padding-bottom-5">Mabuhay ID</label>
            <input type="text" name="mabuhayID" class="form-control input-sm" value="<?php echo get_post('mabuhayID')?>">
          </div>
          <div class="col-md-2">
            <label class="text-white text-bold padding-bottom-5">Date Applied</label>
            <input type="text" name="date" class="form-control input-sm" value="<?php echo get_post('date')?>">
          </div>
          <div class="col-md-3">
            <label class="text-white text-bold padding-bottom-5">Transaction #</label>
            <input type="text" name="applicationCode" class="form-control input-sm" value="<?php echo get_post('applicationCode')?>">
          </div>
          <div class="col-md-2">
            <label class="text-white text-bold padding-bottom-5">Status</label>
            <select class="form-control input-sm" name="status">
              <option value="" <?php echo get_post('status') == '' ? 'selected="selected"' : ''?>></option>
              <option value="0" <?php echo get_post('status') == '0' || get_post('status') === null ? 'selected="selected"' : ''?>>New</option>
              <option value="1" <?php echo get_post('status') == '1' ? 'selected="selected"' : ''?>>Processing</option>
              <option value="2" <?php echo get_post('status') == '2' ? 'selected="selected"' : ''?>>Completed</option>
            </select>
          </div>
          <div class="col-md-2">
            <label class="text-white text-bold padding-bottom-5">&nbsp;</label>
            <button class="btn btn-block btn-sm bg-cyan text-white">Search</button>
          </div>
        </div>
      </div>
    </form>
    <!-- Form End-->
    <!-- Actions and Commands -->
    <div class="padding-top-20">
      <strong class="text-yellow padding-bottom-5">Actions and Commands:</strong> <br class="visible-xs"><br class="visible-xs">
      <span class="text-white offset-right-5">
        <i class="fa fa-check padding-5 bg-cyan text-white offset-right-5" aria-hidden="true"></i> Approve
      </span>
      <span class="text-white offset-right-5">
        <i class="fa fa-envelope-o padding-5 bg-orange text-white offset-right-5" aria-hidden="true"></i> Message
      </span>
      <span class="text-white offset-right-5">
        <i class="fa fa-times padding-5 bg-green text-white offset-right-5" aria-hidden="true"></i> Decline
      </span>
      <span class="text-white offset-right-5">
        <i class="fa fa-search padding-5 bg-cyan text-white offset-right-5" aria-hidden="true"></i> Details
      </span> <br class="visible-xs">
      <span class="text-white offset-right-5">
        <i class="fa fa-credit-card padding-5 bg-yellow text-black offset-right-5 offset-top-5" aria-hidden="true"></i> Payment
      </span>
      <span class="text-white offset-right-5">
        <i class="fa fa-comments padding-5 bg-blue text-white offset-right-5" aria-hidden="true"></i> Feedback
      </span>
    </div>
  </div>
</div>

<!-- Quickserve Table -->
<div class="table-responsive offset-top-20">
  <table class="table table-transparent text-white">
    <thead class="bg-blue text-white">
      <tr>
        <td>#</td>
        <td>Transaction #</td>
        <td>Mabuhay ID</td>
        <td>Barangay</td>
        <td>Status</td>
        <td>Service</td>
        <td>For</td>
        <td>Task</td>
        <td>Action</td>
        <td>Service Duration</td>
        <td>Req. Docs</td>
        <td>Progress</td>
        <td>Last Update</td>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($items as $k => $item) {
        echo "<tr class='text-left' data-safid='".$item['safID']."'>";
          echo '<td>' . ($k+1) . '</td>';
          echo '<td>' . $item['ApplicationCode'] . '</td>';
          echo '<td>' . $item['MabuhayID'] . '</td>';
          echo '<td>' . strtolower($item['Barangay']) . '</td>';
          echo '<td>' . $item['functionStatus'] . '</td>';
          echo '<td>' . $item['ServiceName'] . '</td>';
          echo '<td>' . $item['documentName'] . '</td>';
          echo '<td>' . $item['FunctionName'] . '</td>';
          echo '<td class="qs-buttons">';
            if ($item['safStatus'] == 0) {
              echo '<a href="javascript:;" onClick="Quickserve.approveProcess(this);"><i class="fa fa-check padding-5 bg-cyan text-white" aria-hidden="true"></i></a>';
            }
            
            echo '<a href="javascript:;" onClick="Chatbox.openChatbox(\'' . $item['MabuhayID'] . '\');"><i class="fa fa-envelope-o padding-5 bg-orange text-white" aria-hidden="true"></i></a>';
            if ($item['safStatus'] == 0) {
              echo '<a href="javascript:;" onClick="Quickserve.declineProcess(this);"><i class="fa fa-times padding-5 bg-green text-white" aria-hidden="true"></i></a>';
            }
            echo '<a href="javascript:;" onClick="Quickserve.viewDetails(this);"><i class="fa fa-search padding-5 bg-cyan text-white" aria-hidden="true"></i></a>';
            echo '<a href="javascript:;" onClick="Quickserve.viewFeedbacks(this);"><i class="fa fa-comments padding-5 bg-blue text-white" aria-hidden="true"></i></a>';
            // payments
            if ($item['FunctionTypeID'] == 4) {
              if ($item['safStatus'] == 0) {
                echo '<a href="javascript:;" onClick="Quickserve.setPayment(this);"><i class="fa fa-credit-card padding-5 bg-yellow text-black" aria-hidden="true"></i></a>';
              } else {
                if ($item['paymentInfo']) {
                  echo '<a href="javascript:;" onClick="Quickserve.paymentReceipt('.$item['paymentInfo']->id.');"><i class="fa fa-file-text padding-5 bg-yellow text-black" aria-hidden="true"></i></a>';
                }
              }
            }
          echo '</td>';
          echo '<td>' . $item['duration'] . '</td>';
          echo '<td>' . $item['reqProgress'] . '</td>';
          echo '<td>' . $item['progress'] . '%</td>';
          echo '<td>' . date('Y/m/d', strtotime($item['LastUpdate'])) . '</td>';
        echo '</tr>';
      }
      ?>
    </tbody>
  </table>
</div>

<input type="hidden" id="chat_current_user" value="<?php echo current_user() ?>">
<input type="hidden" id="current_user_name" value="<?php echo $accountInfo->FirstName . ' ' . $accountInfo->LastName ?>">

<?php view('modals/quickserve') ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/autosize.js/4.0.2/autosize.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker3.min.css" />


<script type="text/javascript">
  $(document).ready(function(){
    Quickserve.items = <?php echo json_encode($items, JSON_HEX_TAG);?>;
  });
</script>
<style type="text/css">
  .qs-buttons a {margin: 2px;}
  #feedbackModal .user_name{
      font-size:14px;
      font-weight: bold;
  }
  #feedbackModal .comments-list {
    min-height: 100px;
  }
  #feedbackModal .comments-list .media{
      border-bottom: 1px dotted #ccc;
      padding-bottom: 10px;
  }
</style>
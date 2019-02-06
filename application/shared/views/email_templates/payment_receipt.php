<!DOCTYPE html>
<html>
  <head>
    <title>Receipt</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <style type="text/css">
    <!--
    .body {
      font-family: "Times New Roman", Arial, Helvetica, sans-serif;
      color: #073963;
      width: 450px;
      max-width: 450px;
      margin: 0 auto;
      padding: 10px;
    }
    .style1 {
      font-size: 20px;
      font-weight: bold;
    }
    .style2 {
      font-size: 13px;
      font-weight: bold;
    }
    .style5 {
      font-size: 13px;
      color: #073963;
      margin-top: -4px;
    }
    .style8 {font-size: 14px; font-weight: bold; color: #073963; }
    .style9 {color: #073963}
    .style10 {color: #990000}
    .style12 {
      font-weight: bold;
      color: #073963;
    }
    .style14 {font-size: 12px; color: #073963; }

    .style15 {
      font-size: 13px;
      line-height: 1;
    }

    .border {
      border-radius: 5px;
      border: 2px solid #92a1b7;
      margin-bottom: 5px;
    }


    .top-logo {
          max-width: 70px;
          width: 100%;
    }

    .zeropad{
      padding: 0;
    }

    .sidepad {
      padding: 0 10px;
    }

    .centerstyle{
      margin-top: -6px;
      font-size: 15px;
      text-align: center;
    }

    .img_checkbox {
      width: 16px;height: 16px;margin: -2px -1px 0 0;
    }

    .rtable {
      padding: 0;
      margin: 0;
    }

    .text-center {
      text-align: center;
    }

    .text-left {
      text-align: left;
    }

    .text-right {
      text-align: right;
    }

    table.rtable {
      border-collapse: collapse;
      width: 100%;
    }
    table.rtable td, table.rtable th {
      border: 1px solid #92a1b7;
      vertical-align: middle !important;
      padding: 2px 5px !important;
    }
    table.rtable tr:first-child th {
      border-top: 0;
    }
    table.rtable tr:last-child td {
      border-bottom: 0;
    }
    table.rtable thead tr:last-child td,
    table.rtable tfoot tr:last-child td {
      border-bottom: 1px solid #92a1b7;
    }
    table.rtable tfoot tr:first-child td {
      border-top: 1px solid #92a1b7;
    }
    table.rtable tr td:first-child,
    table.rtable tr th:first-child {
      border-left: 0;
    }
    table.rtable tr td:last-child,
    table.rtable tr th:last-child {
      border-right: 0;
    }

    -->
    </style>
  </head>
  <body>
    <div class="body">
      <div class="row gutter-0 border">
        <div class="col-xs-12 zeropad">
          <table class="table table-condensed rtable">
            <tr>
              <td style="border: 0;width: 25%;vertical-align: middle;text-align: center;">
                <img src="<?php echo public_url(); ?>resources/images/rp.png" class="top-logo" />
              </td>
              <td style="border: 0;width: 50%;text-align: center;">
                <span class="style1">OFFICIAL RECEIPT</span>
                <span class="style9"><br>
                  Republic of the Philippines<br />
                </span>
                <?php if ($scopeName) {
                  echo '<span style="font-size:12px;">' . $scopeName . '</span><br>';
                } ?>
                <span class="style2">OFFICE OF THE TREASURER</span>
              </td>
              <td style="border: 0;width: 25%;vertical-align: middle;text-align: center;">
                <img src="<?php echo public_url() . 'assets/logo/' . logo_filename($scopeLogo);?>" class="top-logo"  />
              </td>
            </tr>
          </table>
        </div>
      </div>

      <div class="row gutter-0 border zeropad">
        <div class="col-xs-12 zeropad">
          <table class="table table-condensed rtable">
            <thead>
              <tr>
                <td width="50%">
                  <span class="style9 style15">
                    <div>Accountable Table Form No.</div>
                    <div>Revised August 1994</div>
                  </span>
                </td>
                <td class="text-center">
                  <span class="style8">ORIGINAL</span>
                </td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="height: 30px">
                  <div class="style5">Date</div>
                  <div class="centerstyle"><?php echo $paymentData->date; ?></div>
                </td>
                <td class="text-center">
                  <span class="style10">No. <?php echo str_pad($paymentData->id, 8, '0', STR_PAD_LEFT) ?></span> <span class="style12">B </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="row gutter-0 border zeropad">
        <div class="col-xs-12 zeropad">
          <table class="table table-condensed rtable">
            <tr>
              <td style="border-top: 0;">
                <div class="style5">Payor</div>
                <div class="centerstyle"><?php echo ($paymentData->payor ?? $payorData->FirstName . ' ' . $payorData->LastName); ?></div>
              </td>
            </tr>
          </table>
        </div>
      </div>

      <div class="row gutter-0 border zeropad">
        <div class="col-xs-12 zeropad">
          <table class="table table-condensed rtable">
            <thead>
              <tr>
                <td width="45%" class="text-center">
                  <span class="style9 style15">
                    Nature of Collection
                  </span>
                </td>
                <td width="30%" class="text-center">
                  <span class="style9 style15">
                    Fund and Account Code
                  </span>
                </td>
                <td class="text-center">
                  <span class="style9 style15">
                    Amount
                  </span>
                </td>
              </tr>
              <tr>
                <td></td>
                <td></td>
                <td><span class="style14">P</span></td>
              </tr>
            </thead>
            <tbody>
              <?php
                $collections = (array) @json_decode($paymentData->collections, true);
                $total = 0;
                foreach ($collections as $i => $k) {
                  $total += $k['amount'];
                  echo '<tr>';
                  echo '<td>'. $k['name'] .'</td>';
                  echo '<td>'. $k['code'] .'</td>';
                  echo '<td class="text-right">'. number_format($k['amount']) .'</td>';
                  echo '</tr>';
                }
                // row placeholder
                for (;$i < 4; $i++) {
                  echo '<tr>
                        <td></td>
                        <td></td>
                        <td>&nbsp;</td>
                      </tr>';
                }
              ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="2"></td>
                <td class="text-right">
                  <span class="style14 pull-left">P</span>
                  <?php echo number_format($total) ?>
                </td>
              </tr>
            </tfoot>
          </table>
          <table class="table table-condensed rtable" style="margin-top: 5px;">
            <tr>
              <td style="border-top: 1px solid #92a1b7;">
                <div class="style5">Amount in Words:</div>
                <div class="centerstyle"><?php echo ucwords(number_to_words($total)) ?> Pesos Only</div>
              </td>
            </tr>
          </table>
        </div>
      </div>

      <div class="row gutter-0 border zeropad">
        <div class="col-xs-12 zeropad">
          <table class="table table-condensed rtable">
            <tbody>
              <tr>
                <td width="60%">
                  <p>Received:<br />
                    <img src="<?php echo public_url() . 'resources/images/' . (strtolower($paymentData->type) == 'cash' ? 'checked-square' : 'square') ?>.png" class='img_checkbox'> Cash<br />
                    <img src="<?php echo public_url() . 'resources/images/' . (strtolower($paymentData->type) == 'treasury warrant' ? 'checked-square' : 'square') ?>.png" class='img_checkbox'> Treasury Warrant<br />
                    <img src="<?php echo public_url() . 'resources/images/' . (strtolower($paymentData->type) == 'check' ? 'checked-square' : 'square') ?>.png" class='img_checkbox'> Check<br />
                    <img src="<?php echo public_url() . 'resources/images/' . (strtolower($paymentData->type) == 'money order' ? 'checked-square' : 'square') ?>.png" class='img_checkbox'> Money Order
                  </p>
                </td>
                <td rowspan="3" style="border-bottom: 0;border-top: 0;text-align: center;">
                  <p class="style14">Received the amount Field Above</p>
                  </p>
                  <div class="style14 text-center"><b><?php echo $paymentData->treasurer; ?></b></div>
                  <div class="style14 text-center">
                    ________________________<br />
                    <?php echo ucfirst($paymentData->scope); ?> Treasurer <br>
                    Collection Officer
                    <div class="padding-10 text-center">
                      <img src="<?php echo public_url() . 'assets/qr/' . $serviceQR; ?>" class=" top-logo"/>
                    </div>
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <span class="style14">Treasury Warrant, Check, Money Order No.</span>
                </td>
              </tr>
              <tr>
                <td>
                  <span class="style14">Date of Treasury Warrant, Check, Money Order No.</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </body>
</html>
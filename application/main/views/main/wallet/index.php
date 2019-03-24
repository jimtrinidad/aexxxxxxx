<!-- Filter Services-->
                <div class="bg-grey padding-10 offset-bottom-10 offset-top-10">
                    <div class="row">
                        <div class="col-xs-8 text-bold text-white padding-top-5 padding-bottom-5">MyWallet Mobile Processing and Verification</div>
                        <div class="col-xs-4 text-right">
                          <button class="btn btn-sm btn-info bg-cyan" onclick="Wallet.addDeposit()"><i class="fa fa-plus"></i> Fund</button>
                        </div>
                    </div>
                </div>   

                <!-- Account Details -->
                <div class="text-white row padding-bottom-10">
                  <div class="col-md-3">
                    <span class="text-yellow">BALANCE:</span>  <?php echo number_format($summary['balance']) ?>     
                  </div>
                  <div class="col-md-3">
                    <span class="text-yellow">Total Transactions:</span>  <?php echo number_format($summary['transactions']) ?>     
                  </div>
                  <div class="col-md-3">      
                    <span class="text-yellow">Total Debit:</span> <?php echo number_format($summary['debit']) ?>      
                  </div>
                  <div class="col-md-3">
                    <span class="text-yellow">Total Credit:</span> <?php echo number_format($summary['credit']) ?>     
                  </div> 
                </div>

                <!-- <div class="clearfix padding-top-10 padding-bottom-10">
                  <h2 class="text-yellow pull-left text-bold">Transactions</h2>
                  <button class="btn btn-sm btn-danger pull-right" onclick="Wallet.addPayment()">Add Test Payment</button>
                </div> -->
                <?php if (count($transactions)) { ?>
                <div class="bg-white table-responsive">
                  <table class="table table-striped">
                        <thead class="bg-green text-white text-upper text-bold">
                          <tr>
                            <th>Date</th>
                            <th>Transaction #</th>
                            <th>Description</th>
                            <th class="text-center">Debit</th>
                            <th class="text-center">Credit</th>
                            <th class="text-center">Balance</th>
                          </tr>
                        </thead>
                        <tbody class="bg-white">
                          <?php
                          foreach ($transactions as $i) {
                            echo '<tr>';
                              echo '<td>' . date('d F, Y', strtotime($i['Date'])) . '</td>';
                              echo '<td>' . $i['Code'] . '</td>';
                              echo '<td>' . $i['Description'] . '</td>';
                              echo '<td class="text-center">' . ($i['debit'] ? number_format($i['debit']) : '') . '</td>';
                              echo '<td class="text-center">' . ($i['credit'] ? number_format($i['credit']) : '') . '</td>';
                              echo '<td class="text-center">' . number_format($i['EndingBalance']) . '</td>';
                            echo '</tr>';
                          }
                          ?>
                        </tbody>
                  </table>
                </div>
                <?php 
                  } else {
                    echo '<h3 class="text-white">No transactions found.</h3>';
                  } 
                ?>
                <!-- Related List End-->  


<?php view('main/wallet/modal'); ?>
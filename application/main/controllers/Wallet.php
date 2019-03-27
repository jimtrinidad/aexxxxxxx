<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Wallet extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // require login
        check_authentication();
    }

    public function index()
    {
        $viewData = array(
            'pageTitle'     => 'My Wallet',
            'accountInfo'   => user_account_details(),
            'jsModules'     => array(
                'wallet'
            )
        );

        $transactions = $this->mgovdb->getRecords('WalletTransactions', array('AccountID' => current_user()), 'Date DESC');
        $summary      = array(
            'balance'   => 0,
            'debit'     => 0,
            'credit'    => 0,
            'transactions'  => 0
        );

        foreach ($transactions as &$i) {
            if ($i['Type'] == 'Credit') {
                $i['credit'] = $i['Amount'];
                $i['debit']  = false;
                $summary['credit'] += $i['Amount'];
                $summary['balance'] += $i['Amount'];
                $summary['transactions']++;
            } else {
                $i['debit']  = $i['Amount'];
                $i['credit'] = false;
                $summary['debit'] += $i['Amount'];
                $summary['balance'] -= $i['Amount'];
                $summary['transactions']++;
            }
        }

        $viewData['transactions'] = $transactions;
        $viewData['summary']      = $summary;

        view('main/wallet/index', $viewData, 'templates/mgov');
    }


    public function add_deposit()
    {
        if (validate('add_deposit') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {

            $saveData = array(
                'Code'              => microsecID(),
                'Bank'              => get_post('Bank'),
                'Branch'            => get_post('Branch'),
                'AccountID'         => current_user(),
                'ReferenceNo'       => get_post('ReferenceNo'),
                'TransactionDate'   => get_post('Date'),
                'Amount'            => get_post('Amount'),
                'Status'            => 0, // pending
                'DateAdded'         => date('Y-m-d H:i:s')  
            );

            if (($ID = $this->mgovdb->saveData('WalletDeposits', $saveData))) {

                $return_data = array(
                    'status'    => true,
                    'message'   => ucfirst(number_to_words(get_post('Amount'))) . ' pesos deposit has been requested. It will be credited to your wallet upon verification.',
                    'id'        => $ID
                );

                $this->verify_deposit($saveData['Code']);

            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Adding deposit slip failed. Please try again later.'
                );
            }

        }

        response_json($return_data);
    }

    public function verify_deposit($code = null)
    {
        if (!$code) {
            $code = get_post('code');
        }

        $deposit = $this->mgovdb->getRowObject('WalletDeposits', $code, 'Code');
        if ($deposit) {

            if ($deposit->Status == 0) {
                $updateData = array(
                    'id'           => $deposit->id,
                    'Status'       => 1,
                    'UpdatedBy'    => current_user(),
                    'VerifiedDate' => date('Y-m-d H:i:s')  
                );

                $this->db->trans_begin();

                if ($this->mgovdb->saveData('WalletDeposits', $updateData)) {
                    $latest_balance = get_latest_wallet_balance();
                    $new_balance    = $latest_balance + $deposit->Amount;

                    $transactionData = array(
                        'Code'          => $deposit->Code,
                        'AccountID'     => $deposit->AccountID,
                        'ReferenceNo'   => $deposit->ReferenceNo,
                        'Date'          => date('Y-m-d h:i:s'),
                        'Description'   => 'Bank Deposit',
                        'Amount'        => $deposit->Amount,
                        'Type'          => 'Credit',
                        'EndingBalance' => $new_balance
                    );

                    if ($this->mgovdb->saveData('WalletTransactions', $transactionData)) {
                        $this->db->trans_commit();
                        $return_data = array(
                            'status'    => true,
                            'message'   => 'Deposit transaction has been posted.'
                        );
                    } else {
                        $this->db->trans_rollback();

                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Saving transaction failed.'
                        );
                    }
                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Verifying deposit failed.'
                    );
                }

            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Deposit request was already verified and credited.'
                );
            }
        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Invalid deposit transaction'
            );
        }

        response_json($return_data);
    }

    public function add_payment()
    {
        if (validate('add_payment') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {

            $latest_balance = get_latest_wallet_balance();

            $amount = get_post('Amount');
            $biller = $this->mgovdb->getRowObjectWhere('Service_Services', array('Code' => get_post('Biller'), 'SubDepartmentID' => DBP_ORG_ID));
            if ($biller) {
                if ($amount > 0) {
                    if ($latest_balance >= $amount) {

                        $desc = 'Bills Payment - ' . $biller->Name . ' (' . $biller->Code . ')';

                        $saveData = array(
                            'Code'          => microsecID(),
                            'AccountID'     => current_user(),
                            'ReferenceNo'   => get_post('ReferenceNo'),
                            'Description'   => $desc,
                            'Date'          => date('Y-m-d H:i:s'),
                            'Amount'        => $amount,
                            'Type'          => 'Debit',
                            'EndingBalance' => ($latest_balance - $amount)
                        );

                        if ($this->mgovdb->saveData('WalletTransactions', $saveData)) {
                            $return_data = array(
                                'status'    => true,
                                'message'   => 'Payment transaction has been added.'
                            );
                        } else {
                            $return_data = array(
                                'status'    => false,
                                'message'   => 'Saving transaction failed.'
                            );
                        }

                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Insufficient balance.'
                        );
                    }
                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Invalid amount.'
                    );
                }
            } else {
                $return_data = array(
                        'status'    => false,
                        'message'   => 'Invalid biller/merchant.'
                    );
            }

        }

        response_json($return_data);
    }

    public function eload()
    {
        if (validate('send_eload') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {

            $latest_balance = get_latest_wallet_balance();

            $amount = get_post('Amount');
            if ($amount > 0) {
                if ($latest_balance >= $amount) {
                    $saveData = array(
                        'Code'          => microsecID(),
                        'AccountID'     => current_user(),
                        'ReferenceNo'   => get_post('Number'),
                        'Description'   => 'eLoad (' . get_post('Number') . ')',
                        'Date'          => date('Y-m-d H:i:s'),
                        'Amount'        => $amount,
                        'Type'          => 'Debit',
                        'EndingBalance' => ($latest_balance - $amount)
                    );

                    if ($this->mgovdb->saveData('WalletTransactions', $saveData)) {
                        $return_data = array(
                            'status'    => true,
                            'message'   => 'Mobile loading transaction has been successful.'
                        );
                    } else {
                        $return_data = array(
                            'status'    => false,
                            'message'   => 'Saving transaction failed.'
                        );
                    }

                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Insufficient balance.'
                    );
                }
            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Invalid amount.'
                );
            }

        }

        response_json($return_data);
    }
}

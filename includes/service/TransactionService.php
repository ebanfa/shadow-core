<?php

/*
 *
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class TransactionService {

    public static $debit_credit_rules = array(
        'A' => array('debit' => 'INCREASE', 'credit' => 'DECREASE'),
        'L' => array('debit' => 'DECREASE', 'credit' => 'INCREASE'),
        'O' => array('debit' => 'DECREASE', 'credit' => 'INCREASE'),
        'R' => array('debit' => 'DECREASE', 'credit' => 'INCREASE'),
        'E' => array('debit' => 'INCREASE', 'credit' => 'DECREASE'),
    );

    /*
     *
     */
    public static function process_financial_event($financial_event_data){
        // Get the event type id
        if(isset($financial_event_data['event_type'])) {
            // Get all transactions types mapped for this event
            $feventtype_txntype_mapping = EntityAPI::find_by_criteria(
                'feventtxntype', array('fevent_type' => $financial_event_data['event_type']));
            // For each transaction type create a transaction data and
            foreach ($feventtype_txntype_mapping as $map_data) {
                // build the transation data from the event 
                $transaction_data = self::create_transaction($financial_event_data, $map_data);
                // Post the transactions
                if(isset($transaction_data['id']))
                    $transaction_data = self::post_transaction($transaction_data, $financial_event_data);
            }
        }
        
    }

    /*
     *
     */
    public static function create_transaction($financial_event_data, $map_data) {
        $transaction_data = array();
        $txn_type_id = $map_data['fetxn_type'];
        $txn_type_data = EntityAPI::get_by_id('transactiontype', $txn_type_id);

        if(isset($txn_type_data['id'])) {
           
            $transaction_data = EntityAPIUtils::init_entity_data('transaction');
            $transaction_data['edit_mode'] = true;
            $transaction_data['txn_type'] = $txn_type_data['id'];
            $transaction_data['entry_date'] = date("Y-m-d H:i:s");
            $transaction_data['name'] = $financial_event_data['name'];
            $transaction_data['txn_date'] = $financial_event_data['event_date'];
            $transaction_data['internal_org'] = $financial_event_data['internal_org'];
            $transaction_data['description'] = $financial_event_data['description'];

            if(isset($financial_event_data['from_party']))  $transaction_data['from_party'] = $financial_event_data['from_party'];
            if(isset($financial_event_data['to_party']))  $transaction_data['to_party'] = $financial_event_data['to_party'];
            if(isset($financial_event_data['payment']))  $transaction_data['payment'] = $financial_event_data['payment'];
            if(isset($financial_event_data['invoice']))  $transaction_data['invoice'] = $financial_event_data['invoice'];

            $transaction_data = EntityAPI::do_create_entity($transaction_data);
        }
        return $transaction_data;
    }

    /*
     *
     */
    public static function post_transaction($transaction_data, $financial_event_data) {
        // Get all accounts mapped to this transaction type
        $glaccount_txntype_mapping = EntityAPI::find_by_criteria(
            'txntypeaccount', array('tta_txn_type' => $transaction_data['txn_type']));
        // For each account
        foreach ($glaccount_txntype_mapping as $map_data) {
            // Get the bu gl account for the current users bu and account
            $bu_glaccount_list = EntityAPI::find_by_criteria('businessunitglaccount', 
                array('internal_org' => $financial_event_data['internal_org'], 'glaccount' => $map_data['tta_account']));

            if(!empty($bu_glaccount_list)) {
                // Get the first object
                $bu_glaccount_data = $bu_glaccount_list[0];
                // Debit or credit the account based on account type
                if(isset($transaction_data['id']) && isset($bu_glaccount_data['id'])) {

                    $transaction_detail_data = EntityAPIUtils::init_entity_data('transactiondetail');
                    $transaction_detail_data['edit_mode'] = true;
                    $transaction_detail_data['transaction'] = $transaction_data['id'];
                    $transaction_detail_data['td_buglaccount'] = $bu_glaccount_data['id'];
                    $transaction_detail_data['name'] = $transaction_data['name'];
                    $transaction_detail_data['dbcr_fg'] = $map_data['db_cr_fg'];
                    $transaction_detail_data['amount'] = $financial_event_data['amount'];
                    $transaction_detail_data['description'] = $transaction_data['description'];
                    $transaction_detail_data = EntityAPI::do_create_entity($transaction_detail_data);
                    // Update the account balance
                    self::update_account_balance($bu_glaccount_data, $map_data['db_cr_fg'], $transaction_detail_data['amount']);
                }
            }
        }  
    }

    /*
     *
     */
    public static function update_account_balance($bu_glaccount_data, $db_cr_fg, $amount) {
        // Get the GL Account
        $gl_account_data = EntityAPI::get_by_id('glaccount', $bu_glaccount_data['glaccount']);
        if(isset($gl_account_data['id'])) {
            // Get the account type
            $account_type = EntityAPI::get_by_id('glaccounttype', $gl_account_data['glacct_type']);
            if(isset($account_type['id'])) {
                // Use the account type code to get the debit credit mapping for this account
                if(isset(self::$debit_credit_rules[$account_type['entity_code']])) {
                    $rule = self::$debit_credit_rules[$account_type['entity_code']];
                    if($db_cr_fg == 'Y') {
                        self::do_update_account_balance($bu_glaccount_data, $rule['debit'], $amount);
                    }
                    else {
                        self::do_update_account_balance($bu_glaccount_data, $rule['credit'], $amount);
                    }
                }
            }
        }
    }

    /*
     *
     */
    public static function do_update_account_balance($bu_glaccount_data, $action, $amount) {
        // Get the account balance object for this account (status active)
        $account_balance_data = self::get_account_balance_data($bu_glaccount_data);
        $account_balance_data['edit_mode'] = false;
        if($action == 'INCREASE')
            $account_balance_data['balance'] = floatval($account_balance_data['balance']) + floatval($amount);
        else
            $account_balance_data['balance'] = floatval($account_balance_data['balance']) - floatval($amount);


        EntityAPI::do_create_entity($account_balance_data); 
    }

    /*
     *
     */
    public static function get_account_balance_data($bu_glaccount_data) {
        // Get the account balance object for this account (status active)
        $account_period_data = ChartOfAccountsAPI::get_current_accounting_period();
        if($account_period_data['id']) {
            $account_balance_list = EntityAPI::find_by_criteria(
                'businessunitglaccountbalance', array('internal_org' => $bu_glaccount_data['internal_org'], 
                    'buglaccount' => $bu_glaccount_data['id'], 'acctng_period' => $account_period_data['id']));
            if (!empty($account_balance_list)) {
                return $account_balance_list[0];
            }
        }
        return array();
        // 
    }
    
}
?>
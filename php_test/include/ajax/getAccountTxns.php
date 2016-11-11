<?php
session_start();
require_once("../AccountingDB.php");

function json_pretty_print($json) {
    return "<pre>" . json_encode($json,JSON_PRETTY_PRINT) . "</pre>";
}

function generate_content() {
    $content = "";
    $txns_json = [
        'data' => []
        ];
    if (!isset($_GET['account_id'])) {
        return json_encode($txns_json,JSON_PRETTY_PRINT);
    }
    $db = AccountingDB::getInstance();
    if (!isset($_SESSION['uid'])) {
        return json_encode($txns_json,JSON_PRETTY_PRINT);
    }
    $user = $db->get_user_account($_SESSION['uid'],$_SESSION['pwd']);
    if (!$user) {
        return json_encode($txns_json,JSON_PRETTY_PRINT);
    }
    $bank_account = $db->get_bank_account_by_id($_GET['account_id']);
    if (!$bank_account) {
        return json_encode($txns_json,JSON_PRETTY_PRINT);
    }
    $bank = $db->get_bank_by_id($bank_account['bank_id']);
    if ($bank['user_id'] != $user['id']) {
        return json_encode($txns_json,JSON_PRETTY_PRINT);
    }
    $txns = $db->get_bank_account_txns_by_bank_account_id($_GET['account_id']);
    foreach ($txns as $txn) {
        $source_files = $db->get_source_files_by_bank_account_txn_id($txn['id']);
        $source_files_json = [];
        foreach ($source_files as $source_file) {
            array_push($source_files_json, $source_file['url']);
        }
        array_push($txns_json['data'],[
            'date'            => $txn['date'],
            'description'     => $txn['description'],
            'amount'          => money_format('$%(#5.2n', $txn['amount']),
            'running_balance' => money_format('$%(#5.2n', $txn['running_balance']),
            'source_files'    => $source_files_json
        ]);
    }
    return json_encode($txns_json,JSON_PRETTY_PRINT);
};

echo generate_content();
?>
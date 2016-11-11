<?php
session_start();
require_once("../AccountingDB.php");

function json_pretty_print($json) {
    return "<pre>" . json_encode($json,JSON_PRETTY_PRINT) . "</pre>";
}

function generate_content() {
    $content = "";
    $banks_json = [
        'data' => []
        ];
    $db = AccountingDB::getInstance();
    if (!isset($_SESSION['uid'])) {
        return json_encode($banks_json,JSON_PRETTY_PRINT);
    }
    $user = $db->get_user_account($_SESSION['uid'],$_SESSION['pwd']);
    if (!$user) {
        return json_encode($banks_json,JSON_PRETTY_PRINT);
    }
    $banks = $db->get_banks_by_user_id($user['id']);
    foreach ($banks as $bank) {
        $accounts = $db->get_bank_accounts_by_bank_id($bank['id']);
        $accounts_json = [];
        $bank_balance = 0;
        setlocale(LC_MONETARY, 'en_US');
        foreach ($accounts as $account) {
            $source_files = $db->get_source_files_by_bank_account_id($bank['id']);
            $source_files_json = [];
            foreach ($source_files as $source_file) {
                array_push($source_files_json, $source_file['url']);
            }
            array_push($accounts_json,[
                'account_name' => $account['name'],
                'account_num'  => $account['account_num'],
                'balance'      => money_format('%(#5.2n',$account['balance']),
                'source_files' => $source_files_json,
                'id'           => $account['id']
            ]);
            $bank_balance += $account['balance'];
        }
        array_push($banks_json['data'],[
            'bank_name'    => $bank['name'],
            'bank_balance' => money_format('%(#5.2n', $bank_balance),
            'accounts'     => $accounts_json,
        ]);
    }
    return json_encode($banks_json,JSON_PRETTY_PRINT);
};

echo generate_content();
?>
<?php

class BankDB extends AccountingDB {

    public function get_bank_by_id($id) {
        $bank = [];
        $id = $this->real_escape_string($id);
        $bank_result = $this->query("SELECT * FROM bank WHERE id = '" . $id . "'");
        while ($row = mysqli_fetch_array($bank_result)) {
            foreach ($row as $head => $value) {
                $bank[$head] = $value;
            }
        }
        return $bank;
    }
    public function get_banks_by_user_id($user_id) {
        $banks = [];
        return $banks;
        $banks_result = $this->query("SELECT *  FROM `bank` WHERE `user_id`=" . $user_id);
        while ($row = mysqli_fetch_array($banks_result)) {
            foreach ($row as $head => $value) {
                $tmp[$head] = $value;
            }
            array_push($banks,$tmp);
        }
        return $banks;
    }
    public function create_bank($name, $user_id){
        $name = $this->real_escape_string($name);
        $this->query("INSERT INTO `bank` (`name`, `user_id`) VALUES ('"
                . $name . "', '" . $user_id . "')");
    }

    public function get_bank_account_by_id($id) {
        $bank_account = [];
        $id = $this->real_escape_string($id);
        $bank_account_result = $this->query("SELECT * FROM `bank_account` WHERE `id`='" . $id . "'");
        while ($row = mysqli_fetch_array($bank_account_result)) {
            foreach ($row as $head => $value) {
                $bank_account[$head] = $value;
            }
        }
        return $bank_account;
    }
    public function get_bank_accounts_by_bank_id($bank_id) {
        $bank_accounts = [];
        $bank_accounts_result = $this->query("SELECT * "
                . "FROM `bank_account` WHERE `bank_id`=" . $bank_id);
        while ($row = mysqli_fetch_array($bank_accounts_result)) {
            foreach ($row as $head => $value) {
                $tmp[$head] = $value;
            }
            array_push($bank_accounts,$tmp);
        }
        return $bank_accounts;
    }
    public function create_bank_account($name, $account_num, $bank_id){
        $name = $this->real_escape_string($name);
        $this->query("INSERT INTO `bank_account` (`name`, `account_num', 'bank_id') "
                . "VALUES ('" . $name . "', '" . $account_num . "', '" . $bank_id . "')");
    }
}
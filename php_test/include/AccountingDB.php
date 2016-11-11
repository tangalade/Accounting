<?php

class AccountingDB extends mysqli {

    // single instance of self shared among all instances
    private static $instance = null;

    // db connection config vars
    private $user = "accounting_1_1";
    private $pass = "QUvar2Cr";
    private $dbName = "accounting_1";
    private $dbHost = "localhost";
    
    // This method must be static, and must return an instance of the object if the object
    // does not already exist.
    public static function getInstance() {
      if (!self::$instance instanceof self) {
        self::$instance = new self;
      }
      return self::$instance;
    }

    // The clone and wakeup methods prevents external instantiation of copies of the Singleton class,
    // thus eliminating the possibility of duplicate objects.
    public function __clone() {
      trigger_error('Clone is not allowed.', E_USER_ERROR);
    }
    public function __wakeup() {
      trigger_error('Deserializing is not allowed.', E_USER_ERROR);
    }
    
    // private constructor
    private function __construct() {
        parent::__construct($this->dbHost, $this->user, $this->pass, $this->dbName);
        if (mysqli_connect_error()) {
            exit('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
        }
        parent::set_charset('utf-8');
    }
    
    //---------------------------
    // Banks
    //---------------------------
    public function get_bank_by_id($id) {
        $bank = [];
        $id = $this->real_escape_string($id);
        $bank_result = $this->query("SELECT * FROM bank WHERE id = '" . $id . "'");
        if ($bank_result->num_rows == 0)
            return false;
        while ($row = mysqli_fetch_array($bank_result)) {
            foreach ($row as $head => $value) {
                $bank[$head] = $value;
            }
        }
        return $bank;
    }
    public function get_banks_by_user_id($user_id) {
        $banks = [];
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

    //---------------------------
    // Bank Accounts
    //---------------------------
    public function get_bank_account_by_id($id) {
        $bank_account = [];
        $id = $this->real_escape_string($id);
        $bank_account_result = $this->query("SELECT * FROM `bank_account` WHERE `id`='" . $id . "'");
        if ($bank_account_result->num_rows == 0)
            return false;
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

    //---------------------------
    // Bank Accounts
    //---------------------------
    public function get_bank_account_txn_by_id($id) {
        $bank_account_txn = [];
        $id = $this->real_escape_string($id);
        $bank_account_txn_result = $this->query("SELECT * FROM `bank_account_txn` WHERE `id`='" . $id . "'");
        if ($bank_account_txn_result->num_rows == 0)
            return false;
        while ($row = mysqli_fetch_array($bank_account_txn_result)) {
            foreach ($row as $head => $value) {
                $bank_account_txn[$head] = $value;
            }
        }
        return $bank_account_txn;
    }
    public function get_bank_account_txns_by_bank_account_id($bank_account_id) {
        $bank_account_txns = [];
        $bank_account_txns_result = $this->query("SELECT * "
                . "FROM `bank_account_txn` WHERE `bank_account_id`=" . $bank_account_id);
        while ($row = mysqli_fetch_array($bank_account_txns_result)) {
            foreach ($row as $head => $value) {
                $tmp[$head] = $value;
            }
            array_push($bank_account_txns,$tmp);
        }
        return $bank_account_txns;
    }
    // FIXME
    public function create_bank_account_txn($name, $account_num, $bank_account_id){
        $name = $this->real_escape_string($name);
        $this->query("INSERT INTO `bank_account` (`name`, `account_num', 'bank_id') "
                . "VALUES ('" . $name . "', '" . $account_num . "', '" . $bank_id . "')");
    }

    //---------------------------
    // User Accounts
    //---------------------------
    public function valid_username($uid) {
        return (strlen($uid)>0) && preg_match('/^[A-Za-z0-9_-]*$/',$uid);
    }
    public function valid_password($pwd) {
        return (strlen($pwd)>0) && preg_match('/^[A-Za-z0-9]*$/',$pwd);
    }

    public function hash_password($password, $salt) {
        return hash("sha256", $password + $salt);
    }
    public function gen_password_salt() {
        return substr(md5(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)),0,16);
    }

    public function username_exists($username) {
        if (!$this->valid_username($username))
            return false;

        $result = $this->query("SELECT * FROM user_accounts WHERE username = '" . $username . "'");
        if ($result->num_rows > 0)
            return true;
        else
            return false;
    }
    public function create_new_user($uid,$pwd,$full_name) {
        if (!$this->valid_username($uid) || !$this->valid_password($pwd))
            return false;
        if ($this->username_exists($uid))
            return false;
        $salt = $this->gen_password_salt();
        $pwd_hash = $this->hash_password($pwd, $salt);
        $this->query("INSERT INTO user_accounts " .
            "(username, password, salt, full_name, pw_update_time) " .
            "VALUES('" . $uid . "', '" . $pwd_hash . "', '" .
            $salt . "', '" . $full_name . "', now())");
        return true;
    }
    public function change_password($uid,$old_pwd,$new_pwd) {
        if (!$this->valid_username($uid) ||
            !$this->valid_password($old_pwd) ||
            !$this->valid_password($new_pwd))
            return false;
        if (!$this->username_exists($uid))
            return false;
        if (!$this->check_username_password($uid, $old_pwd))
            return false;
        $new_salt = $this->gen_password_salt();
        $new_pwd_hash = $this->hash_password($new_pwd, $new_salt);
        $this->query("UPDATE user_accounts SET password = '" .
                $new_pwd_hash . "', salt = '" . $new_salt . "'");
        return true;
    }
    public function check_username_password($uid,$pwd) {
        if (!$this->valid_username($uid) || !$this->valid_password($pwd))
            return false;
        if (!$this->username_exists($uid))
            return false;
        $result = $this->query("SELECT * from user_accounts WHERE username = '" .
                $uid . "'");
        $row = mysqli_fetch_array($result);
        if ($this->hash_password($uid, $row['salt']) != $row['password'])
            return false;
        return true;
    }
    public function get_user_account($uid,$pwd) {
        if (!$this->valid_username($uid) || !$this->valid_password($pwd))
            return false;
        if (!$this->username_exists($uid))
            return false;
        $result = $this->query("SELECT * from user_accounts WHERE username = '" .
                $uid . "'");
        $row = mysqli_fetch_array($result);
        if ($this->hash_password($uid, $row['salt']) != $row['password'])
            return false;
        return $row;
    }
 
    //---------------------------
    // Source Files
    //---------------------------
    public function get_source_files_by_bank_account_id($bank_account_id) {
        $source_files = [];
        $source_file_ids = [];
        $source_file_ids_result = $this->query("SELECT * " .
                "FROM `bank_account_to_source_file` WHERE `bank_account_id`=" . $bank_account_id);
        while ($row = mysqli_fetch_array($source_file_ids_result)) {
            array_push($source_file_ids,$row['source_file_id']);
        }
        foreach ($source_file_ids as $source_file_id) {
            $result = $this->query("SELECT * from source_file WHERE id = '" .
                    $source_file_id . "'");
            $row = mysqli_fetch_array($result);
            foreach ($row as $head => $value) {
                $tmp[$head] = $value;
            }
            array_push($source_files,$tmp);
        }
        return $source_files;
    }
    public function get_source_files_by_bank_account_txn_id($bank_account_txn_id) {
        $source_files = [];
        $source_file_ids = [];
        $source_file_ids_result = $this->query("SELECT * " .
                "FROM `bank_account_txn_to_source_file` WHERE `bank_account_txn_id`=" . $bank_account_txn_id);
        while ($row = mysqli_fetch_array($source_file_ids_result)) {
            array_push($source_file_ids,$row['source_file_id']);
        }
        foreach ($source_file_ids as $source_file_id) {
            $result = $this->query("SELECT * from source_file WHERE id = '" .
                    $source_file_id . "'");
            $row = mysqli_fetch_array($result);
            foreach ($row as $head => $value) {
                $tmp[$head] = $value;
            }
            array_push($source_files,$tmp);
        }
        return $source_files;
    }
}
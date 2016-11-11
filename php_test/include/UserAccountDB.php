<?php

class UserAccountDB extends AccountingDB {

    public function valid_username($uid) {
        return (strlen($uid)>0) && preg_match('/^[A-Za-z0-9_-]*$/',$uid);
    }
    public function valid_password($pwd) {
        return (strlen($pwd)>0) && preg_match('/^[A-Za-z0-9]*$/',$pwd);
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

    public function change_password($username,$old_password,$new_password) {
        if (!$this->valid_username($username) ||
            !$this->valid_password($old_password) ||
            !$this->valid_password($new_password))
            return false;
        if (!$this->username_exists($username))
            return false;
        $result = $this->query("SELECT * from user_accounts WHERE username = '" .
                $username . "'");
        $row = mysqli_fetch_array($result);
        if ($this->hash_password($old_password, $row['salt']) != $row['password'])
            return false;
        $new_salt = $this->gen_password_salt();
        $new_pw_hash = $this->hash_password($new_password, $new_salt);
        $this->query("UPDATE user_accounts SET password = '" .
                $new_pw_hash . "', salt = '" . $new_salt . "'");
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

    public function hash_password($password, $salt) {
        return hash("sha256", $password + $salt);
    }
    public function gen_password_salt() {
        return substr(md5(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)),0,16);
    }
}
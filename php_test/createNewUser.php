<?php 
include "include/common.php";
require_once("include/AccountingDB.php");
$_SESSION['page_id'] = "New User Registration";
    
$uid  = isset($_POST['uid'])  ? $_POST['uid']  : $_SESSION['uid'];
$pwd  = isset($_POST['pwd'])  ? $_POST['pwd']  : $_SESSION['pwd'];
$name = isset($_POST['name']) ? $_POST['name'] : $_SESSION['name'];
$attempted = ($_SERVER['REQUEST_METHOD'] == 'POST');

if ($attempted == 1) {
    if (!strlen($uid)) {
        $uid_missing = TRUE;
    } else if (!strlen($pwd)) {
        $pwd_missing = TRUE;
    } else if (!strlen($name)) {
        $name_missing = TRUE;
    } else {
        $db = AccountingDB::getInstance();
        if (!$db->valid_username($uid)) {
            $invalid_uid = TRUE;
        } else if (!$db->valid_password($pwd)) {
            $invalid_pwd = TRUE;
        }else if ($db->username_exists($uid)) {
            $uid_exists = TRUE;
        } else if (!$db->create_new_user($uid, $pwd, $name)) {
            $unknown_error = TRUE;
        } else {
            $success = TRUE;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title><?php include 'include/content/htmlTitle.php'?></title>
<link rel="stylesheet" type="text/css" href="css/general.css">

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="datatables/DataTables-1.10.8/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="datatables/Responsive-1.0.7/css/responsive.dataTables.css">
  
<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="datatables/jQuery-2.1.4/jquery-2.1.4.js"></script>
  
<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="datatables/DataTables-1.10.8/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="datatables/Responsive-1.0.7/js/dataTables.responsive.js"></script>

<script type="text/javascript">
    $(document).ready( function () {
    } );
</script>
</head>
<body>
<div class="content-wrapper">
<div class="doc-header">
    <?php include 'include/content/header.php'?>
</div>
<div class="doc-body">
    <div class="doc-body-content-wrapper">
<?php
    if (!$success) {
?>
        <form name="userInfo" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
            <table class="main-form">
                <tr><td colspan="2"><p>User name</p></td></tr>
                <tr><td colspan="2"><input name="uid" type="text" id="username" value="<?=$uid?>" autofocus/></td></tr>
<?php   if ($uid_missing) {?>
                <tr><td colspan="2" class="form-error"><p>Username required</p></td></tr>
<?php   } else if ($uid_exists) {?>
                <tr><td colspan="2" class="form-error"><p>Username already exists</p></td></tr>
<?php   } else if ($invalid_uid) {?>
                <tr><td colspan="2" class="form-error"><p>Invalid username (A-Z,a-z,0-9,_,-)</p></td></tr>
<?php   }?>
                <tr><td colspan="2"><p>Password</p></td></tr>
                <tr><td colspan="2"><input name="pwd" type="password" id="password" value="<?=$pwd?>"/></td></tr>
<?php   if ($pwd_missing) {?>
                <tr><td colspan="2" class="form-error"><p>Password required</p></td></tr>
<?php   } else if ($invalid_pwd) {?>
                <tr><td colspan="2" class="form-error"><p>Invalid password (A-Z,a-z,0-9)</p></td></tr>
<?php   }?>
                <tr><td colspan="2"><p>Full name</p></td></tr>
                <tr><td colspan="2"><input name="name" type="text" id="name" value="<?=$name?>"/></td></tr>
<?php   if ($name_missing) {?>
                <tr><td colspan="2" class="form-error"><p>Full name required</p></td></tr>
<?php   }?>
                <tr>
                    <td><input type="submit" name="submitok" value="Register" /></td>
                    <td><p><a href="login.php">Current user?</a></p></td>
                </tr>
            </table>
        </form>
<?php
    } else {
?>
        <p>Hi there <?=$name?></p>
        <p>Please proceed to <a href="login.php">Login</a> to access the system</p>
<?php
    }
?>
    </div>
</div>
<div class="doc-footer">
    <div class="doc-footer-content-wrapper">
        <?php include 'include/content/footer.php'?>
    </div>
</div>
</div>
</body>
</html>
<?php 
session_start();
require_once("include/AccountingDB.php");
include "include/common.php";
$_SESSION['page_id'] = "Login";

$uid  = isset($_POST['uid'])  ? $_POST['uid']  : $_SESSION['uid'];
$pwd  = isset($_POST['pwd'])  ? $_POST['pwd']  : $_SESSION['pwd'];
$attempted = ($_SERVER['REQUEST_METHOD'] == 'POST');

if ($attempted == 1) {
    if (!strlen($uid)) {
        $uid_missing = TRUE;
    } else if (!strlen($pwd)) {
        $pwd_missing = TRUE;
    } else {
        $db = AccountingDB::getInstance();
        if (!$db->check_username_password($uid,$pwd)) {
            $invalid_cred = TRUE;
        } else {
            $account = $db->get_user_account($uid,$pwd);
            $_SESSION['uid'] = $uid;
            $_SESSION['pwd'] = $pwd;
            $_SESSION['full_name'] = $account['full_name'];
            $name = $_SESSION['full_name'];
            header("Location: " . $_SESSION['prev_page_url']);
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
        <form name="userInfo" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
            <table class="main-form">
                <tr><td colspan="2"><p>User name</p></td></tr>
                <tr><td colspan="2"><input name="uid" type="text" id="username" value="<?=$_POST['uid']?>" autofocus/></td></tr>
<?php   if ($uid_missing) {?>
                <tr><td colspan="2" class="form-error"><p>Username required</p></td></tr>
<?php   }?>
                <tr><td colspan="2"><p>Password</p></td></tr>
                <tr><td colspan="2"><input name="pwd" type="password" id="password" value="<?=$_POST['pwd']?>"/></td></tr>
<?php   if ($pwd_missing) {?>
                <tr><td colspan="2" class="form-error"><p>Password required</p></td></tr>
<?php   } else if ($invalid_cred) {?>
                <tr><td colspan="2" class="form-error"><p>Invalid username/password combination</p></td></tr>
<?php   }?>
                <tr>
                    <td><input type="submit" name="submitok" value=" Login " /></td>
                    <td><p><a href="createNewUser.php">New user?</a></p></td>
                </tr>
            </table>
        </form>
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
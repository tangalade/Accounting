<?php 
    require_once("include/AccountingDB.php");
    if (!isset($_GET["account_id"])) {
        header("Location: bankList.php");
    }
    $account_id = $_GET["account_id"];
    $title = "Account Transactions";
?>
<!DOCTYPE html>
<html>
<head>
<title><?=$title?></title>
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
    function toggle_right_menu_overlay(button) {
        var overlay = $('#right-menu-overlay-wrapper');
        if (button.hasClass("active")) {
            overlay.hide();
            button.removeClass("active");
        } else {
            button.siblings('td').removeClass("active");
            overlay.show();
            button.addClass("active");
        }
    };
    function toggle_login_overlay(button) {
        var overlay = $('#login-overlay-wrapper');
        if (button.hasClass("active")) {
            overlay.hide();
            button.removeClass("active");
        } else {
            overlay.show();
            button.addClass("active");
        }
    };
    $(document).ready( function () {
        var table = $('#account_txns').DataTable( {
//            "ajax": "include/getBankAccounts.php",
            "language": {
                "emptyTable": <?='"No account transactions found"'?>
            },
            "aoColumns": [
                {
                    "data": "date", 
                    "sClass": "date"
                },
                {
                    "data": "description", 
                    "sClass": "string"
                },
                {
                    "data": "amount", 
                    "sClass": "currency"
                },
                {
                    "data": "available_balance",
                    "sClass": "currency"
                }
            ]
        } );
        $('#right-menu-overlay-wrapper').hide();
        $('#import-button').on('click', null, function () {
            toggle_right_menu_overlay($(this));
        });
        $('#login-overlay-wrapper').hide();
        $('#login-button').on('click', null, function () {
            toggle_login_overlay($(this));
        });
    } );
</script>
</head>
<body>
<div class="content-wrapper">
    <div class="right-menu-overlay-wrapper" id="right-menu-overlay-wrapper">
        <div class="right-menu-overlay-title-wrapper">
            <h3>Import account transactions</h3>
        </div>
        <div class="right-menu-overlay-content-wrapper">
            <form action="include/uploadBankAccountData.php" method="post" enctype="multipart/form-data">
                <input type="file" name="uploadedFile" accept=".csv" id="uploadedFile">
                <input type="submit" value="Import" name="submit">
                <div class="file-types">
                    <h6 class="file-types">.csv</h6>
                </div>
            </form>
        </div>
    </div>
    <div class="login-overlay-wrapper" id="login-overlay-wrapper">
        <div class="login-overlay-content-wrapper">
            <form action="include/login.php" method="post" enctype="multipart/form-data">
                <h4>User name</h4>
                <input type="text" name="username" id="username">
                <h4>Password</h4>
                <input type="password" name="password" id="password">
                <h4 class="new-user-button"><a href="createNewUser.php">New user?</a></h4>
                <input type="submit" value="Login" name="submit">
            </form>
        </div>
    </div>
<div class="doc-header">
    <div class="doc-header-content-wrapper">
        <div class="title-content-wrapper">
            <h1><?=$title?></h1>
            <h3><?php
            $account = AccountingDB::getInstance()->get_bank_account_by_id($account_id);
            echo $account['name'] . ' - ' . $account['account_num'];
            ?>
            </h3>
        </div>
        <div class="login-wrapper">
            <div class="login-content-wrapper" id="login-button">Login</div>
        </div>
        <div class="header-menu-wrapper">
            <?php include 'include/content/headerMenu.php'?>
        </div>
        <div class="header-right-menu-wrapper">
            <div class="header-right-menu">
                <table class="header-menu">
                    <tr>
                        <td id="import-button">Import</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="doc-body">
    <div class="doc-body-content-wrapper">
    <table id="account_txns" class="display nowrap" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Available Balance</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Available Balance</th>
            </tr>
        </tfoot>
    </table>
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
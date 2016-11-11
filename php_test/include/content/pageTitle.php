<div class="title-content-wrapper">
<?php
switch ($_SESSION['page_id']) {
    case "Bank Accounts":
?>
        <h1>Bank Accounts</h1>
<?php   break;
    case "Account Transactions":
?>
        <h1>Account Transactions</h1>
        <h3><?php
            $account = AccountingDB::getInstance()->get_bank_account_by_id($_SESSION['account_id']);
            echo $account['name'] . ' - ' . $account['account_num'];
            ?>
        </h3>
<?php   break;
    case "Import Account Transactions":
?>
        <h1>Import Account Transactions</h1>
        <h3><?php
            $account = AccountingDB::getInstance()->get_bank_account_by_id($_SESSION['account_id']);
            echo $account['name'] . ' - ' . $account['account_num'];
            ?>
        </h3>
<?php   break;
    case "New User Registration":
?>
            <h1>New User Registration</h1>
<?php   break;
    case "Login":
?>
        <h1>Login</h1>
<?php   break;
    case "Home":
    default:
?>
        <h1>Accounting</h1>
<?php   break;
}
?>
</div>
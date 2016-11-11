<?php
    session_start();
    
    switch ($_SESSION['page_id']) {
        case "Home":
            $html_title = "Accounting";
            break;
        case "Bank Accounts":
            $html_title = "Bank Accounts";
            break;
        case "Import Account Transactions":
            $html_title = "Import Account Transactions";
            break;
        case "Account Transactions":
            $html_title = "Account Transactions";
            break;
        case "New User Registration":
            $html_title = "New User Registration";
            break;
        case "Login":
            $html_title = "Login";
            break;
        case "Home":
        default:
            $html_title = "Accounting";
            break;
    }
?>
<?=$html_title?>
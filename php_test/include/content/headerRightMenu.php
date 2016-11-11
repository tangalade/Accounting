<?php
switch ($_SESSION['page_id']) {
    case "Bank Accounts":
?>
    <table class="header-menu">
        <tr>
            <td class="header-right-menu-button">Add</td>
        </tr>
    </table>
<?php       break;
    case "Account Transactions":
?>
    <table class="header-menu">
        <tr>
            <td class="header-right-menu-button">Import</td>
        </tr>
    </table>
<?php
        break;
}
?>
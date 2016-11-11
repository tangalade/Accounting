<?php
switch ($_SESSION['page_id']) {
    case "Bank Accounts":
    case "Account Transactions":
?>
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
            $(document).ready( function () {
                $('.header-right-menu-button').on('click', null, function (e) {
                    toggle_right_menu_overlay($(this));
                    e.stopPropagation();
                });
                $('#right-menu-overlay-wrapper').on('click', null, function (e) {
                    e.stopPropagation();
                });
                $(document).on('click', null, function () {
                    var overlay = $('#right-menu-overlay-wrapper');
                    if (overlay.css('display') != 'none')
                        overlay.hide();
                    $('.header-right-menu-button').removeClass("active");
                });
            });
        </script>
<?php
}
switch ($_SESSION['page_id']) {
    case "Bank Accounts":
?>
        <div class="right-menu-overlay-content-wrapper">
            <form action="include/createNewBankAccount.php" method="post" enctype="multipart/form-data">
                <table>
                    <tr class="title">
                        <td><h3>Add new bank account</h3></td>
                    </tr>
                    <tr><td class="header">Bank</td></tr>
                    <tr>
                        <td>
                            <!--FIXME: get available banks from DB-->
                            <select>
                                <option value="1">Bank of America</option>
                                <option value="2">Capital One</option>
                            </select>
                        </td>
                    </tr>
                    <tr><td class="header">Account name</td></tr>
                    <tr>
                        <td><input type="text" name="account_name" id="account_name"></td>
                    </tr>
                    <tr><td class="header">Account number</td></tr>
                    <tr>
                        <td><input type="text" name="account_num" id="account_num"></td>
                    </tr>
                    <tr>
                        <td><input type="submit" value="Add" name="submit"></td>
                    </tr>
                </table>
            </form>
        </div>
<?php
        break;
    case "Account Transactions":
?>
        <div class="right-menu-overlay-content-wrapper">
            <form action="importAccountTxns.php?account_id=<?=$_SESSION['account_id']?>" method="post" enctype="multipart/form-data">
                <table>
                    <tr class="title">
                        <td colspan="2"><h3>Import account transactions</h3></td>
                    </tr>
                    <tr><td colspan="2" class="header">Source file (.csv)</td></tr>
                    <tr>
                        <td colspan="2">
                            <input type="file" name="uploadedFile" accept=".csv" id="uploadedFile">
                        </td>
                    </tr>
                    <tr><td colpsan="2" class="header">Template</td></tr>
                    <tr>
                        <td>
                            <!--FIXME: get templates from DB-->
                            <select>
                                <option value="boa_credit_card">BoA Credit Card</option>
                                <option value="boa_checking">BoA Checking</option>
                            </select>
                        </td>
                        <td><input type="submit" value="Import" name="submit"></td>
                    </tr>
                </table>
            </form>
        </div>
<?php
}
?>

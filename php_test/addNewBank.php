<!DOCTYPE html>
<?php
require_once("include/db.php");
$bankNameIsUnique = true;
$bankNameIsValid = true;
$bankNameIsEmpty = false;					
$bankName2IsEmpty = false;					
        
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    /** Check whether the user has filled in the wisher's name in the text field "user" */
    if ($_POST['bank_name'] == "") {
        $bankNameIsEmpty = true;
    }
    if ($_POST['bank_name2'] == "") {
        $bankName2IsEmpty = true;
    }
    if ($_POST['bank_name'] != $_POST['bank_name2']) {
        $bankNameIsValid = false;
    }

    $bank_id = AccountingDB::getInstance()->get_bank_id_by_name($_POST["bank_name"]);
    if ($bank_id) {
       $bankNameIsUnique = false;
    }

    /** Check whether the boolean values show that the input data was validated successfully.
     * If the data was validated successfully, add it as a new entry in the "wishers" database.
     * After adding the new entry, close the connection and redirect the application to editWishList.php.
     */
    if (!$bankNameIsEmpty && !$bankName2IsEmpty && $bankNameIsUnique && $bankNameIsValid) {
        AccountingDB::getInstance()->create_bank($_POST["bank_name"]);
        header('Location: addBankAccount.php' );
        exit;
    }
}
?>

<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <title>Add a New Bank</title>
    </head>
    <body>
      Welcome!<br>
        <form action="addNewBank.php" method="POST">
            Bank name: <input type="text" name="bank_name"/><br/>
    <?php
    if ($bankNameIsEmpty) {
        echo ("Enter the bank name, please!");
        echo ("<br/>");
    }
    ?>            
            Please confirm your bank_name: <input type="text" name="bank_name2"/><br/>
    <?php
    if ($bankName2IsEmpty) {
        echo ("Confirm the bank name, please!");
        echo ("<br/>");
    } elseif (!$bankNameIsValid) {
        echo ("The bank names do not match!");
        echo ("<br/>");
    } elseif (!$bankNameIsUnique) {
        echo ("This bank already exists. Please check the spelling and try again.");
        echo ("<br/>");
    }
    ?>
            <input type="submit" value="Add"/>
        </form>
     </body>
</html>
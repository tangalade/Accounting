<?php 
include "include/common.php";
require_once("include/AccountingDB.php");
if (!isset($_GET["account_id"]) || !isset($_SESSION['uid'])) {
    header("Location: login.php");
}
$account_id = $_GET["account_id"];
$_SESSION['page_id'] = "Import Account Transactions";
$_SESSION['account_id'] = $account_id;
?>

<?php
$target_dir = getcwd() . "/uploads/source_files/";
$target_file = $target_dir . basename($_FILES["uploadedFile"]["name"]);
$supported_file_types = ["csv"];

function filter_rows(&$rows,$num_cols) {
    $num_row = 0;
    for ($num_row = 0; $num_row<count($rows); $num_row++) {
        if (count($rows[$num_row]) != $num_cols) {
            array_splice($rows,$num_row,1);
            $num_row--;
        }
    }
}
function validate_upload($uploaded_file,$target_file,$supported_file_types) {
    $uploadOk = TRUE;
    $file_type = pathinfo($uploaded_file["name"],PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
//        echo $file_type;    
    }
    // Check if file already exists
    // FIXME: figure out how to name/place files, or check for duplicates with diff names or something
    if (file_exists($target_file)) {
//        echo "Sorry, file already exists.";
//        $uploadOk = 0;
    }
    // Check file size
    if ($uploaded_file["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if( !in_array($file_type, $supported_file_types) ) {
        echo "Sorry, file type " . $file_type . " is not supported.";
        $uploadOk = 0;
    }
    return $uploadOk;
}

if (!validate_upload($_FILES["uploadedFile"],$target_file,$supported_file_types)) {
    echo "Sorry, your file was not uploaded.";
    exit;
}
if (move_uploaded_file($_FILES["uploadedFile"]["tmp_name"], $target_file)) {
//    echo "The file ". basename($_FILES["uploadedFile"]["name"]). " has been uploaded." . PHP_EOL;
} else {
    echo "Sorry, there was an error uploading your file.";
    exit;
}

$file = fopen($target_file, r);
if (!$file) {
    echo "Could not open the target file.";
    exit;
}

$delimiter = ",";
$enclosure = '"';
$escape    = "\\";
$rows      = [];;
while ($line = fgets($file)) {
    $fields = str_getcsv($line,$delimiter,$enclosure,$escape);
    array_push($rows,$fields);
}
$num_cols = count($rows[count($rows)-1]);

filter_rows($rows, $num_cols);

$rows_json = [
    'data' => []
];
foreach ($rows as $row) {
    $row_json = [];
    foreach ($row as $col) {
        array_push($row_json, $col);
    }
    array_push($rows_json['data'],$row_json);
}

$rows_js = "var data = [" . PHP_EOL;
foreach ($rows_json['data'] as $row) {
    $rows_js .= "[" . PHP_EOL;
    foreach ($row as $field) {
        $rows_js .= "\"$field\"," . PHP_EOL;
    }
    $rows_js .= "]," . PHP_EOL;
}
$rows_js .= "];" . PHP_EOL;
//echo json_encode($rows_json,JSON_PRETTY_PRINT);
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
        <?=$rows_js?>
        var table = $('#csv_account_txns').DataTable( {
            "data": data,
            "language": {
                "emptyTable": <?='"No account transactions found"'?>
            },
            "iDisplayLength": 15,
            "ordering": false,
            "bFilter": false,
            "bLengthChange": false
        });
        $('#csv_account_txns th select').change(function (e) {
            var option = $(this).find(':selected').text();
            var th = $(this).closest('th');
            var col = th.index();
            var th_foot = th.closest('table').children('tfoot').find('th:eq(' + col + ')');
            th_foot.html(option);
        });
    });
</script>
</head>
<body>
<div class="content-wrapper">
    <div class="right-menu-overlay-wrapper" id="right-menu-overlay-wrapper">
        <?php include 'include/content/headerRightMenuOverlay.php'?>
    </div>
<div class="doc-header">
    <?php include 'include/content/header.php'?>
</div>
<div class="doc-body">
    <div class="doc-body-content-wrapper">
    <table id="csv_account_txns" class="display nowrap" cellspacing="0" width="100%">
        <thead>
            <tr>
<?php
    for ($idx=0; $idx<$num_cols; $idx++) {
?>
                <th class="selector">
                    <select>
                        <option <?=($idx > 3)?'selected="selected"':''?>></option>
                        <option value="date" <?=($idx == 0)?'selected="selected"':''?>>Date</option>
                        <option value="description" <?=($idx == 1)?'selected="selected"':''?>>Description</option>
                        <option value="amount" <?=($idx == 2)?'selected="selected"':''?>>Amount</option>
                        <option value="running_balance" <?=($idx == 3)?'selected="selected"':''?>>Running Balance</option>
                    </select>
                </th>
<?php
    }
?>
            </tr>
        </thead>
        <tfoot>
            <tr>
<?php
    for ($idx=0; $idx<$num_cols; $idx++) {
        switch($idx) {
        case 0:
?>
                <th>Date</th>
<?php       break;
        case 1:
?>
                <th>Description</th>
<?php       break;
        case 2:
?>
                <th>Amount</th>
<?php       break;
        case 3:
?>
                <th>Running Balance</th>
<?php       break;
        default:
?>
                <th></th>
<?php
        }
    }
?>
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
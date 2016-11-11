<?php 
include "include/common.php";
require_once("include/AccountingDB.php");
if (!isset($_GET["account_id"]) || !isset($_SESSION['uid'])) {
    header("Location: login.php");
}
$account_id = $_GET["account_id"];
$_SESSION['page_id'] = "Account Transactions";
$_SESSION['account_id'] = $account_id;
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
    function source_file_format(source_files) {
        var content = "";
        for (var i=0; i<source_files.length; i++) {
            var extension = source_files[i].split('.').pop();
            var icon_url;
            switch(extension) {
                case 'csv':
                    icon_url = 'resources/icon_csv.png';
                    break;
                case 'txt':
                default:
                    icon_url = 'resources/icon_txt.png';
                    break;
            };
            content += '<a href="' + source_files[i] + '">';
            content += '<img class="file-type" src="' + icon_url + '">';
            content += '</a>';
        }
        return content;
    };
    $(document).ready( function () {
        var table = $('#account_txns').DataTable( {
            "ajax": "include/ajax/getAccountTxns.php?account_id=<?=$account_id?>",
            "language": {
                "emptyTable": <?='"No account transactions found"'?>
            },
            "iDisplayLength": 15,
            "aoColumns": [
                {
                    "data": "date", 
                    "sClass": "date"
                },
                {
                    "data": "description", 
                    "sClass": "string fill"
                },
                {
                    "data": "source_files", 
                    "sClass": "source_files",
                    "render": function(data,type,full,meta) {
                        return source_file_format(full.source_files);
                    }
                },
                {
                    "data": "amount", 
                    "sClass": "currency"
                },
                {
                    "data": "running_balance",
                    "sClass": "currency"
                }
            ]
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
    <table id="account_txns" class="display nowrap" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th></th>
                <th>Amount</th>
                <th>Running Balance</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th></th>
                <th>Amount</th>
                <th>Running Balance</th>
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
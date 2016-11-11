<?php
include "include/common.php";
$_SESSION['page_id'] = "Bank Accounts";
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
    function format ( d ) {
        content = '<div class="sub-accounts-wrapper">';
        content += '<table class="sub-accounts" cellpadding="5" cellspacing="0" border="0">';
        if ( d.accounts.length === 0 ) {
            content += '<tr>';
            content += '<td class="string">' + 'No accounts found' + '</td>';
            content += '</tr>';
        } else {
            content += '<tr class="header">';
            content += '<th>Account</th>'
            content += '<th></th>'
            content += '<th>Balance</th>'
            for (var i=0; i<d.accounts.length; i++) {
                content += '<tr>';
                content += '<td class="string fill">';
                content += '<a href="' + 'http://accounting.m2kt1x.com/accountTxns.php?account_id=' + d.accounts[i].id + '">';
                content += '</a>';
                content += d.accounts[i]['account_name'] + ' - ' + d.accounts[i]['account_num'];
                content += '</td>';
                content += '<td class="source_files">';
                content += source_file_format(d.accounts[i].source_files);
                content += '</td>';
                content += '<td class="currency">' + d.accounts[i]['balance']      + '</td>';
                content += '</tr>';
            }
        }
        content += '</table>';
        content += '</div>';
        return content;
    };
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
        var table = $('#bank_accounts').DataTable( {
            "ajax": "include/ajax/getBankAccounts.php",
            "language": {
                "emptyTable":<?=(!isset($_SESSION['uid']))
                                ? '"No user specified"'
                                : '"No banks found"';?>
            },
            "iDisplayLength": 15,
            "aoColumns": [
                {
                    "data": "bank_name", 
                    "sClass": "string fill main_element"
                },
                {
                    "data": "bank_balance",
                    "sClass": "currency main_element"
                }
            ]
        } );
        $('#bank_accounts tbody').on('click', 'td.main_element', function () {
            var tr = $(this).closest('tr');
            var row = table.row(tr);
            
            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
                tr.next('tr').removeClass("shown_l1_row");
            }
            else {
                // Open this row
                row.child( format(row.data()) ).show();
                tr.addClass('shown');
                tr.next('tr').addClass("shown_l1_row");
            }
        } );
        // so that the links inside the row do not show the sub accounts
        $('#bank_accounts tbody').on('click', 'td.main_element.source_files a', function (e) {
            e.stopPropagation();
        } );
        $('#bank_accounts tbody').on('click','table.sub-accounts tbody td', function() {
            var tr = $(this).closest('tr');
            var a = tr.children('td:first').children('a');
            var url = a.attr('href');
            window.location.href = url;
        } );
    } );
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
    <table id="bank_accounts" class="display nowrap" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Banks</th>
                <th>Net Worth</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Banks</th>
                <th>Net Worth</th>
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
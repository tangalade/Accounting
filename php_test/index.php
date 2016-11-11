<?php
include "include/common.php";
$_SESSION['page_id'] = "Home";
?>
<!DOCTYPE html>
<html>
<head>
<title><?php include 'include/content/htmlTitle.php'?></title>
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="datatables/DataTables-1.10.8/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="datatables/Responsive-1.0.7/css/responsive.dataTables.css">
<link rel="stylesheet" type="text/css" href="css/general.css">
  
<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="datatables/jQuery-2.1.4/jquery-2.1.4.js"></script>

<script type="text/javascript">
    $(document).ready( function () {
        $('#login-button').click(function() {
            window.location.href = $(this).children('a').attr("href");
        });
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
        <p>Hi there, hurry up and do something.</p>
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
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>Wish List of 
        <?php echo htmlentities($_GET["bank"])."<br/>";?>
        <?php
        $con = mysqli_connect("localhost", "root", "");
if (!$con) {
    exit('Connect Error (' . mysqli_connect_errno() . ') '
           . mysqli_connect_error());
}
//set the default client character set 
mysqli_set_charset($con, 'utf-8');
        ?>
    </body>
</html>

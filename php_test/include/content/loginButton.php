<div class="login-content-wrapper">
    <table class="login-content">
        <tr>
<?php if(!isset($_SESSION['uid'])) { ?>
            <td class="login-content-right" id="login-button"><a href="login.php">Login</a></td>
<?php } else { ?>
            <td class="login-content-left" id="login-button"><a href="logout.php">Sign Out</a></td>
            <td class="login-content-right" id="login-button"><a href="login.php">Hi, <?=$_SESSION['full_name']?></a></td>
<?php } ?>
        </tr>
    </table>
</div>

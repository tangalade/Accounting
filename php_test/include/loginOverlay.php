<script type="text/javascript">
    function toggle_login_overlay(button) {
        var overlay = $('#login-overlay-wrapper');
        if (button.hasClass("active")) {
            overlay.hide();
            button.removeClass("active");
        } else {
            overlay.show();
            button.addClass("active");
        }
    };
    $(document).ready( function () {
        $('#login-overlay-wrapper').hide();
        $('#login-button').on('click', null, function () {
            toggle_login_overlay($(this));
        });
    } );
</script>

<div class="login-overlay-wrapper" id="login-overlay-wrapper">
    <div class="login-overlay-content-wrapper">
        <form action="include/login.php" method="post" enctype="multipart/form-data">
            <h4>User name</h4>
            <input type="text" name="username" id="username">
            <h4>Password</h4>
            <input type="password" name="password" id="password">
            <h4 class="new-user-button"><a href="createNewUser.php">New user?</a></h4>
            <input type="submit" value="Login" name="submit">
        </form>
    </div>
</div>

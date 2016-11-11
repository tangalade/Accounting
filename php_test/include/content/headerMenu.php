<script type="text/javascript">
    $(document).ready( function () {
        $('#home-menu-button').click(function() {
            window.location.href = $(this).children('a').attr("href");
        });
        $('#accounts-menu-button').click(function() {
            window.location.href = $(this).children('a').attr("href");
        });
    } );
</script>
<table class="header-menu">
    <tr>
        <td id="home-menu-button" class="<?=($_SESSION['page_id'] == "Home") ? "active" : "";?>">
            <a href="index.php">Home</a>
        </td>
        <td id="accounts-menu-button" class="<?=($_SESSION['page_id'] == "Bank Accounts") ? "active" : "";?>">
            <a href="accountList.php">Accounts</a>
        </td>
    </tr>
</table>

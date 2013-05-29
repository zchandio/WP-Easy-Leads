<form action="<?php echo admin_url('admin.php?page=leads/plugin.php') ?>" method="post">
    <table cellpadding="20">
        <tr>
            <td><label for="user_name">Username</label></td>
            <td><input type="text" size="40" name="user_name" id="user_name"></td>
        </tr>

        <tr>
            <td><label for="user_email">Email</label></td>
            <td><input type="text" size="40" name="user_email" id="user_email"></td>
        </tr>

        <tr>
            <td>&nbsp;</td>
            <td><input type="submit" value="Register" name="leads_registeration" class="button"></td>
        </tr>
    </table>
    <?php wp_nonce_field('leads_nonce_register','nonce_register'); ?>
</form>

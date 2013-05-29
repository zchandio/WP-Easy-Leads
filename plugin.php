<?php
/**
 * Created by touqeer.shafi@gmail.com
 * Date: 4/29/13
 * Time: 3:41 PM
 * Filename : plugin.php
 */

wp_enqueue_script( 'leads_admin', plugins_url().'/leads/js/admin.js' );
wp_enqueue_style( 'leads_admin', plugins_url().'/leads/css/admin.css' );

if ( !empty($_POST) && wp_verify_nonce($_POST['nonce_register'],'leads_nonce_register') )
{
    if(isset($_POST['leads_registeration'])){
        $leads_activated = lead_check();
    }
}
?>
<div class="wrap">
    <div id="icon-themes" class="icon32"><br></div>
    <h2 class="nav-tab-wrapper">
        <a href="#" class="nav-tab nav-tab-active" rel="#manage_option"> Settings </a>
    </h2>

    <div id="tabs">
        <?php
            if(isset($leads_activated) && $leads_activated == true) {
                 echo   notification('Plugin has been activated');
            }else if(isset($leads_activated) && $leads_activated == false){
                 echo   notification('User name or email is incorrect');
            }
        ?>

        <?php
            if(isset($_GET['updated']) && $_GET['updated'] == 'true') {
                echo notification('Options Updated');
            }else if(isset($_GET['updated']) && $_GET['updated'] != 'true'){
                echo notification('Some thing went wrong');
            }
        ?>

        <div id="manage_option" class="tab">
            <?php
                 if( get_option('leads_activated') == 'true')
                     require_once 'includes/leads_settings.php';
                 else
                     require_once 'includes/leads_registeration.php';
            ?>
        </div>
    </div>
</div>

<br class="clear">
</div>



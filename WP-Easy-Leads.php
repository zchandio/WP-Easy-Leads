<?php
/**
 * Created by touqeer.shafi@gmail.com
 * Date: 4/29/13
 * Time: 3:17 PM
 * Filename : WP-Easy-Leads.php
 */

/*
Plugin Name: Wp Leads
Plugin URI: http://www.mamdaniweb.com/
Version: 1.0
Author: mamdaniweb.com
Description: The Ultimate plugin to increase your list of leads. Make more money by using our beautiful plugins functionality to grow your business list by over 1000%.
*/

// Include Shortcodes
include_once 'shortcode.php';
include_once 'includes/settings.php';


add_action( 'admin_menu', 'register_leads_plugin' );
function register_leads_plugin(){
    add_menu_page( 'Wp Plugin', 'Leads Settings', 'manage_options', 'leads/plugin.php', '', '', 1000 );

}

register_activation_hook(__FILE__, 'my_plugin_activate');
add_action('admin_init', 'my_plugin_redirect');

function my_plugin_activate() {
    add_option('my_plugin_do_activation_redirect', true);
}

function my_plugin_redirect() {
    if (get_option('my_plugin_do_activation_redirect', false)) {
        delete_option('my_plugin_do_activation_redirect');
        wp_redirect(admin_url('admin.php?page=leads/plugin.php'));
    }
}



function wp_leads_post() {
    $labels = array(
        'name'               => _x( 'Leads', 'post type general name' ),
        'singular_name'      => _x( 'Lead', 'post type singular name' ),
        'add_new'            => _x( 'Add New', 'leads' ),
        'add_new_item'       => __( 'Add New Lead' ),
        'edit_item'          => __( 'Edit Lead' ),
        'new_item'           => __( 'New Lead' ),
        'all_items'          => __( 'All Leads' ),
        'view_item'          => __( 'View Lead' ),
        'search_items'       => __( 'Search Lead' ),
        'not_found'          => __( 'No leads found' ),
        'not_found_in_trash' => __( 'No leads found in the Trash' ),
        'parent_item_colon'  => '',
        'menu_name'          => 'Wp Leads'
    );
    $args = array(
        'labels'        => $labels,
        'description'   => 'Wp Leads',
        'public'        => true,
        'menu_position' => 6,
        'supports'      => array( 'title', 'editor', 'thumbnail' ),
        'has_archive'   => false,
    );
    register_post_type( 'wp_lead', $args );
}
add_action( 'init', 'wp_leads_post' );

add_action( 'add_meta_boxes', 'leads_meta_box' );
function leads_meta_box() {
    add_meta_box(
        'lead_meta_box',
        __( 'Leads Custom Fields', 'Wp Leads' ),
        'leads_box_content',
        'wp_lead',
        'normal',
        'high'
    );
}

function leads_box_content($post){ ?>

<table cellspacing="10" width="100%">
    <tr>
        <td>Form Action</td>
        <td><input type="text" size="40" name="form_action" id="form_action" value="<?php echo get_post_meta($post->ID,'form_action',true) ?>"></td>
    </tr>
    <tr>
        <td>Submit Button Text</td>
        <td><input type="text" size="40" name="submit_btn" id="submit_btn" value="<?php echo get_post_meta($post->ID,'submit_btn',true) ?>"></td>
    </tr>
    <tr>
        <td>Submit Button Class</td>
        <td>
            <select name="btn_class" id="btn_class">
                <?php $btn_class = get_post_meta($post->ID,'btn_class',true) ?>
                <option <?php selected($btn_class,'green',true) ?> value="green">Green</option>
                <option <?php selected($btn_class,'grey',true) ?> value="grey">Grey</option>
                <option <?php selected($btn_class,'turquise',true) ?> value="turquise">Turquise</option>
                <option <?php selected($btn_class,'white',true) ?> value="white">White</option>
                <option <?php selected($btn_class,'blue',true) ?> value="blue">Blue</option>
                <option <?php selected($btn_class,'red',true) ?> value="red">Red</option>
            </select>
        </td>
    </tr>

    <tr>
        <td> <label for="no_thanks_btn">No Thanks Button Text (Optional)</label> </td>
        <td><input type="text" name="no_thanks_btn_text" size="40" id="no_thanks_btn"/> </td>
    </tr>

    <tr>
        <td> <label for="no_thanks_btn">No Thanks Button Url </label> </td>
        <td><input type="text" name="no_thanks_btn_url" size="40" id="no_thanks_btn_url"/> </td>
    </tr>

    <tr>
        <td>Submit Button Class</td>
        <td>
            <select name="btn_class_no_thanks" id="btn_class_no_thanks">
                <?php $btn_class = get_post_meta($post->ID,'btn_class',true) ?>
                <option <?php selected($btn_class,'green',true) ?> value="green">Green</option>
                <option <?php selected($btn_class,'grey',true) ?> value="grey">Grey</option>
                <option <?php selected($btn_class,'turquise',true) ?> value="turquise">Turquise</option>
                <option <?php selected($btn_class,'white',true) ?> value="white">White</option>
                <option <?php selected($btn_class,'blue',true) ?> value="blue">Blue</option>
                <option <?php selected($btn_class,'red',true) ?> value="red">Red</option>
            </select>
        </td>
    </tr>

    <tr>
        <td>Form Code</td>
        <td><textarea name="form_code" id="form_code" style="height: 100px;width: 400px;" ><?php echo get_post_meta($post->ID,'form_code',true) ?></textarea></td>
    </tr>
</table>
<?php wp_nonce_field( 'wp_leads_nonce', 'nonce_leads', '', true ) ?>
<?php }

add_action("wp_ajax_wp_leads_update", "wp_leads_update");
add_action("wp_ajax_nopriv_wp_leads_update", "wp_leads_update");

function wp_leads_update(){
    $data = array();
    foreach($_POST as $key => $value){
        if(preg_match('/^(leads_)/i',$key)){
            $data[$key] = $value;
        }
    }
    $data['leads_enable_lead'] = (isset($_POST['leads_enable_lead']) && !empty($_POST['leads_enable_lead'])) ? $_POST['leads_enable_lead'] : '';
    update_option('wp_leads_options',serialize($data));

    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || !strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        wp_safe_redirect( admin_url('admin.php?page=leads/plugin.php&updated=true') );
    }else{
        echo json_encode(array('updated' => 'true'));
    }
    die();
}

function form_data($data, $key){
    if(isset($data[$key])){
        return $data[$key];
    }
    return;
}

global $wpdb;
$leads_options = unserialize(get_option('wp_leads_options'));
if (isset($_GET['page_id'])) {
    $page_id = intval($_GET['page_id']);
} else {
    $uri = $_SERVER['REQUEST_URI'];
    $basedir = getcwd();
    if (strpos($basedir, '\\')) {
        $wp_dir = end(explode('\\', $basedir));
    } else {
        $dir_parts = explode('/', $basedir);
        $wp_dir = $dir_parts[count($dir_parts) - 2];
    }
    $wp_dir = '/' . $wp_dir . '/';

    if ($uri == '/' || $uri == $wp_dir) {
        if(get_option("show_on_front") == 'page') {
            $page_id = intval(get_option("page_on_front"));
        } else {
            $page_id = NULL;
        }
    } else {
        $uri = explode('/', $uri);
        $page_name = $uri[count($uri) - 2];
        $page_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type = 'page'", $page_name));
    }
}
$leads_page = $leads_options['leads_enable_lead'];

if(!empty($leads_page) && get_option('leads_activated') == 'true') {
    if(curPageURL() == site_url() .'/'){
        function leads_custom_page_template($template){
            return 'leads';
        }
        add_filter('template', 'leads_custom_page_template');

        function leads_theme_root($theme_root)
        {
            return str_replace('wp-content/themes', 'wp-content/plugins', $theme_root);
        }
        add_filter('theme_root', 'leads_theme_root');

        function testing($uri)
        {
            return preg_replace('#/themes/(.+)#','/plugins/leads', $uri);
        }
        add_filter('stylesheet_directory_uri', 'testing',10,2);
    }

}



add_action( 'save_post', 'my_project_updated_send_email' );

function my_project_updated_send_email( $post_id ) {

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    if($_POST['post_type'] != 'wp_lead'){
        return;
    }

    if ( !wp_verify_nonce($_POST['nonce_leads'],'wp_leads_nonce') )
    {
        return;
    }

    if ( 'wp_lead' == $_POST['post_type'] ) {
        if ( !current_user_can( 'edit_page', $post_id ) )
            return;
    } else {
        if ( !current_user_can( 'edit_post', $post_id ) )
            return;
    }

    update_post_meta( $post_id, 'submit_btn', $_POST['submit_btn'] );
    update_post_meta( $post_id, 'form_action', $_POST['form_action'] );
    update_post_meta( $post_id, 'btn_class', $_POST['btn_class'] );
    update_post_meta( $post_id, 'lead_template', $_POST['lead_template'] );
    update_post_meta( $post_id, 'form_code', $_POST['form_code'] );
    update_post_meta( $post_id, 'no_thanks_btn_text', $_POST['no_thanks_btn_text'] );
    update_post_meta( $post_id, 'no_thanks_btn_url', $_POST['no_thanks_btn_url'] );
    update_post_meta( $post_id, 'btn_class_no_thanks', $_POST['btn_class_no_thanks'] );

}

function curPageURL() {
    $pageURL = 'http';
    if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}


add_action( 'add_meta_boxes', 'leads_page_template' );
function leads_page_template() {
    add_meta_box(
        'lead_page_template_box',
        __( 'Leads Templates', 'Wp Leads' ),
        'leads_page_template_box',
        'wp_lead',
        'side',
        'low'
    );
}

function leads_page_template_box($post) {
    $lead_template = get_post_meta($post->ID,'lead_template',true);
    ?>
        <select name="lead_template" id="lead_template" style="width: 200px;">
            <option <?php selected('transparent',$lead_template,true) ?> value="transparent">Transparent</option>
            <option <?php selected('white',$lead_template,true) ?> value="white">White</option>
            <option <?php selected('black',$lead_template,true) ?> value="black">Black</option>
        </select>
    <?php
}

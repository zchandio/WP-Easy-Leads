<?php $data = unserialize(get_option('wp_leads_options')); ?>
<form action="<?php echo admin_url('admin-ajax.php?action=wp_leads_update   ') ?>" id="leads_form" method="post">
    <table width="80%" cellspacing="20">
        <tr>
            <td>
                <label for="enable_lead">Enable Lead</label>
            </td>
            <td>
                <input type="checkbox" <?php checked( get_option('page_on_front') , form_data($data,'leads_enable_lead'), true ); ?> value="<?php echo get_option('page_on_front'); ?>" name="leads_enable_lead" id="enable_lead">
                            <span class="disc">
                                Enable leads on Home Page
                            </span>
            </td>
        </tr>

        <tr>
            <td>
                <label for="lead_id">Select Lead</label>
            </td>
            <td>
                <select name="leads_id" id="lead_id" style="width: 250px;">
                    <?php
                    $leads = new WP_Query("post_type=wp_lead&posts_per_page=-1");
                    while($leads->have_posts()) : $leads->the_post();
                        echo '<option></option>';
                        echo '<option '.selected( form_data($data,'leads_id'), $post->ID, false).' value="'.$post->ID.'">'.get_the_title().'</option>';

                    endwhile;
                    ?>
                </select>
                            <span class="disc">
                                Select Lead which you want to show on Home Page
                            </span>
            </td>
        </tr>
        <tr>
            <td>
                <label for="google_analtics_id">Google Analytics ID</label>
            </td>
            <td>
                <input size="50" type="text" name="leads_google_analtics_id" value="<?php echo form_data($data,'leads_google_analtics_id') ?>" id="google_analtics_id">
                    <span class="disc">
                        Enter You Google Analytics id
                    </span>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><input type="submit" value="Submit" class="button"></td>
        </tr>
    </table>
</form>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
    <title>
        <?php
        global $page, $paged;
        wp_title('|', true, 'right');
        bloginfo('name');
        if ($paged >= 2 || $page >= 2)
            echo ' | ' . sprintf('Page %s', max($paged, $page));
        ?>
    </title>

    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
    <?php wp_head() ?>
</head>
<body>
<?php
   global $leads_options;
   $query = new WP_Query( array( 'post_type' => 'wp_lead', 'post__in' => array( $leads_options['leads_id'] ) ) );
   while($query->have_posts()) : $query->the_post(); ?>
   <div id="featured_image">
       <?php
       $lead_template = get_post_meta($post->ID,'lead_template',true);
        if (has_post_thumbnail( $post->ID ) ):
        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
            <img src="<?php echo $image[0] ?>" alt="<?php the_title() ?>">
       <?php endif; ?>
   </div>
   <div id="lead_wrap">
       <div id="lead_content">
           <div id="<?php echo $lead_template  ?>">
           <div id="content">
               <?php the_content(); ?>
           </div>
               <?php
               $form_html = get_post_meta($post->ID,'form_code',true);
               $hiddens = array();
               if(!empty($form_html)) {
                   $dom = new DOMDOCUMENT();
                   $dom->loadHTML($form_html);
                   $form = $dom->getElementsByTagName("form")->item(0);
                   $form_action = $form->attributes->getNamedItem("action")->nodeValue;
                   $inputs = $dom->getElementsByTagName('input');
                   foreach($inputs as $input){
                       $input_type = $input->attributes->getNamedItem("type");
                       if (is_null($input_type)) continue;
                       if ($input_type->nodeValue == 'hidden') {
                           $hiddens[] = $input->attributes->getNamedItem("name")->nodeValue;
                           $hiddens_values[] = $input->attributes->getNamedItem("value")->nodeValue;
                       } else if ($input_type->nodeValue == 'text') {
                           $textinputs[] = $input->attributes->getNamedItem("name")->nodeValue;
                       }
                   }
               }

               ?>
               <form action="<?php echo (isset($form_action)) ? $form_action : get_post_meta($post->ID,'form_action',true) ?>" method="post">
               <table cellpadding="10" width="100%">
                   <tr>
                       <td><label for="name">Name</label></td>
                       <td><input type="text" name="<?php echo isset($textinputs[0]) ? $textinputs[0] : 'name' ?>" value="" id="name"></td>
                   </tr>
                   <tr>
                       <td><label for="email">Email</label></td>
                       <td><input type="text" name="<?php echo isset($textinputs[1]) ? $textinputs[1] : 'email' ?>" id="email"></td>
                   </tr>
                   <tr class="input_btn_tr">
                       <td colspan="2">
                           <input type="submit" class="btn medium <?php echo get_post_meta($post->ID,'btn_class',true) ?>" value="<?php echo get_post_meta($post->ID,'submit_btn',true) ?>">
                       </td>
                   </tr>
                   <?php
                        $no_thanks_btn_text = get_post_meta('no_thanks_btn_text',$post->ID,true);
                        if(!empty($no_thanks_btn_text)) { ?>
                            <tr class="input_btn_tr">
                                <td colspan="2">
                                    <a class="<?php echo get_post_meta('btn_class_no_thanks',$post->ID,true) ?>" href="<?php echo get_post_meta('no_thanks_btn_url',$post->ID,true) ?>"><?php echo $no_thanks_btn_text ?></a>
                                </td>
                            </tr>
                        <?php }

                        for($i =0; $i < count($hiddens); $i++){
                           echo '<input type="hidden" name="'.$hiddens[$i].'" value="'.$hiddens_values[$i].'">';
                        }
                   ?>
               </table>
           </form>
           </div>
           <div id="terms-wrap">
                   <a href="http://wpeasyleads.com/"><img src="<?php echo plugins_url(); ?>/leads/images/form1/wp-easy-leads-white.png" style="width:50%;"/></a>
                   <p>Copyright &copy; <a href="<?php echo site_url() ?>"><?php bloginfo('name') ?></a></p>
           </div>
       </div>
   </div>
   <script type="text/javascript">  var _gaq = _gaq || [];  _gaq.push(['_setAccount', 'UA-<?php echo $leads_options['leads_google_analtics_id']; ?>']);  _gaq.push(['_trackPageview']);  (function() {    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);  })();</script>
   <?php endwhile; ?>
<?php wp_footer() ?>

</body>
</html>

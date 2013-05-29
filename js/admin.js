/**
 * Created by touqeer.shafi@gmail.com
 * Date: 4/29/13
 * Time: 3:43 PM
 * Filename :
 */
jQuery(document).ready(function ($) {
    $('h2.nav-tab-wrapper a').click(function (e) {
        e.preventDefault();
        var div = $(this).attr('rel');
        $(this).parent('h2').find('a').removeClass('nav-tab-active')
        $(this).addClass('nav-tab-active');
        $(div).parent("#tabs").find('.tab').each(function () {
            $(this).addClass('hide').hide();
            $(div).fadeIn('fast').removeClass('.hide');
        });
    })

    /*$("#leads_form").submit(function(e){
        e.preventDefault();
        var action = $(this).attr('action');
        $.post(action,$(this).serialize(),function(data){
            //var json = $.parseJson(data);
            alert(data);
        })
    })*/
})

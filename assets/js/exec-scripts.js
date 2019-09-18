jQuery(document).ready(function($) {
    jQuery('.__loadAction').on('click', function(){
        var btn = jQuery(this);
        btn.prop('disabled', true);
        btn.closest('.item-action').addClass('loading');
        var data = {
            'action': 'scripts_actions',
            'undfd-action': btn.data('action')
        };
        jQuery.post(ajaxurl, data, function (response) {
            btn.prop('disabled', false);
            btn.closest('.item-action').removeClass('loading');
        });
    }); 
});
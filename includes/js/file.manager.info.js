
function filemanager_info_files($) {
    jQuery('.filemanagerinfo').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();

        const path = [];
        var i = 0;
        var x = 0;

        jQuery('.checkbox').each(function () {
            if(jQuery(this).is(':checked')){
                path[i] = jQuery(this).attr('name');
            }
            i++;
        });

        jQuery.ajax({
            type: 'post',
            url: file_manager_info_ajax,
            data: {
                'path': path,
                'action': 'file_manager_info'
            },
            dataType: 'json',
            success: function(data){
                jQuery(".filemanager-info-wrapper").append(data);
                jQuery( ".info-window" ).each(function () {
                    jQuery(this).draggable();
                });
                jQuery('.info-window-close').on('click', function(event) {
                    event.preventDefault();
                    jQuery(this).parent().remove();
                });
            },
            error: function(errorThrown){
                //error stuff here.text
            }
        });
    });
}

jQuery(document).ready(function($) {
	filemanager_info_files($);
});

function filemanager_info_files($) {
    jQuery('.filemanagerinfo').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();

        const paths = [];
        var i = 0;
        var x = 0;

        jQuery('.checkbox').each(function () {
            if(jQuery(this).is(':checked')){
                paths[i] = jQuery(this).attr('name');
                i++;
            }
        });
        if(i === 0){
            paths[i] = jQuery("#currentdir").html();
            console.log(paths);
            i++;
        }
        
        jQuery.each( paths, function( key, value ) {
            jQuery.ajax({
                type: 'post',
                url: file_manager_info_ajax,
                data: {
                    'path': value,
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
                        event.stopPropagation();
                        event.stopImmediatePropagation();
                        jQuery(this).parent().remove();
                    });
                },
                error: function(errorThrown){
                    //error stuff here.text
                }
            });
        });
    });
}

jQuery(document).ready(function($) {
	filemanager_info_files($);
});
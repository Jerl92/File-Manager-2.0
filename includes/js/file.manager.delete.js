
function filemanager_delete_files($) {
    jQuery('.btndelete').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();

        var currentdir = jQuery("#currentdir").html();
        var currentpostid = jQuery("#currentpostid").html();
        
        const path = [];
        var i = 0;
        var x = 0;

        jQuery('.checkbox').each(function () {
            if(jQuery(this).is(':checked')){
                console.log(jQuery(this).attr('name'));
                path[i] = jQuery(this).attr('name');
                i++;
            }
        });
        
        if(x === 0) {
            jQuery.ajax({
                type: 'post',
                url: file_manager_delete_ajax,
                data: {
                    'path': path,
                    'action': 'delete_filemanager_files'
                },
                dataType: 'json',
                success: function(data){
                    console.log(data);
                    file_manager_get($, currentdir, currentpostid)
                },
                error: function(errorThrown){
                    //error stuff here.text
                }
            });
        }
    });
}

jQuery(document).ready(function($) {
	filemanager_delete_files($);
});
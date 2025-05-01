
function filemanager_createfile_files($) {
    jQuery('.btnnewfile').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
        jQuery("#subnav-content-file").toggleClass("subnav-content-display");
    });
    jQuery('.newfile').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
        var currentdir = jQuery('#currentdir').html()+'/';
        var currentpostid = jQuery('#currentpostid').html();
        var inputVal = jQuery("#lnamefile").val();

        jQuery.ajax({
            type: 'post',
            url: file_manager_createfile_ajax,
            data: {
                'inputVal': currentdir+'/'+inputVal,
                'action': 'createfile_filemanager_files'
            },
            dataType: 'json',
            success: function(data){
                console.log(data);
                filemanager_get($, currentdir, currentpostid);
            },
            error: function(errorThrown){
                //error stuff here.text
            }
        });
        jQuery("#lnamefile").html('');
        jQuery("#subnav-content-file").toggleClass("subnav-content-display");

    });
}

jQuery(document).ready(function($) {
    filemanager_createfile_files($);
});

function filemanager_createdir_files($) {
    jQuery('.btnnewdir').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
        jQuery("#subnav-content-dir").toggleClass("subnav-content-display");
    });
    jQuery('.newdir').on('click', function() {
        var currentdir = jQuery('#currentdir').html()+'/';
        var currentpostid = jQuery('#currentpostid').html();
        var inputVal = jQuery("#lname").val();

        jQuery.ajax({
            type: 'post',
            url: file_manager_createdir_ajax,
            data: {
                'inputVal': currentdir+'/'+inputVal,
                'action': 'createdir_filemanager_files'
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
        jQuery("#lname").html('');
        jQuery("#subnav-content-dir").toggleClass("subnav-content-display");
    });
}

jQuery(document).ready(function($) {
	filemanager_createdir_files($);
});
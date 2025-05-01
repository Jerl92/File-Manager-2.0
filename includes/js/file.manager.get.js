function file_manager_get($, path, postid){

    var uploads = [];
    var i = 0;
    jQuery('.fileupload').each(function() {
        uploads[i] = jQuery(this);
        i++;
    });

    if(path == null && postid == null){
        jQuery('.workplace-path').click(function($){

            var $this = jQuery(this),
            object_id = $this.data('object-id');
            postid = $this.data('post-id');
    
            console.log(object_id);
    
            jQuery.ajax({
                type: 'post',
                url: file_manager_get_ajax,
                data: {
                    'object_id': object_id,
                    'postid': postid,
                    'upload': uploads,
                    'action': 'file_manager_get'
                },
                dataType: 'json',
                success: function(data){
                    console.log(data);
                    jQuery('.file-manager-wrapper').empty();
                    jQuery('.file-manager-wrapper').html(data);
                    var pageHeight = jQuery( '#content' ).height();
                    jQuery('#sidebar').height(pageHeight+75);
                    file_manager_get($);
                    filemanager_info_files($);
                    filemanager_uploads_files($);
                    filemanager_delete_files($);
                    filemanager_home($);
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            });
        });
    } else {
        
        var object_id = path;
        var postid = postid;

        console.log(object_id);

        jQuery.ajax({
            type: 'post',
            url: file_manager_get_ajax,
            data: {
                'object_id': object_id,
                'postid': postid,
                'upload': uploads,
                'action': 'file_manager_get'
            },
            dataType: 'json',
            success: function(data){
                console.log(data);
                jQuery('.file-manager-wrapper').empty();
                jQuery('.file-manager-wrapper').html(data);
                var pageHeight = jQuery( '#content' ).height();
                jQuery('#sidebar').height(pageHeight+75);
                file_manager_get($);
                filemanager_info_files($);
                filemanager_uploads_files($);
                filemanager_delete_files($);
                filemanager_home($);
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
    }

}

jQuery(document).ready(function($) {
    file_manager_get($);
});
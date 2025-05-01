function filemanager_home($){
    jQuery('.home-filemanager').click(function($){

        var $this = jQuery(this),
        object_id = $this.data('object-id');

        window.location.href = object_id;

    });
}

jQuery(document).ready(function($) {
    filemanager_home($);
});
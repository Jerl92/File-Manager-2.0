
function makeid(length) {
    var result           = '';
    var characters       = '0123456789';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

function uploadFormData($, formData, files_obj, object_id, post_id, rand) {

    jQuery.ajax({
        url: file_manager_upload_ajax+"?upload_dir="+object_id+"&relativepath="+files_obj.webkitRelativePath,
        type: 'POST',
        xhr: function() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt){
              if (evt.lengthComputable) {
                  var percentComplete = evt.loaded / evt.total;
                  percentComplete = parseInt(percentComplete * 100);
                  jQuery('.percent_'+rand).html(null);
                  jQuery('.percent_'+rand).html(percentComplete+'%');
              }
            }, false);
            return xhr;
        },
        success: function (data) {
            console.log(data);
            jQuery('.fileupload_'+rand).remove();
            file_manager_get($, object_id, post_id);
        },
        error: function(data) {
            console.log(data);
        },
        data: formData,
        cache: false,
        contentType: false,
        processData: false
    });
    return false;

}

function createFormData($, files_obj, object_id, post_id) {
    var form_data = new FormData();
    for(i=0; i<files_obj.length; i++) {
        var rand = makeid(24);
        jQuery('#sequentialupload').after('<tr class="fileupload fileupload_'+rand+'" style="display:none;"><td class="filemanager-table-td"><input type="checkbox" id="filemanager-checkbox" class="checkbox" name="'+object_id+files_obj[i].name+'" value="'+object_id+files_obj[i].name+'"></td><td><div class="workplace-path" data-object-id="'+object_id+files_obj[i].name+'" data-post-id="'+post_id+'">'+files_obj[i].name+'</div><td class="percent_'+rand+'">0%</td></tr>');
        jQuery('.filemanager-table tr:first').after('<tr class="fileupload_'+rand+'"><td class="filemanager-table-td"><input type="checkbox" id="filemanager-checkbox" class="checkbox" name="'+object_id+files_obj[i].name+'" value="'+object_id+files_obj[i].name+'"></td><td><div class="workplace-path" data-object-id="'+object_id+files_obj[i].name+'" data-post-id="'+post_id+'">'+files_obj[i].name+'</div><td class="percent_'+rand+'">0%</td></tr>');
        form_data.append('myfile', files_obj[i]);
        uploadFormData($, form_data, files_obj[i], object_id, post_id, rand);
    }
}

function filemanager_uploads_files($) {       
    jQuery('.uploadfile').click(function(event){
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
        var object_id = jQuery('#currentdir').text();
        var post_id = jQuery('#currentpostid').text();
        jQuery('#fileupload').trigger("click");
        let pickerfiles = document.getElementById('fileupload');
        if(pickerfiles) { 
            pickerfiles.addEventListener('change', event => {
                event.preventDefault();
                event.stopPropagation();
                event.stopImmediatePropagation();
                createFormData($, event.target.files, object_id, post_id);
            });
        }
    });
}

jQuery(document).ready(function($) {
    filemanager_uploads_files($);
});
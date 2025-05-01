
function makeid(length) {
    var result           = '';
    var characters       = '0123456789';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

function uploadFormData($, formData, files_obj, rand) {
    var object_id = jQuery('#currentdir').html()+'/';
    var post_id = jQuery('#currentpostid').html();
    jQuery.ajax({
        type: 'POST',
        data: formData,
        cache: false,
        processData: false,
        contentType: false, 
        url: file_manager_upload_ajax+"?upload_dir="+object_id+"&relativepath="+files_obj.webkitRelativePath,
        xhr: function() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener('progress', function(evt){
              if (evt.lengthComputable) {
                  var percent_Complete = evt.loaded / evt.total;
                  percentComplete = parseInt(percent_Complete * 100);
                  jQuery('.percent_'+rand).html(percentComplete+'%');
              }
            }, false);
            return xhr;
        },
        success: function (data) {
            console.log(data);
            jQuery('.fileupload_'+rand).remove();
            filemanager_get($, object_id, post_id);
        },
        error: function(data) {
            console.log(data);
        }
    });
}

function createFormData($, files_obj) {
    var form_data = new FormData();
    var object_id = jQuery('#currentdir').html()+'/';
    var post_id = jQuery('#currentpostid').html();
    for(i=0; i<files_obj.length; i++) {
        var rand = makeid(6);
        if(files_obj[i].webkitRelativePath === ''){
            jQuery('#sequentialupload').after('<tr class="fileupload fileupload_'+rand+'" style="display:none;"><td class="filemanager-table-td"><input type="checkbox" id="filemanager-checkbox" class="checkbox" name="'+object_id+files_obj[i].name+'" value="'+object_id+files_obj[i].name+'"></td><td><div class="workplace-path" data-object-id="'+object_id+files_obj[i].name+'" data-post-id="'+post_id+'">'+files_obj[i].name+'</div><td class="percent_'+rand+'">0%</td></tr>');
            jQuery('.filemanager-table tr:first').after('<tr class="fileupload_'+rand+'"><td class="filemanager-table-td"><input type="checkbox" id="filemanager-checkbox" class="checkbox" name="'+object_id+files_obj[i].name+'" value="'+object_id+files_obj[i].name+'"></td><td><div class="workplace-path" data-object-id="'+object_id+files_obj[i].name+'" data-post-id="'+post_id+'">'+files_obj[i].name+'</div><td class="percent_'+rand+'">0%</td></tr>');
        } else {
            var relativepath = files_obj[i].webkitRelativePath;
            jQuery('#sequentialupload').after('<tr class="fileupload fileupload_'+rand+'" style="display:none;"><td class="filemanager-table-td"><input type="checkbox" id="filemanager-checkbox" class="checkbox" name="'+object_id+relativepath+'" value="'+object_id+relativepath+'"></td><td><div class="workplace-path" data-object-id="'+object_id+relativepath+'" data-post-id="'+post_id+'">'+relativepath+'</div><td class="percent_'+rand+'">0%</td></tr>');
            jQuery('.filemanager-table tr:first').after('<tr class="fileupload_'+rand+'"><td class="filemanager-table-td"><input type="checkbox" id="filemanager-checkbox" class="checkbox" name="'+object_id+relativepath+'" value="'+object_id+relativepath+'"></td><td><div class="workplace-path" data-object-id="'+object_id+relativepath+'" data-post-id="'+post_id+'">'+relativepath+'</div><td class="percent_'+rand+'">0%</td></tr>');
        }
        form_data.append('myfile', files_obj[i]);
        uploadFormData($, form_data, files_obj[i], rand);
    }
}

function filemanager_uploads_files($) {
    jQuery('.uploadfile').click(function(event){
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
        jQuery('#fileupload').trigger("click");
        jQuery('#fileupload').bind('change', function(event) {
            event.preventDefault();
            event.stopPropagation();
            event.stopImmediatePropagation();
            createFormData($, event.target.files);
        });
    });
    jQuery('.uploaddir').click(function(event){
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
        jQuery('#dirupload').trigger("click");
        jQuery('#dirupload').bind('change', function(event) {
            event.preventDefault();
            event.stopPropagation();
            event.stopImmediatePropagation();
            createFormData($, event.target.files);
        });
    });
}

jQuery(document).ready(function($) {
    filemanager_uploads_files($);
});
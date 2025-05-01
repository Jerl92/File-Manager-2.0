function filemanager_CodeMirror($){
    var editor_path = jQuery(".editor_path").html();
    if(document.getElementById("editor") !== null){
        var myCodeMirror = CodeMirror(
            document.getElementById('editor'), {
                lineNumbers: true,
                mode: "text/html"
            });
            fetch(editor_path)
                .then(response => response.text())
                .then(data => {
                    myCodeMirror.setValue(data);
                }); 
        jQuery('#savefile').click(function($){
            var getValue = myCodeMirror.getValue();
            var currentpath = jQuery("#currentdir").html();
            jQuery.ajax({
                type: 'post',
                url: file_manager_codemirror_ajax,
                data: {
                    'object_id': getValue,
                    'link': currentpath,
                    'action': 'save_filemanager_files'
                },
                dataType: 'json',
                success: function(data){
                    console.log(data);
                    jQuery("#filemanagerlog").html('File saved!');
                    jQuery("#filemanagerlog").show().delay(5000).queue(function(n) {
                        jQuery(this).hide();
                    });
                },
                error: function(errorThrown){
                    //error stuff here.text
                }
            });
        });
    }
}
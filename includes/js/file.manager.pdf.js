function filemanager_pdf($){
    var currentpath = jQuery(".editor_path").html();
    PDFObject.embed(currentpath, ".pdfviewer");
}


jQuery(document).ready(function($) {
	filemanager_pdf($);
});
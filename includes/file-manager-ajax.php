<?php

/* Enqueue Script */
add_action( 'wp_enqueue_scripts', 'wp_filemanager_ajax_scripts' );

/**
 * Scripts
 */
function wp_filemanager_ajax_scripts() {
	/* Plugin DIR URL */
	$url = trailingslashit( plugin_dir_url( __FILE__ ) );

	wp_register_script( 'file-manager-get', $url . "js/file.manager.get.js", array( 'jquery' ), '1.0.0', true );
	wp_localize_script( 'file-manager-get', 'file_manager_get_ajax', admin_url( 'admin-ajax.php' ) );
	wp_enqueue_script( 'file-manager-get' );	

    wp_register_script( 'file-manager-upload', $url . "js/file.manager.upload.js", array( 'jquery' ), '1.0.0', true );
	wp_localize_script( 'file-manager-upload', 'file_manager_upload_ajax', plugin_dir_url( __DIR__ ) . 'includes/upload.php' );
	wp_enqueue_script( 'file-manager-upload' );	

    wp_register_script( 'file-manager-info', $url . "js/file.manager.info.js", array( 'jquery' ), '1.0.0', true );
	wp_localize_script( 'file-manager-info', 'file_manager_info_ajax', admin_url( 'admin-ajax.php' ) );
	wp_enqueue_script( 'file-manager-info' );	

    wp_register_script( 'file-manager-delete', $url . "js/file.manager.delete.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'file-manager-delete', 'file_manager_delete_ajax', admin_url( 'admin-ajax.php' ) );
    wp_enqueue_script( 'file-manager-delete' );
    
    wp_register_script( 'file-manager-home', $url . "js/file.manager.home.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'file-manager-home', 'file_manager_home_ajax', admin_url( 'admin-ajax.php' ) );
    wp_enqueue_script( 'file-manager-home' );

    wp_register_script( 'file-manager-createfile', $url . "js/file.manager.createfile.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'file-manager-createfile', 'file_manager_createfile_ajax', admin_url( 'admin-ajax.php' ) );
    wp_enqueue_script( 'file-manager-createfile' );

    wp_register_script( 'file-manager-createdir', $url . "js/file.manager.createdir.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'file-manager-createdir', 'file_manager_createdir_ajax', admin_url( 'admin-ajax.php' ) );
    wp_enqueue_script( 'file-manager-createdir' );
    
    wp_register_script( 'file-manager-codemirror', $url . "js/file.manager.codemirror.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'file-manager-codemirror', 'file_manager_codemirror_ajax', admin_url( 'admin-ajax.php' ) );
    wp_enqueue_script( 'file-manager-codemirror' );

    wp_register_script( 'file-manager-pdf', $url . "js/file.manager.pdf.js", array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'file-manager-pdf', 'file_manager_pdf_ajax', admin_url( 'admin-ajax.php' ) );
    wp_enqueue_script( 'file-manager-pdf' );

}

/* 3. AJAX CALLBACK
------------------------------------------ */
/* AJAX action callback */
add_action( 'wp_ajax_file_manager_get', 'file_manager_get' );
add_action( 'wp_ajax_nopriv_file_manager_get', 'file_manager_get' );

function file_manager_get($post) {
    $posts  = array();

    $path = $_POST['object_id'];

    $uploads = $_POST['upload'];

    $postid = $_POST['postid'];

    $path_origin = get_post_meta($postid, "_workplace_path", true);

    $workplace = $path;

    $files = array_diff(scandir($workplace), array('.', '..'));

    similar_text(realpath($path_origin),realpath($path),$percent_path);

    include_once(dirname(__FILE__) . '/id3/getid3.php');

    $html[] = '<div id="currentdir" style="display:none">'.realpath($path).'</div>';

    $html[] = '<div id="currentpostid" style="display:none">'.$postid.'</div>';

    $path_info = pathinfo($path);
    $extension_strtolower = strtolower($path_info['extension']);

    $workplaceright = get_post_meta( $postid, "_workplace_right", true);

    if($workplaceright[-1]['read'] == 1) {
        $read_path = true;
    }
    if($workplaceright[-1]['write'] == 1) {
        $write_path = true;
    }
    if($workplaceright[$user->ID]['read'] == 1) {
        $read_path = true;
    }
    if($workplaceright[$user->ID]['write'] == 1) {
        $write_path = true;
    }

    $html[] .= '<div class="filemanagerbtn">';

        $html[] .= '<div id="filemanagerbtnup">';
            if ($percent_path != 100) {
                    $html[] .= '<div class="workplace-path filemanagerbtnup" data-object-id="'. dirname($path) . '" data-post-id="'.$postid.'">';
                        $html[] .= 'Parent directory';
                    $html[] .= '</div>';
            } else {
            global $wp;
            $link = home_url( $wp->request );
                $html[] .= '<div class="home-filemanager filemanagerbtnup" data-object-id="'.$link.'">';
                    $html[] .= 'Home';
                $html[] .= '</div>';
            }
            if(is_dir($path .'/'. $file)) {
                if ($write_path == true) {
                    $html[] .= '<div class="uploadfile filemanagerbtnup">Upload File</div>';
                    $html[] .= '<input id="fileupload" type="file" name="fileupload" multiple style="display:none;"/>';
                    $html[] .= '<div class="uploaddir filemanagerbtnup">Upload Dir</div>';
                    $html[] .= '<input id="dirupload" type="file" name="fileupload" webkitdirectory multiple style="display: none;">';
                    $html[] .= '<div class="btnnewfile filemanagerbtnup">Create file</div>';
                    $html[] .= '<div id="subnav-content-file" class="subnav-content" style="display: none;">';
                        $html[] .= '<span>';
                            $html[] .= '<input type="text" id="lnamefile" name="lname"></input>';
                            $html[] .= '<button class="newfile">Create</button>';
                    $html[] .= '<span>';
                    $html[] .= '</div>';
                    $html[] .= '<div class="btnnewdir filemanagerbtnup">Create dir</div>';
                    $html[] .= '<div id="subnav-content-dir" class="subnav-content" style="display: none;">';
                        $html[] .= '<span>';
                            $html[] .= '<input type="text" id="lname" name="lname"></input>';
                            $html[] .= '<button class="newdir">Create</button>';
                        $html[] .= '<span>';
                    $html[] .= '</div>';
                    $html[] .= '<div class="btndelete filemanagerbtnup">Delete</div>';
                }
            }
            if ($extension_strtolower == 'txt' || $extension_strtolower == 'html' || $extension_strtolower == 'php' || $extension_strtolower == 'js' || $extension_strtolower == 'log') { 
                if ($write_path == true) {
                    $html[] .= '<div id="savefile" class="filemanagerbtnup">Save</div>';
                }
            }
        $html[] .= '</div>';

        $html[] .= '<div id="filemanagerbtndown">';
            $html[] .= '<div class="filemanagerinfo filemanagerbtndown">';
                $html[] .= 'Info';
            $html[] .= '</div>';
        $html[] .= '</div>';
        
    $html[] .= '</div>';

    $html[] .= '<div id="filemanagerlog"></div>';
    
    $dir = 0;
    $file_ = 0;
    if(is_dir($path)){
        foreach($files as $file){
            if(is_dir($path .'/'. $file)) {
                $dir++;
            }
            if(!is_dir($path .'/'. $file)) {
                $file_++;
            }
        }

        $html[] .= '<table class="filemanager-table-info">';
            $html[] .= '<tr>';
                $html[] .= '<td>';
                    $html[] .= realpath($path);
                $html[] .= '</td>';
                $html[] .= '<td style="float: right; padding-right: 5px;">';
                    $html[] .= $file_ . ' Files & ' . $dir . ' Directory';
                $html[] .= '</td>';
            $html[] .= '</tr>';
        $html[] .= '</table>';       
    }

    $html[] .= '<table class="filemanager-table">';
        foreach($uploads as $upload){
            $html[] .= $upload;
        }    
        if(is_dir(realpath($path))) {
            $html[] .= '<tr>';
                $html[] .= '<th class="filemanager-table-td">';
                    $html[] .= '<input type="checkbox" id="filemanager-all-checkbox" class="checkbox" name="checkbox">';
                $html[] .= '</th>';
                $html[] .= '<th>Filename</th>';
                $html[] .= '<th>Size</th>';
            $html[] .= '</tr>';
        }
        foreach($files as $file){
            $html[] .= '<tr>';
                $html[] .= '<td class="filemanager-table-td">';
                    $html[] .= '<input type="checkbox" id="filemanager-checkbox" class="checkbox" name="'. $path .'/'. $file .'" value="'. $path .'/'. $file .'">';
                $html[] .= '</td>';
                $html[] .= '<td>';
                    $html[] .= '<div class="workplace-path" data-object-id="'. $path .'/'. $file .'" data-post-id="'.$postid.'">';
                        $html[] .= $file;
                    $html[] .= '</div>';
                $html[] .= '</td>';
                $html[] .= '<td>';
                    if(!is_dir($path .'/'. $file)) {
                        $html[] .= formatSizeUnits(filesize($path .'/'. $file));
                    }
                $html[] .= '</td>';
            $html[] .= '</tr>';
        }
    $html[] .= '</table>';

    if(!is_dir($path)) {
        $dir = plugins_url( '', __FILE__ );
        $path_info = pathinfo($path);
        $extension_strtolower = strtolower($path_info['extension']);
        $getID3 = new getID3;
        $fileID3 = $getID3->analyze($path);

        $u = 0;
        $b = 0;

        if ($dh = opendir($path_info['dirname'])) {
            while (($file = readdir($dh)) !== false) {
                if(!is_dir($path)) {
                    $allFiles[] = $file;
                }
            }
            closedir($dh);
        }
        $files = array_diff($allFiles, array('.', '..'));

        sort($files);
                            
        foreach($files as $file) {

            if($file == basename($path)) {
                $u = $b;
            }
            $b++;
        }

        $before = $files[$u-1];
        $next = $files[$u+1];

        $before_file = null;
        $next_file = null;

        if(!is_dir($path_info['dirname'] .'/'. $before)){
            $before_file = $before;
        }

        if(!is_dir($path_info['dirname'] .'/'. $next)){
            $next_file = $next;
        }

        $html[] .= '<div class="filemanager-info-wrapper">';
            $html[] .= '<div class="filemanager-info-basename" style="float: left;">';
                $html[] .= '<h4>'. $path_info['basename'] .'</h4>';
            $html[] .= '</div>';

            $html[] .= '<div class="file-info-navigate" style="float: right; width: 11%;">';
            if (isset($before_file)) {
                $html[] .= '<div class="file-before" style="float: left; margin: 15px;"><div class="workplace-path" data-object-id="'. $path_info['dirname'] .'/'. $before_file .'" data-post-id="'.$postid.'">Previous</div></div>';
            }
            if (isset($next_file)) {
                $html[] .= '<div class="file-next" style="margin: 15px;"><div class="workplace-path" data-object-id="'. $path_info['dirname'] .'/'. $next_file .'" data-post-id="'.$postid.'">Next</div></div>';
            }
            $html[] .= '</div>';
        $html[] .= '</div>';

        if($extension_strtolower == 'mkv' || $extension_strtolower == 'avi' || $extension_strtolower == 'mp4'){
            $html[] .= '<video controls="controls" preload="auto" controlsList="nodownload" name="media" width="100%" height="100%" style="margin-bottom: 0;">';
                $html[] .= '<source src="'.$dir.'/download.php?path='.$path.'" typ type="video/mp4">';
            $html[] .= '</video>';
            $html[] .= '<div class="filemanager-info-wrapper">';
                $html[] .= '<div class="filemanager-info-basename">';
                    $html[] .= '<h4>'. $path_info['basename'] .'</h4>';
                $html[] .= '</div>';
                $html[] .= '<div class="filemanager-info-dirname">';
                    $html[] .= '<h6>'. $path_info['dirname'] .'</h6>';
                $html[] .= '</div>';
                $html[] .= '<div class="filemanager-info-filesize">';
                    $html[] .= '<p>'. formatSizeUnits(filesize($path)).'</p>';
                $html[] .= '</div>';
                $html[] .= '<div class="filemanager-info-wrapper">';

                    $html[] .= '<div class="filemanager-info-video-wrapper">';

                        $html[] .= '<div class="filemanager-info-video">';
                            $html[] .= 'Video';
                        $html[] .= '</div>';

                        $html[] .= '<div class="filemanager-info-video-duration">';
                            $html[] .= 'Duration:'. $fileID3['playtime_string'];
                        $html[] .= '</div>';

                        $html[] .= '<div class="filemanager-info-video-dataformat">';
                            $html[] .= 'Codec:' . $fileID3['video']['dataformat'];    
                        $html[] .= '</div>';   

                        $html[] .= '<div class="filemanager-info-video-resolution">';
                            $html[] .= 'Dimensions:' . $fileID3['video']['resolution_x'].'x'.$fileID3['video']['resolution_y'];
                        $html[] .= '</div>';

                        $html[] .= '<div class="filemanager-info-video-frame-rate">';
                            $html[] .= 'Frame rate:' . $fileID3['video']['frame_rate'];
                        $html[] .= '</div>';

                    $html[] .= '</div>';

                    $html[] .= '<div class="filemanager-info-audio-wrapper">';

                        $html[] .= '<div class="filemanager-info-audio">';
                            $html[] .= 'Audio';
                        $html[] .= '</div>';

                        $html[] .= '<div class="filemanager-info-audio-dataformat">';
                            $html[] .= 'Codec:' . $fileID3['audio']['dataformat'];
                        $html[] .= '</div>';

                        $html[] .= '<div class="filemanager-info-audio-bits-per-sample">';
                            $html[] .= 'Bits per sample:' . $fileID3['audio']['bits_per_sample'];
                        $html[] .= '</div>';

                        $html[] .= '<div class="filemanager-info-audio-channelmode">';
                            $html[] .= 'Channelmode:' . $fileID3['audio']['channelmode'];
                        $html[] .= '</div>';

                        $html[] .= '<div class="filemanager-info-audio-sample-rate">';
                            $html[] .= 'Sample rate:' . $fileID3['audio']['sample_rate'];
                        $html[] .= '</div>';

                    $html[] .= '</div>';

                $html[] .= '</div>';
            $html[] .= '</div>';
        } else if( $extension_strtolower == 'jpg' || $extension_strtolower == 'jpeg' || $extension_strtolower == 'bmp'){
            $html[] .= '<img src="'.$dir.'/download.php?path='.$path.'">';
            $html[] .= '<div class="filemanager-info-wrapper">';
                $html[] .= '<div class="filemanager-info-basename">';
                    $html[] .= '<h4>'. $path_info['basename'] .'</h4>';
                $html[] .= '</div>';
                $html[] .= '<div class="filemanager-info-dirname">';
                    $html[] .= '<h6>'. $path_info['dirname'] .'</h6>';
                $html[] .= '</div>';
                $html[] .= '<div class="filemanager-info-filesize">';
                    $html[] .= '<p>'. formatSizeUnits(filesize($path)).'</p>';
                $html[] .= '</div>';
            $html[] .= '</div>';
        } else if ($extension_strtolower == 'txt' || $extension_strtolower == 'html' || $extension_strtolower == 'php' || $extension_strtolower == 'js' || $extension_strtolower == 'log') { 
            $html[] .= '<div class="editor_path" style="display:none;">'.$dir.'/download.php?path='.$path.'</div>';
            $html[] .= '<div id="editor"></div>';
        } else if ($extension_strtolower == 'pdf') {
            $html[] .= '<div class="editor_path" style="display:none;">'.$dir.'/download.php?path='.$path.'</div>';
            $html[] .= '</div><div class="pdfviewer" width="100%" height="100%"></div>';
        } else if(!is_dir($path)) {
            $html[] .= '<div class="filemanager-download-wrapper">';
                $html[] .= '<div class="filemanager-info-wrapper">';
                    $html[] .= '<div class="filemanager-info-basename">';
                        $html[] .= '<h4>'. $path_info['basename'] .'</h4>';
                    $html[] .= '</div>';
                    $html[] .= '<div class="filemanager-info-dirname">';
                        $html[] .= '<h6>'. $path_info['dirname'] .'</h6>';
                    $html[] .= '</div>';
                    $html[] .= '<div class="filemanager-info-filesize">';
                        $html[] .= '<p>'. formatSizeUnits(filesize($path)).'</p>';
                    $html[] .= '</div>';
                    $html[] .= '<div class="filemanager-download-btn">';
                        $html[] .= '<a href="'.$dir.'/download.php?path='.$path.'">Download</a>';
                    $html[] .= '</div>';
                $html[] .= '</div>';
            $html[] .= '</div>';
        }

        if(!is_dir($path)) {
            $html[] .= '<input type="checkbox" id="filemanager-checkbox" class="checkbox" style="display: none;" name="'. $path .'" value="'. $path .'" checked>';
        }
    }

    return wp_send_json ( implode($html) );  
}  

function countFiles($path) {
    $size = 0;
    $ignore = array('.','..');
    $files = scandir($path);
    foreach($files as $t) {
        if(in_array($t, $ignore)) continue;
        if (is_dir(rtrim($path, '/') . '/' . $t)) {
        $size += countFiles(rtrim($path, '/') . '/' . $t);
        } else {
        $size++;
        }   
    }
    return $size;
}

function GetDirectorySize($path){
    $bytestotal = 0;
    $path = realpath($path);
  
    if($path!==false && $path!='' && file_exists($path)){
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object){
            $bytestotal += $object->getSize();
        }
    }
    return $bytestotal;
}

function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824)
    {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    }
    elseif ($bytes >= 1048576)
    {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    }
    elseif ($bytes >= 1024)
    {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    }
    elseif ($bytes > 1)
    {
        $bytes = $bytes . ' bytes';
    }
    elseif ($bytes == 1)
    {
        $bytes = $bytes . ' byte';
    }
    else
    {
        $bytes = '0 bytes';
    }

    return $bytes;
}

add_action( 'wp_ajax_file_manager_info', 'file_manager_info' );
add_action( 'wp_ajax_nopriv_file_manager_info', 'file_manager_info' );

function file_manager_info($post) {

    $path = $_POST['path'];

    if ( $path != '') {
        $i = 0;
        $direname = dirname($path);
        $basename = strtolower(basename($path));
        $getpwuid = posix_getpwuid(fileowner($path));
        $html[] = "<div class='info-window'>";
        $html[] .= "<div class='info-window-close'>X</div>";
            $html[] .= "<div class='info-window-name'>" . $basename . "</div>";
            $html[] .= "<div class='info-window-dir-name'>" . $direname . "</div>";
            $html[] .= "<div class='info-window-fileperms'>" . fileperms($path) . "</div>";
            foreach ($getpwuid as $username) {
            if ($i <= 0) {
                $html[] .= "<div class='info-window-getpwuid'>" . $username . "</div>";
            }
            $i++;
            }
            if (is_dir($path)) {
            $html[] .= "<div class='info-window-count'>" . countFiles($path) . "</div>";
            try {
                $html[] .= "<div class='info-window-count-size'>" . formatSizeUnits(GetDirectorySize($path)) . "</div>";
            } catch (Exception $e) {
                $html[] .= $e.'<br>';
            }
            } else {
            $html[] .= "<div class='info-window-size'>" . formatSizeUnits(filesize($path)) . "</div>";
            }
        $html[] .= "</div>";
    }
  
    if($html != null) {
      return wp_send_json ( implode($html) );
    } else {
      return wp_send_json ( null );
    }
  
}  

/* AJAX action callback */
add_action( 'wp_ajax_delete_filemanager_files', 'delete_filemanager_files' );
add_action( 'wp_ajax_nopriv_delete_filemanager_files', 'delete_filemanager_files' );
function removeDirectory($path) {

  $files = glob($path . '/*');
  foreach ($files as $file) {
    is_dir($file) ? removeDirectory($file) : unlink($file);
  }
  rmdir($path);

  return;
}

function delete_filemanager_files($posts) {

  $object_id = $_POST['path'];

  foreach ($object_id as $file) {
    if (is_dir($file)) {
        removeDirectory($file);
    } else {
        unlink($file);
    }
    $html[] = $file;
  }

  return wp_send_json ( implode($html) );

}

/* AJAX action callback */
add_action( 'wp_ajax_createfile_filemanager_files', 'createfile_filemanager_files' );
add_action( 'wp_ajax_nopriv_createfile_filemanager_files', 'createfile_filemanager_files' );
function createfile_filemanager_files($posts) {

  $object_id = $_POST['inputVal'];

  $myfile = fopen($object_id, "w");
  fwrite($myfile, "");
  fclose($myfile);

  return wp_send_json ( $object_id );

}

/* AJAX action callback */
add_action( 'wp_ajax_createdir_filemanager_files', 'createdir_filemanager_files' );
add_action( 'wp_ajax_nopriv_createdir_filemanager_files', 'createdir_filemanager_files' );
function createdir_filemanager_files($posts) {

  $object_id = $_POST['inputVal'];

  mkdir($object_id);

  return wp_send_json ( $object_id );

}

/* AJAX action callback */
add_action( 'wp_ajax_save_filemanager_files', 'save_filemanager_files' );
add_action( 'wp_ajax_nopriv_save_filemanager_files', 'save_filemanager_files' );
function save_filemanager_files($posts) {

  $object_id = stripslashes($_POST['object_id']);
  $link = $_POST['link'];

  $myfile = fopen($link, "w");
  $write = fwrite($myfile, $object_id);
  fclose($myfile);

  return wp_send_json ( $write );

}

?>
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

}

/* 3. AJAX CALLBACK
------------------------------------------ */
/* AJAX action callback */
add_action( 'wp_ajax_file_manager_get', 'file_manager_get' );
add_action( 'wp_ajax_nopriv_file_manager_get', 'file_manager_get' );

function file_manager_get($post) {
    $posts  = array();

    $path = $_POST['object_id'];

    $postid = $_POST['postid'];

    $path_origin = get_post_meta($postid, "_workplace_path", true);

    $workplace = $path;

    $files = array_diff(scandir($workplace), array('.', '..'));

    similar_text($path_origin,$path,$percent_path);

    include_once(dirname(__FILE__) . '/id3/getid3.php');

    $html[] = '<div id="currentdir" style="display:none">'.$path.'/'.'</div>';

    $html[] = '<div id="currentpostid" style="display:none">'.$postid.'</div>';

    $html[] .= '<div id="sequentialupload" class="sequentialupload" data-object-id="'.$path.'"></div>';

    $html[] .= '<div class="filemanagerbtn">';

        $html[] .= '<div id="filemanagerbtnup">';
            if ($percent_path != 100) {
                    $html[] .= '<div class="workplace-path filemanagerbtnup" data-object-id="'. dirname($path) . '" data-post-id="'.$postid.'">';
                        $html[] .= 'Parent directory';
                    $html[] .= '</div>';
            } else {
            global $wp;
            $link = home_url( $wp->request );
                $html[] .= '<a herf="'.$link.'" class="filemanagerbtnup">';
                    $html[] .= 'Home';
                $html[] .= '</a>';
            }
            $html[] .= '<div class="uploadfile filemanagerbtnup">Upload File</div>';
            $html[] .= '<input id="fileupload" type="file" name="fileupload" multiple style="display:none;"/>';
        $html[] .= '</div>';

        $html[] .= '<div id="filemanagerbtndown">';
            $html[] .= '<div class="filemanagerinfo filemanagerbtndown">';
                $html[] .= 'Info';
            $html[] .= '</div>';
        $html[] .= '</div>';
    
    $html[] .= '</div>';

    $html[] .= '<div class="filemanager-info-wrapper">';
    
    $html[] .= '<table class="filemanager-table">';
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

    $dir = plugins_url( '', __FILE__ );
    $path_info = pathinfo($path);
    $extension_strtolower = strtolower($path_info['extension']);
    $getID3 = new getID3;
    $fileID3 = $getID3->analyze($path);
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

    $html[] .= '<input type="checkbox" id="filemanager-checkbox" class="checkbox" style="display: none;" name="'. $path .'" value="'. $path .'" checked>';

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

    $paths = $_POST['path'];

    foreach ( $paths as $path ) {
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
    }
  
    if($html != null) {
      return wp_send_json ( implode($html) );
    } else {
      return wp_send_json ( null );
    }
  
}  
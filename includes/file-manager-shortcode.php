 <?php

function filemanager_shortcode() { 

    $args = array(
        'numberposts' => -1,
        'post_type'   => 'workplace'
    );
    
    $workplaces = get_posts( $args );

    echo '<div id="sequentialupload" class="sequentialupload"></div>';

    ?><div class="file-manager-wrapper"><?php

        foreach($workplaces as $workplace){
            $workplace->post_title;    
            $path = get_post_meta($workplace->ID, "_workplace_path", true);
            echo '<div class="workplace-path" data-object-id="' . $path . '" data-post-id="' . $workplace->ID . '">'.$path.'</div><br>';
        }

    ?></div><?php
}

add_shortcode('file-manager', 'filemanager_shortcode');

 ?>
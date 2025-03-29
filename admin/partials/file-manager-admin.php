<?php

    /**
     * Register a custom menu page.
     */
    function wpdocs_register_my_custom_menu_page() {
            // add top level menu page
            add_menu_page(
                    'File Manager', 
                    'File Manager', 
                    'manage_options', 
                    'my-setting-admin', 
                    'create_admin_page'
                );
                 // seconde option du sous-menu
                 add_submenu_page( 
                    "my-setting-admin",   // slug du menu parent
                    __( "Mon thème - Mon sous-menu - Configuration", "montheme" ),  // texte de la balise <title>
                    __( "Workplace", "montheme" ),   // titre de l'option de sous-menu
                    "manage_options",  // droits requis pour voir l'option de menu
                    'edit.php?post_type=workplace',
                    false // fonction de rappel pour créer la page
                 );
                // seconde option du sous-menu
                add_submenu_page( 
                    "my-setting-admin",   // slug du menu parent
                    __( "Mon thème - Mon sous-menu - Configuration", "montheme" ),  // texte de la balise <title>
                    __( "Shares Links", "montheme" ),   // titre de l'option de sous-menu
                    "manage_options",  // droits requis pour voir l'option de menu
                    'edit.php?post_type=share',
                    false // fonction de rappel pour créer la page
                );
                add_submenu_page( 
                    "my-setting-admin",   // slug du menu parent
                    __( "Mon thème - Mon sous-menu - Configuration", "montheme" ),  // texte de la balise <title>
                    __( "Disk", "montheme" ),   // titre de l'option de sous-menu
                    "manage_options",  // droits requis pour voir l'option de menu
                    'edit.php?post_type=disk',
                    false // fonction de rappel pour créer la page
                );
    }
    add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );

    function wporg_add_custom_box() {
        $screens = [ 'workplace' ];
        foreach ( $screens as $screen ) {
            add_meta_box(
                'wporg_box_id',                 // Unique ID
                'Custom Meta Box Title',      // Box title
                'wporg_custom_box_html',  // Content callback, must be of type callable
                $screen                            // Post type
            );
        }
    }
    add_action( 'add_meta_boxes', 'wporg_add_custom_box' );

    function wporg_custom_box_html( $post ) {
        wp_nonce_field(basename(__FILE__), "meta-box-nonce");
        $blogusers = get_users();
        ?>
            <div>
                <label>workplace Path</label>
                <br>
                <?php $workplace_path = get_post_meta($post->ID, "_workplace_path", true); ?>
                <input name="workplace-path-textarea" type="text" id="workplace-path-textarea" class="workplace-path-textarea" value="<?php echo $workplace_path; ?>" size="50">
                <br>
                <label>User right</label>
                <div class="flex">
                    <?php $workplace_right = get_post_meta($post->ID, "_workplace_right", true); ?>
                    <?php foreach ($blogusers as $bloguser) {
                        echo "<div class='flex-user'>";
                        $read_ = false;
                        $write_ = false;
                        echo $bloguser->user_login . ' - ';
                        $my_option_read = $workplace_right[$bloguser->ID]['read'];
                        if ($my_option_read == 1) {
                            $read_ = true;
                        }
                        if ($read_ == true) {
                            echo 'Read<input type="checkbox" id="read" name="read[]" value="' . $bloguser->ID .'" checked>';
                        } else {
                            echo 'Read<input type="checkbox" id="read" name="read[]" value="' . $bloguser->ID .'">';
                        }

                        $my_option_write = $workplace_right[$bloguser->ID]['write'];
                        if ($my_option_write == 1) {
                            $write_ = true;
                        }
                        if ($write_ == true) {
                            echo 'Write<input type="checkbox" id="write" name="write[]" value="' . $bloguser->ID .'" checked>';
                        } else {
                            echo 'Write<input type="checkbox" id="write" name="write[]" value="' . $bloguser->ID .'">';
                        }
                        echo "</div>";
                    }
                    $read_ = false;
                    $write_ = false;
                    echo "<div class='flex-user'>";
                    echo 'Public - ';
                    $my_option_read = $workplace_right[-1]['read'];
                    if ($my_option_read == 1) {
                        $read_ = true;
                    }
                    if ($read_ == true) {
                        echo 'Read<input type="checkbox" id="read" name="read[]" value="-1" checked>';
                    } else {
                        echo 'Read<input type="checkbox" id="read" name="read[]" value="-1">';
                    }

                    $my_option_write = $workplace_right[-1]['write'];
                    if ($my_option_write == 1) {
                        $write_ = true;
                    }
                    if ($write_ == true) {
                        echo 'Write<input type="checkbox" id="write" name="write[]" value="-1" checked>';
                    } else {
                        echo 'Write<input type="checkbox" id="write" name="write[]" value="-1">';
                    } ?>
                        </div>
                </div>
            </div>

        <?php  
    }

    
    function save_workplace_meta_box($post_id, $post, $update) {
        if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;
        if(!current_user_can("edit_post", $post_id))
            return $post_id;
        if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
            return $post_id;
        $slug = "workplace";
        if($slug != $post->post_type)
            return $post_id;

        if( ! isset( $_POST['workplace-path-textarea'] ) )
        return; 
        update_post_meta( $post_id, "_workplace_path", $_POST['workplace-path-textarea'] );

        
        if( isset( $_POST['read'] ) )
        foreach($_POST['read'] as $read_id) {
            $array[$read_id]['read'] = true;
        }
    
        if( isset( $_POST['write'] ) )
        foreach($_POST['write'] as $read_id) {
            $array[$read_id]['write'] = true;
        }

        update_post_meta( $post_id, "_workplace_right", $array );

    }
    add_action("save_post", "save_workplace_meta_box", 10, 3);

?>
<?php
/*
Plugin Name: Easy Author Image
Plugin URI: http://lawsonry.com/
Description: Adds an author image uploader to your profile page. Upload an author image right from your profile page with the click of a button.
Version: 1.7
Author: Jesse Lawson
Author URI: http://www.lawsonry.com
Text Domain: easy-author-image
License: GPL2

Hey I love learning from other people's code too, so please feel free to dive into this and if you don't understand anything or if you're confused about something, go to the plugin page and leave me a comment. 

Same goes for if you have any suggestions or comments. 

Thanks!

- Jesse Lawson
- https://github.com/jesselawson
- jesselawson.org (my blog)

*/

// Enqueue scripts for back-end use
function q_enqueue_backend_scripts() {
	
	// Register our .js that triggers a custom media upload box to show up when we are uploading our author profile image
	wp_register_script( 'easy-author-image-uploader', plugins_url( 'js/easy-author-image-uploader.js', __FILE__ ), array( 'jquery', 'media-upload', 'thickbox' ) );

	// If we are currently viewing the profile field, enqueue our custom js file
	if ( 'profile' == get_current_screen() -> id || 'user-edit' == get_current_screen() ->id ) {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_media();
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_style( 'thickbox' );

		wp_enqueue_script( 'easy-author-image-uploader' );

	}

}
add_action( 'admin_enqueue_scripts', 'q_enqueue_backend_scripts');

function easy_author_image_init() {
	global $pagenow;
	if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) {
		add_filter( 'gettext', 'q_replace_thickbox_button_text', 1, 3 ); // here we call our function to replace the button text for the avatar uploader
	}
}	

// Initialize the options
add_action( 'admin_init', 'easy_author_image_init' ); 

// Here we grab the css for the elements in our admin page
function q_plugin_styles() {
	wp_register_style( 'easy_author_image', plugins_url( 'css/easy-author-image.css', __FILE__ ) );
	wp_enqueue_style( 'easy_author_image' );
}

add_action( 'wp_enqueue_scripts', 'q_plugin_styles');

// First, we'll add a special filter to change the text of the image uploader text when we're uploading an avatar	
function q_replace_thickbox_button_text($translated_text, $text, $domain) {
		if ( 'Insert into Post' == $text) {
			$referer = strpos( wp_get_referer(), 'profile' );
			if ( $referer != '' ) {
				return __( 'Make this my author profile picture!', 'easy-author-image' );
			}
		}
		return $translated_text;
	}
	
// Second, we'll have to manually push the new new profile field onto the profile page (as of 16-June-2013, user-edit.php (Core WP page) manually places the profile fields, and doesn't use do_settings_section(
function q_add_custom_profile_fields( $user ) {
	
	// Display image uploader button
	$avatar = get_user_meta( $user->ID, 'author_profile_picture', true );
	?>
		<h3><?php _e( 'Profile Picture', 'easy-author-image' ); ?></h3>
		
		<input type="hidden" id="author_profile_picture_url" name="author_profile_picture_url" value="<?php echo esc_url( $avatar ); ?>" />

		<table class="form-table">
			<tr>
				<th><label for="author_profile_picture_button"><span class="description"><?php _e( 'Upload a picture to use as the profile image.', 'easy-author-image' ); ?></span></label></th>
				<?php
					$buttontext = "";
					if( '' != $avatar ) {
						$buttontext = __( 'Change profile picture', 'easy-author-image' );
					} else {
						$buttontext = __( 'Upload new profile picture', 'easy-author-image' );
					}
				?>
				<td>
					<input id="author_profile_picture_button" type="button" class="button" value="<?php echo $buttontext; ?>" data-popup-title="<?php _e( 'Profile Image', 'easy-author-image' ); ?>" data-popup-button-title="<?php _e( 'Set Profile Image', 'easy-author-image' ); ?>" />
					<input id="author_profile_picture_remove" type="button" class="button" value="<?php _e( 'Delete profile picture', 'easy-author-image' ); ?>" />
				</td>
			</tr>
			
			<tr>
				<th><label for="author_profile_picture_preview"><span class="description"><?php _e( 'Preview:', 'easy-author-image' ); ?></span></label></th>
				<td>
					<?php if ( '' != $avatar ){ ?>
						<img alt="" src="<?php echo esc_url( $avatar ); ?>" class="author_profile_picture_preview" height="96" width="96" />
						<p class="description"><?php _e( 'You can update the picture from above.', 'easy-author-image' ); ?></p>
					<?php } else { ?>
						<img alt="" src="<?php echo esc_url( plugins_url( 'assets/profile.png', __FILE__ ) ); ?>" data-preview="<?php echo esc_url( plugins_url( 'assets/profile.png', __FILE__ ) ); ?>" class="author_profile_picture_preview" height="96" width="96" />
						<p class="description"><?php _e( 'This profile does not yet have an image. Click the button above to upload one (or select one from your media gallery).', 'easy-author-image' ); ?></p>
					<?php } ?>
					<div id="upload_success"></div>
				</td>
			</tr>
		</table>
		<style type="text/css">
			#author_profile_picture_remove{ display: none; }
		</style>
	<?php
	
}

// Third, we'll create this callback function to be called when the profile field is saved. 
function q_save_custom_profile_fields( $user_id ) {
    
    if ( !current_user_can( 'edit_user', $user_id ) )
        return FALSE;
            
    update_user_meta( $user_id, 'author_profile_picture', $_POST['author_profile_picture_url'] );
}

// Add our functions to profile display and update hooks
add_action( 'show_user_profile', 'q_add_custom_profile_fields' );
add_action( 'edit_user_profile', 'q_add_custom_profile_fields' );
add_action( 'personal_options_update', 'q_save_custom_profile_fields' );
add_action( 'edit_user_profile_update', 'q_save_custom_profile_fields' );

// Now, let's create an easy function to grab our author image

function author_image_circle( $user_id=999999, $_size="small" ) {
	
	if( $user_id==999999 ) {
		$avatar = get_user_meta(get_the_ID(), 'author_profile_picture', true);
	} else {
		$avatar = get_user_meta($user_id, 'author_profile_picture');
	}
	$size = ( ($_size == "small" || $_size == "medium" || $_size == "large") ? $_size : "medium");
	
	$output = '<div class="circular-'.$size.'" style="background: url( '.$avatar.');"></div>';
	
	echo $output;
}

function get_author_image_url( $user_id = 999999 ) {
	if( $user_id == 999999 ) {
		$avatar = get_user_meta( get_the_ID(), 'author_profile_picture', true );
	} else {
		$avatar = get_user_meta( $user_id, 'author_profile_picture', true );
	}
	
	return $avatar;
}

// The meat and potatoes
function get_easy_author_image( $avatar, $id_or_email, $size, $default, $alt ) {
    
	$url = get_user_meta( $id_or_email, 'author_profile_picture', true);
	if( esc_url( $url ) ) {
		$avatar = "<img alt='{$alt}' src='{$url}' class='avatar avatar-{$size} photo avatar-default' height='{$size}' width='{$size}' />";
		return $avatar;
	} else {
    	return $avatar; 
    }
}

add_filter( 'get_avatar', 'get_easy_author_image', 10, 5);

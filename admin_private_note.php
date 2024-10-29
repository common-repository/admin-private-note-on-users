<?php
/*
Plugin Name: Admin Private Note on users
Plugin URI: http://wplizer.com
Description: Easily add hidden and private notes for your users and they will never see your notes about them!
Author: wplizer
Author URI: wplizer.com
Text Domain: admin-private-note
Domain Path: /languages/
Version: 1.1
*/


add_action('init', 'rsf_init');
function rsf_init()
{
	 if ( current_user_can( 'manage_options' ) && current_user_can( 'edit_users' ))
	 {
		add_action( 'show_user_profile', 'rsf_show_apn',99999 );
		add_action( 'edit_user_profile', 'rsf_show_apn',99999 );
		//add_action( 'personal_options_update', 'rsf_save_apn' );
		add_action( 'edit_user_profile_update', 'rsf_save_apn',99999 );
	 } 
}


function rsf_show_apn( $user ) { ?>

	<h3>Admin Private Notes</h3>
	<table class="form-table">
		<tr>
			<th><label for="rsf_admin_note">your private notes about this user:</label></th>
			<td>
			<?php wp_editor( 
			( get_the_author_meta( 'rsf_admin_note', $user->ID ) ),
			'rsf_admin_note' ,
			array('teeny'=>true,'media_buttons'=>false,'textarea_rows'=>5));
			?>
				<span class="description">Users will never see your notes about them...</span>
			</td>
		</tr>
	</table>
<?php }

function rsf_save_apn( $user_id ) {
	
	if ( !current_user_can( 'edit_user', $user_id ) )
	{
		return false;
	}
	else
	{
		update_usermeta( $user_id, 'rsf_admin_note', $_POST['rsf_admin_note'] );
	}
}

add_filter('manage_users_columns', 'rsf_admin_note_column');
function rsf_admin_note_column($columns) {
    $columns['rsf_admin_note'] = 'Admin Note';
    return $columns;
}
 
add_action('manage_users_custom_column',  'rsf_admin_note_column_content', 10, 3);
function rsf_admin_note_column_content($value, $column_name, $user_id) {
    $user = get_userdata( $user_id );
	if ( 'rsf_admin_note' == $column_name )
	{
		$note=( get_the_author_meta( 'rsf_admin_note', $user_id ) );
		if($note)
		{
			return "<div style='max-height:60px;overflow:hidden;' class='admin_note_more' title=''>".$note.'</div>';
		}
		else
		{
			return '';
		}
		
	}
	else{
		 return $value;
	}
		
   
}



function pw_load_scripts($hook) {
	wp_enqueue_script( 'rsf_readmore',  plugin_dir_url( __FILE__ ). 'assets/readmore.min.js', array('jquery') );
	wp_enqueue_script( 'rsf_admin_note_js',  plugin_dir_url( __FILE__ ). 'assets/base.js',array('rsf_readmore')  );
	
}
add_action('admin_enqueue_scripts', 'pw_load_scripts');
?>
<?php
require_once('./bb-load.php');

if ( isset($_GET['username']) ) :
	$user = bb_get_user_by_name( $_GET['username'] );
	if ( !$user )
		bb_die(__('Username not found.'));
	wp_redirect( get_user_profile_link( $user->ID ) );
	exit;
endif;

bb_repermalink(); // The magic happens here.
$user = bb_get_user( $user_id );

if ( !$user )
	bb_die(__('User not found.'));

if ( $self ) {
	if ( strpos($self, '.php') !== false ) {
		require($self);
	} else {
		require( BBPATH . 'profile-base.php' );
	}
	return;
}

$reg_time = strtotime( $user->user_registered );
$profile_info_keys = get_profile_info_keys();

if ( !isset( $_GET['updated'] ) )
	$updated = false;
else
	$updated = true;

do_action( 'bb_profile.php_pre_db', $user_id );

if ( $user->is_bozo && $user->ID != $bb_current_user->ID && !bb_current_user_can('moderate') )
	$profile_info_keys = array();

$posts = get_recent_user_replies( $user_id );
$threads = get_recent_user_threads( $user_id );

do_action( 'bb_profile.php', $user_id );

bb_load_template( 'profile.php', array('reg_time', 'profile_info_keys', 'updated', 'threads') );
?>

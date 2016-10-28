<?php 
/*
 * Configuration file
 *
 * @author			Han Lin Yap (aka Codler)
 * @website			http://www.zencodez.net
 * @last-modified	24 mars 2010
 * @version			1.1
 * ----------------------------------------
*/
if (basename($_SERVER['PHP_SELF']) != 'index.php') die();

// Forum root url
define('ROOT', '/forum/');

define('FANCY_URL', true);

// Database information
$db_con = array(
	'host'		=> 'localhost',
	'user'		=> '',
	'password'	=> '',
	'database'	=> ''
);

// Site theme
$theme = "default";

// Site password
$site_pw = "";
$admin_pw = "";

// table prefix for forum
$forum_prefix = '';

// Debug mode
$debug = false;

// require login
$require_login = false;

$thread_structure = array(
	0 => array( 'key' 	=> 'thread_views', 
				'value' => 'Läst',
				'width' => '50px'),
	2 => array( 'key' 	=> 'thread_name', 
				'value' => 'Ämne'),
	3 => array( 'key' 	=> 'user_alias', 
				'value' => 'Skapad av',
				'width' => '130px'),
	4 => array( 'key' 	=> 'date', 
				'value' => 'Senaste inlägg',
				'width' => '150px')
);

$url_route = array(
	'thread-edit' 	=> array(	'fancy'		=> 'thread-edit/', 
								'normal'	=> '?s=thread_edit&id='),
	'thread' 		=> array(	'fancy'		=> 'thread/', 
								'normal'	=> '?s=thread&id='),
	'pagination'	=> array(	'fancy'		=> '', 
								'normal'	=> '?p='),
	'search'		=> array(	'fancy'		=> 'search/', 
								'normal'	=> '?s=search&w='),
	'PAGE'	 		=> array(	'fancy'		=> '', 
								'normal'	=> '?s=')
);

// -- Stop edit below -----------------------------
$db = new database($db_con);
$db->debug = $debug;
if ($db->debug) {
	echo $db->error;
}

if (!$require_login) {
	$_SESSION['logged_in'] = true;
}

$forum = new forum($forum_prefix, $db, ROOT);
if (isset($_SESSION['user_level'])) {
	$forum->permission($_SESSION['user_level']);
}
?>
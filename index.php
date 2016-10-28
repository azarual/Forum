<?php

/*
 * Index file
 *
 * @author			Han Lin Yap (aka Codler)
 * @website			http://www.zencodez.net
 * @last-modified	20 mars 2010
 * @version			1.0
 * ----------------------------------------
*/

function __autoload($class_name) {
    require_once dirname(__FILE__) . "/modules/" . $class_name . '.php';
}

if (!isset($_SESSION)) session_start();

require_once(dirname(__FILE__) . "/db.php");
require_once(dirname(__FILE__) . "/core_forum.php");
require_once(dirname(__FILE__) . "/config.php");
require_once(dirname(__FILE__) . "/yap_goodies.php");

$page = str_replace('..','',get_page());
if (file_exists($page)) {
	// check login
	if (!$_SESSION['logged_in'] && 
		$page != "themes/" . $theme . "/" . 'login.php' &&
		$page != 'submit.php') { 
		redirect(ROOT . get_route("page") . 'login'); 
	}

	if ($page != 'submit.php' && $page != 'fetch.php' && $page != 'rest.php') {
		require_once(dirname(__FILE__) . "/themes/" . $theme . "/header.php");
		
		// Forum undo function
		if (isset($_SESSION['prev_thread_delete_undo'])&&is_numeric($_SESSION['prev_thread_delete_undo'])) {
			$forum->remove_thread($_SESSION['prev_thread_delete_undo']);
			$_SESSION['prev_thread_delete_undo'] = false;
		}
		if (isset($_SESSION['prev_post_delete_undo'])&&is_numeric($_SESSION['prev_post_delete_undo'])) {
			$forum->remove_post($_SESSION['prev_post_delete_undo']);
			$_SESSION['prev_post_delete_undo'] = false;
		}
	}
	require_once($page);

	if ($page != 'submit.php' && $page != 'fetch.php' && $page != 'rest.php') {
		require_once(dirname(__FILE__) . "/themes/" . $theme . "/footer.php");
		
		// Forum undo function
		if (isset($_SESSION['thread_delete_undo'])&&is_numeric($_SESSION['thread_delete_undo'])) {
			$_SESSION['prev_thread_delete_undo'] = $_SESSION['thread_delete_undo'];
			$_SESSION['thread_delete_undo'] = false;
		}
		if (isset($_SESSION['post_delete_undo'])&&is_numeric($_SESSION['post_delete_undo'])) {
			$_SESSION['prev_post_delete_undo'] = $_SESSION['post_delete_undo'];
			$_SESSION['post_delete_undo'] = false;	
		}
	}
	
} else {
	header("HTTP/1.1 404 Not Found");
	die();
}
?>


<?php
/*
 * Rest - Querystring - handler
 *
 * @author			Han Lin Yap (aka Codler)
 * @website			http://www.zencodez.net
 * @last-modified	20 mars 2010
 * @version			1.0
 * ----------------------------------------
*/
if (basename($_SERVER['PHP_SELF']) != 'index.php') die();

$type = $_GET['type'];

// admin
// check login
if (!$_SESSION['logged_in'] || $_SESSION['user_level'] != 10) { 
	redirect(ROOT . get_route("page") . 'login'); 
}

if ($type == 'thread_sticky') {
	$thread_id = $_GET['id'];
	$status = $_GET['status'];
	if (!is_numeric($thread_id)) $thread_id = 0;
	if (!is_numeric($status)) $status = 0;	
	
	$forum->set_sticky($thread_id,$status);
	
	redirect(ROOT);
}

if ($type == 'thread_delete') {
	$thread_id = $_GET['id'];
	if (!is_numeric($thread_id)) 
		redirect(ROOT);
	
	if (isset($_SESSION['thread_delete_undo'])&&is_numeric($_SESSION['thread_delete_undo'])) {
		$_SESSION['thread_delete_undo'] = $thread_id;
	} else {
		$_SESSION['thread_delete_undo'] = $thread_id;
	}
	
	redirect(ROOT);
}

if ($type == 'thread_delete_undo') {
	$_SESSION['prev_thread_delete_undo'] = false;
	redirect(ROOT);
}

if ($type == 'post_delete') {
	$post_id = $_GET['id'];
	if (!is_numeric($post_id)) 
		redirect(ROOT);
	
	$post = $forum->get_post($post_id);
	$thread = $forum->get_thread($post['thread_id']);
	$fancy_url = new url($thread['thread_name']);
		
	if (isset($_SESSION['post_delete_undo'])&&is_numeric($_SESSION['post_delete_undo'])) {
		$_SESSION['post_delete_undo'] = $post_id;
	} else {
		$_SESSION['post_delete_undo'] = $post_id;
	}
	redirect(ROOT . get_route('thread') .$thread['thread_id']. "/" . $fancy_url->post());
}

if ($type == 'post_delete_undo') {	
	$post = $forum->get_post($_SESSION['prev_post_delete_undo']);
	$thread = $forum->get_thread($post['thread_id']);
	$fancy_url = new url($thread['thread_name']);
	
	$_SESSION['prev_post_delete_undo'] = false;
	
	redirect(ROOT . get_route('thread') .$thread['thread_id']. "/" . $fancy_url->post());
}

redirect(ROOT);
?>
<?php
/*
 * Form-post handler
 *
 * @author			Han Lin Yap (aka Codler)
 * @website			http://www.zencodez.net
 * @last-modified	20 mars 2010
 * @version			1.0
 * ----------------------------------------
*/
if (basename($_SERVER['PHP_SELF']) != 'index.php') die();

$type = form::get_submit_type();

if ($type == 'thread_add') {
	$fields = array('name','subject','message');
	if (!form::check_captcha()) {
		redirect(ROOT);
	}
}
if ($type == 'post_add') {
	$fields = array('id','name','message','reply_post');
	if (!form::check_captcha()) {
		redirect(ROOT);
	}
}
if ($type == 'login') {
	$fields = array('name','password');
}

if ($type == 'post_edit') {
	$fields = array('id', 'subject','message');
}

$post_data = form::get_posts($type, $fields);

// No valid data found
if (!$post_data)
	redirect(ROOT);
	
// Prevent Sql injection.
foreach ($post_data AS $k => $v) {
	$db->safe($v);
	$post_data[$k] = $v; 
}

if ($type == 'login') {
	if (strlen($post_data['name']) > 0 && strlen($post_data['password']) > 0) {
		if ($post_data['password'] == $site_pw || $post_data['password'] == $admin_pw) {
			$_SESSION['logged_in'] = true;
			if ($post_data['password'] == $admin_pw) {
				$_SESSION['user_level'] = 10;
			}
			redirect(ROOT);
		} 
	}
	redirect(ROOT . "?s=login&failed");
}

// check login
if (!$_SESSION['logged_in']) { 
	redirect(ROOT . get_route("page") . 'login'); 
}

if ($type == 'thread_add') {
	if (strlen($post_data['name']) > 0 && strlen($post_data['subject']) > 0 && strlen($post_data['message']) > 0) {
		$thread_id = $forum->add_thread($post_data);
		$fancy_url = new url($post_data['subject']);
		redirect(ROOT . get_route('thread') .$thread_id . "/" . $fancy_url->post());
	}
}

if ($type == 'post_add') {
	if (strlen($post_data['name']) > 0 && 
		strlen($post_data['id']) > 0 && 
		strlen($post_data['message']) > 0 &&
		strlen($post_data['reply_post']) > 0) {

		$post_id = $forum->add_post($post_data);
		$thread = $forum->get_thread($post_data['id']);
		$fancy_url = new url($thread['thread_name']);
		redirect(ROOT . get_route('thread') .$post_data['id']. "/" . $fancy_url->post() . "#p".$post_id);
	}
}

// admin
// check login
if (!$_SESSION['logged_in'] || $_SESSION['user_level'] != 10) { 
	redirect(ROOT . get_route("page") . 'login'); 
}

if ($type == 'post_edit') {
	if (strlen($post_data['id']) > 0 && 
		strlen($post_data['subject']) > 0 && 
		strlen($post_data['message']) > 0) {

		$forum->change_post($post_data['id'], $post_data['message']);
		$post = $forum->get_post($post_data['id']);
		$thread = $forum->get_thread($post['thread_id']);
		$forum->change_thread($thread['thread_id'], $post_data['subject']);
		$fancy_url = new url($post_data['subject']);
		redirect(ROOT . get_route('thread') .$thread['thread_id']. "/" . $fancy_url->post() . "#p".$post_data['id']);
	}
}

redirect(ROOT);
?>
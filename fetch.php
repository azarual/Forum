<?php
/*
 * Ajax handler
 *
 * @author			Han Lin Yap (aka Codler)
 * @website			http://www.zencodez.net
 * @last-modified	20 mars 2010
 * @version			1.0
 * ----------------------------------------
*/
if (basename($_SERVER['PHP_SELF']) != 'index.php') die();

$type = $_POST['type'];
if ($type == 'pagination_thread') {
	$pagination = $_POST['p'];
	if (!is_numeric($pagination)) $pagination = 0;
	if ($pagination < 0) die("Pagination out of range");
	
	$data = $forum->get_threads(10,$pagination*10);
	echo table($data,$thread_structure,array('id'=>'table_thread')); 
	
}

if ($type == 'pagination_thread_bar') {
	$pagination = $_POST['p'];
	if (!is_numeric($pagination)) $pagination = 0;
	if ($pagination < 0) die("Pagination out of range");
	
	echo $forum->get_pagination($pagination);
}

if ($type == 'search') {
	$search = $_POST['s'];
	if (strlen($search) == 0)  die();
	$db->safe($search);
	
	$structure = array(
		0 => array( 'key' 	=> 'result', 
					'value' => 'Sökord: <a href="' . ROOT . get_route('search') . $search . '">' . $search . '</a>',
					'width' => '200px')
	);
		
	$data = $forum->get_search($search);
	echo table($data,$structure,array('id'=>'search_result')); 
}

if ($type == 'autocomplete') {
	$word = $_POST['w'];
	$word = preg_replace("/[^a-z]/",'',strtolower($word));
	if (strlen($word)<3) die();
	
	// Get one word from threads
	$r = $db->select("SELECT thread_name AS sentence FROM `yap_threads` WHERE thread_name REGEXP '(^|[[:space:]]+)".$word."[[:alpha:]]+([[:space:]]+|$)' LIMIT 1");
	if (!$r) die();
	// Get from posts if not found in threads
	if ($r['rows']==0)
		$r = $db->select("SELECT post_message AS sentence FROM `yap_posts` WHERE post_message REGEXP '(^|[[:space:]]+)".$word."[[:alpha:]]+([[:space:]]+|$)' LIMIT 1");
	if (!$r || $r['rows'] == 0) die();
	preg_match("/(^|\s+)(".$word."\w+)(\s+|$)/",$r['data'][0]['sentence'],$word_complete);
	echo $word_complete[2];
	
}
?>
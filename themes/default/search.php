<?php 
/*
 * thread-page
 *
 * @author			Han Lin Yap (aka Codler)
 * @website			http://www.zencodez.net
 * @last-modified	20 mars 2010
 * @version			1.0
 * ----------------------------------------
*/
if (basename($_SERVER['PHP_SELF']) != 'index.php') die(); ?>
<a href="<?php echo ROOT; ?>">Tillbaka</a>
<br /><br />
<div id="fast_search">
<?php 
form::open('search',ROOT . get_route('PAGE') . 'submit');
form::text(false,'search', 'Sök', 'fast_search_input', false, true);
form::close();
?>
</div>
<?php
$search = $_GET['w'];
if (strlen($search) > 0) {
	$db->safe($search);
	
	$structure = array(
		0 => array( 'key' 	=> 'result', 
					'value' => 'Sökord: ' . $search),
		1 => array( 'key' 	=> 'user_alias', 
					'value' => '',
					'width' => '130px'),
		2 => array( 'key' 	=> 'date', 
					'value' => '',
					'width' => '150px')
	);
		
	$data = $forum->get_search($search);
	echo table($data,$structure,array('id'=>'search_result')); 
}
?>

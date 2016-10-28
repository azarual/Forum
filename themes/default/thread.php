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
if (basename($_SERVER['PHP_SELF']) != 'index.php') die();

$thread_id = $_GET['id'];
if (!is_numeric($thread_id)) $thread_id = 0;

$thread_data = $forum->get_thread($thread_id);
if ($thread_data) {
	$forum->register_view($thread_id);
}
$structure = array(
	0 => array( 'key' 	=> 'post_info', 
				'value' => '',
				'width' => '200px'),
	1 => array( 'key' 	=> 'post_message', 
				'value' => $thread_data['thread_name'])
);

$data = $forum->get_posts($thread_id);
?>
<a href="<?php echo ROOT; ?>">Tillbaka</a>
<?php if (isset($_SESSION['post_delete_undo'])&&is_numeric($_SESSION['post_delete_undo'])) : ?>
	<a class="undo" href="<?php echo ROOT; ?>?s=rest&type=post_delete_undo">Ångra borttagna post</a>
<?php endif; ?>
<?php echo table($data,$structure,array('id'=>'table_post', 'anchor'=>'post_id')); ; ?>
<br /><br />
<div id="form_send">
<?php 
form::open('post_add',ROOT . get_route('PAGE') . 'submit');
form::hidden('id',$thread_id);
form::hidden('reply_post',0);
form::text('Namn','name', false, false, 'name');
form::message('Meddelande','message');
form::captcha("Vad blir ","? (för validering)");
form::submit('Posta svar');
form::close();
?>
</div>
<div id="statistic">
<span class="box" style="height: auto; width: auto;">
<div id="smilies">
</div>
</span>
</div>
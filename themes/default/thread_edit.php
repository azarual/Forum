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

$post_id = $_GET['id'];
if (!is_numeric($post_id)) $post_id = 0;

$data = $forum->get_post($post_id);

$structure = array(
	0 => array( 'key' 	=> 'post_info', 
				'value' => '',
				'width' => '200px'),
	1 => array( 'key' 	=> 'post_message', 
				'value' => $thread_data['thread_name'])
);

$thread_data = $forum->get_thread($data['thread_id']);
?>
<a href="<?php echo ROOT; ?>">Tillbaka</a>
<table>
	<thead>
		<tr>
			<th></th>
			<th><?php echo $thread_data['thread_name']; ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php echo $forum->get_user_alias($data['user_id']); ?></td>
			<td>
				<div id="form_send">
				<h1>Ändra inlägg</h1>
				<?php 
				form::open('post_edit',ROOT . get_route('PAGE') . 'submit');
				form::hidden('id',$post_id);
				form::text('Ämne','subject', $thread_data['thread_name']);
				form::message('Meddelande','message', $data['post_message']);
				form::submit('Ändra post');
				form::close();
				?>
				</div>
				<span class="box" style="height: auto; width: auto;">
					<div id="smilies">
					</div>
				</span>
			</td>
		</tr>
	</tbody>
</table>
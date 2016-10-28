<?php 
/*
 * main-page
 *
 * @author			Han Lin Yap (aka Codler)
 * @website			http://www.zencodez.net
 * @last-modified	20 mars 2010
 * @version			1.0
 * ----------------------------------------
*/
if (basename($_SERVER['PHP_SELF']) != 'index.php') die();

$pagination = $_GET['p'];
if (!is_numeric($pagination)) $pagination = 0;
if ($pagination < 0) redirect("?");
?>
<?php if (isset($_SESSION['thread_delete_undo'])&&is_numeric($_SESSION['thread_delete_undo'])) : ?>
	<a class="undo" href="<?php echo ROOT; ?>?s=rest&type=thread_delete_undo">Ångra borttagna tråd</a>
<?php endif; ?>
<div id="thread">
<?php
$data = $forum->get_threads(10,$pagination*10);
echo table($data,$thread_structure,array('id'=>'table_thread')); ?>
</div>
<div id="pagination">
<?php echo $forum->get_pagination($pagination); ?>
</div>
<div id="fast_search">
<?php 
form::open('search',ROOT . get_route('PAGE') . 'submit');
form::text(false,'search', 'Snabb sök', 'fast_search_input', false, true);
form::close();
?>
</div>
<br /><br />
<div id="form_send" class="box">
<?php 
form::open('thread_add',ROOT . get_route('PAGE') . 'submit');
form::text('Namn','name', false, false, 'name');
form::text('Ämne','subject', false, false, 'subject', true);
form::message('Meddelande','message');
form::captcha("Vad blir ","? (för validering)");
form::submit('Posta tråden');
form::close();
?>
</div>
<div id="statistic">
<span class="box">
Antal trådar <b>:</b> <?php echo $forum->count_thread(); ?><br />
Antal inlägg <b>:</b> <?php echo $forum->count_post(); ?><br />
Antal användare <b>:</b> <?php echo $forum->count_user(); ?><br />
Antal online <b>:</b> <span class="metroroll online"></span><br />
<script>
$.getJSON("http://metroroll.zencodez.net/ref/online2.php?oi=1324&oc=2b57a33356&type=json&jsoncallback=?", function(data) {
	$(".metroroll.online").text(data.online);
});
</script>
</span>
</div>
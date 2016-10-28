<?php 
/*
 * login-page
 *
 * @author			Han Lin Yap (aka Codler)
 * @website			http://www.zencodez.net
 * @last-modified	21 mars 2010
 * @version			1.0
 * ----------------------------------------
*/
if (basename($_SERVER['PHP_SELF']) != 'index.php') die(); ?>

<div id="form_login">
<?php if (isset($_GET['failed'])) : ?>
	<span class="box">Felaktig Lösenord</span>
<?php endif; ?>
<?php
form::open('login',ROOT . get_route('PAGE') . 'submit');
form::text('Smeknamn','name', false, false, 'name');
form::password('Lösenord','password');
form::submit('Logga in');
form::close();
?>
<div style="display:none" id="browser_msie">
<p>Du använder Internet Explorer<br />
Rekommenderar att du använder någon av följande<br />
<a href="http://www.mozilla.com?from=sfx&amp;uid=188254&amp;t=561"><img border="0" width="128" height="128" src="<?php echo ROOT; ?>images/Firefox.png" title="Firefox" /></a>
<a href="http://www.google.com/chrome"><img border="0" width="128" height="128" src="<?php echo ROOT; ?>images/Chrome.png" title="Chrome" /></a>
<a href="http://www.opera.com/"><img border="0" width="128" height="128" src="<?php echo ROOT; ?>images/Opera.png" title="Opera" /></a>
<a href="http://www.apple.com/safari/"><img border="0" width="128" height="128" src="<?php echo ROOT; ?>images/Safari.png" title="Safari" /></a>
</p>
</div>
<script>
if ($.browser.msie) {
	$("#browser_msie").show();
}
</script>
</div>

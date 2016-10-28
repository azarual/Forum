<?php
/*
 * template: header
 *
 * @author			Han Lin Yap (aka Codler)
 * @website			http://www.zencodez.net
 * @last-modified	20 mars 2010
 * @version			1.0
 * ----------------------------------------
*/
if (basename($_SERVER['PHP_SELF']) != 'index.php') die();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sv" lang="sv">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<style type="text/css">
<!--
/* --- reset --- */
span {
	margin: 0;
	padding: 0;
}

/* --- html5 --- */
header, footer {
	clear: both;
	display: block;
}

/* --- tags --- */
body {
	color: #555;
	font-size: 14px;
	font-family: sans-serif;
}

a {
	color: #555;
	text-decoration: none;
}
a:hover {
	color: #000;
	text-decoration: underline;
}

b {
	color: #1B8AD1;
}
i {
	color: red;
}
i b, b i {
	color: purple;
}

label {
	display:block;
	margin: 6px 0 4px 0;
}

input {
	display: block;
	font-size: 14px;
	height: 25px;
	width: 200px;
}

textarea {
	display: block;
	font-size: 14px;
	height: 200px;
	width: 98%;
}

footer {
	border-top: 1px solid #EEE;
	margin: 20px 0px;
	padding: 10px;
	text-align: center;
}

/* --- general --- */
#container {
	margin: 0 auto;
	width: 80%;
}

/* --- class --- */
.box {
	 border: 1px solid #DDD; 
	display: inline-block;
	padding: 10px;
	
	/* CSS3 */
	background-image: -webkit-gradient(
		linear,
		left bottom,
		left top,
		color-stop(0.08, rgb(237,237,237)),
		color-stop(0.25, rgb(245,245,245)),
		color-stop(0.46, rgb(255,255,255))
	);
	background-image: -moz-linear-gradient(
		center bottom,
		rgb(237,237,237) 8%,
		rgb(245,245,245) 25%,
		rgb(255,255,255) 46%
	);
	
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
}

.undo {
	background-color: #FFFF33;
	
}

span.metroroll.online {
	display: inline !important;
}

/* --- Pagination ---*/
#pagination {
	text-align: center;
}

#pagination .active {
	text-decoration: underline;
}

#pagination span {
	display: inline-block;
	height: 20px;
	width: 25px;
}

#pagination a {
	display: inline-block;
	padding-top: 3px; 
	height: 20px;
	width: 25px;
}
#pagination a:hover {
	border: 1px solid #1B8AD1;
	padding-top: 2px;
	height: 18px;
	width: 23px;	
}

#pagination .jump {
	display: inline;
	font-size: 12px;
	text-align: right;
	height: 12px;
	width: 20px;
}

/* --- fast search ---*/
#fast_search input {
	font-size: 12px;
	height: 18px;
	width: 100px;
}

.highlight td {
	background-color: #feffef;
	border-left: 2px solid #ddd;
}

.thread_posts {
	color: #1B8AD1;
	font-weight: bold;
}
.thread_notice {
	font-size: 12px;
	font-style:italic; 
}

.thread_notice a:visited {
	display: none;
}

#table_thread tbody td img {
	border: none;
}

#table_post tr td:first-child {
	font-weight: bold;
}

#table_post tr td:first-child a{
	font-weight: normal;
	font-style: italic;
}

#table_post tr td:last-child {
    font-size: 12px;
}

#table_post tr:target td {
    background-color: #ffdab9;
}

#table_post tbody td span {
	float:right;
}
#table_post tbody td span a {
	color: #1B8AD1;
}

#table_post tbody td img {
	border: 1px solid #CCC;
	max-width: 800px;
}

table {
	width: 100%;
	text-align: left;
}
thead {
	font-size: 20px;
	font-weight: bold;
}

th {
	border-bottom: 3px solid #DDD;
}

tbody tr:hover td:first-child {
	border-right: 2px solid #1B8AD1;
}

tbody td {
	border-bottom: 1px solid #EEE;
	padding: 5px 2px;
}

tbody td a:hover img {
	border: 2px solid #CCC;
}

#form_login {
	text-align: center;
}

#form_login input {
	margin: 0 auto;
}

#form_send, #statistic {
	display: inline-block;
	width: 48%;
}
#form_send {
	float: left;
	padding: 10px;
}

#form_send input {
	min-width: 200px;
	width: 48%;
}

#form_send input[name='subject'] {
	width: 98%;
}

#statistic {
	float: right;
}

#statistic td {
	border: 0;
	padding: 0;
	margin: 0;
	vertical-align: top;
}

#statistic span {
	display: block;
	height: 150px;
	width: 200px;
}

#statistic a, #statistic img {
	border: 0;
}

/* - paypal - */
#statistic input {
	height: auto;
	width: auto;
}

-->
</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript">
<!--

/* Made by Han Lin Yap
 * 2010-03-20
 */
 RegExp.escape = function(str)
{
  var specials = new RegExp("[.*+?|()\\[\\]{}\\\\]", "g"); // .*+?|()[]{}\
  return str.replace(specials, "\\$&");
}
 
 function createSelection(field, start, end){
		if( field.createTextRange ){
			var selRange = field.createTextRange();
			selRange.collapse(true);
			selRange.moveStart("character", start);
			selRange.moveEnd("character", end);
			selRange.select();
		} else if( field.setSelectionRange ){
			field.setSelectionRange(start, end);
		} else {
			if( field.selectionStart ){
				field.selectionStart = start;
				field.selectionEnd = end;
			}
		}
		field.focus();
	};

 
function extract_querystring(url) {
	var raw_queries = url.split("?");
	if (raw_queries.length < 2) return false;
	raw_queries = raw_queries[1].split("&");
	var queries = new Array();
	for (i in raw_queries) {
		var t = raw_queries[i].split("=");
		queries[t[0]] = t[1];
	}
	return queries;
}

$(document).ready(function () {
/*
	smiley_dir = "<?php echo ROOT; ?>images/emoticon/";
	config_smilies = {
		'-.-' 	: '-.-.png',
		//'^^'	: '^^3.png',
		'o.O'	: '0.0.png',
		'(a)'	: 'angel.png',
		':D'	: 'biggrin.png',
		'(6)'	: 'devil.png',
		'xD'	: 'XD.png',
		'haha'	: 'hahaaa.png'
		
	};
*/
	smiley_dir = "<?php echo ROOT; ?>images/emoticon/smile/";
	config_smilies = {
		'angel'		: 'th_091_-1.gif',
		'awesome'	: 'oh17.gif',
		'x3'		: '120.gif',
		'happy bday': 'th_091_-2.gif',
		"i'm dead"	: 'oh79.gif',
		'T_T'		: 'oh41.gif',
		'spazzing'	: '070.gif',
		'rofl'		: 'oh42.gif',
		'ehhh'		: 'oh31.gif',
		'aaaah'		: 'th_107_.gif',
		'gaaah'		: 'oh23.gif',
		'sick'		: 'th_040_.gif',
		'victory'	: '121.gif',
		'@_@'		: 'oh01.gif',
		'cold'		: 'oh07.gif',
		'whatever'	: 'oh09.gif',
		'puppyeyes'	: 'oh10.gif',
		'mihihi'	: 'oh12.gif',
		'xD'		: 'oh15.gif',
		'ghost'		: 'oh18.gif',
		'rabu'		: 'oh19.gif',
		'=_='		: 'oh21.gif',
		'redcard'	: 'oh28.gif',
		'shy'		: 'oh32.gif',
		'sweat'		: 'oh39.gif',
		'snigger'	: 'oh40.gif',
		'nowall'	: 'oh43.gif',
		'doh'		: 'oh44.gif',
		'idea'		: 'oh52.gif',
		'darkemo'	: 'oh53.gif',
		'bye'		: 'oh56.gif',
		'cry'		: 'oh57.gif',
		'goodjob'	: 'oh73.gif',
		'sigh'		: 'oh74.gif',
		'idontwanttohear': 'th_081_.gif',
		'scared'	: 'th_110_.gif'
	};
	
	for (var smile in config_smilies) {
		$("<img src=\"" + smiley_dir + config_smilies[smile] + "\" title=\"" + smile + "\" />").appendTo("#statistic #smilies");
	}
	$("#statistic #smilies img").live('click',function () {
		$("#form_send textarea").val($("#form_send textarea").val() + $(this).attr('title'));
	});
	
	//init
	if ($("#table_thread").length > 0 && window.location.hash.length > 0) {
		update_pagination();
	}
	var hash = location.hash;

	setInterval(function()
	{
		if (location.hash != hash)
		{
			if ($("#table_thread").length > 0 && window.location.hash.length > 0) {
				update_pagination();
			}
			hash = location.hash;
		}
	}, 100);


	$("#table_post tbody tr").each(function () {
	
		var post_message = $(this).find("td:last").html();
		
		// smilies - posts
		var raw_text = post_message.split(/(<a.+?>|<\/a>)/gi);
		for (i = 0; i < Math.floor(raw_text.length / 2); i++) {
			if ((i*2) % 3 != 2) {
				for (var smile in config_smilies) {
					raw_text[i*2] = raw_text[i*2].replace(new RegExp(RegExp.escape( smile ), "gi" ), "<img src=\"" + smiley_dir + config_smilies[smile] + "\" title=\"" + smile + "\" />");
				}
			}
		}
		var new_text = "";
		for (i = 0; i < raw_text.length; i++) {
				new_text += raw_text[i];
		}
		$(this).find("td:last").html(new_text);
		
		// URL to image
		$("a", $(this).find("td:last")).each(function () {
			var url = $(this).attr("href");
			var url_lower = url.toLowerCase();
			if (url_lower.lastIndexOf('.png')==Math.max(4,url_lower.length-4) || 
				url_lower.lastIndexOf('.jpg')==Math.max(4,url_lower.length-4) || 
				url_lower.lastIndexOf('.gif')==Math.max(4,url_lower.length-4)) {
				$(this).replaceWith($("<img src=\"" + url + "\" />"));
			}
			if (url_lower.indexOf('http://www.youtube.com')==0) {
				var q = extract_querystring(url);
				var yt = '<object style="height: 385px; width: 640px">';
				yt += '<param name="movie" value="http://www.youtube.com/v/' + q['v']+ '">';
				yt += '<param name="allowFullScreen" value="true">';
				yt += '<param name="allowScriptAccess" value="always">';
				yt += '<embed src="http://www.youtube.com/v/' + q['v']+ '" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" width="740" height="385"></object>';
				$(this).replaceWith($(yt));
			}
		});
	});
	
	// Ajax Pagination
	function update_pagination() {
		var query = extract_querystring(window.location.hash);
		var page = query['p'];
		
		$("#table_thread").parent().load("<?php echo ROOT . get_route('PAGE'); ?>fetch",{'type':'pagination_thread','p':page}, function(response, status, xhr) {
			if (response.indexOf('id="table_thread"') == -1) {
				status = "error";
			}
			if (status == "error") {
				window.location.href = '<?php echo ROOT . get_route('pagination'); ?>' + page;
			}
		});
		$("#pagination").load("<?php echo ROOT . get_route('PAGE'); ?>fetch",{'type':'pagination_thread_bar','p':page});
	}
	
	$("#pagination a").live('click', function () {
		var url = $(this).attr("href");
		
		<?php if (defined('FANCY_URL') && FANCY_URL) : ?>
		var new_page = url.split("/").pop();
		<?php else : ?>
		var query = extract_querystring(url);
		var new_page = query['p'];
		<?php endif; ?>
		
		if (window.location.hash.length > 0) {
			var query = extract_querystring(window.location.hash);
			var current_page = query['p'];
		}
		
		if (!isNaN(new_page) && new_page != current_page) {
			window.location.hash = '?p=' + new_page;
			//update_pagination();
		}
		return false;
	});
	$("#pagination .jump").live('keydown', input_int);
	$("#pagination .jump").live('focusout', function () {
		var value = $(this).val();
		if (!isNaN(value)) {
			window.location.hash = '?p=' + (parseInt(value)-1);
		}
	});
	
	// fast search
	$("#fast_search form").submit(function () { $("#fast_search_input").trigger("blur"); return false; });
	$("#fast_search #fast_search_input").change(function () {
		if ($("#search_result").length == 0) {
			//var pos = $(this).offset()
			//pos.top += $(this).height();
			
			$("<span><table id=\"search_result\"><tr><td></td></tr></table></span>").insertAfter($(this));
		}
		
		$("#search_result").load("<?php echo ROOT . get_route('PAGE'); ?>fetch",{'type':'search','s':$(this).val()});
		
		return false;
	});
	// autocomplete fast search 
	$("#fast_search #fast_search_input").keyup(autocomplete);
	// and subject-field
	$("#form_send input[name='subject']").keyup(autocomplete);
	
	var autocomplete_cache = {};
	function autocomplete(event) {
		var k = event.which;
		var input = $(this);
		if (input.val().length < 3) return false;
		var word = input.val().split(/\s+/).pop();
		if (word.length < 3) return false;
		// key 0-9
		if ((k >= 48 && k <= 57) || (k >= 65 && k <= 90) || k == 127) {
			// load cache
			if (word in autocomplete_cache) {
				autocomplete_update(autocomplete_cache[word]);
			
			// Ajax
			} else {
				$.post("<?php echo ROOT . get_route('PAGE'); ?>fetch", {'type':'autocomplete','w':word}, autocomplete_update);
			}
		}
		function autocomplete_update(data) {
			// trim
			data = data.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
			//var input = $("#fast_search #fast_search_input");
			var word = input.val().split(/\s+/).pop();
			old_input_value = input.val().length;
			if (data.length > word.length) {
				if (data.indexOf(word) != -1) {
					autocomplete_cache[word] = data;
					input.val(input.val() + data.substr(word.length));
					createSelection(input.get(0), old_input_value, input.val().length);
				}
			}
		}
	}
	

});

function input_int(event) {
	var k = event.which;
	var value = $(this).val();
	
	// Key arrow up
	if (event.keyCode == 38) {
		value++;
		$(this).val(value);
		return false;
	// Key arrow down
	} else if (event.keyCode == 40) {
		value--;
		$(this).val(value);
		return false;
	}
	
	// Key enter
	if (k == 13) {
		$(this).trigger('blur');
	}
	
	// key 0-9
	if ((k >= 32 && k <= 47) || (k >= 58 && k <= 126) || k > 127) {
		// Key arrow left and right
		if (event.keyCode != 37 && event.keyCode != 39) {
			event.preventDefault();
		}
	}
}

-->
</script>
</head>
<body>
<div id="container">
<header></header>
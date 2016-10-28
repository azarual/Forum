<?php 
/*
 * Useful functions - Yap goodies
 *
 * @author			Han Lin Yap (aka Codler)
 * @website			http://www.zencodez.net
 * @last-modified	7 april 2010
 * @version			1.1
 * ----------------------------------------
 * New in 1.1 (7 april 2010)
 * Added captcha
 *
 * 1.0 (20 mars 2010)
*/

/*
 * --- Index ---
 * Form (class)
 *		captcha
 *		open
 *		text
 *		submit
 *		input
 *		message
 *		close
 *
 * happy_date
 * table
 * redirect
 */


class form {
	public static $link_name = 'yap_goodies';
	
	function __construct($type, $location='', $method='post') {
		echo "<form action=\"".$location."\" method=\"".$method."\">\r\n";
		$this->hidden(self::$link_name . '_submit_type',$type);
	}
	
	public function captcha($label_before=false, $label_after=false) {
		$first = rand(0,9);
		$second = rand(0,9);
		self::hidden(self::$link_name . '_captcha', md5($first+$second));
		self::text($label_before . $first . "+" . $second . $label_after, self::$link_name . '_captcha_check');
	}
	
	public function check_captcha() {
		if (isset($_POST[self::$link_name . '_captcha'])&&isset($_POST[self::$link_name . '_captcha_check'])) {
			if ($_POST[self::$link_name . '_captcha'] == md5($_POST[self::$link_name . '_captcha_check'])) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function open($type, $location='', $method='post') {
		echo "<form action=\"".$location."\" method=\"".$method."\">\r\n";
		self::hidden(self::$link_name . '_submit_type',$type);
	}
	
	public function get_submit_type() {
		if (isset($_POST[self::$link_name . '_submit_type'])) {
			return $_POST[self::$link_name . '_submit_type'];
		} else {
			return false;
		}
	}
	
	public function get_posts($type, $fields) {
		if (!is_array($fields)) return false;
		if ($_POST[self::$link_name . '_submit_type'] != $type) return false;
	
		$data = array();
		foreach ($fields AS $v) {
			$data[$v] = trim($_POST[$v]);
			
			// Cookie
			if (isset($_POST[self::$link_name . '_remember_' . $v])) {
				// Max length
				if (strlen(trim($_POST[$v])) < 500) {
					setcookie(self::$link_name . "_remember_field[" . $v . "]", trim($_POST[$v]), time()+8*24*60*60);
				}
			}
		}
		return $data;
	}
	
	public function hidden($field, $value, $id=false) {
		self::input('hidden', false, $field, $value, $id);
	}
	
	public function text($label, $field, $value=false, $id=false, $remember=false, $auto_empty=false) {
		self::input('text', $label, $field, $value, $id, $remember, $auto_empty);
	}
	
	public function password($label, $field, $id=false) {
		self::input('password', $label, $field, $id);
	}
	
	public function submit($label, $field='') {
		self::input('submit', false, $field, $label);
	}
	
	public function input($type, $label, $field='', $value=false, $id=false, $remember=false, $auto_empty=false) {
		if (!$id) $id = "l".rand();
		if ($label) echo "<label for=\"".$id."\">".$label."</label>\r\n";
		
		// remember / cookie
		if ($remember) {
			if (isset($_COOKIE[self::$link_name . "_remember_field"])) {
				$cookie = $_COOKIE[self::$link_name . "_remember_field"][$remember];
				if (isset($cookie)) {
					$value = htmlspecialchars(stripslashes(stripslashes($cookie)));
				}
			}
			self::hidden(self::$link_name . '_remember_' . $field,'true');
		}	
		
		if ($value||$value===0) {
			if ($auto_empty) {
				echo "<input type=\"".$type."\" id=\"".$id."\" name=\"".$field."\" value=\"".$value."\" onfocus=\"if(this.value=='".addslashes($value)."')this.value=''\" onblur=\"if(this.value=='')this.value='".addslashes($value)."'\"/>\r\n";
			} else {
				echo "<input type=\"".$type."\" id=\"".$id."\" name=\"".$field."\" value=\"".$value."\"/>\r\n";
			}
		} else {
			echo "<input type=\"".$type."\" id=\"".$id."\" name=\"".$field."\" />\r\n";
		}
	}
	
	public function message($label, $field, $value=false, $id=false) {
		if (!$id) $id = "l".rand();
		echo "<label for=\"".$id."\">".$label."</label>\r\n";
		if ($value) {
			echo "<textarea id=\"".$id."\" name=\"".$field."\">".$value."</textarea>\r\n";
		} else {
			echo "<textarea id=\"".$id."\" name=\"".$field."\"></textarea>\r\n";
		}
	}
	
	public function close() {
		echo "</form>\r\n";
	}
}

function happy_date($date) {
	$time = strtotime($date);
	$ago = time() - $time;
	
	// less then 1 hours
	if ($ago < 60*60) {
		return date("i:s",$ago);
	}
	
	/*
	// less then 1 minute
	if ($ago < 60) {
		return $ago . "s";
	}
	// less then 1 hour
	if ($ago < 60*60) {
		return ceil($ago / 60) . "m";
	}
	// less then 1 day
	if ($ago < 24*60*60) {
		return ceil($ago / 60 / 60) . "h";
	}
	// less then 1 week
	if ($ago < 7*24*60*60) {
		return ceil($ago / 24 / 60 / 60) . "d";
	}
	*/
	return date('Y-m-d',$time);
}

function table($data, $structure, $config=array()) {
	if (!is_array($data) || !is_array($structure)) return false;
	$r = (isset($config['id'])) ? "<table id=\"".$config['id']."\">" : "<table>\r\n";
	$r .= "<thead>\r\n";
	$r .= "<tr>\r\n";
	foreach ($structure AS $v) {		
		$r .= ($v['width']) ? "<th width=\"".$v['width']."\">" : "<th>";
		$r .= $v['value'];
		$r .= "</th>\r\n";
	}
	$r .= "</tr>\r\n";
	$r .= "</thead>\r\n";
	// ---
	$r .= "<tbody>\r\n";
 	foreach ($data AS $k) {	
		
		$r .= "<tr";
		if ($k['highlight']) $r .= " class=\"".$k['highlight']."\"";
		if (isset($config['anchor'])) $r .= " id=\"p".$k[$config['anchor']]."\"";
		$r .= ">\r\n";
		
		foreach ($structure AS $v) {
			$r .= "<td>";
			if (array_key_exists($v['key'],$k)) {
				$r .= $k[$v['key']];
			}
			$r .= "</td>\r\n";
		}
		$r .= "</tr>\r\n";
	} 
	$r .= "</tbody>\r\n";
	$r .= "</table>\r\n";
	
	return $r;
} 

// redirect
function redirect($u) {
	if (!headers_sent()) {
		header("Location: ".$u);
		die();
	}
	echo '<meta http-equiv="Refresh" content="0; url='.$u.'" />';
	echo '<a href="'.$u.'">Click here if the page don\'t redirect</a>';
	die();
}
?>
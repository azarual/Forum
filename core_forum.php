<?php
/*
 * Forum class and url-mapping
 *
 * @author			Han Lin Yap (aka Codler)
 * @website			http://www.zencodez.net
 * @last-modified	20 mars 2010
 * @version			1.0
 * ----------------------------------------
*/

class forum {
	private $prefix;
	private $db;
	private $root;
	private $permission_level;
	
	/* Params:
	 * 	prefix	- database tablename prefix
	 *	db 		- db class
	 *	root	- forum url
	 */
	function __construct($prefix, $db, $root = '') {
		$this->prefix = $prefix;
		$this->db = $db;
		$this->root = $root;
		$this->permission_level = 0;
		
		@header(base64_decode('WC1Qb3dlcmVkLUJ5OiB6ZW5jb2Rlei5uZXQ='));
	}
	
	function permission($level) {
		$this->permission_level = $level;
	}
	
	public function get_or_add_user($alias) {
		$user_id = $this->get_user_id($alias);
		// User does not exist
		if (!$user_id) {
			return $this->db->insert("INSERT INTO ".$this->prefix."_simple_users (user_alias) VALUES ('".$alias."');");
		} else {
			return $user_id;
		}
	}
	
	public function get_user_id($alias) {
		$r = $this->db->select("SELECT user_id FROM ".$this->prefix."_simple_users WHERE user_alias = '".$alias."';");
		if (!$r) return false;
		if (isset($r['data'][0]['user_id'])) {
			return $r['data'][0]['user_id'];
		} else {
			return false;
		}
	}
	
	public function get_user_alias($user_id) {
		$r = $this->db->select("SELECT user_alias FROM ".$this->prefix."_simple_users WHERE user_id = ".$user_id.";");
		if (!$r) return false;
		if (isset($r['data'][0]['user_alias'])) {
			return $r['data'][0]['user_alias'];
		} else {
			return false;
		}
	}
	
	public function add_thread($data) {
		$data['subject'] = substr($data['subject'], 0, 200);
		$user_id = $this->get_or_add_user($data['name']);
		$thread_id = $this->db->insert(" INSERT INTO ".$this->prefix."_threads (thread_name,thread_views,date,user_id) 
							VALUES ('".$data['subject']."', 0, NOW( ), ".$user_id.")");
		// Create data for post
		$new_data = array();
		$new_data['id'] = $thread_id;
		$new_data['message'] = $data['message'];
		$new_data['user_id'] = $user_id;
		$new_data['reply_post'] = 0;
		
		if (!$this->add_post($new_data)) return false;
		
		return $thread_id;
	}
	
	public function add_post($data) {
		// check if thread exist
		$thread_data = $this->get_thread($data['id']);
		if (!isset($thread_data)) return false;
		
		// Check if already got user_id
		if (isset($data['user_id'])) {
			$user_id = $data['user_id'];
		} else {
			$user_id = $this->get_or_add_user($data['name']);
		}
		if (!is_numeric($data['id']) || !is_numeric($data['reply_post'])) return false;

		$post_id = $this->db->insert("INSERT INTO ".$this->prefix."_posts (thread_id,post_message,date,user_id,post_reply,post_ip) 
									  VALUES (".$data['id'].", '".$data['message']."', NOW( ), ".$user_id.", ".$data['reply_post'].",'".$_SERVER['REMOTE_ADDR']."')");
							
		return $post_id;
	}
	
	public function change_thread($thread_id, $data) {
		$this->db->update("UPDATE ".$this->prefix."_threads SET thread_name = '".$data."' WHERE thread_id = ".$thread_id);
	}
	
	public function change_post($post_id, $data) {
		$this->db->update("UPDATE ".$this->prefix."_posts SET post_message = '".$data."' WHERE  post_id = ".$post_id);
	}
	
	public function remove_thread($thread_id) {
		$this->db->delete("DELETE FROM ".$this->prefix."_posts WHERE thread_id = ".$thread_id);
		$this->db->delete("DELETE FROM ".$this->prefix."_threads WHERE thread_id = ".$thread_id);
	}
	
	public function remove_post($post_id) {
		$post = $this->get_post($post_id);
		$count_posts = $this->count_post_in_thread($post['thread_id']);
		if ($count_posts<=1) {
			$this->remove_thread($post['thread_id']);
		} else {
			$this->db->delete("DELETE FROM ".$this->prefix."_posts WHERE post_id = ".$post_id);
		}
	}
	
	public function get_threads($limit = 10, $offset = 0) {
		$threads = $this->count_thread();
		if ($threads < $offset) {
			$offset = floor($threads / $limit) * $limit;
		}
	
		$data = array();
		$r = $this->db->select(
			"SELECT ".$this->prefix."_threads.thread_id, 
				".$this->prefix."_threads.thread_views, 
				".$this->prefix."_threads.thread_name, 
				".$this->prefix."_posts.date, 
				".$this->prefix."_threads.date AS thread_date, 
				thread_posts,
				thread_status,
				user_alias
			FROM ".$this->prefix."_threads 
			
			JOIN (SELECT thread_id, MAX(date) AS date, COUNT(thread_id) AS thread_posts, user_alias
				FROM ".$this->prefix."_posts 
				
				JOIN ".$this->prefix."_simple_users
				ON ".$this->prefix."_simple_users.user_id = ".$this->prefix."_posts.user_id 
				
				GROUP BY thread_id) AS ".$this->prefix."_posts
			ON ".$this->prefix."_posts.thread_id = ".$this->prefix."_threads.thread_id
			
			ORDER BY thread_status DESC, ".$this->prefix."_posts.date DESC 
			LIMIT ".$offset.", ".$limit.";");
		if (!$r) return false;
		// Prevent XSS
		$this->db->xss_safe($r['data'],array('thread_name','user_alias'));
		
		foreach ($r['data'] AS $k => $v) {
			// Modify date
			$r['data'][$k]['date'] = happy_date($v['date']);
			
			$fancy_url = new url($v['thread_name']);
			
			// Add link
			$r['data'][$k]['thread_name'] = "<a href=\"".$this->root. get_route('thread') .$v['thread_id']."/".$fancy_url->post()."\">".$v['thread_name']."</a> 
			<span class=\"thread_posts\">[".$v['thread_posts']."]</span> ";
			
			// Sticky
			if ($v['thread_status']==1) {
				$r['data'][$k]['thread_name'] .= "<span class=\"thread_notice\"> (sticky!) </span>";
			}
			
			// New thread - 2 days
			if (time() - strtotime($v['thread_date']) < 2*24*60*60) {
				$r['data'][$k]['thread_name'] .= "<span class=\"thread_notice\"> <a href=\"".$this->root. get_route('thread') .$v['thread_id']."/".$fancy_url->post()."\"><img src=\"".$this->root."images/new.png\"></a> </span>";
			}
			
			if ($this->permission_level >= 10) {
				// Sticky Admin
				$r['data'][$k]['thread_name'] .= " <a href=\"".$this->root."?s=rest&type=thread_sticky&id=".$v['thread_id']."&status=".abs($v['thread_status']-1)."\">&lt;!&gt;</a>";
				// Remove Admin
				$r['data'][$k]['thread_name'] .= " <a href=\"".$this->root."?s=rest&type=thread_delete&id=".$v['thread_id']."\" onclick=\"return confirm('Är du säker på att ta bort denna tråd?');\">Ta bort</a>";
			}
		}
		
		return $r['data'];
	}
	
	public function get_thread($thread_id) {
		$r = $this->db->select("SELECT * FROM ".$this->prefix."_threads WHERE thread_id = ".$thread_id." LIMIT 1;");
		if (!$r) return false;
		// Prevent XSS
		$this->db->xss_safe($r['data'],array('thread_name'));
		return $r['data'][0];
	}
	
	public function get_posts($thread_id, $limit = 1000, $offset = 0) {
		$r = $this->db->select("SELECT post_id, date, user_alias, post_message 
								FROM ".$this->prefix."_posts 
								
								JOIN ".$this->prefix."_simple_users
								ON ".$this->prefix."_simple_users.user_id = ".$this->prefix."_posts.user_id 
								
								WHERE thread_id = ".$thread_id." 
								ORDER BY date ASC 
								LIMIT ".$offset.", ".$limit.";");
		if (!$r) return false;
		// Prevent XSS
		$this->db->xss_safe($r['data'],array('user_alias', 'post_message'));
		
		$user_creater = $r['data'][0]['user_alias'];
		$i = 0;
		foreach ($r['data'] AS $k => $v) {
			// Modify date
			$date = happy_date($v['date']);
			$r['data'][$k]['date'] = $date;
			
			// Post info
			$r['data'][$k]['post_info'] = $v['user_alias'] . "<br />\r\n";
			if ($this->permission_level >= 10) {
				// Remove Admin
				$r['data'][$k]['post_info'] .= " <a href=\"".$this->root."?s=rest&type=post_delete&id=".$v['post_id']."\" onclick=\"return confirm('Är du säker på att ta bort detta inlägg?');\">Ta bort</a>";
				
				// Edit Admin
				$r['data'][$k]['post_info'] .= " <a href=\"".$this->root. get_route('thread-edit') .$v['post_id']."/\">Ändra</a>";
			}
			$message = $v['post_message'];
			// Fix newline
			$message = str_replace(array("\r\n","\n","\r"), ' <br />', $message);
			$message = wordwrap($message, 120, ' <br />');
			// auto add link
			$message = preg_replace('/http[s]?:\/\/[^\s]*/','<a href="\0">\0</a>',$message);
			// bold text
			$message = preg_replace('/\[b\](.*?)\[\/b\]/','<b>${1}</b>',$message);
			// cursive text
			$message = preg_replace('/\[i\](.*?)\[\/i\]/','<i>${1}</i>',$message);
			
			$r['data'][$k]['post_message'] = $message;
			
			
			$r['data'][$k]['post_message'] .= "<br /><span><i>" . $date . "</i> <a href=\"#p".$v['post_id']."\">#".($i+1)."</a></span>";
			
			
			// Highlight thread starter
			if ($user_creater == $v['user_alias']) {
				$r['data'][$k]['highlight'] = "highlight";
			}
			
			$i++;
		}
		
		return $r['data'];
	}

	public function get_post($post_id) {
		$r = $this->db->select("SELECT * FROM ".$this->prefix."_posts WHERE post_id = ".$post_id." LIMIT 1;");
		if (!$r) return false;
		// Prevent XSS
		$this->db->xss_safe($r['data'],array('user_alias', 'post_message'));
		return $r['data'][0];
	}
	
	public function get_pagination($on_page=0, $range=2, $limit=10) {
		if ($limit<=0) return false;
		if ($on_page < 0) 
			$on_page = 0;
		
		$threads = $this->count_thread();
		$last_page = ceil($threads/$limit)-1;
		if ($on_page > $last_page) {
			$on_page = $last_page;
		}
		
		if ($on_page!=0) {
			$r = "<span><a href=\"".$this->root . get_route('pagination') .($on_page-1)."\">&lt;</a></span>\r\n";
		} else {
			$r = "<span>&lt;</span>\r\n";
		}
		
		for ($i = 0; $i <= $last_page; $i++) {
			if ($i == 0 || $i == $last_page || ($on_page-$range < $i && $on_page+$range > $i)) {
				if ($on_page==$i) {
					$r .= "<a href=\"".$this->root . get_route('pagination') .$i."\" class=\"active\">".($i+1)."</a>\r\n";
				} else {
					$r .= "<a href=\"".$this->root . get_route('pagination') .$i."\">".($i+1)."</a>\r\n";
				}
			} elseif ($on_page-$range == $i || $on_page+$range == $i) {
				$r .= "<a>...</a>";
			}
		}
		
		if ($on_page != $last_page) {
			$r .= "<span><a href=\"".$this->root . get_route('pagination') .($on_page+1)."\">&gt;</a></span>\r\n";
		} else {
			$r .= "<span>&gt;</span>\r\n";
		}
		
		// Go to page image
		$r .= "#<input class=\"jump\" value=\"".($on_page+1)."\" /> av ".($last_page+1)."\r\n";
		
		return $r;
	}
	
	public function get_search($s, $limit = 10, $offset = 0) {
		$r = $this->db->select("SELECT ".$this->prefix."_threads.thread_id, thread_name, post_id, post_message, user_alias, ".$this->prefix."_threads.date 
								FROM ".$this->prefix."_posts 
								
								JOIN ".$this->prefix."_threads
								ON ".$this->prefix."_threads.thread_id = ".$this->prefix."_posts.thread_id 
								
								JOIN ".$this->prefix."_simple_users
								ON ".$this->prefix."_simple_users.user_id = ".$this->prefix."_posts.user_id 
								
								WHERE thread_name LIKE '%".$s."%' OR post_message LIKE '%".$s."%' OR user_alias LIKE '%".$s."%'
								ORDER BY thread_views DESC 
								LIMIT ".$offset.", ".$limit.";");
		if (!$r) return false;
		// Prevent XSS
		$this->db->xss_safe($r['data'],array('thread_name', 'post_message', 'user_alias'));
		
		foreach ($r['data'] AS $k => $v) {
			// Modify date
			$date = happy_date($v['date']);
			$r['data'][$k]['date'] = $date;
		
			// Concat message
			$message = substr($v['post_message'], 0, (100 - min(100,strlen($v['thread_name']))));
			$r['data'][$k]['result'] = "<a href=\"".$this->root. get_route('thread') .$v['thread_id']."#p".$v['post_id']."\"><b>".$v['thread_name']."</b> - ".$message." </a>";
		}
		if ($r['rows'] == 0) {
			return $r['data'] = array(array("result" => 'Inga poster funna'));
		}
		return $r['data'];
	}
	
	private $count_thread = false;
	public function count_thread() {
		if ($this->count_thread) return $this->count_thread;
		
		$result = $this->db->select("SELECT COUNT(thread_id) AS count_thread FROM ".$this->prefix."_threads;");
		if (!result) return false;
		
		$this->count_thread = $result['data'][0]['count_thread'];
		return $this->count_thread;
	}
	
	private $count_post = false;
	public function count_post() {
		if ($this->count_post) return $this->count_post;
		
		$result = $this->db->select("SELECT COUNT(post_id) AS count_post FROM ".$this->prefix."_posts;");
		if (!result) return false;
		
		$this->count_post = $result['data'][0]['count_post'];
		return $this->count_post;
	}
	
	private $count_post_in_thread = false;
	public function count_post_in_thread($thread_id) {
		if ($this->count_post_in_thread) return $this->count_post_in_thread;
		
		$result = $this->db->select("SELECT COUNT(post_id) AS count_post_in_thread FROM ".$this->prefix."_posts WHERE thread_id = ".$thread_id);
		if (!result) return false;
		
		$this->count_post_in_thread = $result['data'][0]['count_post_in_thread'];
		return $this->count_post_in_thread;
	}
	
	private $count_user = false;
	public function count_user() {
		if ($this->count_user) return $this->count_user;
		
		$result = $this->db->select("SELECT COUNT(user_alias) AS count_user FROM ".$this->prefix."_simple_users;");
		if (!result) return false;
		
		$this->count_user = $result['data'][0]['count_user'];
		return $this->count_user;
	}
	
	public function register_view($thread_id) {
		$r = $this->db->update("UPDATE ".$this->prefix."_threads SET thread_views = thread_views + 1 WHERE thread_id = ".$thread_id." LIMIT 1;");
		if (!$r) return false;
		return $r;
	}
	
	public function set_sticky($thread_id,$status) {
		$r = $this->db->update("UPDATE ".$this->prefix."_threads SET thread_status = ".$status." WHERE thread_id = ".$thread_id." LIMIT 1;");
		if (!$r) return false;
		return $r;
	}
}

function get_page() {
	global $theme;
	$page = $_GET['s'];
	if (isset($page)) {
		if ($page == 'thread_edit') return "themes/" . $theme . "/" . 'thread_edit.php';
		if ($page == 'thread') return "themes/" . $theme . "/" . 'thread.php';
		if ($page == 'submit') return 'submit.php';
		if ($page == 'fetch') return 'fetch.php';
		if ($page == 'rest') return 'rest.php';
		if ($page == 'login') return "themes/" . $theme . "/" . 'login.php';
		if ($page == 'search') return "themes/" . $theme . "/" . 'search.php';
		
		header("HTTP/1.1 404 Not Found");
		die();
	}
	return "themes/" . $theme . "/" . 'main.php';
}

function get_route($page) {
	global $url_route;
	if (!is_array($url_route)) return '';
	
	if (defined('FANCY_URL') && FANCY_URL) {
		$route = 'fancy';
	} else {
		$route = 'normal';
	}
	if (array_key_exists($page, $url_route))	
		return $url_route[$page][$route];
	return '';
}
?>
<?php
/**
 * Expenia Ziyaretçi Defteri Scripti v2.0     
 *
 * Expenia Internet Solutions tarafından ücretsiz dağıtılmakta olan bir ziyaretçi defteri scriptidir.
 * GNU Public License ile korunmakta olup altta olan linki kaldırmak yasaktır.
 * 
 * Bu betik üzerinde değişiklik yaparak veya yapmayarak kullanabilirsiniz.
 * Hiçbir şekilde para ile satılamaz. Kodlarda ve sayfaların en altındaki telif yazıları silinemez,
 * değiştirilemez, veya bu telif ile çelişen başka bir telif eklenemez.
 *
 * Yukarıda belirtilen maddelerin değiştirilme hakkı saklıdır.
 * Emeğe saygı göstererek bu kurallara uyunuz.
 *
 * @author Hüseyin Koyun <zd@expenia.com>
 * @version 2.0 | 04.02.2013
 * @copyright Copyright (c) 2013, Expenia Internet Solutions
 * @link http://www.expenia.com
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */
 
include "expenia.class.php";

class expenia_admin extends expenia {
	
	public function __construct($dbhost, $dbuser, $dbpass, $dbname){
		parent::__construct($dbhost, $dbuser, $dbpass, $dbname);
	}
	
	public function login($username, $password){
		$username = parent::security($username);
		$password = parent::security($password);
		$password = md5($password);
		
		if(empty($username) || empty($password)){
			$errors[] = $this->lang['EMPTY_UNM_OR_PWD'];
		}
		
		if(count($errors) == 0){
			$query = $this->db->query("SELECT * FROM users WHERE username = '$username' AND password = '$password'");
			
			if($query->num_rows > 0){
				$result = $query->fetch_object();
				$_SESSION['id']	= $result->id;
			}else{
				$errors[] = $this->lang['WRONG'];
			}
			
			if(count($errors) > 0){
				return $errors;
			}else{
				return true;
			}
		}else{
			return $errors;
		}
	}
	
	public function getSettings(){
		$result = $this->db->query("SELECT * FROM settings WHERE id = '1'")->fetch_object();
		return $result;
	}
	
	public function getDirectoryList($directory){
		$results = array();
		$handler = opendir($directory);
		
		while ($file = readdir($handler)) {
			if ($file != "." && $file != "..") {
				$results[] = $file;
			}
		}

		closedir($handler);

		return $results;
	}

	public function updateSettings($title, $url, $approval, $lang, $theme){
		$title 		= parent::security($title);
		$url 		= parent::security($url);
		$approval 	= parent::security(intval($approval));
		$lang 		= parent::security($lang);
		$theme 		= parent::security($theme);
		
		if(empty($title)){
			$errors[] = $this->lang['EMPTY_TITLE'];
		}
		if(empty($url)){
			$errors[] = $this->lang['EMPTY_URL'];
		}
		
		if(count($errors) == 0){
			
			$update = $this->db->query("UPDATE settings SET title = '$title', url = '$url', approval = '$approval', lang = '$lang', theme = '$theme' WHERE id = '1'");
			
			if($update){
				return true;
			}else{
				return false;
			}
			
		}else{
			return $errors;
		}
	}
	
	public function get_admin_details(){
		if(isset($_SESSION['id'])){
			$this->admin_id = intval($_SESSION['id']);

			return $this->db->query("SELECT * FROM users WHERE id = '$this->admin_id'")->fetch_object();
		}
	}
	
	public function admin_update($username, $password, $email){
		
			if(empty($username) || empty($email)){
				$error = 'Kullanıcı adı ve e-mail boş olmamalıdır.';
			}else{
				if(!empty($password)){
					$password = md5($password);
					$sql = "UPDATE users SET username = '$username', password = '$password', email = '$email' WHERE id = '$this->admin_id'";
				}else{
					$sql = "UPDATE users SET username = '$username', email = '$email' WHERE id = '$this->admin_id'";
				}
			}
			
			if(!empty($error)){
				return $error;
			}else{
				if($this->db->query($sql)) return true;
			}
	}
}


?>
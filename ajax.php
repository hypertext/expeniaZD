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

include_once "libs/config.inc.php";

// eğer XHR isteği gönderilmişse
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
	// approve message
	if(isset($_GET['onayla'])){
		if(isset($_SESSION['id'])){
			$id = intval($_POST['msg_id']);
			
			if($zd->db->query("UPDATE messages SET approval = '1' WHERE id = '$id'")){
				echo 1;
			}else{
				header("location: http://www.google.com");
			}
		}
	}
	
	// delete message
	if(isset($_GET['sil'])){
		if(isset($_SESSION['id'])){
			$id = intval($_POST['msg_id']);
			
			if($zd->db->query("DELETE FROM messages WHERE id = '$id'")){
				echo 1;
				$zd->db->query("DELETE FROM messages WHERE parent_id = '$id'");
			}else{
				header("location: http://www.google.com");
			}
		}
	}
	
	// reply message
	if(isset($_GET['cevapla'])){
		if(isset($_SESSION['id'])){
			$id = intval($_POST['m_id']);
			$response = nl2br($zd::security($_POST['response']));
			if(!empty($response)){
				if($zd->db->query("INSERT INTO messages (message, parent_id) VALUES ('$response', '$id')")){
					echo 'OK';
				}
			}else{
				echo 'Can\'t be empty!';
			}
		}
	}
}
?>
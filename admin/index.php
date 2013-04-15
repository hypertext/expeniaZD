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
include "../libs/config.inc.php"; if(empty($_SESSION['id'])){ header('location: login.php'); } 
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Expenia</title>
  <meta name="description" content="Expenia">
  <meta name="author" content="Hüseyin Koyun">
  <link href='http://fonts.googleapis.com/css?family=Elsie' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="css/styles.css?v=1.0">
  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>
<body>
<div id="top">
	<div class="wrapper" style="padding-top: 15px;">
		<a href="index.php">Expenia Ziyaretçi Defteri Yönetim</a>
	</div>
</div>
<div class="wrapper main">
	<div class="left-menu">
		<ul>
			<li><a href="index.php">Anasayfa</a></li>
			<li><a href="?settings">Ayarlar</a></li>
			<li><a href="?admin">Yönetici Bilgileri</a></li>
			<li><a href="?about">Hakkında</a></li>
			<li><a href="login.php?logout">Çıkış yap</a></li>
		</ul>
	</div>
	<div class="content-right">
		<?php
		if(isset($_GET['settings'])){
		$settings = $zd->getSettings();

		$theme_s 	= isset($_POST['theme']) ? $_POST['theme'] : $settings->theme;
		$lang_s 	= isset($_POST['lang']) ? $_POST['lang'] : $settings->lang;
		$url 		= isset($_POST['url']) ? $_POST['url'] : $settings->url;
		$title 		= isset($_POST['title']) ? $_POST['title'] : $settings->title;
		$approval 	= isset($_POST['approval']) ? $_POST['approval'] : $settings->approval;
		
		if(isset($_POST['submit-settings'])){
			$zd->updateSettings($title, $url, $approval, $lang_s, $theme_s);
		}
		?>
			<h2>Ayarlar</h2>
			<form action="" style="margin-top: 10px;" method="POST">
				<div class="control-group">
					<label class="control-label" for="title">Sayfa başlığı:</label>
					<div class="controls">
						<input type="text" name="title" class="input" id="title" value="<?=$title?>">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="url">Sayfa adresi:</label>
					<div class="controls">
						<input type="text" name="url" class="input" id="url" value="<?=$url?>">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="approval">Onay durumu:</label>
					<div class="controls">
						<label class="rad">Onaylı <input type="radio" name="approval" class="input" value="1" id="approval"<?php if($approval == 1) echo ' checked'; ?>></label>
						<label class="rad">Onaysız <input type="radio" name="approval" class="input" value="0" id="approval"<?php if($approval == 0) echo ' checked'; ?>></label>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="lang">Sayfa dili:</label>
					<div class="controls">
						<select style="height: 28px" name="lang" id="lang">
						<?php
							$languages = $zd->getDirectoryList('../lang');
							foreach($languages as $langs){
								include '../lang/'.$langs;
								$langs = str_replace('.php', '', $langs);
								echo '<option value="' . $langs . '"';
								if($lang_s == $langs){
									echo ' selected';
								}
								echo '>' . $lang['language'] . '</option>';
							}
						?>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="theme">Tema:</label>
					<div class="controls">
						<select style="height: 28px" name="theme" id="theme">
						<?php
						$themes = $zd->getDirectoryList('../themes');
						foreach($themes as $theme){
							$findfile = glob("../themes/$theme/*.txt");
							$themename = explode('/', $findfile[0]);
							$count = count($themename);
							$themename = $themename[$count-1];
							$themename = str_replace('.txt', '', $themename);
							echo '<option value="' . $theme . '"';
							if($theme == $theme_s){
								echo ' selected';
							}
							echo '>' . $themename . '</option>';
						}
						?>
						</select>
					</div>
				</div>
				<div class="control-group">
					<input type="submit" name="submit-settings" class="btn" value="Güncelle">
				</div>
			</form>
		<?php
		}elseif(isset($_GET['admin'])){
		$admin = $zd->get_admin_details();
		
		$username 	= isset($_POST['username']) ? $_POST['username'] : $admin->username;
		$password 	= isset($_POST['password']) ? $_POST['password'] : '';
		$email 		= isset($_POST['email']) ? $_POST['email'] : $admin->email;
		
		?>
			<h2>Yönetici Bilgileri</h2>
			<form action="" style="margin-top: 10px;" method="POST">
			<?php
				if(isset($_POST['submit-admin'])){
					$update = $zd->admin_update($username, $password, $email);
					
					if($update != 1){
						echo '<ul id="errors">'.$update.'</ul>';
					}
				}
				
			?>
				<div class="control-group">
					<label class="control-label" for="username">Kullanıcı Adı:</label>
					<div class="controls">
						<input type="text" name="username" class="input" id="username" value="<?=$username?>">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="email">E-Mail:</label>
					<div class="controls">
						<input type="text" name="email" class="input" id="email" value="<?=$email?>">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="password">Şifre:</label>
					<div class="controls">
						<input type="password" name="password" class="input" id="password" value="<?=$password?>">
					</div>
				</div>
				<div class="control-group">
					<input type="submit" name="submit-admin" class="btn" value="Güncelle">
				</div>
			</form>
		<?php
		}elseif(isset($_GET['about'])){
			echo '<h2>Hakkında</h2>';

			$user_agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; tr; rv:1.9.0.6) Gecko/2009011913 Firefox/3.0.6';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://expenia.comze.com/zd.php?host='.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'&version='.$zd->version);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, $post ? true : false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post ? $post : false);
			curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
			$icerik = curl_exec($ch);
			curl_close($ch);
			$icerik = explode('<!-- Hosting24 Analytics Code -->', $icerik);
			echo $icerik[0];
			
		}elseif(isset($_GET['logout'])){
			echo 'logout';
		}else{
			?>
			<h2>Anasayfa</h2>
			
			<p class="welcome">
				<strong style="font-weight:bold">Expenia Ziyaretçi Defteri Yönetim Paneline Hoşgeldiniz!</strong><br /><br />
				
				Ziyaretçi Defterine yazılan mesajları okumak, onaylamak, cevaplamak ve silmek için yönetici girişi yaptıktan sonra <a href="<?=$zd->settings->url?>">anasayfa</a>'ya gidiniz. <br /><br />
				
				Yönetici panelinde ziyaretçi defterinin ayarlarını değiştirebilir, yönetici bilgilerini değiştirebilir ve ziyaretçi defteri hakkında bilgi alabilirsiniz.<br /><br /><br />
			</p>
			<?php
		}
		?>
	</div>
	<div style="clear:both"></div>
</div>
<div id="footer"><div class="wrapper">&copy; 2013 Expenia Internet Solutions <span style="float:right;"><a target="_blank" href="http://www.huseyin.at">Hüseyin Koyun</a></span></div></div>
</body>
</html>
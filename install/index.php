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
error_reporting(E_ALL^E_NOTICE); 
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Expenia Ziyaretçi Defteri | Kurulum</title>
	<style type="text/css">
		body { font-family: Arial, Helvetica, sans-serif; }
		.hatalar { padding-left: 20px; color: red; line-height: 20px; border: 1px solid red; width: 660px; margin: 0 auto; font-size: 12px; background-color: #FEDACE; }
		form { width: 700px; margin: 0 auto; font-size: 12px; }
		form fieldset { border: 1px solid #eee; margin-top: 10px; border-radius: 3px; }
		form fieldset legend { font-weight: bold; background-color: #eee; padding: 5px; border-radius: 2px; border: 1px solid #ccc; color: #333; }
		form fieldset p {}
		form fieldset p label { width: 200px; display: block; float: left; height: 18px; padding: 4px; }
		form fieldset p input[type="submit"] {
			-moz-box-shadow:inset 0px 1px 0px 0px #ffffff;
			-webkit-box-shadow:inset 0px 1px 0px 0px #ffffff;
			box-shadow:inset 0px 1px 0px 0px #ffffff;
			background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #ededed), color-stop(1, #dfdfdf) );
			background:-moz-linear-gradient( center top, #ededed 5%, #dfdfdf 100% );
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ededed', endColorstr='#dfdfdf');
			background-color:#ededed;
			-moz-border-radius:3px;
			-webkit-border-radius:3px;
			border-radius:3px;
			border:1px solid #dcdcdc;
			display:inline-block;
			color:#333;
			font-size:12px;
			font-weight:bold;
			padding:4px 7px;
			text-decoration:none;
			text-shadow:1px 1px 0px #ffffff;
		}
		form fieldset p input[type="submit"]:hover {
			background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #dfdfdf), color-stop(1, #ededed) );
			background:-moz-linear-gradient( center top, #dfdfdf 5%, #ededed 100% );
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#dfdfdf', endColorstr='#ededed');
			background-color:#dfdfdf;
		}
		form fieldset p input[type="submit"]:active {
			position:relative;
			top:1px;
		}
		small { padding: 4px; }
		input[type=text], input[type=password] { width: 200px; border: 1px solid #999; padding: 4px; height: 18px; color: #333; border-radius: 3px; -moz-border-radius: 3px; -webkit-border-radius: 3px; border-top-color: #ccc; border-left-color: #ccc}
		.line { line-height: 24px; }
		.left { float:left; width: 200px; border: }
		.right {  }
	</style>
</head>

<body>
	<?php
		if(isset($_POST['install'])){
			extract($_POST);
			
			$errors = '';
			
			if(empty($dbhost) || empty($dbname) || empty($dbuser) || empty($dbpass)){
				$errors[] = 'Veritabanı ayarlarını girmelisiniz.';
			}
			if(!filter_var($url, FILTER_VALIDATE_URL)){
				$errors[]= 'Defteri adresi geçersiz.';
			}
			if(empty($username) ||  empty($email) || empty($password)){
				$errors[] = 'Kullanıcı bilgilerini girmelisiniz.';
			}
			
			if(is_array($errors)){
				echo '<div class="hatalar">';
				foreach($errors as $error){
					echo $error.'<br />';
				}
				echo '</div>';
			}else{
				
				// Veritabani olusturuluyor
				$password = md5($password);
				
				$sql = "
					CREATE TABLE IF NOT EXISTS `messages` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `parent_id` int(11) NOT NULL,
					  `name` varchar(100) NOT NULL,
					  `email` varchar(100) NOT NULL,
					  `message` text NOT NULL,
					  `approval` enum('0','1') NOT NULL,
					  `timestamp` int(11) NOT NULL,
					  `ip` varchar(30) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

					CREATE TABLE IF NOT EXISTS `settings` (
					  `id` int(1) NOT NULL AUTO_INCREMENT,
					  `theme` varchar(100) NOT NULL,
					  `lang` varchar(20) NOT NULL,
					  `url` varchar(100) NOT NULL,
					  `title` varchar(100) NOT NULL,
					  `approval` enum('0','1') NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

					INSERT INTO `settings` (`id`, `theme`, `lang`, `url`, `title`, `approval`) VALUES
					(1, 'default', 'tr_TR', '$url', '$title', '0');

					CREATE TABLE IF NOT EXISTS `users` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `username` varchar(50) NOT NULL,
					  `password` varchar(50) NOT NULL,
					  `email` varchar(50) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

					INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES
					(1, '$username', '$password', '$email');
					
					INSERT INTO `messages` (`id`, `name`, `email`, `message`, `approval`, `timestamp`, `ip`) VALUES
					(1, 'Hüseyin Koyun', 'mail@huseyin.at', '<strong>Expenia Ziyaretçi Defterine Hoş Geldin!</strong><br />\r\n<br />\r\nZiyaretçi defterini güle güle kullanman dileğiyle.<br />\r\n<br />\r\nSaygılarımla<br />\r\nHüseyin Koyun<br /><br />\r\n<a href=\"http://www.huseyin.at\" target=\"_blank\">www.huseyin.at</a>', '1', 1359973814, '127.0.0.1');
					";
					
				$db = new mysqli($dbhost, $dbuser, $dbpass, $dbname) 
				or die ('Could not connect to the database server' . mysqli_connect_error());
				$db->query("SET NAMES 'utf8'");
				
				$explode = explode(";", $sql);
				
				$count = count($explode);

				for($line = 0; $line < $count; $line++){
					if(strlen($explode[$line]) > 9){
						$db->query($explode[$line]) or die(mysqli_connect_error());
					}
				}
				
				
				// config dosyasi olusturuluyor
				
$config = '<?php
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
 
error_reporting(E_ALL^E_NOTICE);

// Veritabanı ayarları
$dbhost = "'.$dbhost.'";
$dbuser = "'.$dbuser.'";
$dbpass = "'.$dbpass.'";
$dbname = "'.$dbname.'";

include_once "admin.class.php";
$zd = new expenia_admin($dbhost, $dbuser, $dbpass, $dbname);
?>';
				
				$config_filename = '../libs/config.inc.php';
				
				if(touch($config_filename)){
					if(is_writeable($config_filename)){
						$config_file = fopen($config_filename, 'w');
						flock($config_file, 2);
						fwrite($config_file, $config);
						flock($config_file, 3);
						fclose($config_file);
					}
				}
				echo '<div style="color:green; width: 700px; margin: 0 auto;">Kurulum başarıyla tamamlandı! "install" klasörünü silip ziyaretçi defterinizi kullanmaya başlayabilirsiniz.</div>';
			}
		}
		

	?>
	<form method="post" action="">
		<fieldset>
			<legend>Sistem Gereksinimleri</legend>
			<div class="line">
				<div class="left">PHP versiyonu</div>
				<div class="right"><? if(phpversion() < '5.0'){ echo '<span style="color:red">Lütfen Güncelleyiniz</span>'; }else{ echo '<span style="color:green">Güncel</span>'; }?></div>
			</div>
			<div class="line">
				<div class="left">MySQLi Eklentisi</div>
				<div class="right"><? if (function_exists('mysqli_connect')) { echo '<span style="color:green">MySQLi Kurulu</span>'; }?></div>
			</div>
			<div class="line">
				<div class="left">libs/</div>
				<div class="right"><? if(is_writeable('../libs')){ echo '<span style="color:green">Yazılabilir</span>'; }else{ echo '<span style="color:red">Lütfen CHMOD ayarlarını 777 yapınız</span>'; } ?></div>
			</div>
			<div class="line">
				<div class="left">config.inc.php</div>
				<div class="right"><? if(is_writeable('../libs/config.inc.php')){ echo '<span style="color:green">Yazılabilir</span>'; }else{ echo '<span style="color:red">Lütfen CHMOD ayarlarını 777 yapınız</span>'; } ?></div>
			</div>
		</fieldset>
		<fieldset>
			<legend>Veritabanı Ayarları</legend>
			<p>
				<label for="dbhost">Veritabanı Sunucu Adresi:</label>
				<input type="text" name="dbhost" value="localhost" id="dbhost">		</p>
			<p>
				<label for="dbname">Veritabanı Adı:</label>
				<input type="text" name="dbname" value="<?=$dbname?>" id="dbname">		</p>
			<p>
				<label for="dbuser">Veritabanı Kullanıcı Adı:</label>
				<input type="text" name="dbuser" value="<?=$dbuser?>" id="dbuser">		</p>
			<p>
				<label for="dbpass">Veritabanı Şifresi:</label>
				<input type="text" name="dbpass" value="<?=$dbpass?>" id="dbpass">		</p>
		</fieldset>
		<fieldset>
			<legend>Sistem Bilgileri</legend>
			<p>
				<label for="url">Defter Adresi:</label>
				<input type="text" name="url" value="http://<?=$_SERVER['HTTP_HOST'].dirname(dirname($_SERVER['PHP_SELF']))?>" id="url"><small>Adresin sonunda "/" olmamalı</small>
			</p>
			<p>
				<label for="title">Sayfa başlığı:</label>
				<input type="text" name="title" value="<?=$title?>" id="title">
			</p>
		</fieldset>
		<fieldset>
			<legend>Yönetici Bilgileri</legend>
			<p>
				<label for="username">Kullanıcı Adı:</label>
				<input type="text" name="username" value="<?=$username?>" id="username">
			</p>
			<p>
				<label for="email">E-mail</label>
				<input type="text" name="email" value="<?=$email?>" id="email">
			</p>
			<p>
				<label for="password">Şifre</label>
				<input type="password" name="password" value="" class="half" id="password">
			</p>
			<p class="box" style="text-align:right"><input type="submit" name="install" value="Kurulumu başlat"></p>
		</fieldset>
	</form>
</body>
</html>
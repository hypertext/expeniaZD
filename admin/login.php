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
 
include "../libs/config.inc.php";

// logout
if(isset($_GET['logout'])){
	if(session_destroy()){
		header('location: login.php');
	}
}

// login
if($_POST){
	$login = $zd->login($_POST['username'], $_POST['password']);
	
	if($login == 1){
		header("location: index.php");
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Expenia Ziyaretçi Defteri | Yönetici Girişi</title>
	<meta name="author" content="Hüseyin Koyun">
	<link href="css/styles.css" rel="stylesheet">
</head>
<body class="login">
	<div class="account-container login stacked">
		<div class="content clearfix">
			<form action="" method="post">
				<h1>Login</h1>
				<hr style="margin: 0px 0 24px 0;"/>
				<div class="login-fields">
					<?php 
					//error meldung werfen
						if(is_array($login)){
							echo '<ul style="margin-bottom: 10px;" id="errors">';
							foreach($login as $err){
								echo $err;
							}
							echo '</ul>';
						}
					?>
					<div class="field">
						<label for="username">Kullanıcı Adı:</label>
						<input type="text" id="username" name="username" value="" placeholder="Kullanıcı Adı" class="login username-field" />
					</div> <!-- /field -->
					<div class="field">
						<label for="password">Şifre:</label>
						<input type="password" id="password" name="password" value="" placeholder="Şifre" class="login password-field"/>
					</div> <!-- /password -->
				</div> <!-- /login-fields -->
				<div class="login-actions">
					<input class="button" type="submit" value="GİRİŞ YAP">
				</div> <!-- .actions -->
			</form>
		</div> <!-- /content -->
	</div> <!-- /account-container -->
</body>
</html>
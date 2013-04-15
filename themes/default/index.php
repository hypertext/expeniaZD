<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Expenia Ziyaretçi Defteri</title>
    <link rel="stylesheet" type="text/css" href="<?php echo $zd->settings->url . '/themes/' . $zd->settings->theme . '/assets/css'; ?>/reset.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $zd->settings->url . '/themes/' . $zd->settings->theme . '/assets/css'; ?>/style.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $zd->settings->url . '/themes/' . $zd->settings->theme . '/assets/css'; ?>/osx.css" />
    <!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="<?php echo $zd->settings->url . '/themes/' . $zd->settings->theme . '/assets/js'; ?>/custom.js"></script>
    <script type="text/javascript" src="<?php echo $zd->settings->url . '/themes/' . $zd->settings->theme . '/assets/js'; ?>/jquery.simplemodal.js"></script>
    <script type="text/javascript" src="<?php echo $zd->settings->url . '/themes/' . $zd->settings->theme . '/assets/js'; ?>/osx.js"></script>
</head>

<body>
	<div id="osx-modal-content">
		<div id="osx-modal-title"><?php echo $zd->lang['SEND_MESSAGE']; ?></div>
		<div class="close"><a href="#" class="simplemodal-close">x</a></div>
		<div id="osx-modal-data">
			<div id="hk_mesaj_gonder_form">
				<div class="sonuc">
				
				</div>
				<form action="" method="POST">
					<p>
						<label for="name"><?php echo $zd->lang['NAME_SURNAME']; ?>:</label>
						<input type="text" name="name" id="name" value="" />
					</p>
					<p>
						<label for="email"><?php echo $zd->lang['EMAIL']; ?>:</label>
						<input type="text" name="email" id="email" value="" />
					</p>
					<p>
						<label for="message"><?php echo $zd->lang['MESSAGE']; ?>:</label>
						<textarea name="message" id="message"></textarea>
					</p>
					<p>
					<label for="captcha">
						<img src="libs/captcha.php" alt="Güvenlik Kodu" title="Güvenlik Kodu" width="80" height="25" />
					</label>
					<a id="reload"><img src="<?php echo $zd->settings->url . '/themes/' . $zd->settings->theme . '/assets/img/reload.png'; ?>" alt="reload" /></a>
					<input type="text" placeholder="<?php echo $zd->lang['PLACEHOLDER_CAPTCHA']; ?>" name="captcha" id="captcha" value="" />
					</p>
					<p>
						<input type="submit" id="submit" value="<?php echo $zd->lang['SEND']; ?>" name="submit" />
					</p>
				</form>
				<div id="smileys">
					<div>
						<a href="javascript:smiley_ekle(';-D');"><img src="<?php echo $zd->settings->url . '/themes/' . $zd->settings->theme . '/assets/img/smilies/emoticon_evilgrin.png'; ?>" alt="" /></a>
						<a href="javascript:smiley_ekle(':-D');"><img src="<?php echo $zd->settings->url . '/themes/' . $zd->settings->theme . '/assets/img/smilies/emoticon_grin.png'; ?>" alt="" /></a>
						<a href="javascript:smiley_ekle(':))');"><img src="<?php echo $zd->settings->url . '/themes/' . $zd->settings->theme . '/assets/img/smilies/emoticon_happy.png'; ?>" alt="" /></a>
						<a href="javascript:smiley_ekle(':-)');"><img src="<?php echo $zd->settings->url . '/themes/' . $zd->settings->theme . '/assets/img/smilies/emoticon_smile.png'; ?>" alt="" /></a>
						<a href="javascript:smiley_ekle(':-O');"><img src="<?php echo $zd->settings->url . '/themes/' . $zd->settings->theme . '/assets/img/smilies/emoticon_surprised.png'; ?>" alt="" /></a>
						<a href="javascript:smiley_ekle(':-P');"><img src="<?php echo $zd->settings->url . '/themes/' . $zd->settings->theme . '/assets/img/smilies/emoticon_tongue.png'; ?>" alt="" /></a>
						<a href="javascript:smiley_ekle(':-(');"><img src="<?php echo $zd->settings->url . '/themes/' . $zd->settings->theme . '/assets/img/smilies/emoticon_unhappy.png'; ?>" alt="" /></a>
						<a href="javascript:smiley_ekle(':-|');"><img src="<?php echo $zd->settings->url . '/themes/' . $zd->settings->theme . '/assets/img/smilies/emoticon_waii.png'; ?>" alt="" /></a>
						<a href="javascript:smiley_ekle(';-)');"><img src="<?php echo $zd->settings->url . '/themes/' . $zd->settings->theme . '/assets/img/smilies/emoticon_wink.png'; ?>" alt="" /></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="wrapper">
		<div id="hk_mesaj_gonder">
			<a class="osx" href="javascript://expenia"><?php echo $zd->lang['SEND_MESSAGE']; ?></a>
		</div>
		<?php foreach($data as $dat):?>
		<div class="hk_mesaj_container">
			<div class="hk_mesaj">
				<div class="hk_mesaj_header">
					<span class="yazar"><?php echo $dat['name']; ?></span> <span><?php echo $zd->lang['WRITES']; ?></span>
					<span style="padding-left: 50px"><?php if(isset($_SESSION['id'])){ echo 'IP: '.$dat['ip']; } ?></span>
					<span class="tarih" title="<?php echo $dat['time']; ?>"><?php echo $dat['date']; ?></span>
				</div>
				<div class="hk_mesaj_content">
					<?php  
					if(isset($_SESSION['id'])){
						echo $dat['message']; 
					}else{
						if($dat['approval'] == 0){
							echo '<span style="color:red">'.$zd->lang['AFTER_APPRÒVAL'].'</span>';
						}else{ 
							echo $dat['message'];
						}
					}
					$response = $zd->show_response($dat['id']);
					
					if($response){
						echo '<div class="response"><strong>'.$zd->lang['RESPONSE'].'</strong> <br />'.$response->message.'</div>';
					}
					?>                              
				</div>
				<?php
				if(isset($_SESSION['id'])){ 
					echo '<div class="islemler">';
					if($dat['approval'] == 0){
						echo '<a href="javascript://expenia" id="'.$dat['id'].'" class="but onayla">'.$zd->lang['APPROVE'].'</a> ';
					}
					if(!$response){ echo '<a href="javascript://expenia" id="'.$dat['id'].'" class="but cevapla">'.$zd->lang['ANSWER'].'</a> '; }
					echo '<a href="javascript://expenia" id="'.$dat['id'].'" class="but sil">'.$zd->lang['DELETE'].'</a> ';
					echo '<form style="margin-top: 10px; display: none;" class="response-form" id="f'.$dat['id'].'" action="" method="POST">';
					echo '<textarea style="float: left; width: 673px;" name="response"></textarea>';
					echo '<input type="hidden" value="'.$dat['id'].'" name="m_id" />';
					echo '<input type="submit" value="'.$zd->lang['SEND'].'" />';
					echo '<div style="clear:both"></div>';
					echo '</form>';
					echo '</div>';
				}
				?>
				
			</div>
		</div>
		<?php endforeach; ?>
		<?php echo $zd->display_pagination(); ?>      
		<div style="clear:both"></div>

		<div class="yonetici">
			<a href="admin">Yönetici Girişi</a>        
		</div>
		<div id="expenia">
			<a href="http://www.expenia.com" target="_blank">Expenia Internet Solutions</a>
		</div>
	</div>
</body>
</html>
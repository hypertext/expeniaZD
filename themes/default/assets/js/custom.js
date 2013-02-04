$().ready(function(){
	
	$("#submit").live('click',function(){
		
		$('.sonuc').ajaxStart(function(){
			$(this).html('<img src="themes/default/assets/img/loader.png" />');
		});
		
		$.ajax({
			type	: "POST",
			url		: "send_message.php",
			data	: $('#hk_mesaj_gonder_form form').serialize(),
			success	: function(data){
				if(data == 'OK'){
					location.reload();
				}else{
					$('.sonuc').html(data);
					var height = 420 + $('ul#errors').height();
					var mtop = $('ul#errors').height() + 70;
					$('#smileys').css('margin-top' , mtop);
					$('#osx-container').height(height);
				}
			}
		});
		
		return false;
	});
	
	// captcha yenileme
	$('#reload').click(function(){
		$('label[for="captcha"] img').attr('src', 'libs/captcha.php?x='+ Math.random());
	});
	
	// onaylama islemi
	$('.onayla').click(function(){
		var id = $(this).attr('id');
		
		$.post('ajax.php?onayla', { msg_id: id }, function(data) {
			if(data == 1){
				$('a#'+id).remove();
			}
		});

	});
	
	// silme islemi
	$('.sil').click(function(){
		var id = $(this).attr('id');
		
		if(confirm('Silmek istediginizden emin misiniz?')){
			$.post('ajax.php?sil', { msg_id: id }, function(data) {
				if(data == 1){
					$('#'+id).parent().parent().parent().slideUp();
				}
			});
		}
		
	});
	$('.cevapla').click(function(){
		var id = $(this).attr('id');
		$('#f'+id).slideToggle();
	});
	
	$('.response-form').submit(function(e){
		e.preventDefault();
		
		$.ajax({
			type	: "POST",
			url		: "ajax.php?cevapla",
			data	: $(this).serialize(),
			success	: function(data){
				if(data == 'OK'){
					location.reload();
				}else{
					alert(data);
				}
			}
		});
	});
});

function smiley_ekle(smiley_kod){
	$('textarea[name="message"]').focus();
	$('textarea[name="message"]').val($('textarea[name="message"]').val()+smiley_kod);
}
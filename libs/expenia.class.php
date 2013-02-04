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
session_start();

class expenia{
	
	public $version = "2.0";
	
	public function __construct($dbhost, $dbuser, $dbpass, $dbname){
		// database connection
		$this->db = new mysqli($dbhost, $dbuser, $dbpass, $dbname) or die ('Veritabanı bağlantısı sağlanamadı: ' . mysqli_connect_error());
		// set names to utf8
		$this->db->query("SET NAMES 'utf8'");
		
		// get settings from database
		$result = $this->db->query("SELECT * FROM settings WHERE id = 1");
		$this->settings = $result->fetch_object();
				
		// define systempath
		if (realpath($system_path) !== FALSE){ $system_path = realpath($system_path).'/'; }

		$system_path = rtrim($system_path, '/').'/';

		if (!is_dir($system_path)){	exit("Hata: ".pathinfo(__FILE__, PATHINFO_BASENAME) ); }

		define('BASEPATH', str_replace("\\", "/", realpath(dirname(__FILE__))));

		// get language file
		include_once str_replace('libs', '', BASEPATH)."/lang/" . $this->settings->lang . ".php";
		$this->lang = $lang;
	}
	
	public function security($var){
        $var = trim($this->db->real_escape_string($var));
        
        if(get_magic_quotes_gpc()){
            $var = stripslashes($var);
        }
        
        return $var;
    }
	
	public function add_message($name, $email, $captcha, $message){
		
		$name = $this->security($name);
		$email = $this->security($email);
		$message = nl2br($message);
		$message = $this->real_escape_string($message);
		
		$timestamp = time();
		
		$ip = $_SERVER['REMOTE_ADDR'];
		
		$errors = array();
		
		if(empty($name)){
            $errors[] = $this->lang['NAME_SURNAME_EMPTY'];
        }
        
        // eğer email değişkeni boş ise dizimize hatayı ekliyoruz
        if(empty($email)){
            $errors[] = $this->lang['EMAIL_EMPTY'];
        }else{ // email değişkeni boş değilse geçerli olup olmadığını kontrol ediyoruz
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                // eğer geçerli değil ise dizimize hatayı ekliyoruz
                $errors[] = $this->lang['INVALID_EMAIL'];
            }
        }
        
        // mesaj alanı 6 karakterden az ise dizimize hatayı ekliyoruz
        if(strlen($message) < 6){
            $errors[] = $this->lang['MESSAGE_TOO_SHORT'];
        }
		
		// güvenlik kodu
		if(empty($captcha)){
			$errors[] = $this->lang['EMPTY_CAPTCHA'];
		}else{
			if (md5($captcha) != $_SESSION['captcha_code']){
				$errors[] = $this->lang['INVALID_CAPTCHA'];
			}
		}
        
		$smiley = array(';-D', ':-D', ':))', ':-)', ':-O', ':-P', ':-(', ':-|', ';-)');
		$source = array(
			'<img src="'.$this->settings->url.'/themes/'.$this->settings->theme.'/assets/img/smilies/emoticon_evilgrin.png" alt=";-D" />',
			'<img src="'.$this->settings->url.'/themes/'.$this->settings->theme.'/assets/img/smilies/emoticon_grin.png" alt=":-D" />',
			'<img src="'.$this->settings->url.'/themes/'.$this->settings->theme.'/assets/img/smilies/emoticon_happy.png" alt=":))" />',
			'<img src="'.$this->settings->url.'/themes/'.$this->settings->theme.'/assets/img/smilies/emoticon_smile.png" alt=":-)" />',
			'<img src="'.$this->settings->url.'/themes/'.$this->settings->theme.'/assets/img/smilies/emoticon_surprised.png" alt=":-O" />',
			'<img src="'.$this->settings->url.'/themes/'.$this->settings->theme.'/assets/img/smilies/emoticon_tongue.png" alt=":-P" />',
			'<img src="'.$this->settings->url.'/themes/'.$this->settings->theme.'/assets/img/smilies/emoticon_unhappy.png" alt=" :-(" />',
			'<img src="'.$this->settings->url.'/themes/'.$this->settings->theme.'/assets/img/smilies/emoticon_waii.png" alt=":-|" />',
			'<img src="'.$this->settings->url.'/themes/'.$this->settings->theme.'/assets/img/smilies/emoticon_wink.png" alt=" ;-)" />'
			);

		$message = str_replace($smiley, $source, $message);
						
		if(count($errors) === 0){
            $approval = ($this->settings->approval == 0) ? 1 : 0;
            
            if($this->db->query("INSERT INTO messages (name, email, message, approval, timestamp, ip) VALUES ('$name', '$email', '$message', '$approval', '$timestamp', '$ip')")){
                return true;
            }
            
        }else{
            // eğer hata var ise hataları geri döndürüyoruz
            return $errors;
        }
	}
	
	public function show_response($id){
		$query = $this->db->query("SELECT * FROM messages WHERE parent_id = '$id'");
		if($query->num_rows == 1){
			return $query->fetch_object();
		}else{
			return false;
		}
	}
	public function get_messages(){
		// eğer yönetici giriş yapmış ise bütün mesajları alıyoruz
        if($_SESSION['loggedIn'] == 1){
            $sql = "SELECT * FROM messages ORDER BY id DESC";
        }else{ // giriş yapmamışsa sadece onaylanmış mesajları alıyoruz
            $sql = "SELECT * FROM messages WHERE parent_id = '0' ORDER BY id DESC";
        }
		
		//adres çubuğuna yazılanı çekiyor kontrol ediyoruz
        $this->pageNumber = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;

        // sayfa başı kaç öğe olacağını belirliyoruz
        $this->perPage = 10;
        
    	//sayfanın sağında ve solunda kaç sayfa göstereceğini belirliyoruz
        $this->padding = 5;
        
        //sayfanın kaçtan başlayacağını hesaplıyoruz
        $this->startIndex = ($this->pageNumber * $this->perPage) - $this->perPage;
        
        //toplam kaç adet veri olduğunu alıyoruz
		$this->rowCount = $this->db->query($sql)->num_rows;
        // ve veritabanından verilerimizi çekiyoruz
        $result = $this->db->query($sql." LIMIT $this->startIndex, $this->perPage");

        $data = array();
        
        $i = 0;
		if($this->rowCount > 0){
        //çektiğimiz verileri array'e tanımlayıp döndürüyoruz
			while($row = $result->fetch_assoc()){
				$data[$i]['id']         = $row['id'];
				$data[$i]['name']       = $row['name'];
				$data[$i]['message']    = $row['message'];
				$data[$i]['approval']   = $row['approval'];
				$data[$i]['ip']   		= $row['ip'];
				$data[$i]['date']       = date('d', $row['timestamp']).' '.$this->lang[date('F', $row['timestamp'])].', '.date('Y', $row['timestamp']);
				$data[$i]['time']       = date('H:i:s', $row['timestamp']);
				$i++;
			}
			return $data;
		}else{
			return false;
		}
        
	}
	
	/**
     *
     * sayfaları yazdırmaya yarayan fonksiyon
     *
     * @access public
     * @return html 
     */
    public function display_pagination(){
        //toplam sayfa sayısı
		$this->numOfPages = ceil($this->rowCount / $this->perPage);

        $return = '';
        $return .= '<div id="pagination">';
        $return .= '<ul>';
        
        if($this->pageNumber > 1){
            $return .= '<li><a href="?page='.($this->pageNumber - 1).'">« '.$this->lang['PRV'].'</a></li>';
        } 
		##################################
		if(($this->pageNumber - $this->padding) > 1){
			$return .= '<li><a>...</a></li>';
			
			$this->lowerLimit = $this->pageNumber - $this->padding;
			
			for($i = $this->lowerLimit; $i < $this->pageNumber; $i++){
				$return .= '<li><a href="?page='.$i.'">'.$i.'</a></li>';
			}
		}else{
			for($i = 1; $i < $this->pageNumber; $i++){
				$return .= '<li><a href="?page='.$i.'">'.$i.'</a></li>';
			}
				
		}
		#####################################
        
        if(($this->pageNumber != 0)){
			$return .= '<li><a class="current">'.$this->pageNumber.'</a></li>';
		}
        
        ######################################
		if($this->pageNumber + $this->padding < $this->numOfPages) {
			$this->upperLimit = $this->pageNumber + $this->padding;
				for($i = ($this->pageNumber + 1); $i <= $this->upperLimit; $i++){
					$return .= '<li><a href="?page='.$i.'">'.$i.'</a></li>';
				}
			$return .= "<li><a>...</a></li>";
		}else{
			for($i = $this->pageNumber + 1; $i < $this->numOfPages + 1; $i++){
				$return .= '<li><a href="?page='.$i.'">'.$i.'</a></li>';
			}
		}
		###################################
        
        if($this->pageNumber != $this->numOfPages){
            $return .= '<li><a href="?page='.($this->pageNumber + 1).'">'.$this->lang['FWD'].' »</a></li>';
        }

        $return .= '</ul>';
        $return .= '</div>';
        
		if($this->numOfPages > 1)
        return $return;
    }
	
}
?>
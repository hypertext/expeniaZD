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

// Veritabanı ayarları
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = 'root';
$dbname = 'expenia_zd';

include_once "admin.class.php";
$zd = new expenia_admin($dbhost, $dbuser, $dbpass, $dbname);
?>
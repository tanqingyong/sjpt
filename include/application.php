<?php
/* for rewrite or iis rewrite */
if (isset($_SERVER['HTTP_X_ORIGINAL_URL'])) {
	$_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_ORIGINAL_URL'];
} else if (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
	$_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
}
/* end */

error_reporting(E_ALL^E_WARNING^E_NOTICE);
define('SYS_VERSION', 'CV2.0');
define('SYS_SUBVERSION', '23221');
define('SYS_TIMESTART', microtime(true));
define('SYS_REQUEST', isset($_SERVER['REQUEST_URI']));
define('DIR_SEPERATOR', strstr(strtoupper(PHP_OS), 'WIN')?'\\':'/');
define('DIR_ROOT', str_replace('\\','/',dirname(__FILE__)));
define('DIR_LIBARAY', DIR_ROOT . '/library');
define('DIR_CLASSES', DIR_ROOT . '/classes');
define('DIR_COMPILED', DIR_ROOT . '/compiled');
define('DIR_VIEWS', DIR_ROOT . '/views');
define('DIR_TEMPLATE', DIR_ROOT . '/template');
define('DIR_FUNCTION', DIR_ROOT . '/function');
define('DIR_CONFIGURE', DIR_ROOT . '/configure');
define('SYS_MAGICGPC', get_magic_quotes_gpc());
define('SYS_PHPFILE', DIR_ROOT . '/configure/system.php');
define('WWW_ROOT', rtrim(dirname(DIR_ROOT),'/'));
define('IMG_ROOT', dirname(DIR_ROOT) . '/static');

/* encoding */
mb_internal_encoding('UTF-8');
/* end */

//系统初始化信息

global $deparment_user_grades ;//这个变量标识那个数据源下的部门有那些权限等级
//第一个表示数据源：1是窝窝团。
//第二层表示部门：1是运营部、2是EDM
$deparment_user_grades = array(1=>array(
1=>array(4=>"管理员",1=>"普通用户"),
2=>array(4=>"管理员",1=>"普通用户"),
3=>array(4=>"管理员",1=>"普通用户"),
4=>array(4=>"管理员",1=>"普通用户"),
5=>array(4=>"管理员",1=>"普通用户"),
6=>array(4=>"管理员",1=>"普通用户"),
7=>array(4=>"管理员",1=>"普通用户")
));

//平台邮件对应关系
global $mail_array;
$mail_array = array('hypd'=>'个性化邮件','hygg'=>'广告邮件','tixing'=>'未支付提醒','crm'=>'激活邮件','webpower'=>'会员日常单','qingxi'=>'会员清洗');
global $edms_mail;
$edms_mail = array('huiyee'=>array('hypd','hygg','tixing'),
                   'webpower'=>array('crm','webpower','qingxi')
                  );
//频道
global $arr_channel;
$arr_channel = array('1'=>'旅游','2'=>'酒店','3'=>'生活馆','4'=>'化妆品','5'=>'55商城');
/* important function */
function __autoload($class_name) {
	$file_name = trim(str_replace('_','/',$class_name),'/').'.class.php';
	$file_path = DIR_LIBARAY. '/' . $file_name;
	if ( file_exists( $file_path ) ) {
		return require_once( $file_path );
	}
	$file_path = DIR_CLASSES. '/' . $file_name;
	if ( file_exists( $file_path ) ) {
		return require_once( $file_path );
	}
	return false;
}

function import($funcpre) {
	$file_path = DIR_FUNCTION. '/' . $funcpre . '.php'; 
	if (file_exists($file_path) ) {
		require_once( $file_path );
	}
}

/* json */
if (!function_exists('json_encode')){function json_encode($v){$js = new JsonService(); return $js->encode($v);}}
if (!function_exists('json_decode')){function json_decode($v,$t){$js = new JsonService($t?16:0); return $js->decode($v);}}
/* end json */

/* import */
import('template');
import('common');

/* ob_handler */
if(SYS_REQUEST){ ob_get_clean(); ob_start(); }
/* end ob */

/***
 * 调试函数
 * @param 
 * @return mixed
 ***/

function dbx() {
	echo '<pre>';
	if(func_num_args()){
		foreach (func_get_args() as $k => $v) {
			echo "------- dbx $k -------<br/>";
			print_r($v);
			echo "<br/>";
		}
	};
	echo '</pre>';
}

function dpx() {
    echo '<pre>';
	if(func_num_args()){
		foreach (func_get_args() as $k => $v) {
			echo "------- dbx $k -------<br/>";
			var_dump($v);
			echo "<br/>";
		}
	};
    echo '</pre>';
}

function dbt() {
    echo '<pre>';
	if(func_num_args()){
		foreach (func_get_args() as $k => $v) {
			echo "------- dbx $k -------<br/>";
			echo "<textarea cols=20 rows=6>";
			print_r($v);
			echo "</textarea>";
			echo "<br/>";
		}
	};
    echo '</pre>';

}

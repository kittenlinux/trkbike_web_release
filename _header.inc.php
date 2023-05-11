<?php
ini_set('display_errors', 'On');
ini_set('error_log',  dirname(__FILE__)."/log/errors.log");
ini_set('log_errors', 'On');

session_start();

require_once (dirname(__FILE__)."/config.inc.php");
require_once (dirname(__FILE__)."/lib/function.inc.php");
require_once (dirname(__FILE__)."/lib/database.inc.php");

require_once (dirname(__FILE__)."/lib/Smarty/libs/Smarty.class.php");
require_once (dirname(__FILE__)."/lib/theme.inc.php");

DEFINE ('FS_ROOT_PATH' , dirname(__FILE__));
DEFINE ('FS_CURRENT_PATH', dirname($_SERVER["SCRIPT_FILENAME"]));
$df = substr(FS_CURRENT_PATH, strlen(FS_ROOT_PATH));
$sc = substr_count($df, "/");
for($sf='';$sc>0;$sc--)
	$sf = $sf . "../";
$sf = $sf ? $sf:'./';
$sf = rtrim($sf, '/');
DEFINE('WEB_ROOT_PATH' , $sf);
DEFINE('FS_THEME_PATH',  FS_ROOT_PATH."/themes/$theme");
DEFINE('WEB_THEME_PATH', WEB_ROOT_PATH."/themes/$theme");

//Smarty for CURRENT SCRIPT
$sm = new Smarty;
$sm->template_dir	= "";//FS_THEME_PATH;
$sm->config_dir	    = "";//FS_THEME_PATH;
$sm->compile_check	= false;
$sm->force_compile	= true;
$sm->use_sub_dirs	= true;
$sm->compile_dir	= FS_ROOT_PATH."/_cache_";//Smarty/templates_c";
$sm->cache_dir		= FS_ROOT_PATH."/_cache_";
$sm->left_delimiter = '<%';
$sm->right_delimiter = '%>';

if(isset($_SESSION['_logged_in'])){
    $_logged_in = $_SESSION['_logged_in'];
    $_user_name = $_SESSION['_login_info']['cn'] . ' ' . $_SESSION['_login_info']['sn'];
    $sm->assign('_logged_in', $_logged_in);
    $sm->assign('_user_name', $_user_name);
}
if(!isset($_dont_display_html))
	ob_start();
?>
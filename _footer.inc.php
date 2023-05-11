<?php

if(isset($_SESSION['_logged_in'])){
    $_logged_in = $_SESSION['_logged_in'];
    $_user_name = $_SESSION['_login_info']['cn'] . ' ' . $_SESSION['_login_info']['sn'];
}

if(!isset($_dont_display_html)){

    $_sm = new Smarty;
    $_sm->template_dir  = FS_THEME_PATH;
    $_sm->config_dir    = FS_THEME_PATH;
    $_sm->compile_check	= false;
    $_sm->force_compile	= true;
    $_sm->use_sub_dirs  = true;
    $_sm->compile_dir	= FS_ROOT_PATH."/_cache_";//Smarty/templates_c";
    $_sm->cache_dir		= FS_ROOT_PATH."/_cache_";
    $_sm->left_delimiter   = '<%';
    $_sm->right_delimiter  = '%>';

    $_sm->assign('WEB_ROOT_PATH', WEB_ROOT_PATH);

    if(isset($_SESSION['_logged_in'])){
        $_sm->assign('_logged_in', $_logged_in);
        $_sm->assign('_user_name', $_user_name);
    }

    $_body_html = trim(ob_get_contents());
    ob_end_clean();

    $_theme_processor = '';
    //$_theme_processor = WEB_ROOT_PATH."/theme.php?p=".WEB_THEME_PATH."&f=";

    if(!isset($_dont_display_theme)){
        $main_page = "main.html";
        $_all_html = trim($_sm->fetch(FS_THEME_PATH."/$main_page"));

        list($_befor_body_html, $_after_body_html) = explode('<!--BODY_CONTENT-->', $_all_html);
    }
    $_all_html = $_befor_body_html.$_body_html.$_after_body_html;

    $_all_html = theme_change_location($_all_html, WEB_THEME_PATH, $_theme_processor);
    $_all_html = theme_change_location($_all_html, WEB_ROOT_PATH,  $_theme_processor, "ACTION=\"");
    $_all_html = theme_change_location($_all_html, WEB_ROOT_PATH,  $_theme_processor, "HREF=\"", 'a');

    echo $_all_html;
}
?>
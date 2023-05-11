<?php

function theme_change_location($_html, $_url_theme_path, $_processor='', $_key='', $_tag='')
{
    if($_key==''){
    	$_s = "HREF=\"";
    	$_html = _replace_theme_location($_s, $_html, $_url_theme_path, $_processor);
    	$_s = "SRC=\"";
    	$_html = _replace_theme_location($_s, $_html, $_url_theme_path);
    	$_s = "BACKGROUND=\"";
    	$_html = _replace_theme_location($_s, $_html, $_url_theme_path);
    	$_s = "IMPORT \"";
    	$_html = _replace_theme_location($_s, $_html, $_url_theme_path);
    	//$_s = "ACTION=\"";
    	//$_html = _replace_theme_location($_s, $_html, $_url_theme_path);
    	$_s = "URL(";
    	$_html = _replace_theme_location($_s, $_html, $_url_theme_path);
    }else{
        $_html = _replace_theme_location($_key, $_html, $_url_theme_path, $_processor, $_tag);
    }
	return $_html;
}

function _replace_theme_location($_s, $_html, $_url_theme_path, $_processor='', $_tag='')
{
    $_url_theme_path = rtrim($_url_theme_path, "/") . "/";
    $_s = strtoupper($_s);
	$end = true;
	$p = 0;
	while($end){
		$_s_html = strtoupper($_html);
		$end = strpos($_s_html, $_s, $p);
		if($end){
			if((substr($_s_html, $end+strlen($_s), 4)!="HTTP")&&(substr($_s_html, $end+strlen($_s), 1)!="/")){
		    	$_f_html = substr($_html, 0, $end+strlen($_s));
                $_a = strrpos($_f_html, '<');
                if($_tag==''){
                    if($_a==false || (strtoupper(substr($_f_html, $_a+1, 1))!='A')){
    		    		$_l_html = substr($_html, strlen($_f_html));
	        			$_html = $_f_html.$_processor.$_url_theme_path.$_l_html;
                        $p = $end+strlen($_s.$_url_theme_path);
                        continue;
                    }
                }else{
                    if(strtoupper($_tag)=='A' && (substr($_s_html, $end+strlen($_s), 1)=='#')){
                        $p = $end+strlen($_s);
                        continue;
                    }
                    if($_a==true && (strtoupper(substr($_f_html, $_a+1, strlen($_tag)))==strtoupper($_tag))){
                        $_l_html = substr($_html, strlen($_f_html));
                        $_html = $_f_html.$_processor.$_url_theme_path.$_l_html;
                        $p = $end+strlen($_s.$_url_theme_path);
                        continue;
                    }
                }
			}
			$p = $end+strlen($_s);
		}
	}
	return $_html;
}

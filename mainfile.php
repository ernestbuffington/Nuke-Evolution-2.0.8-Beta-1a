<?php

/*=======================================================================
 Nuke-Evolution Basic: Enhanced PHP-Nuke Web Portal System
 =======================================================================*/

/************************************************************************/
/* PHP-NUKE: Advanced Content Management System                         */
/* ============================================                         */
/*                                                                      */
/* Copyright (c) 2002 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

/*****[CHANGES]**********************************************************
-=[Base]=-
      NukeSentinel                             v2.5.08      07/11/2006
      Nuke Patched                             v3.1.0       06/26/2005
      Language Selector                        v3.0.0       12/11/2005
      Admin File Check                         v3.0.0       11/19/2005
      PHP Input Filter                         v1.2.2       10/14/2005
      HTML Parser                              v1.2.0       10/27/2005
      Caching System                           v1.0.0       10/29/2005
      Debugger                                 v1.0.0       11/14/2005
      Module Simplifications                   v1.0.0       11/17/2005
      Evolution Functions                      v1.5.0       11/24/2005
      Theme Management                         v1.0.2       12/14/2005
-=[Mod]=-
      Admin Icon/Link Pos                      v1.0.0       06/02/2005
      Advanced Username Color                  v1.0.5       06/11/2005
      Evolution Version Checker                v1.0.0       06/16/2005
      Lock Modules                             v1.0.0       08/04/2005
      Group Colors                             v1.0.0       10/20/2005
      Censor                                   v1.0.0       10/20/2005
      NBBCode                                  v9.26.0      11/23/2005
      Color Toggle                             v1.0.0       11/25/2005
      Lazy Google Tap                          v1.0.0       01/27/2005
      Switch Content Script                    v2.0.0       03/30/2006
-=[Module]=-
      CNB Your Account                         v4.4.2       06/15/2005
-=[Other]=-
      SSL Administration                       v1.0.0       08/29/2005
      Validation                               v1.1.0       10/17/2005
      Extra Functions                          v1.0.0       12/22/2005
 ************************************************************************/

if(defined('NUKE_EVO')) return;

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Access Denied');
}
// Define File 4/29/2010
define_once('NUKE_EVO', '2.0.8');
define_once('EVO_EDITION', 'Beta 1a');
define('PHPVERS', phpversion());
define_once('EVO_VERSION', NUKE_EVO . ' ' . EVO_EDITION);
define('PHP_5', version_compare(PHPVERS, '5.0.0', '>='));

if (!ini_get('register_globals')) {
	$import = true;
	// Need register_globals so try the built in import function
	if (function_exists('import_request_variables')) {
		@import_request_variables('GPC');
	} else {
		function evo_import_globals($array) {
			foreach ($array as $k => $v) {
				global $$k;
				$$k = $v;
			}
		}
		if (!empty($_GET)) {
			evo_import_globals($_GET);
		}
		if (!empty($_POST)) {
			evo_import_globals($_POST);
		}
		if (!empty($_COOKIE)) {
			evo_import_globals($_COOKIE);
		}
	}
}

$admin = (isset($_COOKIE['admin'])) ? $_COOKIE['admin'] : false;
$user = (isset($_COOKIE['user'])) ? $_COOKIE['user'] : false;
if ((isset($_POST['name']) && !empty($_POST['name'])) && (isset($_GET['name']) && !empty($_GET['name']))) {
    $name = (isset($_GET['name']) && !stristr($_GET['name'],'..') && !stristr($_GET['name'],'://')) ? addslashes(trim($_GET['name'])) : false;
} else {
    $name = (isset($_REQUEST['name']) && !stristr($_REQUEST['name'],'..') && !stristr($_REQUEST['name'],'://')) ? addslashes(trim($_REQUEST['name'])) : false;
}
$start_mem = function_exists('memory_get_usage') ? memory_get_usage() : 0;
$start_time = get_microtime();

// Stupid handle to create REQUEST_URI for IIS 5 servers
if (strstr($_SERVER['SERVER_SOFTWARE'], 'IIS') && isset($_SERVER['SCRIPT_NAME'])) {
    $requesturi = $_SERVER['SCRIPT_NAME'];
    if (isset($_SERVER['QUERY_STRING'])) {
        $requesturi .= '?'.$_SERVER['QUERY_STRING'];
    }
    $_SERVER['REQUEST_URI'] = $requesturi;
}

// PHP5 with register_long_arrays off?
if (PHP_5 && (!@ini_get('register_long_arrays') || @ini_get('register_long_arrays') == '0' || strtolower(@ini_get('register_long_arrays')) == 'off')) {
    $HTTP_POST_VARS = $_POST;
    $HTTP_GET_VARS = $_GET;
    $HTTP_SERVER_VARS = $_SERVER;
    $HTTP_COOKIE_VARS = $_COOKIE;
    $HTTP_ENV_VARS = $_ENV;
    $HTTP_POST_FILES = $_FILES;
    if (isset($_SESSION)) $HTTP_SESSION_VARS = $_SESSION;
}

if (isset($_COOKIE['DONATION'])) {
    setcookie('DONATION', null, time()-3600);
    $type = preg_match('/IIS|Microsoft|WebSTAR|Xitami/', $_SERVER['SERVER_SOFTWARE']) ? 'Refresh: 0; URL=' : 'Location: ';
	$url = str_replace('&amp;', "&", $url);
    header($type . 'modules.php?name=Donations&op=thankyou');
}

//Inspired by phoenix-cms at website-portals.net
//Absolute Nuke directory
define('NUKE_BASE_DIR', dirname(__FILE__) . '/');
//Absolute Nuke directory + includes
define('NUKE_BLOCKS_DIR', NUKE_BASE_DIR . 'blocks/');
define('NUKE_IMAGES_DIR', NUKE_BASE_DIR . 'images/');
define('NUKE_INCLUDE_DIR', NUKE_BASE_DIR . 'includes/');
define('NUKE_LANGUAGE_DIR', NUKE_BASE_DIR . 'language/');
define('NUKE_MODULES_DIR', NUKE_BASE_DIR . 'modules/');
define('NUKE_THEMES_DIR', NUKE_BASE_DIR . 'themes/');
define('NUKE_ADMIN_DIR', NUKE_BASE_DIR . 'admin/');
define('NUKE_RSS_DIR', NUKE_INCLUDE_DIR . 'rss/');
define('NUKE_DB_DIR', NUKE_INCLUDE_DIR . 'db/');
define('NUKE_ADMIN_MODULE_DIR', NUKE_ADMIN_DIR . 'modules/');
define('NUKE_FORUMS_DIR', (defined("IN_ADMIN") ? './../' : 'modules/Forums/'));
define('NUKE_CACHE_DIR', NUKE_INCLUDE_DIR . 'cache/');
define('NUKE_CLASSES_DIR', NUKE_INCLUDE_DIR . 'classes/');
// define the INCLUDE PATH
define('INCLUDE_PATH', NUKE_BASE_DIR);

define('GZIPSUPPORT', extension_loaded('zlib'));
define('GDSUPPORT', extension_loaded('gd'));
define('CAN_MOD_INI', !stristr(ini_get('disable_functions'), 'ini_set'));

//Check for these functions to see if we can use the new captcha
if(function_exists('imagecreatetruecolor') && function_exists('imageftbbox')) {
    define('CAPTCHA',true);
}

if (CAN_MOD_INI) {
    ini_set('magic_quotes_sybase', 0);
    ini_set('zlib.output_compression', 0);
}

// Include config file
@require_once(NUKE_BASE_DIR.'config.php');

// Check if nuke is installed
if (!isset($installed)){
	header('Location: install.php');
}

if(!$directory_mode) {
    $directory_mode = 0777;
} else {
    $directory_mode = 0755;
}
if (!$file_mode) {
    $file_mode = 0666;
} else {
    $file_mode = 0644;
}

// Include the required files
@require_once(NUKE_DB_DIR.'db.php');
//$db->debug = true;
// Include Error Logger and identify class
@require_once(NUKE_CLASSES_DIR.'class.identify.php');
global $agent;
$agent = identify::identify_agent();
@require_once(NUKE_INCLUDE_DIR.'log.php');

if (ini_get('output_buffering') && !isset($agent['bot'])) {
    ob_end_clean();
    header('Content-Encoding: none');
}

$do_gzip_compress = false;
if (GZIPSUPPORT && !ini_get('zlib.output_compression') && isset($_SERVER['HTTP_ACCEPT_ENCODING']) && preg_match('/gzip/i', $_SERVER['HTTP_ACCEPT_ENCODING'])) {
    if (version_compare(PHPVERS, '4.3.0', '>=')) { # PHP 4.2.x seems to give memleak
        ob_start('ob_gzhandler');
    } else {
        $do_gzip_compress = true;
        ob_start();
        ob_implicit_flush(0);
        header('Content-Encoding: gzip');
    }
} else {
    ob_start();
    ob_implicit_flush(0);
}

@require_once(NUKE_CLASSES_DIR.'class.cache.php');
@require_once(NUKE_CLASSES_DIR.'class.debugger.php');
require_once(NUKE_INCLUDE_DIR.'functions_evo.php');
include_once(NUKE_INCLUDE_DIR.'validation.php');

/*****[BEGIN]******************************************
 [ Base:    PHP Input Filter                   v1.2.2 ]
 ******************************************************/
/*if (PHPVERS > '4.0' && (!defined('NO_SECURITY') && !defined('ADMIN_FILE'))) {
    require_once(NUKE_CLASSES_DIR.'class.inputfilter.php');
    $data = array_merge($_POST, $_GET);
    //This is the new php input class it will filter out bad HTML code and XSS
    //data from POSTs or GETs
    if(defined('MEDIUM_SECURITY')) {
        if(isset($_POST['message']) && !empty($_POST['message'])){
            if (preg_match("/(<.*?pre\s?.*?>.*<.*?pre\s?'.*?>|document.location.*?=.*document\..*)/i", $_POST['message'])) {
                InputFilter::filtered('',$_POST['message']);
            }
        }
        $filter = new InputFilter("", "", 1, 1, 0);
    } else {
        $filter = new InputFilter("", "", 1, 1, 1);
    }
    if(!empty($data)) {
        $data = $filter->process($data);
    }
    define('INPUT_FILTER',true);
}*/
/*****[END]********************************************
 [ Base:    PHP Input Filter                   v1.2.2 ]
 ******************************************************/
// We globalize the $cookie and $userinfo variables,
// so that they dont have to be called each time
// And as you can see, getusrinfo() is now deprecated.
// Because you dont have to call it anymore, just call $userinfo
if(is_user()) {
    $cookie = cookiedecode();
    $userinfo = get_user_field('*', $cookie[1], true);
} else {
    $cookie = array();
    $userinfo = get_user_field('*', 'Anonymous', true);
}

//If they have been deactivated send them to logout to kill their cookie and sessions
if (is_array($userinfo) && isset($userinfo['user_active']) && $userinfo['user_id'] != 1 && $userinfo['user_id'] != 0 && $userinfo['user_active'] == 0 && $_GET['name'] != 'Your_Account') {
    redirect('modules.php?name=Your_Account&op=logout');
    die();
}

if(stristr($_SERVER['REQUEST_URI'], '.php/')) {
    redirect(str_replace('.php/', '.php', $_SERVER['REQUEST_URI']));
}

include_once(NUKE_MODULES_DIR.'Your_Account/includes/mainfileend.php');

if (isset($_POST['clear_cache'])) {
    $cache->clear();
}

define('NUKE_FILE', true);
$dbi = $db->db_connect_id;
$badreasons = 4;
$sitekey = md5($_SERVER['HTTP_HOST']);
$gfx_chk = 0;
$tipath = 'images/topics/';
$reasons = array('As Is', 'Offtopic', 'Flamebait', 'Troll', 'Redundant', 'Insighful', 'Interesting', 'Informative', 'Funny', 'Overrated', 'Underrated');
$AllowableHTML = array('b'=>1, 'i'=>1, 'a'=>2, 'em'=>1, 'br'=>1, 'strong'=>1, 'blockquote'=>1, 'tt'=>1, 'li'=>1, 'ol'=>1, 'ul'=>1, 'pre'=>1);

$nukeconfig = load_nukeconfig();
foreach($nukeconfig as $var => $value) {
    $$var = $value;
}

/*****[BEGIN]******************************************
 [ Base:    Language Selector                  v3.0.0 ]
 ******************************************************/
@require_once(NUKE_INCLUDE_DIR.'language.php');
/*****[END]********************************************
 [ Base:    Language Selector                  v3.0.0 ]
 ******************************************************/
$adminmail = stripslashes($adminmail);
$foot1 = stripslashes($foot1);
$foot2 = stripslashes($foot2);
$foot3 = stripslashes($foot3);
$commentlimit = intval($commentlimit);
$minpass = intval($minpass);
$pollcomm = intval($pollcomm);
$articlecomm = intval($articlecomm);
$my_headlines = intval($my_headlines);
$top = intval($top);
$storyhome = intval($storyhome);
$user_news = intval($user_news);
$oldnum = intval($oldnum);
$ultramode = intval($ultramode);
$banners = intval($banners);
$multilingual = intval($multilingual);
$useflags = intval($useflags);
$notify = intval($notify);
$moderate = intval($moderate);
$admingraphic = intval($admingraphic);
$httpref = intval($httpref);
$httprefmax = intval($httprefmax);
$domain = str_replace('http://', '', $nukeurl);
if(isset($default_Theme)) $Default_Theme = $default_Theme;
if (CAN_MOD_INI) ini_set('sendmail_from', $adminmail);

/*****[BEGIN]******************************************
 [ Base:     Evolution Functions               v1.5.0 ]
 ******************************************************/
$evoconfig = load_evoconfig();
$board_config = load_board_config();
/*****[END]********************************************
 [ Base:     Evolution Functions               v1.5.0 ]
 ******************************************************/
/*****[BEGIN]******************************************
 [ Mod:     Lock Modules                       v1.0.0 ]
 [ Mod:     Queries Count                      v2.0.0 ]
 [ Other:   SSL Administration                 v1.0.0 ]
 [ Base:    Censor                             v1.0.0 ]
 [ Base:    Caching System                     v3.0.0 ]
 [ Mod:     Color Toggle                       v1.0.0 ]
 [ Mod:     Lazy Google Tap                    v1.0.0 ]
 [ Base:    Switch Content Script              v2.0.0 ]
 ******************************************************/
$lock_modules = intval($evoconfig['lock_modules']);
$queries_count = intval($evoconfig['queries_count']);
$adminssl = intval($evoconfig['adminssl']);
$censor_words = $evoconfig['censor_words'];
$censor = intval($evoconfig['censor']);
$usrclearcache = intval($evoconfig['usrclearcache']);
$use_colors = intval($evoconfig['use_colors']);
$lazy_tap = intval($evoconfig['lazy_tap']);
$img_resize = intval($evoconfig['img_resize']);
$img_width = intval($evoconfig['img_width']);
$img_height = intval($evoconfig['img_height']);
$wysiwyg = $evoconfig['textarea'];
$capfile = $evoconfig['capfile'];
$collapse = intval($evoconfig['collapse']);
$collapsetype = intval($evoconfig['collapsetype']);
$module_collapse = intval($evoconfig['module_collapse']);
$evouserinfo_ec = intval($evoconfig['evouserinfo_ec']);
$analytics = $evoconfig['analytics'];

$more_js = '';
$more_styles = '';
/*****[END]********************************************
 [ Mod:     Lock Modules                       v1.0.0 ]
 [ Mod:     Queries Count                      v2.0.0 ]
 [ Other:   SSL Administration                 v1.0.0 ]
 [ Base:    Censor                             v1.0.0 ]
 [ Base:    Caching System                     v3.0.0 ]
 [ Mod:     Color Toggle                       v1.0.0 ]
 [ Mod:     Lazy Google Tap                    v1.0.0 ]
 [ Base:    Switch Content Script              v2.0.0 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Mod:     Lazy Google Tap                    v1.0.0 ]
 [ Base:    Theme Management                   v1.0.2 ]
 [ Base:    NukeSentinel                      v2.5.08 ]
 [ Mod:     Custom Text Area                   v1.0.0 ]
 ******************************************************/
require_once(NUKE_INCLUDE_DIR.'functions_browser.php');
require_once(NUKE_INCLUDE_DIR.'themes.php');
include_once(NUKE_INCLUDE_DIR.'functions_tap.php');
require_once(NUKE_CLASSES_DIR.'class.variables.php');
if (!defined('NO_SENTINEL')) {
    require_once(NUKE_INCLUDE_DIR.'nukesentinel.php');
}
include_once(NUKE_CLASSES_DIR.'class.wysiwyg.php');
/*****[END]********************************************
 [ Mod:     Lazy Google Tap                    v1.0.0 ]
 [ Base:    Theme Management                   v1.0.2 ]
 [ Base:    NukeSentinel                      v2.5.08 ]
 [ Mod:     Custom Text Area                   v1.0.0 ]
 ******************************************************/

if (file_exists(NUKE_INCLUDE_DIR.'custom_files/custom_mainfile.php')) {
    require_once(NUKE_INCLUDE_DIR.'custom_files/custom_mainfile.php');
}

if(!defined('FORUM_ADMIN') && !isset($ThemeSel) && !defined('RSS_FEED')) {
    $ThemeSel = get_theme();
    include_once(NUKE_THEMES_DIR . $ThemeSel . '/theme.php');
}

/*****[BEGIN]******************************************
 [ Base:    Admin File Check                   v3.0.0 ]
 ******************************************************/
if (!defined('FORUM_ADMIN')) {
    global $admin_file;
    if(!isset($admin_file) || empty($admin_file)) {
        die('You must set a value for $admin_file in config.php');
    } elseif (!empty($admin_file) && !file_exists(NUKE_BASE_DIR.$admin_file.'.php')) {
        die('The $admin_file you defined in config.php does not exist');
    }
}
/*****[END]********************************************
 [ Base:    Admin File Check                   v3.0.0 ]
 ******************************************************/

function define_once($constant, $value) {
    if(!defined($constant)) {
        define($constant, $value);
    }
}

function is_admin($trash=0) {
    static $adminstatus;
    if(isset($adminstatus)) return $adminstatus;
    $admincookie = isset($_COOKIE['admin']) ? $_COOKIE['admin'] : false;
    if (!$admincookie) { return $adminstatus = 0; }
    $admincookie = (!is_array($admincookie)) ? explode(':', base64_decode($admincookie)) : $admincookie;
    $aid = $admincookie[0];
    $pwd = $admincookie[1];
    $aid = substr(addslashes($aid), 0, 25);
    if (!empty($aid) && !empty($pwd)) {
        if (!function_exists('get_admin_field')) {
            global $db, $prefix;
            $pass = $db->sql_ufetchrow("SELECT `pwd` FROM `" . $prefix . "_authors` WHERE `aid` = '" .  str_replace("\'", "''", $aid) . "'", SQL_ASSOC);
            $pass = (isset($pass['pwd'])) ? $pass['pwd'] : '';
        } else {
            $pass = get_admin_field('pwd', $aid);
        }
        if ($pass == $pwd && !empty($pass)) {
            return $adminstatus = 1;
        }
    }
    return $adminstatus = 0;
}

function is_user($trash=0) {
    static $userstatus;
    if(isset($userstatus)) return $userstatus;
    $usercookie = isset($_COOKIE['user']) ? $_COOKIE['user'] : false;
    if (!$usercookie) { return $userstatus = 0; }
    $usercookie = (!is_array($usercookie)) ? explode(':', base64_decode($usercookie)) : $usercookie;
    $uid = $usercookie[0];
    $pwd = $usercookie[2];
    $uid = intval($uid);
    if (!empty($uid) AND !empty($pwd)) {
        $user_password = get_user_field('user_password', $uid);
        if ($user_password == $pwd && !empty($user_password)) {
            return $userstatus = 1;
        }
    }
    return $userstatus = 0;
}

function cookiedecode($trash=0) {
    global $cookie;
    static $rcookie;
    if(isset($rcookie)) { return $rcookie; }
    $usercookie = $_COOKIE['user'];
    $rcookie = (!is_array($usercookie)) ? explode(':', base64_decode($usercookie)) : $usercookie;
    $pass = get_user_field('user_password', $rcookie[1], true);
    if ($rcookie[2] == $pass && !empty($pass)) {
        return $cookie = $rcookie;
    }
    return false;
}

function title($text) {
    OpenTable();
    echo '<div class="title" style="text-align: center"><strong>'.$text.'</strong></div>';
    CloseTable();
    echo '<br />';
}

function is_active($module) {
    global $prefix, $db, $cache;
    static $active_modules;
    if (is_array($active_modules)) {
        return(isset($active_modules[$module]) ? 1 : 0);
    }
    if ((($active_modules = $cache->load('active_modules', 'config')) === false) || empty($active_modules)) {
        $active_modules = array();
        $result = $db->sql_query('SELECT `title` FROM `'.$prefix.'_modules` WHERE `active`="1"');
        while(list($title) = $db->sql_fetchrow($result, SQL_NUM)) {
            $active_modules[$title] = 1;
        }
        $db->sql_freeresult($result);
        $cache->save('active_modules', 'config', $active_modules);
    }
    return (isset($active_modules[$module]) ? 1 : 0);
}

function render_blocks($side, $block) {
    global $plus_minus_images, $currentlang, $collapse, $collapsetype;
    define_once('BLOCK_FILE', true);

    //Include the block lang files
    if (file_exists(NUKE_LANGUAGE_DIR.'blocks/lang-'.$currentlang.'.php')) {
        include_once(NUKE_LANGUAGE_DIR.'blocks/lang-'.$currentlang.'.php');
    } else {
        include_once(NUKE_LANGUAGE_DIR.'blocks/lang-english.php');
    }
/*****[BEGIN]******************************************
 [ Mod:     Switch Content Script              v2.0.0 ]
 ******************************************************/
    if($collapse) {
        if (!$collapsetype) {
            $block['title'] = $block['title'] . "&nbsp;&nbsp;&nbsp;<img src=\"".$plus_minus_images['minus']."\" class=\"showstate\" name=\"minus\" width=\"9\" height=\"9\" border=\"0\" onclick=\"expandcontent(this, 'block".$block['bid']."')\" alt=\"\" style=\"cursor: pointer;\" />";
        } else {
            $block['title'] = "<a href=\"javascript:expandcontent(this, 'block".$block['bid']."')\">".$block['title']."</a>";
        }
        $block['content'] = "<div id=\"block".$block['bid']."\" class=\"switchcontent\">".$block['content']."</div>";
    }
/*****[END]********************************************
 [ Mod:     Switch Content Script              v2.0.0 ]
 ******************************************************/
    if (empty($block['url'])) {
        if (empty($block['blockfile'])) {
            if ($side == 'c' || $side == 'd') {
                themecenterbox($block['title'], decode_bbcode($block['content'], 1, true));
            } else {
                themesidebox($block['title'], decode_bbcode($block['content'], 1, true), $block['bid']);
            }
        } else {
            blockfileinc($block['title'], $block['blockfile'], $side, $block['bid']);
        }
    } else {
        headlines($block['bid'], $side, $block);
    }
}

function blocks_visible($side) {
    global $showblocks;

    $showblocks = ($showblocks == null) ? 3 : $showblocks;

    $side = strtolower($side[0]);

    //If there are no blocks for this module && not admin file
    if (!$showblocks && !defined('ADMIN_FILE')) return false;

    //If in the admin show l blocks
    if (defined('ADMIN_FILE')) {
        return true;
    }

    //If set to 3 its all blocks
    if ($showblocks == 3) return true;

    //Count the blocks on the side
    $blocks = blocks($side, true);

    //If there are no blocks
    if (!$blocks) {
        return false;
    }

    //Check for blocks to show
    if (($showblocks == 1 && $side == 'l') || ($showblocks == 2 && $side == 'r')) {
        return true;
    }

    return false;
}

function blocks($side, $count=false) {
    global $prefix, $multilingual, $currentlang, $db, $userinfo, $cache;
    static $blocks;

    $querylang = ($multilingual) ? 'AND (`blanguage`="'.$currentlang.'" OR `blanguage`="")' : '';
    $side = strtolower($side[0]);
    if((($blocks = $cache->load('blocks', 'config')) === false) || !isset($blocks)) {
        $sql = 'SELECT * FROM `'.$prefix.'_blocks` WHERE `active`="1" '.$querylang.' ORDER BY `weight` ASC';
        $result = $db->sql_query($sql);
        while($row = $db->sql_fetchrow($result, SQL_ASSOC)) {
            $blocks[$row['bposition']][] = $row;
        }
        $db->sql_freeresult($result);
        $cache->save('blocks', 'config', $blocks);
    }
    if ($count) {
        return (isset($blocks[$side]) ? count($blocks[$side]) : 0);
    }
    $blockrow = (isset($blocks[$side])) ? $blocks[$side] : array();
    for($i=0,$j = count($blockrow); $i < $j; $i++) {
        $bid = intval($blockrow[$i]['bid']);
        $view = $blockrow[$i]['view'];
        if(isset($blockrow[$i]['expire'])) {
            $expire = intval($blockrow[$i]['expire']);
        } else {
            $expire = '';
        }
        if(isset($blockrow[$i]['action'])) {
            $action = $blockrow[$i]['action'];
            $action = substr($action, 0,1);
        } else {
            $action = '';
        }
        $now = time();
        if ($expire != 0 AND $expire <= $now) {
            if ($action == 'd') {
                $db->sql_query('UPDATE `'.$prefix.'_blocks` SET `active`="0", `expire`="0" WHERE `bid`="'.$bid.'"');
                $cache->delete('blocks', 'config');
                return;
            } elseif ($action == 'r') {
                $db->sql_query('DELETE FROM `'.$prefix.'_blocks` WHERE `bid`="'.$bid.'"');
                $cache->delete('blocks', 'config');
                return;
            }
        }
        if (empty($blockrow[$i]['bkey'])) {
            if ( ($view == '0' || $view == '1') ||
               ( ($view == '3' AND is_user()) ) ||
               ( $view == '4' AND is_admin()) ||
               ( ($view == '2' AND !is_user())) ) {
                render_blocks($side, $blockrow[$i]);
            } else {
                if (substr($view, strlen($view)-1) == '-') {
                    $ingroups = explode('-', $view);
                    if (is_array($ingroups)) {
                        foreach ($ingroups as $group) {
                            if (isset($userinfo['groups'][($group)])) {
                                render_blocks($side, $blockrow[$i]);
                            }
                        }
                    }
                }
            }
        }
    }
    return;
}

function blockfileinc($blockfiletitle, $blockfile, $side=1, $bid) {
    global $collapse;

    if (!file_exists(NUKE_BLOCKS_DIR.$blockfile)) {
        $content = _BLOCKPROBLEM;
    } else {
        include(NUKE_BLOCKS_DIR.$blockfile);
    }
    if (empty($content)) {
        $content = _BLOCKPROBLEM2;
    }
/*****[BEGIN]******************************************
 [ Mod:     Switch Content Script              v2.0.0 ]
 ******************************************************/
    if($collapse) {
        $content = "&nbsp;<div id=\"block".$bid."\" class=\"switchcontent\">".$content."</div>";
    }
/*****[END]********************************************
 [ Mod:     Switch Content Script              v2.0.0 ]
 ******************************************************/
    if ($side == 'r' || $side == 'l') {
        themesidebox($blockfiletitle, $content, $bid);
    } else {
        themecenterbox($blockfiletitle, $content);
    }
}

function rss_content($url) {
    if (!evo_site_up($url)) return false;
    require_once(NUKE_CLASSES_DIR.'class.rss.php');
    if ($rss = RSS::read($url)) {
        $items = $rss['items'];
        $site_link = $rss['link'];
        $content = '';
        for ($i=0,$j = count($items);$i  <$j;$i++) {
            $link = $items[$i]['link'];
            $title2 = $items[$i]['title'];
            $content .= "<strong><big>&middot;</big></strong> <a href=\"$link\" target=\"new\">$title2</a><br />\n";
        }
        if (!empty($site_link)) {
            $content .= "<br /><a href=\"$site_link\" target=\"_blank\" /><strong>"._HREADMORE.'</strong></a>';
        }
        return $content;
    }
    return false;
}

function headlines($bid, $side=0, $row='') {
    global $prefix, $db, $my_headlines, $cache;
    if(!$my_headlines) return;
    $bid = intval($bid);
    if (!is_array($row)) {
        $row = $db->sql_ufetchrow('SELECT `title`, `content`, `url`, `refresh`, `time` FROM `'.$prefix.'_blocks` WHERE `bid`='.$bid, SQL_ASSOC);
    }
    $content = trim($row['content']);
    if ($row['time'] < (time()-$row['refresh']) || empty($content)) {
        $content = rss_content($row['url']);
        $btime = time();
        $db->sql_query("UPDATE `".$prefix."_blocks` SET `content`='".Fix_Quotes($content)."', `time`='$btime' WHERE `bid`='$bid'");
        $cache->delete('blocks', 'config');
    }
    if (empty($content)) {
        $content = _RSSPROBLEM.' ('.$row['title'].')';
    }
    $content = '<span class="content" />'.$content.'</span>';
    if ($side == 'c' || $side == 'd') {
        themecenterbox($row['title'], $content);
    } else {
        themesidebox($row['title'], $content, $bid);
    }
}

function ultramode() {
    global $db, $prefix, $multilingual, $currentlang;
    $querylang = ($multilingual == 1) ? "AND (s.alanguage='".$currentlang."' OR s.alanguage='')" : "";
    $sql = "SELECT s.sid, s.catid, s.aid, s.title, s.time, s.hometext, s.comments, s.topic, s.ticon, t.topictext, t.topicimage FROM `".$prefix."_stories` s LEFT JOIN `".$prefix."_topics` t ON t.topicid = s.topic WHERE s.ihome = '0' ".$querylang." ORDER BY s.time DESC LIMIT 0,10";
    $result = $db->sql_query($sql);
    while ($row = $db->sql_fetchrow($result, SQL_ASSOC)) {
        $rsid = $row['sid'];
        $raid = $row['aid'];
        $rtitle = htmlspecialchars(stripslashes($row['title']));
        $rtime = $row['time'];
        $rcomments = $row['comments'];
        $topictext = $row['topictext'];
        $topicimage = ($row['ticon']) ? stripslashes($row['topicimage']) : '';
        $rtime = formatTimestamp($rtime, 'l, F d');
        $content .= "%%\n".$rtitle."\n/modules.php?name=News&file=article&sid=".$rsid."\n".$rtime."\n".$raid."\n".$topictext."\n".$rcomments."\n".$topicimage."\n";
    }
    $db->sql_freeresult($result);
    if (file_exists(NUKE_BASE_DIR."ultramode.txt") && is_writable(NUKE_BASE_DIR."ultramode.txt")) {
        $file = fopen(NUKE_BASE_DIR."ultramode.txt", "w");
        fwrite($file, "General purpose self-explanatory file with news headlines\n".$content);
        fclose($file);
    } else {
        global $debugger;
        $debugger->handle_error('Unable to write ultramode content to file', 'Error');
    }
}

// Adds slashes to string and strips PHP+HTML for SQL insertion and hack prevention
// $str: the string to modify
// $nohtml: strip PHP+HTML tags, false=no, true=yes, default=false
function Fix_Quotes($str, $nohtml=false) {
    global $db;
    //If there is not supposed to be HTML
    if ($nohtml) $str = strip_tags($str);
    // Quote if not integer
    /*if (!is_numeric($str)) {
        $str = str_replace('%27', "'", $str);
        $str = $db->sql_addq($str);
    }*/
    return $str;
}

function Remove_Slashes($str) {
    static $magic_quotes;
    if (!isset($magic_quotes)) $magic_quotes = get_magic_quotes_gpc();
    if ($magic_quotes) $str = stripslashes($str);
    return $str;
}

function check_words($message) {
    global $censor_words, $censor;
    if(empty($message)) {
        return '';
    }
    if($censor == 0 || empty($censor_words)) {
        return $message;
    }
    $censor_words = trim($censor_words);
    $words = explode(' ', $censor_words);
    for($i=0, $j = count($words);$i < $j;$i++){
        $word = trim($words[$i]);
        $replace = str_repeat('*', strlen($word));
        if($censor == 1) {
            $message = preg_replace('/\b'.$word.'\b/i', $replace, $message);
        } else if($censor == 2) {
            $message = preg_replace('/'.$word.'/i', $replace, $message);
        }
    }
    return $message;
}

function check_html($str, $strip='') {
/*****[BEGIN]******************************************
 [ Base:    PHP Input Filter                   v1.2.2 ]
 ******************************************************/
    if(defined('INPUT_FILTER')) {
        if ($strip == 'nohtml') {
            global $AllowableHTML;
        }
        if (!is_array($AllowableHTML)) {
            $html = '';
        } else {
            $html = '';
            foreach($AllowableHTML as $type => $key) {
                 if($key == 1) {
                   $html[] = $type;
                 }
            }
        }
        $html_filter = new InputFilter($html, "", 0, 0, 1);
        $str = $html_filter->process($str);
    } else {
/*****[END]********************************************
 [ Base:    PHP Input Filter                   v1.2.2 ]
 ******************************************************/
        $str = Fix_Quotes($str, !empty($strip));
/*****[BEGIN]******************************************
 [ Base:    PHP Input Filter                   v1.2.2 ]
 ******************************************************/
    }
/*****[END]********************************************
 [ Base:    PHP Input Filter                   v1.2.2 ]
 ******************************************************/
    return $str;
}

function filter_text($Message, $strip='') {
    $Message = check_words($Message);
    $Message = check_html($Message, $strip);
    return $Message;
}

function formatTimestamp($time, $format='') {
    global $datetime, $locale, $userinfo, $board_config;
    if (empty($format)) {
        if (isset($userinfo['user_dateformat']) && !empty($userinfo['user_dateformat'])) {
            $format = $userinfo['user_dateformat'];
        } else if (isset($board_config['default_dateformat']) && !empty($board_config['default_dateformat'])) {
            $format = $board_config['default_dateformat'];
        } else {
            $format = 'D M d, Y g:i a';
        }
    }
    if (isset($userinfo['user_timezone']) && !empty($userinfo['user_timezone'])) {
        $tz = $userinfo['user_timezone'];
    } else if (isset($board_config['board_timezone']) && !empty($board_config['board_timezone'])) {
        $tz = $board_config['board_timezone'];
    } else {
        $tz = '10';
    }
    setlocale(LC_TIME, $locale);
    if (!is_numeric($time)) {
        preg_match('/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/', $time, $datetime);
        $time = gmmktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]);
    }

    $datetime = EvoDate($format, $time, $tz);
    return $datetime;
}

function get_microtime() {
	//Cut's microtime by 1/3 this way
	if (PHP_5) {
		return microtime(true);
	} else {
	    list($usec, $sec) = explode(' ', microtime());
	    return ((float)$usec + (float)$sec);
	}
}

function get_author($aid) {
    global $user_prefix, $db;
    static $users;
    if (is_array($users[$aid])) {
        $row = $users[$aid];
    } else {
        $row = get_admin_field('*', $aid);
        $users[$aid] = $row;
    }
    $result = $db->sql_query('SELECT `user_id` from `'.$user_prefix.'_users` WHERE `username`="'.$aid.'"');
    $userid = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);
/*****[BEGIN]******************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
    if (isset($userid[0])) {
        $aid = "<a href=\"modules.php?name=Profile&amp;mode=viewprofile&amp;u=".$userid[0]."\">".UsernameColor($aid)."</a>";
    } else if (isset($row['url']) && $row['url'] != 'http://') {
        $aid = "<a href=\"".$row['url']."\">".UsernameColor($aid)."</a>";
    } else {
        $aid = UsernameColor($aid);
/*****[END]********************************************
 [ Mod:    Advanced Username Color             v1.0.5 ]
 ******************************************************/
    }
    return $aid;
}

if(!function_exists('themepreview')) {
    function themepreview($title, $hometext, $bodytext='', $notes='') {
        echo '<strong>'.$title.'</strong><br /><br />'.$hometext;
        if (!empty($bodytext)) {
            echo '<br /><br />'.$bodytext;
        }
        if (!empty($notes)) {
            echo '<br /><br /><strong>'._NOTE.'</strong> <i>'.$notes.'</i>';
        }
    }
}

if(!function_exists('themecenterbox')) {
    function themecenterbox($title, $content) {
        OpenTable();
        echo '<center><span class="option"><strong>'.$title.'</strong></span></center><br />'.$content;
        CloseTable();
        echo '<br />';
    }
}

function getTopics($s_sid) {
    global $prefix, $topicname, $topicimage, $topictext, $db;
    $sid = intval($s_sid);
    $result = $db->sql_query('SELECT t.topicname, t.topicimage, t.topictext FROM `'.$prefix.'_stories` s LEFT JOIN `'.$prefix.'_topics` t ON t.topicid = s.topic WHERE s.sid = "'.$sid.'"', SQL_ASSOC);
    $row = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);
    $topicname = $row['topicname'];
    $topicimage = $row['topicimage'];
    $topictext = stripslashes($row['topictext']);
}

/*****[BEGIN]******************************************
 [ Module:    Advertising                    v7.8.3.1 ]
 ******************************************************/
function ads($position) {
    global $prefix, $db, $sitename, $adminmail, $nukeurl, $banners;
    if(!$banners) { return ''; }
    $position = intval($position);
    $result = $db->sql_query("SELECT * FROM `".$prefix."_banner` WHERE `position`='$position' AND `active`='1' ORDER BY RAND() LIMIT 0,1");
    $numrows = $db->sql_numrows($result);
    if ($numrows < 1) return '';
    $row = $db->sql_fetchrow($result, SQL_ASSOC);
    $db->sql_freeresult($result);
    foreach($row as $var => $value) {
        if (isset($$var)) unset($$var);
        $$var = $value;
    }
    $bid = intval($bid);
    if(!is_admin()) {
        $db->sql_query("UPDATE `".$prefix."_banner` SET `impmade`=" . $impmade . "+1 WHERE `bid`='$bid'");
    }
    $sql2 = "SELECT `cid`, `imptotal`, `impmade`, `clicks`, `date`, `ad_class`, `ad_code`, `ad_width`, `ad_height` FROM `".$prefix."_banner` WHERE `bid`='$bid'";
    $result2 = $db->sql_query($sql2);
    list($cid, $imptotal, $impmade, $clicks, $date, $ad_class, $ad_code, $ad_width, $ad_height) = $db->sql_fetchrow($result2, SQL_NUM);
    $db->sql_freeresult($result2);
    $cid = intval($cid);
    $imptotal = intval($imptotal);
    $impmade = intval($impmade);
    $clicks = intval($clicks);
    /* Check if this impression is the last one and print the banner */
    if (($imptotal <= $impmade) && ($imptotal != 0)) {
        $db->sql_query("UPDATE `".$prefix."_banner` SET `active`='0' WHERE `bid`='$bid'");
        $sql3 = "SELECT `name`, `contact`, `email` FROM `".$prefix."_banner_clients` WHERE `cid`='$cid'";
        $result3 = $db->sql_query($sql3);
        list($c_name, $c_contact, $c_email) = $db->sql_fetchrow($result3, SQL_NUM);
        $db->sql_freeresult($result3);
        if (!empty($c_email)) {
            $from = $sitename.' <'.$adminmail.'>';
            $to = $c_contact.' <'.$c_email.'>';
            $message = _HELLO." $c_contact:\n\n";
            $message .= _THISISAUTOMATED."\n\n";
            $message .= _THERESULTS."\n\n";
            $message .= _TOTALIMPRESSIONS." $imptotal\n";
            $message .= _CLICKSRECEIVED." $clicks\n";
            $message .= _IMAGEURL." $imageurl\n";
            $message .= _CLICKURL." $clickurl\n";
            $message .= _ALTERNATETEXT." $alttext\n\n";
            $message .= _HOPEYOULIKED."\n\n";
            $message .= _THANKSUPPORT."\n\n";
            $message .= "- $sitename "._TEAM."\n";
            $message .= $nukeurl;
            $subject = $sitename.': '._BANNERSFINNISHED;
            $mailcommand = evo_mail($to, $subject, $message, "From: $from\nX-Mailer: PHP/" . PHPVERS);
            $mailcommand = removecrlf($mailcommand);
        }
    }
    if ($ad_class == "code") {
        $ad_code = stripslashes($ad_code);
        $ads = "<center>$ad_code</center>";
    } elseif ($ad_class == "flash") {
        $ads = "<center>"
              ."<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0\" width=\"".$ad_width."\" height=\"".$ad_height."\" id=\"$bid\">"
              ."<param name=\"movie\" value=\"".$imageurl."\" />"
              ."<param name=\"quality\" value=\"high\" />"
              ."<embed src=\"".$imageurl."\" quality=\"high\" width=\"".$ad_width."\" height=\"".$ad_height."\" name=\"".$bid."\" align=\"\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\"></embed></object>"
              ."</center>";
    } else {
        $ads = "<center><a href=\"index.php?op=ad_click&amp;bid=$bid\" target=\"_blank\"><img src=\"$imageurl\" border=\"0\" alt=\"$alttext\" title=\"$alttext\" /></a></center>";
    }
    return $ads;
}

function makePass() {
    $cons = 'bcdfghjklmnpqrstvwxyz';
    $vocs = 'aeiou';
    for ($x=0; $x < 6; $x++) {
        mt_srand ((double) microtime() * 1000000);
        $con[$x] = substr($cons, mt_rand(0, strlen($cons)-1), 1);
        $voc[$x] = substr($vocs, mt_rand(0, strlen($vocs)-1), 1);
    }
    mt_srand((double)microtime()*1000000);
    $num1 = mt_rand(0, 9);
    $num2 = mt_rand(0, 9);
    $makepass = $con[0] . $voc[0] .$con[2] . $num1 . $num2 . $con[3] . $voc[3] . $con[4];
    return $makepass;
}
/*****[END]********************************************
 [ Module:    Advertising                    v7.8.3.1 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Base:    Theme Management                   v1.0.2 ]
 [ Base:    Evolution Functions                v1.5.0 ]
 ******************************************************/
function get_theme() {
    static $ThemeSel;
    if (isset($ThemeSel)) return $ThemeSel;
    global $Default_Theme, $cookie;

    #Quick Theme Change - Theme Management (JeFFb68CAM)
    if(isset($_REQUEST['chngtheme']) && is_user()) {
        ChangeTheme($_REQUEST['theme'], $cookie[0]);
    }

    #Theme Preview Mod - Theme Management (JeFFb68CAM)
    if(isset($_REQUEST['tpreview']) && ThemeAllowed($_REQUEST['tpreview'])) {
        $ThemeSel = $_REQUEST['tpreview'];
        if(!is_user()) {
            setcookie('guest_theme', $ThemeSel, time()+84600);
        }
        return $ThemeSel;
    }

    #Theme Preview for guests Mod - Theme Management (JeFFb68CAM)
    if (isset($_COOKIE['guest_theme']) && !is_user()) {
        return (ThemeAllowed($_COOKIE['guest_theme']) ? $_COOKIE['guest_theme'] : $Default_Theme);
    }

    #New feature to grab a backup theme if the one we are trying to use does not exist, no more missing theme errors :)
    $ThemeSel = (ThemeAllowed($nTheme = (isset($cookie[9]) ? $cookie[9] : $Default_Theme))) ? $nTheme : ThemeBackup($nTheme);

    return $ThemeSel;
}
/*****[END]********************************************
 [ Base:    Theme Management                   v1.0.2 ]
 [ Base:    Evolution Functions                v1.5.0 ]
 ******************************************************/

// Function to translate Datestrings
function translate($phrase) {
    switch($phrase) {
        case'xdatestring': $tmp='%A, %B %d @ %T %Z'; break;
        case'linksdatestring': $tmp='%d-%b-%Y'; break;
        case'xdatestring2': $tmp='%A, %B %d'; break;
        default: $tmp=$phrase; break;
    }
    return $tmp;
}

function removecrlf($str) {
    return strtr($str, '\015\012', ' ');
}

function validate_mail($email) {
    if(strlen($email) < 7 || !preg_match('/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/', $email)) {
        DisplayError(_ERRORINVEMAIL);
        return false;
    } else {
        return $email;
    }
}

function encode_mail($email) {
    $finished = '';
    for($i=0, $j = strlen($email); $i<$j; ++$i) {
        $n = mt_rand(0, 1);
        $finished .= ($n) ? '&#x'.sprintf('%X',ord($email{$i})).';' : '&#'.ord($email{$i}).';';
    }
    return $finished;
}

/*****[BEGIN]******************************************
 [ Mod:    Advanced Username Color             v1.0.6 ]
 [ Base:   Evolution Functions                 v1.5.0 ]
 ******************************************************/
function UsernameColor($username, $old_name=false) {
    global $db, $user_prefix, $use_colors, $cache;
    static $cached_names;
    if($old_name) { $username = $old_name; }
    if(!$use_colors) return $username;
    $plain_username = strtolower($username);
    if(isset($cached_names[$plain_username])) {
        return $cached_names[$plain_username];
    }
    if(!is_array($cached_names)) {
        $cached_names = $cache->load('UserColors', 'config');
    }
    if (!isset($cached_names[$plain_username])) {
            list($user_color, $uname) = $db->sql_ufetchrow("SELECT `user_color_gc`, `username` FROM `" . $user_prefix . "_users` WHERE `username` = '" . str_replace("'", "\'", $username) . "'", SQL_NUM);
            $uname = (!empty($uname)) ? $uname : $username;
            $username = (strlen($user_color) == 6) ? '<span style="color: #'. $user_color .'"><strong>'. $uname .'</strong></span>' : $uname;
            $cached_names[$plain_username] = $username;
            $cache->save('UserColors', 'config', $cached_names);
    }
    return $cached_names[$plain_username];
}
/*****[END]********************************************
 [ Mod:    Advanced Username Color             v1.0.6 ]
 [ Base:   Evolution Functions                 v1.5.0 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Mod:    Group Colors                        v1.0.0 ]
 [ Base:   Evolution Functions                 v1.5.0 ]
 ******************************************************/
function GroupColor($group_name) {
    global $db, $prefix, $use_colors, $cache;
    static $new_groups = array(), $GroupColors;
    $plain_group_name = $group_name;
    if(!$use_colors) return $group_name;
    if(isset($new_groups[$plain_group_name])) {
        return $new_groups[$plain_group_name];
    }
    if ((($GroupColors = $cache->load('GroupColors', 'config')) === false) || !isset($cache)) {
        $GroupColors = array();
        $sql = "SELECT `group_color`, `group_name` FROM `" . $prefix . "_bbadvanced_username_color` ORDER BY `group_name` ASC";
        $result = $db->sql_query($sql, true);
        while ($row = $db->sql_fetchrow($result, SQL_ASSOC)) {
            $GroupColors[$row['group_name']] = $row['group_color'];
        }
        $db->sql_freeresult($result);
        $cache->save('GroupColors', 'config', $GroupColors);
    }
    $group_name = isset($GroupColors[$group_name]) ? (strlen($GroupColors[$group_name]) == 6) ? '<span style="color: #'. $GroupColors[$group_name] .'"><strong>'. $plain_group_name .'</strong></span>' : $plain_group_name : $plain_group_name;
    return $new_groups[$plain_group_name] = $group_name;
}
/*****[END]********************************************
 [ Mod:    Group Colors                        v1.0.0 ]
 [ Base:   Evolution Functions                 v1.5.0 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Mod:     NBBCode                           v9.21.0 ]
 ******************************************************/
include_once(NUKE_INCLUDE_DIR.'nbbcode.php');
/*****[END]********************************************
 [ Mod:     NBBCode                           v9.21.0 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Base:    Switch Content Script              v2.0.0 ]
 ******************************************************/
function get_plus_minus_image () {
    static $theme;
    static $image;
    if(isset($image) && is_array($image)) return $image;
    if(empty($theme)) {
        if(function_exists('get_theme')) {
            $theme = get_theme();
        }
    }
    $theme_folder = (!empty($theme)) ? ((defined(NUKE_THEMES_DIR)) ? NUKE_THEMES_DIR.$theme.'/images/' : dirname(__FILE__) . '/themes/'.$theme.'/images/') : '';
    $image['plus'] = (file_exists($theme_folder.'plus.gif')) ? 'themes/'.$theme.'/images/plus.gif' : 'images/plus.gif';
    $image['minus'] = (file_exists($theme_folder.'minus.gif')) ? 'themes/'.$theme.'/images/minus.gif' : 'images/minus.gif';
    return $image;
}
$plus_minus_images = get_plus_minus_image();
/*****[END]********************************************
 [ Base:    Switch Content Script              v2.0.0 ]
 ******************************************************/

/*****[BEGIN]******************************************
 [ Include:  Zip Class                                ]
 ******************************************************/
include_once(NUKE_CLASSES_DIR.'class.zip.php');
/*****[END]********************************************
 [ Include:  Zip Class                                ]
 ******************************************************/

referer();

?>
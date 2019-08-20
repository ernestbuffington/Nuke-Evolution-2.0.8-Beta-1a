<?php
/*=======================================================================
 Nuke-Evolution Basic: Enhanced PHP-Nuke Web Portal System
 =======================================================================*/

/************************************************************************
   Nuke-Evolution: Advanced Installer
   ============================================
   Copyright (c) 2010 by The Nuke-Evolution Team

   Filename       : install.php
   Author         : SgtLegend (www.darkforgegfx.com)
   Orginal Author : JeFFb68CAM (www.Evo-Mods.com)
   Version        : 1.0.1
   Date           : 19.04.2010 (mm.dd.yyyy)

   Notes          : You may NOT use this installer for your own
                    needs or script. It is written specifically
                    for Nuke-Evolution.
************************************************************************/

session_start();

include('install/functions.php');
include('includes/functions_selects.php');

$nuke_name = "Nuke Evolution (Basic)";
$sql_version = @mysql_get_server_info();

if (!isset($_SESSION['language']) || $_SESSION['language'] == 'english'){
    $_SESSION['language'] = (isset($_POST['language'])) ? $_POST['language'] : 'english';
}

if ($_SESSION['language']){
    if (is_file('install/language/lang_' . $_SESSION['language'] . '/lang-install.php')){
        include('install/language/lang_' . $_SESSION['language'] . '/lang-install.php');
    } else {
        include('install/language/lang_english/lang-install.php');
    }
}

$step = (isset($_REQUEST['step'])) ? $_REQUEST['step'] : 0;
if (!$step) $step = '1';
$total_steps = '9';
$next_step = $step+1;
$continue_button = '<input type="hidden" name="step" value="'.$next_step.'" /><input type="submit" class="button" name="submit" value="'.$install_lang['continue'].' '.$next_step.'" />';
check_required_files();
$safemodcheck = ini_get('safe_mod');

if ($safemodcheck == 'On' || $safemodcheck == 'on' || $safemodcheck == true){
    include('install/header.php');
    echo '<table id="menu" border="1" width="100%">';
    echo '  <tr>';
    echo '    <th id="rowHeading" align="center">'.$nuke_name.' '.$install_lang['installer_heading'].' '.$install_lang['failed'].'</th>';
    echo '  </tr>';
    echo '  <tr>';
    echo '    <td align="center"><span style="color: #ff0000;"><strong>'.$install_lang['safe_mode'].'</strong></span></td>';
    echo '  </tr>';
    echo '</table>';
    include('install/footer.php');
    exit;
}

if (isset($_POST['download_file']) && !empty($_SESSION['configData']) && !$_POST['continue']){
    header("Content-Type: text/x-delimtext; name=config.php");
    header("Content-disposition: attachment; filename=config.php");
    $configData = $_SESSION['configData'];
    echo $configData;
    exit;
}

if ($step >= 5){
    if (!mysql_connect($_SESSION['dbhost'], $_SESSION['dbuser'], $_SESSION['dbpass'])){
        die ($install_lang['couldnt_connect'] . mysql_error());
    }
    if (!mysql_select_db($_SESSION['dbname'])){
        die ($install_lang['couldnt_select_db'] . mysql_error());
    }
}

if ($step == 1){
    include('install/header.php');
    $lang_select = language_select('english', "language", dirname(__FILE__) . '/install/language');
    echo '<form action="" method="post">';
    echo '<center><strong>'.$nuke_name.' '.$install_lang['installer_heading'].' '.$step.' '.$install_lang['installer_heading2'].' '.$total_steps.'</strong></center>';
	echo '<fieldset><legend>'.$install_lang['lang_stitle'].'</legend>';
	echo '  <dl>';
	echo '    <dt><label>'.$install_lang['lang_select'].'</label></dt>';
	echo '    <dd>'.$lang_select.'</dd>';
	echo '  </dl>';
	echo '</fieldset>';
	echo '<center>'.$continue_button.'</center>';
	echo '</form>';
    include('install/footer.php');
} elseif ($step == 2){
    include('install/header.php');
	$sub_step = (isset($_POST['sub_step'])) ? $_POST['sub_step'] : '';
	$root_path = dirname(__FILE__);
	if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
		$root_path = str_replace('\\', '/', $root_path);
	}
	if (!function_exists('posix_getpwuid') || strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
		define('_FILE_OWNER', 'N/A');
		define('_PROCESS_UID', 0);
		define('_PROCESS_OWNER', 'N/A');
	} else {
		define('_FILE_OWNER', get_current_user());
		define('_PROCESS_UID', posix_geteuid());
		$processUser = posix_getpwuid(_PROCESS_UID);
		define('_PROCESS_OWNER', $processUser['name']);
		unset($processUser);
	}
	if (!$sub_step){
		echo '<form action="" method="post">';
		echo '<center><strong>'.$nuke_name.' '.$install_lang['installer_heading'].' '.$step.' '.$install_lang['installer_heading2'].' '.$total_steps.'</strong></center>';
		echo '<fieldset><legend>'.$install_lang['server_title'].'</legend>';
		echo '  <dl>';
		echo '    <dt><label>'.$install_lang['os'].'</label></dt>';
		echo '    <dd>'.PHP_OS.'</dd>';
		echo '  </dl>';
		echo '  <dl>';
		echo '    <dt><label>'.$install_lang['po'].'</label></dt>';
		echo '    <dd>'._PROCESS_OWNER.' ('._PROCESS_UID.')</dd>';
		echo '  </dl>';
		echo '  <dl>';
		echo '    <dt><label>'.$install_lang['fo'].'</label></dt>';
		echo '    <dd>'._FILE_OWNER.' ('.getmyuid().')</dd>';
		echo '  </dl>';
		echo '  <dl>';
		echo '    <dt><label>'.$install_lang['rp'].'</label></dt>';
		echo '    <dd>'.$root_path.'</dd>';
		echo '  </dl>';
		echo '  <dl>';
		echo '    <dt><label>'.$install_lang['rtp'].'</label></dt>';
		echo '    <dd>'.permissions($root_path).'</dd>';
		echo '  </dl>';
		echo '  <dl>';
		echo '    <dt><label>'.$install_lang['interface'].'</label></dt>';
		ob_start();
		echo php_sapi_name();
		$php_sapi_name = ob_get_clean();
		echo '    <dd>'.$php_sapi_name.'</dd>';
		echo '  </dl>';
		echo '  <dl>';
		echo '    <dt><label>'.$install_lang['openbasedir'].'</label></dt>';
		ob_start();
		echo ini_get('open_basedir');
		$basedir = ob_get_clean();
		echo '    <dd>'.(!empty($basedir) ? $basedir : '&nbsp;').'</dd>';
		echo '  </dl>';
		echo '  <dl>';
		echo '    <dt><label>'.$install_lang['safemode'].'</label></dt>';
		ob_start();
		echo ini_get('safe_mode');
		$safemode = ob_get_clean();
		echo '    <dd>'.(($safemode) ? 'enabled' : 'disabled').'</dd>';
		echo '  </dl>';
		echo '  <dl>';
		echo '    <dt><label>'.$install_lang['safemodegid'].'</label></dt>';
		ob_start();
		echo ini_get('safe_mode_gid');
		$safemodegid = ob_get_clean();
		echo '    <dd>'.(($safemodegid) ? 'enabled' : 'disabled').'</dd>';
		echo '  </dl>';
		echo '  <dl>';
		echo '    <dt><label>'.$install_lang['safemodeexecdir'].'</label></dt>';
		ob_start();
		echo ini_get('safe_mode_exec_dir');
		$safemodeexec = ob_get_clean();
		echo '    <dd>'.(!empty($safemodeexec) ? $safemodeexec : '&nbsp;').'</dd>';
		echo '  </dl>';
		echo '  <dl>';
		echo '    <dt><label>'.$install_lang['safemodeincludedir'].'</label></dt>';
		ob_start();
		echo ini_get('safe_mode_exec_dir');
		$safemodeinc = ob_get_clean();
		echo '    <dd>'.(!empty($safemodeinc) ? $safemodeinc : '&nbsp;').'</dd>';
		echo '  </dl>';
		echo '  <dl>';
		echo '    <dt><label>'.$install_lang['disablefunctions'].'</label></dt>';
		ob_start();
		echo ini_get('disable_functions');
		$disable_functions = ob_get_clean();
		echo '    <dd>'.(!empty($disable_functions) ? $disable_functions : '&nbsp;').'</dd>';
		echo '  </dl>';
		echo '</fieldset>';
		echo '<center><input type="hidden" name="step" value="2" /><input type="hidden" name="sub_step" value="1" /><br /><input type="submit" class="button" value="'.$install_lang['next_step'].'" /></center>';
		echo '</form>';
	} elseif ($sub_step == 1){
		echo '<form action="" method="post">';
		echo '<center><strong>'.$nuke_name.' '.$install_lang['installer_heading'].' '.$step.' '.$install_lang['installer_heading2'].' '.$total_steps.'</strong></center>';
		echo '<fieldset><legend>'.$install_lang['server_title'].'</legend>';
		require('install/file.inc');
		echo '<center><input type="hidden" name="step" value="2" /><input type="hidden" name="sub_step" value="2" /><br /><input type="submit" class="button" value="'.$install_lang['next_step'].'" /></center>';
		echo '</fieldset>';
		echo '</form>';
	} elseif ($sub_step == 2){
		echo '<form action="" method="post" enctype="multipart/form-data" accept-charset="utf-8">';
		echo '<center><strong>'.$nuke_name.' '.$install_lang['installer_heading'].' '.$step.' '.$install_lang['installer_heading2'].' '.$total_steps.'</strong></center>';
		echo '<fieldset><legend>'.$install_lang['server_title'].'</legend>';
		echo '  <dl>';
		echo '    <dt><label>'.$install_lang['file_uploads'].'</label></dt>';
		ob_start();
		echo ini_get('file_uploads');
		$file_uploads = ob_get_clean();
		echo '    <dd>'.(!empty($file_uploads) ? 'enabled' : 'disabled').'</dd>';
		echo '  </dl>';
		echo '  <dl>';
		echo '    <dt><label>'.$install_lang['upload_tmp_dir'].'</label></dt>';
		ob_start();
		echo ini_get('upload_tmp_dir');
		$upload_tmp_dir = ob_get_clean();
		echo '    <dd>'.(!empty($upload_tmp_dir) ? $upload_tmp_dir : '&nbsp;').'</dd>';
		echo '  </dl>';
		echo '  <dl>';
		echo '    <dt><label>'.$install_lang['upload_max_filesize'].'</label></dt>';
		ob_start();
		echo ini_get('upload_max_filesize');
		$upload_max_filesize = ob_get_clean();
		echo '    <dd>'.(!empty($upload_max_filesize) ? $upload_max_filesize : '&nbsp;').'</dd>';
		echo '  </dl>';
		if (ini_get('file_uploads')){
			echo '  <dl>';
			echo '    <dt><label>'.$install_lang['upload_file'].'</label></dt>';
			echo '    <dd><input type="file" name="testfile" size="50" /></dd>';
			echo '  </dl>';
		}
		echo '</fieldset>';
		echo '<center><input type="hidden" name="step" value="2" /><input type="hidden" name="sub_step" value="3" /><br /><input type="submit" class="button" value="'.$install_lang['next_step'].'" /></center>';
		echo '</form>';
	} elseif ($sub_step == 3){
		echo '<form action="" method="post" enctype="multipart/form-data" accept-charset="utf-8">';
		echo '<center><strong>'.$nuke_name.' '.$install_lang['installer_heading'].' '.$step.' '.$install_lang['installer_heading2'].' '.$total_steps.'</strong></center>';
		echo '<fieldset><legend>'.$install_lang['server_title'].'</legend>';
		require('install/upload.inc');
		echo '</fieldset>';
		echo '<center><input type="hidden" name="step" value="'.$next_step.'" />'.$continue_button.'</center>';
		echo '</form>';
	}
    include('install/footer.php');
} elseif ($step == 3){
    include('install/header.php');
    echo '<form action="" method="post">';
    echo '<center><strong>'.$nuke_name.' '.$install_lang['installer_heading'].' '.$step.' '.$install_lang['installer_heading2'].' '.$total_steps.'</strong></center>';
	echo '<fieldset><legend>'.$install_lang['chmod_check'].'</legend>';
	echo '<div style="text-align: left;">';
    echo chmod_files();
	echo '</div>';
	echo '</fieldset>';
	echo '<center>'.$continue_button.'</center>';
    echo '</form>';
    include('install/footer.php');
} elseif ($step == 4){
    include('install/header.php');
	$confirm = (isset($_POST['confirm'])) ? $_POST['confirm'] : '';
    if (!$confirm){
		echo '<form action="" method="post">';
		echo '<center><strong>'.$nuke_name.' '.$install_lang['installer_heading'].' '.$step.' '.$install_lang['installer_heading2'].' '.$total_steps.'</strong></center>';
		echo '<fieldset><legend>'.$install_lang['mysql_info'].'</legend>';
		echo '  <dl>';
		echo '    <dt><label>'.$install_lang['dbhost'].'</label></dt>';
		echo '    <dd><input type="text" value="localhost" name="dbhost" size="60" class="input" /></dd>';
		echo '  </dl>';
		echo '  <dl>';
		echo '    <dt><label>'.$install_lang['dbname'].'</label></dt>';
		echo '    <dd><input type="text" name="dbname" size="60" class="input" /></dd>';
		echo '  </dl>';
		echo '  <dl>';
		echo '    <dt><label>'.$install_lang['dbuser'].'</label></dt>';
		echo '    <dd><input type="text" name="dbuser" size="60" class="input" /></dd>';
		echo '  </dl>';
		echo '  <dl>';
		echo '    <dt><label>'.$install_lang['dbpass'].'</label></dt>';
		echo '    <dd><input type="password" name="dbpass" size="60" class="input" /></dd>';
		echo '  </dl>';
		echo '  <dl>';
		echo '    <dt><label>'.$install_lang['prefix'].'</label></dt>';
		echo '    <dd><input type="text" value="nuke" name="prefix" size="60" class="input" /></dd>';
		echo '  </dl>';
		echo '  <dl>';
		echo '    <dt><label>'.$install_lang['user_prefix'].'</label></dt>';
		echo '    <dd><input type="text" value="nuke" name="user_prefix" size="60" class="input" /></dd>';
		echo '  </dl>';
		echo '  <dl>';
		echo '    <dt><label>'.$install_lang['dbtype'].'</label></dt>';
		echo '    <dd><select size="1" name="dbtype">';
		// Quake fix for db type selection
		$handle = opendir('includes/db');
		while(false !== ($file = readdir($handle))){
			if (preg_match('/(.*?)\.php/i', $file, $database)){
				if (strtolower($database[1]) != 'db' && strtolower($database[1]) != 'db-old'){
					echo '<option value="'.strtolower($database[1]).'">'.ucfirst($database[1]).'</option>';
				}
			}
		}
		closedir($handle);
		echo '    </select></dd>';
		echo '  </dl>';
		echo '</fieldset>';
		echo '<center><input type="hidden" name="step" value="'.$step.'"><input type="submit" class="button" name="confirm" value="'.$install_lang['confirm_data'].'"></center>';
		echo '</form>';
	} else {
		echo '<form action="" method="post">';
		echo '<center><strong>'.$nuke_name.' '.$install_lang['installer_heading'].' '.$step.' '.$install_lang['installer_heading2'] . " ".$total_steps.'</strong></center>';
		echo '<fieldset><legend>'.$install_lang['mysql_check'].'</legend>';
		echo '<div style="text-align: left;">';
        echo validate_data($_POST);
		echo '</div>';
		echo '</fieldset>';
		echo '</form>';
	}
    include('install/footer.php');
} elseif ($step == 5){
    include('install/header.php');
    echo '<form action="" method="post">';
    echo '<center><strong>'.$nuke_name.' '.$install_lang['installer_heading'].' '.$step.' '.$install_lang['installer_heading2'].' '.$total_steps.'</strong></center>';
	echo '<fieldset><legend>'.$install_lang['sql_install'].'</legend>';
	echo '<div style="text-align: left;">';
    echo do_sql('install/install.sql');
	echo '</div>';
	echo '</fieldset>';
    echo '</form>';
    include('install/footer.php');
} elseif ($step == 6){
	$ret = validate_admin();
    include('install/header.php');
	$confirm = (isset($_POST['confirm'])) ? $_POST['confirm'] : '';
    if (!$confirm){
		echo '<form action="" method="post">';
		echo '<center><strong>'.$nuke_name.' '.$install_lang['installer_heading'].' '.$step.' '.$install_lang['installer_heading2'].' '.$total_steps.'</strong></center>';
		echo '<fieldset><legend>'.$install_lang['setup_admin'].'</legend>';
		echo '  <dl>';
		echo '    <dt><label>'.$install_lang['admin_nick'].'</label></dt>';
		echo '    <dd><input type="text" name="admin_nick" size="40" class="input" /></dd>';
		echo '  </dl>';
		echo '  <dl>';
		echo '    <dt><label>'.$install_lang['admin_pass'].'</label></dt>';
		echo '    <dd><input type="password" name="admin_pass" size="40" class="input" /></dd>';
		echo '  </dl>';
		echo '  <dl>';
		echo '    <dt><label>'.$install_lang['admin_cpass'].'</label></dt>';
		echo '    <dd><input type="password" name="admin_cpass" size="40" class="input" /></dd>';
		echo '  </dl>';
		echo '  <dl>';
		echo '    <dt><label>'.$install_lang['admin_email'].'</label></dt>';
		echo '    <dd><input type="text" name="admin_email" size="40" class="input" /></dd>';
		echo '  </dl>';
		echo '  <dl>';
		echo '    <dt><label>'.$install_lang['admin_web'].'</label></dt>';
		echo '    <dd><input type="text" name="admin_website" size="40" class="input" value="http://" /></dd>';
		echo '  </dl>';
		echo '</fieldset>';
		echo '<center><input type="hidden" name="step" value="'.$step.'"><input type="submit" class="button" name="confirm" value="'.$install_lang['confirm_data'].'"></center>';
		echo '</form>';
	} else {
		echo '<form action="" method="post">';
		echo '<center><strong>'.$nuke_name.' '.$install_lang['installer_heading'].' '.$step.' '.$install_lang['installer_heading2'] . " ".$total_steps.'</strong></center>';
		echo '<fieldset><legend>'.$install_lang['admin_check'].'</legend>';
		echo '<div style="text-align: left;">';
        echo $ret;
		echo '</div>';
		echo '</fieldset>';
		echo '</form>';
	}
    include('install/footer.php');
} elseif ($step == 7){
	$return = (isset($_POST['return'])) ? true : false;
    include('install/header.php');
    echo '<form action="" method="post">';
	echo '<center><strong>'.$nuke_name.' '.$install_lang['installer_heading'].' '.$step.' '.$install_lang['installer_heading2'].' '.$total_steps.'</strong></center>';
	echo '<fieldset><legend>'.$install_lang['setup_config'].'</legend>';
    echo site_form(0,$return);
	echo '</fieldset>';
	echo '<center><input type="hidden" name="step" value="'.$next_step.'" />'.$continue_button.'</center>';
	echo '</form>';
    include('install/footer.php');
} elseif ($step == 8){
    include('install/header.php');
	echo '<center><strong>'.$nuke_name.' '.$install_lang['installer_heading'].' '.$step.' '.$install_lang['installer_heading2'].' '.$total_steps.'</strong></center>';
	echo '<fieldset><legend>'.$install_lang['setup_overview'].'</legend>';
    echo site_form(0);
	echo '</fieldset>';
	echo '<center><form action="" method="post">';
	echo '<input type="hidden" name="step" value="'.$next_step.'" />'.$continue_button;
	echo '</form>';
	echo '<form action="" method="post">';
	echo '<input type="hidden" name="step" value="7" /><br /><input type="submit" name="return" class="button" value="'.$install_lang['return_setup'].'" />';
	echo '</form></center>';
    include('install/footer.php');
} elseif ($step == 9){
    include('install/header.php');
	echo '<center><strong>'.$nuke_name.' '.$install_lang['installer_heading'].' '.$step.' '.$install_lang['installer_heading2'].' '.$total_steps.'</strong></center>';
	echo '<fieldset><legend>'.$install_lang['finish_install'].'</legend>';
	echo '<div style="text-align: center;">';
    echo $install_lang['done'].'<br /><br />'.$install_lang['delete'];
	echo '</div>';
	echo '</fieldset>';
    include('install/footer.php');
}

?>
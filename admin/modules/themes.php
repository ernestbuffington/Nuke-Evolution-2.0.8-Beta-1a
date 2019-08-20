<?php
/*=======================================================================
 Nuke-Evolution Basic: Enhanced PHP-Nuke Web Portal System
 =======================================================================*/

/************************************************************************
   Nuke-Evolution: Theme Management
   ============================================
   Copyright (c) 2005 by The Nuke-Evolution Team

   Filename      : themes.php
   Author        : JeFFb68CAM (www.Evo-Mods.com)
   Version       : 1.0.2
   Date          : 11.27.2005 (mm.dd.yyyy)

   Notes         : Allows admin to easily manage themes.
************************************************************************/

/*****[CHANGES]**********************************************************
-=[Base]=-
      Nuke Patched                             v3.1.0       06/26/2005
 ************************************************************************/

if (!defined('ADMIN_FILE')) {
    die ("Illegal File Access");
}

global $prefix, $db;

require_once(NUKE_CLASSES_DIR.'class.paginator.php');

function theme_header() {
    global $admin_file;
    OpenTable();
        echo "<center>"
        ."<a href=\"$admin_file.php?op=themes\">" . _THEMES_HEADER . "</a>"
        ."<br /><br />"
        ."<table border='0' width='70%'><tr><td>"
        ."<img src='images/evo/ok.png' alt='' width='10' height='10' /></td><td>"
        ."<i>" . _THEMES_DEFAULT . "</i></td><td>" . get_default() . "</td>"
        ."</tr><tr><td>"
        ."<img src='images/evo/ok.png' alt='' width='10' height='10' /></td><td>"
        ."<i>" . _THEMES_NUMTHEMES . "</i></td><td>" . count(get_themes('all')) . "</td>"
        ."</tr><tr><td>"
        ."<img src='images/evo/ok.png' alt='' width='10' height='10' /></td><td>"
        ."<i>" . _THEMES_NUMUNINSTALLED . "</i></td><td>" . count(get_themes('uninstalled')) . "</td>"
        ."</tr>"
        ."<tr><td>"
        ."<img src='images/evo/ok.png' alt='' width='10' height='10' /></td><td>"
        ."<i>" . _THEMES_MOSTPOPULAR . "</i></td><td>" . ThemeMostPopular() . "</td>"
        ."</tr>"
        ."</table>"
        ."<br />"
        ."[ <a href=\"$admin_file.php?op=theme_users\">" . _THEMES_USER_OPTIONS . "</a> | <a href=\"$admin_file.php?op=theme_options\">" . _THEMES_OPTIONS . "</a> | <a href=\"$admin_file.php\">" . _THEMES_RETURNMAIN . "</a> ]"
        ."</center>";
    CloseTable();
    echo "<br />";
}

function InstallTheme(){
	global $admin_file, $db, $prefix, $module_name, $userinfo, $HTTP_POST_FILES, $HTTP_POST_VARS;
	
	$AllowedExtensions = array('zip');
	$path_parts = pathinfo($HTTP_POST_FILES['file']['name']);
	$extension  = $path_parts['extension'];
	$extension  = strtolower($extension);
	$filename = $HTTP_POST_FILES['file']['name'];
	if(in_array($extension, $AllowedExtensions)){
		$theme_name_check = substr($filename, 0, -4);
		if(!file_exists('themes/'. $theme_name_check)){
			if( move_uploaded_file($HTTP_POST_FILES['file']['tmp_name'], 'themes/'.$HTTP_POST_FILES['file']['name'])){
				$archive = new PclZip('themes/'.$filename);
				if (($list = $archive->listContent()) == 0){
					die("Error : ".$archive->errorInfo(true));
				}
				$valid = false;
				foreach($list as $id => $file){
					if (strpos($file['filename'], 'theme_info.php') !== false){
						$valid = true;
					}
				}
				if (!$valid){	
					@unlink('themes/'.$HTTP_POST_FILES['file']['name']);
					die("Error : Not a valid theme zip.");
				}
				if ($archive->extract(PCLZIP_OPT_PATH, 'themes/') == 0){
					die("Error : ".$archive->errorInfo(true));
				}
				if ($valid == TRUE){
					@unlink('themes/'.$HTTP_POST_FILES['file']['name']);			
					$theme = substr($filename, 0, -4);
					redirect($admin_file.'.php?op=theme_quickinstall&amp;theme=' . $theme);
				}		
			}
		} else {
			die('Error: Theme already exists in databse.');
		}
	}
}

function downloadTheme($theme){
	global $admin_file, $aid, $db, $prefix, $module_name, $userinfo, $admin;
	
	if (is_god($admin)){
		$themezip = NUKE_THEMES_DIR.$theme.".zip";
		$themedir = NUKE_THEMES_DIR.$theme."/";
		$downloadarchive = new PclZip($themezip);
		$v_list = $downloadarchive->create($themedir, PCLZIP_OPT_REMOVE_PATH, NUKE_THEMES_DIR);
		if ($v_list == 0) {
			die("Error : ".$downloadarchive->errorInfo(true));
		}
		//  This is where the code goes to download the archive.
		redirect("themes/".$theme.".zip");
	} else {
		redirect($admin_file.'.php?op=themes');
	}
}

function theme_footer() {
    echo "<br />";
    OpenTable();
    echo "<table border='0' width='100%'><tr><td align='right'>";
    echo "&copy; The <a href='http://nuke-evolution.com' target='_blank'>Nuke-Evolution</a> Team<br />Theme Management by: <a href='http://evo-mods.com' target='_blank'>JeFFb68CAM</a>";
    echo "</td></tr></table>";
    CloseTable();
}

function display_main() {
    global $admin_file, $aid, $db, $prefix, $bgcolor2, $bgcolor1, $bgcolor3, $admin;
    $installed_themes = get_themes('all');
    $uninstalled_themes = get_themes('uninstalled');
	$extract = '';

    function make_a_row($theme) {
        global $admin_file, $bgcolor2, $bgcolor1, $bgcolor3, $db, $prefix, $user_prefix, $admin;

        if (preg_match('/^['._THEMES_THEME_MISSING.'&#_]+$/i',  ThemeGetStatus($theme['theme_name'], $theme['active']))) {
            if ($db->sql_query("DELETE FROM " . $prefix . "_themes WHERE theme_name = '".$theme['theme_name']."'")) {
                $db->sql_query("UPDATE " . $user_prefix . "_users SET theme = '" . get_default() . "' WHERE theme = '".$theme['theme_name']."'");
            }
            return ;
        }

        $bold = (is_default($theme['theme_name'])) ? " style='font-weight: bold;'" : "";
		if (is_god($admin)){
			$extract = "<a href=\"$admin_file.php?op=downloadTheme&amp; theme=".$theme['theme_name'] ."\" title=\"" . _DOWNLOAD_FILES. "\"><img src=\"images/zip.gif\" alt=\"\" title=\"" . _DOWNLOAD_FILES. "\" border =\"0\" width=\"16\" height=\"16\" /></a> ";
        }
        $theme_edit = "<img src=\"images/edit.png\" alt=\"\" title=\""._THEMES_EDIT."\" border=\"0\" width=\"16\" height=\"16\" />";
        $default_link = (is_default($theme['theme_name']) || !theme_exists($theme['theme_name'])) ? "<img src=\"images/default.png\" alt=\"\" title=\""._THEMES_MAKEDEFAULT."\" border=\"0\" width=\"16\" height=\"16\" />" : "<a href=\"$admin_file.php?op=theme_makedefault&amp;theme=" . $theme['theme_name'] . "\"><img src=\"images/default.png\" alt=\"\" title=\""._THEMES_MAKEDEFAULT."\" border=\"0\" width=\"16\" height=\"16\" /></a>";
        $activate_link = (is_default($theme['theme_name'])) ? "<img src=\"images/deactive.png\" alt=\"\" title=\""._THEMES_DEACTIVATE."\" border=\"0\" width=\"16\" height=\"16\" />" : ((ThemeIsActive($theme['theme_name'], true)) ? "<a href=\"$admin_file.php?op=theme_deactivate&amp;theme=" . $theme['theme_name'] . "\"><img src=\"images/deactive.png\" alt=\"\" title=\""._THEMES_DEACTIVATE."\" border=\"0\" width=\"16\" height=\"16\" /></a>" : "<a href=\"$admin_file.php?op=theme_activate&amp;theme=" . $theme['theme_name'] . "\"><img src=\"images/enable.png\" alt=\"\" title=\""._THEMES_ACTIVATE."\" border=\"0\" width=\"16\" height=\"16\" /></a>");
        if($theme['permissions'] == 1) {
            $permissions = _THEMES_ALLUSERS;
        } elseif ($theme['permissions'] == 2) {
            $permissions = _THEMES_GROUPSONLY;
        } elseif ($theme['permissions'] == 3) {
            $permissions = _THEMES_ADMINS;
        }
        echo "<tr $bold>\n";
        echo "  <td align='center' class='row1'><img src='themes/".$theme['theme_name']."/images/thumb.png' alt='' /></td>\n";
        echo "  <td align='center' class='row1'>" . $theme['theme_name'] . "</td>\n";
        echo "  <td align='center' class='row1'>" . $theme['custom_name'] . "</td>\n";
        echo "  <td align='center' class='row1'>" . ThemeNumUsers($theme['theme_name']) . "</td>\n";
        echo "  <td align='center' class='row1'>" . ThemeGetStatus($theme['theme_name'], $theme['active']) . "</td>\n";
        echo "  <td align='center' class='row1'>" . $permissions . "</td>\n";
        echo "  <td align='center' class='row1'><small>".$extract."<a href=\"$admin_file.php?op=theme_edit&amp;theme=" . $theme['theme_name'] . "\">" . $theme_edit . "</a> " . $default_link . " " . $activate_link . " <a href=\"index.php?tpreview=" . $theme['theme_name'] . "\"><img src=\"images/preview.png\" alt=\""._THEMES_VIEW."\" title=\""._THEMES_VIEW."\" border=\"0\" width=\"16\" height=\"16\" /></a> <a href=\"$admin_file.php?op=theme_uninstall&amp;theme=" .$theme['theme_name'] . "\"><img src=\"images/delete.png\" alt=\""._THEMES_UNINSTALL." ".$theme['theme_name']."\" title=\""._THEMES_UNINSTALL." ".$theme['theme_name']."\" border=\"0\" width=\"16\" height=\"16\" /></a></small></td>\n";
        echo "</tr>\n";
    }


    function CategoryOpen($text, $data) {
        global $bgcolor3;
        echo "<table border='0' align='center' width='99%' class='bodyline'>\n";
        echo "  <tr>";
        echo "    <th height='20' width='100%' align='center'><strong>$text</strong></th>\n";
        echo "  </tr>\n";
        if (count($data) == 0) {
            echo "<tr>\n";
			echo "  <td width='100%' class='row1' align='center'><strong>" . _THEMES_NONE . "</strong></td>\n";
			echo "</tr>\n";
        }
    }
    function CategoryClose() {
        echo  "</table>\n";
    }

    OpenTable();
        echo "<table border='0' align='center' width='99%' cellpadding='4' cellspacing='1' class='forumline'>\n";
        echo "  <tr>\n";
        echo "    <th width='18%' align='center'><span class=\"content\"><strong>" . _THEMES_PREVIEW . "</strong></span></th>\n";
        echo "    <th width='15%' align='center'><span class=\"content\"><strong>" . _THEMES_NAME . "</strong></span></th>\n";
        echo "    <th width='15%' align='center'><span class=\"content\"><strong>" . _THEMES_CUSTOMN . "</strong></span></th>\n";
        echo "    <th width='10%' align='center'><span class=\"content\"><strong>" . _THEMES_NUMUSERS . "</strong></span></th>\n";
        echo "    <th width='10%' align='center'><span class=\"content\"><strong>" . _THEMES_STATUS . "</strong></span></th>\n";
        echo "    <th width='15%' align='center'><span class=\"content\"><strong>" . _THEMES_PERMISSIONS . "</strong></span></th>\n";
        echo "    <th width='30%' align='center'><span class=\"content\"><strong>" . _THEMES_OPTS. "</strong></span></th>\n";
        echo "  </tr>\n";
        echo "<tr>\n";
        echo "  <th width='100%' align='center' colspan='7'><span class=\"title\"><strong>" . _THEMES_INSTALLED . "</strong></span></th>\n";
        echo "</tr>\n";
        if (count($installed_themes) == 0) {
            echo "<tr>\n";
            echo "  <td width='100%' colspan='7' align='center' class='catBottom'><strong>" . _THEMES_NONE . "</strong></td>\n";
            echo "</tr>\n";
        } else {
            if (is_array($installed_themes)) {
                foreach($installed_themes as $theme) {
                    make_a_row($theme);
                }
            }
        }
        echo "</table><br />\n";
        echo "<table border='0' align='center' width='99%' cellpadding='4' cellspacing='1' class='forumline'>\n";
        echo "  <tr>\n";
        echo "    <th width='100%' align='center' colspan='6'><span class=\"title\"><strong>" . _THEMES_UNINSTALLED . "</strong></span></th>\n";
        echo "  </tr>\n";
        if (count($uninstalled_themes) == 0) {
            echo "<tr>\n";
			echo "  <td width='100%' align='center' class='row1'><strong>" . _THEMES_NONE . "</strong></td>\n";
			echo "</tr>\n";
        }
        if (is_array($uninstalled_themes)) {
            foreach($uninstalled_themes as $theme) {
                echo "<tr>\n";
				echo "  <td width='40%' align='center' class='row1'>" . $theme . "</td>\n";
				echo "  <td width='20%' align='center' class='row1'>" . ThemeGetStatus($theme) . "</td>\n";
				if (is_god($admin)){
					$extract = "<a href=\"$admin_file.php?op=downloadTheme&amp; theme=".$theme ."\" title=\"" . _DOWNLOAD_FILES. "\">" . _THEME_BACKUP . "</a> | ";
				}
				echo "  <td width='40%' align='center' class='row1'><small>[ ".$extract."<a href=\"$admin_file.php?op=theme_quickinstall&amp;theme=" . $theme . "\">" . _THEMES_QINSTALL . "</a> | <a href=\"$admin_file.php?op=theme_install&amp;theme=" . $theme . "\">" . _THEMES_INSTALL . "</a> | <a href=\"$admin_file.php?op=theme_makedefault&amp;theme=" . $theme . "\">" . _THEMES_MAKEDEFAULT . "</a> | <a href=\"index.php?tpreview=" . $theme . "\">" . _THEMES_VIEW . "</a> ]</small></td>\n";
				echo "</tr>\n";
            }
		}
        echo "</table><br />\n";
		echo "<form method='post' action='' enctype='multipart/form-data'>\n";
		echo "<table border='0' align='center' width='99%' cellpadding='4' cellspacing='1' class='forumline'>\n";
        echo "  <tr>\n";
        echo "    <th width='100%' align='center'><span class=\"title\"><strong>" . _ALLOWED_EXT . "</strong></span></th>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "    <td width='100%' class='row1' align='center'>\n";
        echo "    <input type='hidden' name='op' value='InstallTheme' />\n";
        echo "    <input type='file' name='file' size='50' /> \n";
        echo "    <input type='submit' value='Install Theme' />\n";
        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";
        echo "</form>\n";

    CloseTable();
}

function theme_edit($theme_name){
    global $prefix, $db, $admin_file;
	
    $theme_info = $db->sql_fetchrow($db->sql_query("SELECT * FROM " . $prefix . "_themes WHERE theme_name = '$theme_name'"));

    OpenTable();
    echo "    <form action='".$admin_file.".php' method='get' />\n";
    echo "<table align='center' border='0' cellpadding='4' cellspacing='1'>\n";
    echo "  <tr>\n";
    echo "    <td align='center' colspan='2' class='option'><strong>". $theme_info['theme_name'] ."</strong></td>\n";
    echo "  </tr>\n";
    if (is_default($theme_info['theme_name'])){
		echo "<tr>\n";
		echo "  <td align='center' colspan='2' class='option'><strong>( "._THEMES_DEFAULT." )</strong></td>\n";
		echo "</tr>\n";
    }
    echo   "<tr>\n";
    if (is_default($theme_info['theme_name'])){
        echo "<td align='center' colspan='2' class='option'>[ <strike>" . _THEMES_MAKEDEFAULT . "</strike> | <strike>" . _THEMES_UNINSTALL . "</strike> ]</td>\n";
    } else {
        echo "<td align='center' colspan='2' class='option'>[ <a href=\"$admin_file.php?op=theme_makedefault&amp;theme=" . $theme_info['theme_name'] . "\">" . _THEMES_MAKEDEFAULT . "</a> | <a href=\"$admin_file.php?op=theme_uninstall&amp;theme=" . $theme_info['theme_name'] . "\">" . _THEMES_UNINSTALL . "</a> ]</td>\n";
    }
    echo "  </tr>\n";
   // echo "    <form action='".$admin_file.".php' method='get'>\n";
    echo "  <tr>\n";
    echo "    <td bgcolor='$bgcolor2'>" . _THEMES_CUSTOMNAME . "</td>\n";
    echo "    <td><input type='text' name='custom_name' value='".$theme_info['custom_name']."' size='50' /></td>\n";
    echo "  </tr>\n";
    $selected1 = ($theme_info['permissions'] == 1) ? 'selected="selected"' : "";
    $selected2 = ($theme_info['permissions'] == 2) ? 'selected="selected"' : "";
    $selected3 = ($theme_info['permissions'] == 3) ? 'selected="selected"' : "";
    if (is_default($theme_info['theme_name'])){
        $disabled = 'disabled="disabled"';
        $selected1 = 'selected="selected"';
        $selected2 = "";
        $selected3 = "";
   }
	echo "  <tr>\n";
    echo "    <td bgcolor='$bgcolor2'>" . _THEMES_ACTIVE . "</td>\n";
    $yes_selected = ($theme_info['active']) ? 'selected="selected"' : "";
    $no_selected = (!$theme_info['active']) ? 'selected="selected"' : "";
    echo "    <td><select name='active' $disabled ><option value='1' $yes_selected>" . _YES . "</option><option value='0' $no_selected>" . _NO . "</option></select></td>\n";
    echo "  </tr>";
    if (is_default($theme_info['theme_name'])){
        echo "<input type='hidden' name='active' value='1' />";
        echo "<input type='hidden' name='permissions' value='1' />";
    }
    echo "  <tr>\n";
    echo "    <td>" . _VIEWPRIV . "</td><td><select name=\"permissions\" $disabled>\n";
    echo "    <option value=\"1\" $selected1>" . _MVALL . "</option>\n";
    echo "    <option value=\"2\" $selected2>"._MVGROUPS."</option>\n";
    echo "    <option value=\"3\" $selected3>" . _MVADMIN . "</option>\n";
    echo "    </select></td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "    <td valign='top'>"._WHATGROUPS.":</td>\n";
    echo "    <td><span class='tiny'>"._WHATGRDESC."</span><br /><select name='groups[]' multiple='multiple' size='5'>";
    $ingroups = explode("-",$theme_info['groups']);
    $groupsResult = $db->sql_query("select group_id, group_name from ".$prefix."_bbgroups WHERE group_description <> 'Personal User'");
    while(list($gid, $gname) = $db->sql_fetchrow($groupsResult)) {
        if (in_array($gid,$ingroups)){ $sel = 'selected="selected"'; } else { $sel = ""; }
        echo "<option value='$gid'$sel>$gname</option>";
    }
    echo "    </select></td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "    <td colspan='2'><fieldset><legend>" . _THEMES_ADV_OPTS . "</legend>\n";
    echo "    <table border='0' width='100%'>";
    if (is_file(NUKE_THEMES_DIR.$theme_info['theme_name'].'/theme_info.php')){
        echo "<tr>\n";
		echo "  <td align='center' colspan='2'><font color='green'>" . _THEMES_ADV_COMP . "</font></td>\n";
		echo "</tr>";
        include(NUKE_THEMES_DIR.$theme_info['theme_name'].'/theme_info.php');
        $loaded_params = (!empty($theme_info['theme_info'])) ? explode(':::', $theme_info['theme_info']) : $default;
        if (empty($theme_info['theme_info'])){
            echo "<tr><td align='center' colspan='2'><strong>" . _THEMES_DEF_LOADED . "</strong></td></tr>";
        }
        if (is_array($params)){
            foreach($params as $key => $param) {
                echo"<tr>\n";
				echo "  <td bgcolor='$bgcolor2'>" . $param_names[$key] . "</td>\n";
				echo "  <td><input type='text' name='" . $param . "' value='".$loaded_params[$key]."' size='50' /></td>\n";
				echo "</tr>";
            }
        }
        echo "<tr>\n";
		echo "  <td bgcolor='$bgcolor2'>" . _THEMES_REST_DEF . "</td>\n";
		echo "  <td><input type='checkbox' value='1' name='restore_default' /></td>\n";
		echo "</tr>";
    } else {
        echo "<tr><td align='center' colspan='2'>" . _THEMES_NOT_COMPAT . "</td></tr>";
    }
    echo "</table>\n";
    echo "    </fieldset>\n";
   // echo "    <table border='0' width='100%'>";
    echo "    <input type='hidden' name='theme_name' value='" . $theme_info['theme_name'] . "' />\n";
    echo "    <input type='hidden' name='op' value='theme_edit_save' />\n";
   // echo "    <table border='0' width='100%'>";
    echo "  <br />\n";
    //echo "    <td align='center' colspan='2'>\n";
    echo "  <center><input type='submit' value='"._SAVECHANGES."' /> <input type=\"button\" class=\"mainoption\" value=\"Go Back\" onclick=\"javascript:history.go(-1)\" /></center>\n";
    echo "  </td>\n";
    //echo "  </td>\n";
    echo "  </tr>";
    echo "</table>";
    CloseTable();
}

function theme_install($theme_name) {
    global $prefix, $db, $admin_file;

    OpenTable();
        echo "<form action='".$admin_file.".php' method='get'>"
        ."<table align='center' border='0' cellpadding='2' cellspacing='2'>"
        ."<tr>"
        ."<td align='center' colspan='2' class='option'><strong>". $theme_name ."</strong></td>"
        ."</tr>"
        //."<form action='".$admin_file.".php' method='get'>"
        ."<tr>"
        ."<td bgcolor='$bgcolor2'>" . _THEMES_CUSTOMNAME . "</td>"
        ."<td><input type='text' name='custom_name' value='".$theme_name."' size='50' /></td>"
        ."</tr>";
        echo"<tr>"
        ."<td bgcolor='$bgcolor2'>" . _THEMES_ACTIVE . "</td>";
        echo"<td><select name='active'><option value='1' selected='selected'>" . _YES . "</option><option value='0'>" . _NO . "</option></select></td>"
        ."</tr>";
        echo "<tr><td>" . _VIEWPRIV . "</td><td><select name=\"permissions\">"
        ."<option value=\"1\" selected='selected'>" . _MVALL . "</option>"
        ."<option value=\"2\">"._MVGROUPS."</option>"
        ."<option value=\"3\">" . _MVADMIN . "</option>"
        ."</select></td></tr>";
        echo "<tr><td valign='top'>"._WHATGROUPS.":</td><td><span class='tiny'>"._WHATGRDESC."</span><br /><select name='groups[]' multiple='multiple' size='5'>";
        $ingroups = explode("-",$theme_info['groups']);
        $groupsResult = $db->sql_query("select group_id, group_name from ".$prefix."_bbgroups WHERE group_description <> 'Personal User'");
        while(list($gid, $gname) = $db->sql_fetchrow($groupsResult)) {
            if(in_array($gid,$ingroups)) { $sel = " selected='selected'"; } else { $sel = ""; }
                      echo "<option value='$gid'$sel>$gname</option>";
        }
        echo "</select></td></tr>";
        echo "<tr><td colspan='2'><fieldset><legend>" . _THEMES_ADV_OPTS . "</legend>";
        echo "<table border='0' width='100%'>";
        if(is_file(NUKE_THEMES_DIR.$theme_name.'/theme_info.php')) {
            echo "<tr><td align='center' colspan='2'><font color='green'>" . _THEMES_ADV_COMP . "</font></td></tr>";
            include(NUKE_THEMES_DIR.$theme_name.'/theme_info.php');
            $loaded_params = $default;
            echo "<tr><td align='center' colspan='2'><strong>" . _THEMES_DEF_LOADED . "</strong></td></tr>";
            foreach($params as $key => $param) {
                echo"<tr>"
                ."<td bgcolor='$bgcolor2'>" . $param_names[$key] . "</td>"
                ."<td><input type='text' name='" . $param . "' value='".$loaded_params[$key]."' size='50' /></td>"
                ."</tr>";
            }
        } else {
            echo "<tr><td align='center' colspan='2'>" . _THEMES_NOT_COMPAT . "</td></tr>";
        }
        echo "</table>";
        echo "</fieldset></td></tr>";
        //echo "<input type='hidden' name='theme_name' value='" . $theme_name . "' />";
        //echo "<input type='hidden' name='op' value='theme_install_save' />";
        echo "<tr><td align='center' colspan='2'><input type='submit' value='"._THEMES_INSTALL."' /><input type='hidden' name='theme_name' value='" . $theme_name . "' /><input type='hidden' name='op' value='theme_install_save' /><br />"._GOBACK."</td></tr>";
        echo "</table>";
        echo "</form>";
    CloseTable();
}

function update_theme($post) {
    global $db, $prefix, $user_prefix, $admin_file, $cache;
    $error = false;
    if(is_array($post['groups'])) {
        $post['groups'] = implode('-', $post['groups']);
    }
    $theme_info = "";
    if(file_exists(NUKE_THEMES_DIR.$post['theme_name'].'/theme_info.php')) {
        include(NUKE_THEMES_DIR.$post['theme_name'].'/theme_info.php');
        for($i=0, $maxi=count($params);$i<$maxi;$i++) {
            $param = $params[$i];
            $theme_info[] = $post[$param];
        }
        $theme_info = implode(':::', $theme_info);
        if($post['restore_default']) {
            $theme_info = implode(':::', $default);
        }
    }

    $sql[] = "UPDATE " . $prefix . "_themes SET custom_name = '" . $post['custom_name'] . "' WHERE theme_name = '" . $post['theme_name'] . "'";
    $sql[] = "UPDATE " . $prefix . "_themes SET active = '" . $post['active'] . "' WHERE theme_name = '" . $post['theme_name'] . "'";
    $sql[] = "UPDATE " . $prefix . "_themes SET permissions = '" . $post['permissions'] . "' WHERE theme_name = '" . $post['theme_name'] . "'";
    $sql[] = "UPDATE " . $prefix . "_themes SET theme_info = '" . $theme_info . "' WHERE theme_name = '" . $post['theme_name'] . "'";
    if (($post['permissions'] > 1) || ($post['active'] != 1)) {
        $sql[] = "UPDATE " . $user_prefix . "_users SET theme = '" . get_default() . "' WHERE theme = '" . $post['theme_name'] . "'";
    }
    $sql[] = "UPDATE " . $prefix . "_themes SET groups = '" . $post['groups'] . "' WHERE theme_name = '" . $post['theme_name'] . "'";
    foreach($sql as $query) {
        if(!$db->sql_query($query)) {
            $error = true;
        }
    }
    $cache->delete($post['theme_name'], 'themes');
    if(!$error) {
        OpenTable();
            echo "<center>\n";
            echo "<strong>" . _THEMES_UPDATED . "</strong><br /><br />\n";
            echo "<a href=\"$admin_file.php?op=themes\">" . _THEMES_RETURN . "</a>\n";
            echo "</center>\n";
        CloseTable();
    } else {
        OpenTable();
            echo "<center>\n";
            echo "<strong><font color='red'>" . _THEMES_UPDATEFAILED . "</font></strong><br /><br />\n";
            echo "<a href=\"$admin_file.php?op=themes\">" . _THEMES_RETURN . "</a>\n";
            echo "</center>\n";
        CloseTable();
    }
}

function install_save($post) {
    global $db, $prefix, $admin_file;
    $post['groups'] = (is_array($post['groups'])) ? implode('-', $post['groups']) : '';

    $theme_info = "";
    if(file_exists(NUKE_THEMES_DIR.$post['theme_name'].'/theme_info.php')) {
        include(NUKE_THEMES_DIR.$post['theme_name'].'/theme_info.php');
        for($i=0, $maxi=count($params);$i<$maxi;$i++) {
            $param = $params[$i];
            $theme_info[] = $post[$param];
        }
        $theme_info = implode(':::', $theme_info);
        if($post['restore_default']) {
            $theme_info = implode(':::', $default);
        }
    }

    $sql = "INSERT INTO " . $prefix . "_themes VALUES('" . $post['theme_name'] . "', '" . $post['groups'] . "', '" . $post['permissions'] . "', '" . $post['custom_name'] . "', '" . $post['active'] . "', '" . $theme_info . "')";
    if($db->sql_query($sql)) {
        OpenTable();
            echo "<center>\n";
            echo "<strong>" . _THEMES_THEME_INSTALLED . "</strong><br /><br />\n";
            echo "<a href=\"$admin_file.php?op=themes\">" . _THEMES_RETURN . "</a>\n";
            echo "</center>\n";
        CloseTable();
    } else {
        OpenTable();
            echo "<center>\n";
            echo "<strong><font color='red'>" . _THEMES_THEME_INSTALLED_FAILED . "</font></strong><br /><br />\n";
            echo "<a href=\"$admin_file.php?op=themes\">" . _THEMES_RETURN . "</a>\n";
            echo "</center>\n";
        CloseTable();
    }
}

function uninstall_theme($theme) {
    global $db, $prefix, $user_prefix, $admin_file;

    function uninstall_success() {
        global $admin_file;
        OpenTable();
            echo "<center>\n";
            echo "<strong>" . _THEMES_THEME_UNINSTALLED . "</strong><br /><br />\n";
            echo "<a href=\"$admin_file.php?op=themes\">" . _THEMES_RETURN . "</a>\n";
            echo "</center>\n";
        CloseTable();
    }
    function uninstall_failed(){
        global $admin_file;
        OpenTable();
            echo "<center>\n";
            echo "<strong>" . _THEMES_THEME_UNINSTALLED_FAILED . "</strong><br /><br />\n";
            echo "<a href=\"$admin_file.php?op=themes\">" . _THEMES_RETURN . "</a>\n";
            echo "</center>\n";
        CloseTable();
    }
    if(!$_POST['confirm']) {
        OpenTable();
            echo "<form name='confirm_uninstall' action='$admin_file.php' method='post'>\n";
            echo "<input type='hidden' name='theme' value='$theme' />";
            echo "<input type='hidden' name='op' value='theme_uninstall' />";
            echo "<input type='hidden' name='confirm' value='1' />";
			echo "<center>\n";
            echo "<strong>" . _THEMES_UNINSTALL1 . "</strong><br /><br />\n";
            echo _THEMES_UNINSTALL2 . "<br />\n";
            echo _THEMES_UNINSTALL3 . "<br /><br />";
            echo "<a href=\"javascript:document.forms['confirm_uninstall'].submit()\">" . _THEMES_THEME_UNINSTALL . "</a><br /><br />\n";
            echo "<a href=\"$admin_file.php?op=themes\">" . _THEMES_RETURN . "</a>\n";
            echo "</center></form>\n";
        CloseTable();
        return false;
    } else {
        if (!is_default($theme)) {
            if ($db->sql_query("DELETE FROM " . $prefix . "_themes WHERE theme_name = '$theme'")) {
                $db->sql_query("UPDATE " . $user_prefix . "_users SET theme = '" . get_default() . "' WHERE theme = '$theme'");
                uninstall_success();
                return true;
            }
        }
        uninstall_failed();
        return false;
    }
    uninstall_failed();
    return false;
}

function theme_makedefault($theme) {
    global $db, $prefix, $admin_file, $cache;
    if(!theme_installed($theme)) {
        $sql = "INSERT INTO " . $prefix . "_themes VALUES('$theme', '', '1', '$theme', '1', '')";
        $db->sql_query($sql);
    }
    $sql=array();
    $sql[] = "UPDATE " . $prefix . "_themes SET active = '1' WHERE theme_name = '$theme'";
    $sql[] = "UPDATE " . $prefix . "_config SET default_Theme = '$theme'";
    $sql[] = "UPDATE " . $prefix . "_themes SET permissions = '1' WHERE theme_name = '$theme'";
    foreach($sql as $query) {
        $db->sql_query($query);
    }
    $cache->delete('nukeconfig', 'config');
    redirect($admin_file . '.php?op=themes');
}

function theme_deactivate($theme) {
    global $db, $prefix, $user_prefix, $admin_file;

    function deactivate_success() {
        global $admin_file;
        OpenTable();
            echo "<center>\n";
            echo "<strong>" . _THEMES_THEME_DEACTIVATED . "</strong><br /><br />\n";
            echo "<a href=\"$admin_file.php?op=themes\">" . _THEMES_RETURN . "</a>\n";
            echo "</center>\n";
        CloseTable();
    }
    function deactivate_failed(){
        global $admin_file;
        OpenTable();
            echo "<center>\n";
            echo "<strong>" . _THEMES_THEME_DEACTIVATED_FAILED . "</strong><br /><br />\n";
            echo "<a href=\"$admin_file.php?op=themes\">" . _THEMES_RETURN . "</a>\n";
            echo "</center>\n";
        CloseTable();
    }

    if(!$_POST['confirm']) {
        OpenTable();
            echo "<form name='confirm_deactivate' action='$admin_file.php' method='post'>";
            echo "<input type='hidden' name='theme' value='$theme' />";
            echo "<input type='hidden' name='op' value='theme_deactivate' />";
            echo "<input type='hidden' name='confirm' value='1' />";
            echo "<center>\n";
            echo "<strong>" . _THEMES_DEACTIVATE1 . "</strong><br /><br />\n";
            echo _THEMES_DEACTIVATE2 . "<br /><br />";
            echo "<a href=\"javascript:document.forms['confirm_deactivate'].submit()\">" . _THEMES_THEME_DEACTIVATE . "</a><br /><br />\n";
            echo "<a href=\"$admin_file.php?op=themes\">" . _THEMES_RETURN . "</a>\n";
            echo "</center></form>\n";
        CloseTable();
        return false;
    } else {
        if (!is_default($theme)) {
            if ($db->sql_query("UPDATE " . $prefix . "_themes SET active='0' WHERE theme_name = '$theme'")) {
                if($db->sql_query("UPDATE " . $user_prefix . "_users SET theme = '" . get_default() . "' WHERE theme = '$theme'")){
                deactivate_success();
                return true;
                }
            }
        }
        deactivate_failed();
        return false;
    }
}

function theme_options($mode, $post) {
    global $prefix, $db, $admin_file, $user_prefix;
    if(!$mode) $mode = 'main';
    switch($mode) {
        case 'main':
            list($usrthemeselect) = $db->sql_fetchrow($db->sql_query("SELECT config_value FROM " . $prefix . "_cnbya_config WHERE config_name = 'allowusertheme'"));
            $thmselect_selected_yes = ($usrthemeselect == 0) ? "selected" : "";
            $thmselect_selected_no = ($usrthemeselect == 1) ? "selected" : "";
            OpenTable();
                echo "<form action='$admin_file.php' method='get'><center>"
                ."<strong>" . _THEMES_MANG_OPTIONS . "</strong><br /><br />\n"
                ."[ <a href='" . $admin_file . ".php?op=theme_transfer'>" . _THEMES_TRANSFER . "</a> ]<br /><br />"
                ."<table border='1' class='bodyline' width='30%'>"
                ."<tr><td class='row2'>"
                ._THEMES_ALLOWCHANGE
                ."</td><td class='row3'>"
                ."<select name=allowusertheme><option value='0' $thmselect_selected_yes>" . _YES . "</option><option value='1' $thmselect_selected_no>" . _NO . "</option></select>"
                ."</td></tr>"
                ."<tr><td class='row2' colspan='2' align='center'>"
                ."<input type='hidden' name='op' value='theme_options'>"
                ."<input type='hidden' name='act' value='save'>"
                ."<input type='submit' value='" . _THEMES_SUBMIT . "'>"
                ."</td></tr>"
                ."</table>"
                ."<br />"
                ."</center></form>";
            CloseTable();
        break;
        case 'save':
            $db->sql_query("UPDATE " . $prefix . "_cnbya_config SET config_value = '" . $post['allowusertheme'] . "' WHERE config_name = 'allowusertheme'");
            OpenTable();
                echo "<center>\n";
                echo "<strong>" . _THEMES_SETTINGS_UPDATED . "</strong><br /><br />\n";
                echo "<a href=\"$admin_file.php?op=themes\">" . _THEMES_RETURN . "</a>\n";
                echo "</center>\n";
            CloseTable();
        break;
    }
    return true;
}

function theme_transfer() {
    global $prefix, $db, $admin_file, $user_prefix;
    if(!$_POST['transfer']) {
        $from_themes = get_themes('dir');
        $to_themes = get_themes('all');
        OpenTable();
            echo "<form action='$admin_file.php' method='post'><center>"
            ."<strong>" . _THEMES_THEME_TRANSFER . "</strong><br />\n"
            ."[ <a href='" . $admin_file . ".php?op=theme_options'>" . _THEMES_RETURN_OPTIONS . "</a> ]<br /><br />"
            ."<table border='0' width='30%'>"
            ."<tr><td align='center'>"
            ._THEMES_FROM
            ."</td><td align='center'>"
            ._THEMES_TO
            ."</td></tr>"
            ."<tr><td align='center'>"
            ."<select name='from'>"
            ."<option value='all'>" . _THEMES_ALLUSERS . "</option>";
            foreach($from_themes as $theme) {
                 echo "<option value='" . $theme['theme_name'] . "'>" . (($theme['custom_name']) ? $theme['custom_name'] : $theme['theme_name']) . " (" . ThemeCount($theme['theme_name']) . ")</option>";
            }
            echo "</select>"
            ."</td><td align='center'>"
            ."<select name='to'>"
            ."<option value='default'>" . _THEMES_DEFAULT . "</option>";
            foreach($to_themes as $theme) {
                 echo"<option value='" . $theme['theme_name'] . "'>" . (($theme['custom_name']) ? $theme['custom_name'] : $theme['theme_name']) . "</option>";
            }
            echo "</select>"
            ."</td></tr>"
            ."<tr><td colspan='2' align='center'>"
            ."<input type='hidden' name='transfer' value='1'>"
            ."<input type='hidden' name='op' value='theme_transfer'>"
            ."<input type='submit' value='" . _THEMES_SUBMIT . "'>"
            ."</td></tr>"
            ."</table>"
            ."<br />"
            ."</center></form>";
        CloseTable();
    } else {
        $where = ($_POST['from'] == 'all') ? "WHERE user_id <> '1'" : "WHERE theme='" . $_POST['from'] . "' AND user_id <> '1'";
        $to = ($_POST['to'] == 'default') ? "" : $_POST['to'];
        $result = $db->sql_query("UPDATE " . $user_prefix . "_users SET theme = '" . $to . "' $where");
        $count = intval($db->sql_affectedrows($result));
        OpenTable();
            echo "<center>\n";
            echo "<strong>$count " . _THEMES_TRANSFER_UPDATED . "</strong><br /><br />\n";
            echo "<a href=\"$admin_file.php?op=themes\">" . _THEMES_RETURN . "</a>\n";
            echo "</center>\n";
        CloseTable();
    }

    return true;
}

function users_themes() {
global $db, $user_prefix, $admin_file;
    OpenTable();
      echo"<table border='2' align='center' width='100%'>\n"
         ."<tr>"
         ."<th width='16%' align='center'><span class=\"content\"><strong>" . _THEMES_USERID . "</strong></span></th>\n"
         ."<th width='16%' align='center'><span class=\"content\"><strong>" . _THEMES_USERNAME . "</strong></span></th>\n"
         ."<th width='16%' align='center'><span class=\"content\"><strong>" . _THEMES_REALNAME . "</strong></span></th>\n"
         ."<th width='16%' align='center'><span class=\"content\"><strong>" . _THEMES_USEREMAIL . "</strong></span></th>\n"
         ."<th width='16%' align='center'><span class=\"content\"><strong>" . _THEMES_USERTHEME . "</strong></span></th>\n"
         ."<th width='16%' align='center'><span class=\"content\"><strong>" . _THEMES_FUNCTIONS. "</strong></span></th>\n"
         ."</tr>";
/*****[BEGIN]******************************************
 [ Base:    Pagination System                  v1.0.0 ]
 ******************************************************/
    $num_rows = $db->sql_numrows($db->sql_query("SELECT * FROM ".$user_prefix."_users"));
    $pagination = new Paginator($_GET['page'],$num_rows);
    $pagination->set_Limit(15);
    $pagination->set_Links(3);
    $limit1 = $pagination->getRange1();
    $limit2 = $pagination->getRange2();
/*****[END]********************************************
 [ Base:    Pagination System                  v1.0.0 ]
 ******************************************************/
    $result = $db->sql_query("SELECT * FROM ".$user_prefix."_users WHERE user_id != '1' ORDER BY username LIMIT $limit1, $limit2");
    while($row = $db->sql_fetchrow($result)) {
        $user_id = intval($row['user_id']);
        $username = Fix_Quotes($row['username']);
        if(empty($row['name'])) {
            $realname = _NOREALNAME;
        } else {
            $realname = Fix_Quotes($row['name']);
        }
        $useremail = Fix_Quotes($row['user_email']);
        if(empty($row['theme'])) {
            $usertheme = get_default();
        } else {
            $usertheme = Fix_Quotes($row['theme']);
        }
        echo  "<tr valign=\"middle\"$bold><form method='post' action='".$admin_file.".php'>"
            ."<td width='16%' align='center' bgcolor='$bgcolor3'>".$user_id."</td></td>\n"
            ."<td width='16%' align='center' bgcolor='$bgcolor3'>" . $username . "</td>\n"
            ."<td width='16%' align='center' bgcolor='$bgcolor3'>" . $realname . "</td>\n"
            ."<td width='16%' align='center' bgcolor='$bgcolor3'>" . $useremail . "</td>\n"
            ."<td width='16%' align='center' bgcolor='$bgcolor3'>" . $usertheme . "</td>\n"
            ."<td width='16%' align='center' bgcolor='$bgcolor3'><select name='op'>\n"
            ."<option value='theme_users_reset'>"._THEMES_USER_RESET."</option>\n"
            ."<option value='theme_users_modify'>"._THEMES_USER_MODIFY."</option>\n"
            ."</select><p><input type=\"hidden\" name=\"theme_userid\" value=\"$user_id\"><input type=\"hidden\" name=\"theme_username\" value=\"$username\"><input type='submit' value='"._THEMES_SUBMIT."'></p></td></form></tr>\n"
            ."</tr>\n";
    }
    echo "</table>";

CloseTable();
/*****[BEGIN]******************************************
 [ Base:    Pagination System                  v1.0.0 ]
 ******************************************************/
OpenTable();
if($pagination->getCurrent()==1)
                 {
                 $first = _THEMES_PAGE_FIRST." | ";
                 } else { $first="<a href=\"" .  $pagination->getPageName() . "?op=theme_users&amp;page=" . $pagination->getFirst() . "\">"._THEMES_PAGE_FIRST."</a> |"; }
                     //check to see that getPrevious() is returning a value. If not there will be no link.
               if($pagination->getPrevious())
                 {
                 $prev = "<a href=\"" .  $pagination->getPageName() . "?op=theme_users&amp;page=" . $pagination->getPrevious() . "\">"._THEMES_PAGE_PREVIOUS."</a> | ";
                 } else { $prev=""._THEMES_PAGE_PREVIOUS." | "; }
               //check to see that getNext() is returning a value. If not there will be no link.
             if($pagination->getNext())
                 {
                 $next = "<a href=\"" . $pagination->getPageName() . "?op=theme_users&amp;page=" . $pagination->getNext() . "\">"._THEMES_PAGE_NEXT."</a> | ";
                 } else { $next=""._THEMES_PAGE_NEXT." | "; }

               //check to see that getLast() is returning a value. If not there will be no link.
               if($pagination->getLast())
                 {
                 $last = "<a href=\"" . $pagination->getPageName() . "?op=theme_users&amp;page=" . $pagination->getLast() . "\">"._THEMES_PAGE_LAST."</a> | ";
                 } else { $last=""._THEMES_PAGE_LAST." | "; }
                         //since these will always exist just print out the values.  Result will be
                         //something like 1 of 4 of 25
                  echo $pagination->getFirstOf() . " "._THEMES_PAGE_OF." " .$pagination->getSecondOf() . " "._THEMES_PAGE_OF." " . $pagination->getTotalItems() . " ";
                            //print the values determined by the if statements above.
                  echo $first . " " . $prev . " " . $next . " " . $last;
                  CloseTable();
/*****[END]********************************************
 [ Base:    Pagination System                  v1.0.0 ]
 ******************************************************/
}

function theme_users_reset($user_id, $username, $theme) {
    global $db,$user_prefix, $admin_file;
    $user_id = intval($user_id);
    $username = Fix_Quotes($username);
    $result = $db->sql_query("UPDATE " . $user_prefix . "_users SET theme = '" . get_default() . "' WHERE user_id = '$user_id' AND username = '$username'");
    redirect($admin_file . '.php?op=themes');
}

function theme_users_modify($user_id, $username, $theme) {
    global $db, $user_prefix, $admin_file;
    if (empty($theme) && !empty($user_id)) {
        OpenTable();
        echo"<table border='2' align='center' width='100%'>\n"
             ."<tr>"
             ."<th width='16%' align='center'><span class=\"content\"><strong>" . _THEMES_USERNAME . "</strong></span></th>\n"
             ."<th width='16%' align='center'><span class=\"content\"><strong>" . _THEMES_USER_SELECT. "</strong></span></th>\n"
             ."</tr>";
        $result = $db->sql_query("SELECT * FROM ".$user_prefix."_users WHERE user_id =".$user_id);
        if ($row = $db->sql_fetchrow($result)) {
            $user_id = intval($row['user_id']);
            $username = Fix_Quotes($row['username']);
            if(empty($row['theme'])) {
                $usertheme = get_default();
            } else {
                $usertheme = $row['theme'];
            }
            echo"<tr valign=\"middle\"$bold><form method='post' action='".$admin_file.".php?op=theme_users_modify'>"
                 ."<td width='50%' align='center' bgcolor='$bgcolor3'>" . $row['username'] . "</td>\n"
                 ."<td width='50%' align='center' bgcolor='$bgcolor3'>". GetThemeSelect('themename')
                 ."<input type=\"hidden\" name=\"user_id\" value=\"$user_id\"><input type=\"hidden\" name=\"username\" value=\"$username\"><input type=\"hidden\" name=\"theme\" value=\"$usertheme\">&nbsp;<input type='submit' value='"._THEMES_SUBMIT."'></td></tr>";
        }
        echo "</table>";
        CloseTable();
    } else if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {
        $db->sql_query("UPDATE " . $user_prefix . "_users SET theme = '" . $theme . "' WHERE user_id = '".$_POST['user_id']."'");
        redirect($admin_file.".php?op=theme_users");
    }
}
if (is_mod_admin()) {
    include_once(NUKE_BASE_DIR.'header.php');
    switch ($op) {
        case 'theme_edit':
            theme_header();
            theme_edit($theme);
            theme_footer();
        break;
        case 'theme_install':
            theme_header();
            theme_install($theme);
            theme_footer();
        break;
        case 'theme_makedefault':
            theme_makedefault($theme);
        break;
        case 'theme_deactivate':
            theme_header();
            theme_deactivate($theme);
            theme_footer();
        break;
        case 'theme_activate':
            if (!is_default($theme)) {
                $sql = "UPDATE " . $prefix . "_themes SET active='1' WHERE theme_name = '$theme'";
                $db->sql_query($sql);
            }
            theme_header();
            display_main();
            theme_footer();
        break;
        case 'theme_install_save':
            theme_header();
            install_save($_GET);
            theme_footer();
        break;
        case 'theme_edit_save':
            theme_header();
            update_theme($_GET);
            theme_footer();
        break;
        case 'theme_quickinstall':
            if(!theme_installed($theme)) {
                $sql = "INSERT INTO " . $prefix . "_themes VALUES('$theme', '', '1', '$theme', '1', '')";
                $db->sql_query($sql);
            }
            theme_header();
            display_main();
            theme_footer();
        break;
        case 'theme_uninstall':
            theme_header();
            uninstall_theme($theme);
            theme_footer();
        break;
        case 'theme_options':
            theme_header();
            theme_options($_GET['act'], $_GET);
            theme_footer();
        break;
        case 'theme_transfer':
            theme_header();
            theme_transfer();
            theme_footer();
        break;
        case "theme_users":
         theme_header();
         users_themes();
         theme_footer();
        break;
        case "theme_users_reset":
         theme_header();
         theme_users_reset(Fix_Quotes($theme_userid),Fix_Quotes($theme_username), Fix_Quotes($_POST['themename']));
         theme_footer();
        break;
        case "theme_users_modify":
        theme_header();
        theme_users_modify(Fix_Quotes($theme_userid), Fix_Quotes($theme_username), Fix_Quotes($_POST['themename']));
        theme_footer();
        break;
		case "InstallTheme":
		theme_header();
		InstallTheme($file);
		display_main();
		theme_footer();
		break;
        default:
            theme_header();
            display_main();
            theme_footer();
        break;
		case "downloadTheme": 
		theme_header();
		downloadTheme($theme);
		display_main();
		theme_footer();
    }
    include_once(NUKE_BASE_DIR.'footer.php');
} else {
    echo "Access Denied";
}

?>
<?php
/*=======================================================================
 Nuke-Evolution Basic: Enhanced PHP-Nuke Web Portal System
 =======================================================================*/

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2002 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/************************************************************************/
/*         Additional security & Abstraction layer conversion           */
/*                           2003 chatserv                              */
/*      http://www.nukefixes.com -- http://www.nukeresources.com        */
/************************************************************************/
/********************************************************/
/* NSN News                                             */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2005 by NukeScripts Network         */
/********************************************************/

if (!defined('ADMIN_FILE')) {
   die ("Access Denied");
}

global $prefix, $db, $admdata;
$module_name = basename(dirname(dirname(__FILE__)));

if (is_mod_admin($module_name)){
	include_once(NUKE_INCLUDE_DIR.'nsnne_func.php');
	$ne_config = ne_get_configs();

	/*********************************************************/
	/* Topics Manager Functions                              */
	/*********************************************************/

	function topicsTop($lang){
		global $admin_file;
		
		OpenTable();
		echo '<div align="center"><a href="'.$admin_file.'.php?op=topicsmanager">'._TOPICS_ADMIN_HEADER.'</a></div>';
		echo '<br /><br />';
		echo '<div align="center">[ <a href="'.$admin_file.'.php">'._TOPICS_RETURNMAIN.'</a> ]</div>';
		CloseTable();
		echo '<br />';
		if (!empty($lang)){
			OpenTable();
			echo '<center><span class="title"><strong>'.$lang.'</strong></span></center>';
			CloseTable();
			echo '<br />';
		}
	}

	function topicsmanager() {
		global $prefix, $db, $admin_file, $tipath;
		
		include(NUKE_BASE_DIR."header.php");
		topicsTop(_TOPICSMANAGER);
		
		OpenTable();
		echo '<center><span class="option"><strong>'._CURRENTTOPICS.'</strong></span><br />'._CLICK2EDIT.'</span></center><br />';
		echo '<table border="0" width="100%" align="center" cellpadding="2">';
		$count = 0;
		$result = $db->sql_query("SELECT topicid, topicname, topicimage, topictext from " . $prefix . "_topics order by topicname");
		$topics = '';
		while($row = $db->sql_fetchrow($result)){
			if ($count == 0){ $topics .= '<tr>'; }
			$topicid = intval($row['topicid']);
			$topicname = $row['topicname'];
			$topicimage = $row['topicimage'];
			$topictext = $row['topictext'];
			$topics .= '<td align="center" width="17%" valign="top"><a href="'.$admin_file.'.php?op=topicedit&topicid='.$topicid.'"><img src="'.$tipath.$topicimage.'" border="0" alt="" /></a><br /><span class="content"><strong>'.$topictext.'</strong></span></td>';
			$count++;
			if ($count == 6){ $topics .= '</tr>'; $count=0; }
		}
		echo $topics;
		if (substr($topics, -5) != '</tr>'){
			echo '</tr>';
		}
		echo '</table>';
		CloseTable();
		echo '<br />';
		OpenTable();
		echo '<form action="'.$admin_file.'.php" method="post">';
		echo '<input type="hidden" name="op" value="topicmake" />';
		echo '<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline">';
		echo '  <tr>';
		echo '    <th class="thHead" colspan="2">'._ADDATOPIC.'</th>';
		echo '  </tr>';
		echo '  <tr>';
		echo '    <td class="row1" width="45%"><strong>'._TOPICNAME.'</strong><br /><span class="gensmall">'._TOPICNAME1.'<br />'._TOPICNAME2.'</span></td>';
		echo '    <td class="row1"><input type="text" name="topicname" size="40" maxlength="20" /></td>';
		echo '  </tr>';
		echo '  <tr>';
		echo '    <td class="row1"><strong>'._TOPICTEXT.'</strong><br /><span class="gensmall">'._TOPICTEXT1.'<br />'._TOPICTEXT2.'</span></td>';
		echo '    <td class="row1"><input type="text" name="topictext" size="40" maxlength="40" /></td>';
		echo '  </tr>';
		echo '  <tr>';
		echo '    <td class="row1"><strong>'._TOPICIMAGE.'</strong></td>';
		echo '    <td class="row1">';
		$handle = opendir($tipath);
		$topic_list = array();
		while($file = readdir($handle)){
			if (preg_match("/^([_0-9a-zA-Z]+)([.]{1})([_0-9a-zA-Z]{3})$/", $file) && $file != 'AllTopics.gif'){
				$files .= $file.' ';
			}
		}
		closedir($handle);
		$file_list = explode(' ', $files);
		sort($file_list);
		for($i=0; $i<count($file_list); $i++){
			if (!empty($file_list[$i])){
				$topic_list[$file_list[$i]] = $file_list[$i];
			}
		}
		echo select_box('topicimage', '', $topic_list);
		echo '    </td>';
		echo '  </tr>';
		echo '  <tr>';
		echo '    <td class="catBottom" align="center" colspan="2"><input type="submit" class="mainoption" value="'._ADDTOPIC.'" /></td>';
		echo '  </tr>';
		echo '</table>';
		echo '</form>';
		CloseTable();
		include(NUKE_BASE_DIR."footer.php");
	}

	function topicedit($topicid) {
		global $prefix, $db, $admin_file, $tipath;
		
		include(NUKE_BASE_DIR."header.php");
		topicsTop(_TOPICSMANAGER);
		
		OpenTable();
		$query = $db->sql_query("SELECT topicid, topicname, topicimage, topictext FROM ". $prefix ."_topics WHERE topicid='$topicid'");
		list($topicid, $topicname, $topicimage, $topictext) = $db->sql_fetchrow($query);
		$topicid = intval($topicid);
		
		echo '<form action="'.$admin_file.'.php" method="post">';
		echo '<input type="hidden" name="topicid" value="'.$topicid.'" />';
		echo '<input type="hidden" name="op" value="topicchange" />';
		echo '<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline">';
		echo '  <tr>';
		echo '    <th class="thHead" colspan="3">'._EDITTOPIC.': '.$topictext.'</th>';
		echo '  </tr>';
		echo '  <tr>';
		echo '    <td class="row1" width="40%"><strong>'._TOPICNAME.'</strong><br /><span class="gensmall">'._TOPICNAME1.'<br />'._TOPICNAME2.'</span></td>';
		echo '    <td class="row1"><input type="text" name="topicname" size="40" maxlength="20" value="'.$topicname.'" /></td>';
		echo '    <td class="row1" align="center"><img src="'.$tipath.$topicimage.'" alt="'.$topictext.'" /></td>';
		echo '  </tr>';
		echo '  <tr>';
		echo '    <td class="row1"><strong>'._TOPICTEXT.'</strong><br /><span class="gensmall">'._TOPICTEXT1.'<br />'._TOPICTEXT2.'</span></td>';
		echo '    <td class="row1" colspan="2"><input type="text" name="topictext" size="40" maxlength="40" value="'.$topictext.'" /></td>';
		echo '  </tr>';
		echo '  <tr>';
		echo '    <td class="row1"><strong>'._TOPICIMAGE.'</strong></td>';
		echo '    <td class="row1" colspan="2">';
		$handle = opendir($tipath);
		$topic_list = array();
		while($file = readdir($handle)){
			if (preg_match("/^([_0-9a-zA-Z]+)([.]{1})([_0-9a-zA-Z]{3})$/", $file) && $file != 'AllTopics.gif'){
				$files .= $file.' ';
			}
		}
		closedir($handle);
		$file_list = explode(' ', $files);
		sort($file_list);
		for($i=0; $i<count($file_list); $i++){
			if (!empty($file_list[$i])){
				$topic_list[$file_list[$i]] = $file_list[$i];
			}
		}
		echo select_box('topicimage', $topicimage, $topic_list);
		echo '    </td>';
		echo '  </tr>';
		echo '  <tr>';
		echo '    <th class="thHead" colspan="3">'._ADDRELATED.'</th>';
		echo '  </tr>';
		echo '  <tr>';
		echo '    <td class="row1"><strong>'._SITENAME.'</strong></td>';
		echo '    <td class="row1" colspan="2"><input type="text" name="name" size="30" maxlength="30" /></td>';
		echo '  </tr>';
		echo '  <tr>';
		echo '    <td class="row1"><strong>'._URL.'</strong></td>';
		echo '    <td class="row1" colspan="2"><input type="text" name="url" value="http://" size="50" maxlength="200" /></td>';
		echo '  </tr>';
		echo '  <tr>';
		echo '    <td class="row1"><strong>'._ACTIVERELATEDLINKS.'</strong></td>';
		echo '    <td class="row1" colspan="2">';
		$res = $db->sql_query("SELECT rid, name, url from ".$prefix . "_related where tid='$topicid'");
		$num = $db->sql_numrows($res);
		echo '<table width="100%" border="0">';
		if ($num == 0){
			echo '<tr>';
			echo '  <td><span class="tiny">'._NORELATED.'</span></td>';
			echo '</tr>';
		} else {
			while($row = $db->sql_fetchrow($res)){
				$rid = intval($row['rid']);
				$name = $row['name'];
				$url = stripslashes($row['url']);
				echo '<tr>';
				echo '  <td align="left"><span class="content"><strong><a href="'.$url.'">'.$name.'</a></strong></td>';
				echo '  <td align="center"><span class="content"><a href="'.$url.'">'.$url.'</a></td>';
				echo '  <td align="right"><span class="content">[ <a href="'.$admin_file.'.php?op=relatededit&amp;tid='.$topicid.'&amp;rid='.$rid.'">'._EDIT.'</a> | <a href="'.$admin_file.'.php?op=relateddelete&amp;tid='.$topicid.'&amp;rid='.$rid.'">'._DELETE.'</a> ]</td>';
				echo '</tr>';
			}
		}
		echo '</table>';
		echo '    </td>';
		echo '  </tr>';
		echo '  <tr>';
		echo '    <td class="catBottom" align="center" colspan="3"><input type="submit" class="mainoption" value="'._SAVECHANGES.'" /> <input type="button" class="mainoption" value="'._DELETE.'" onclick="window.location=\''.$admin_file.'.php?op=topicdelete&amp;topicid='.$topicid.'\'" /></td>';
		echo '  </tr>';
		echo '</table>';
		echo '</form>';
		CloseTable();
		include(NUKE_BASE_DIR."footer.php");
	}

	function relatededit($tid, $rid){
		global $prefix, $db, $admin_file;
		
		include(NUKE_BASE_DIR."header.php");
		topicsTop(_TOPICSMANAGER);
		
		$rid = intval($rid);
		$tid = intval($tid);
		$row = $db->sql_fetchrow($db->sql_query("SELECT name, url FROM ". $prefix ."_related WHERE rid='$rid'"));
		$name = $row['name'];
		$url = $row['url'];
		$row2 = $db->sql_fetchrow($db->sql_query("SELECT topictext, topicimage FROM ".$prefix . "_topics WHERE topicid='$tid'"));
		$topictext = $row2['topictext'];
		$topicimage = $row2['topicimage'];
		
		OpenTable();
		
		echo '<form action="'.$admin_file.'.php" method="post">';
		echo '<input type="hidden" name="op" value="relatedsave">';
		echo '<input type="hidden" name="tid" value="'.$tid.'">';
		echo '<input type="hidden" name="rid" value="'.$rid.'">';
		echo '<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline">';
		echo '  <tr>';
		echo '    <th class="thHead" colspan="3">'._EDITRELATED.' ( '._TOPIC.': '.$topictext.' )</th>';
		echo '  </tr>';
		echo '  <tr>';
		echo '    <td class="row1" width="40%"><strong>'._SITENAME.'</strong></td>';
		echo '    <td class="row1"><input type="text" name="name" size="30" maxlength="30" value="'.$name.'" /></td>';
		echo '    <td class="row1" align="center"><img src="images/topics/'.$topicimage.'" alt="'.$topictext.'" /></td>';
		echo '  </tr>';
		echo '  <tr>';
		echo '    <td class="row1"><strong>'._URL.'</strong></td>';
		echo '    <td class="row1" colspan="2"><input type="text" name="url" value="'.$url.'" size="50" maxlength="200" /></td>';
		echo '  </tr>';
		echo '  <tr>';
		echo '    <td class="catBottom" align="center" colspan="3"><input type="submit" class="mainoption" value="'._SAVECHANGES.'" /> '._GOBACK.'</td>';
		echo '  </tr>';
		echo '</table>';
		echo '</form>';
		CloseTable();
		include(NUKE_BASE_DIR."footer.php");
	}

	function relatedsave($tid, $rid, $name, $url){
		global $prefix, $db, $admin_file;
		
		$rid = intval($rid);
		$db->sql_query("UPDATE ". $prefix . "_related SET name='$name', url='$url' where rid='$rid'");
		redirect($admin_file.'.php?op=topicedit&topicid='.$tid);
	}

	function relateddelete($tid, $rid){
		global $prefix, $db, $admin_file;
		
		$rid = intval($rid);
		$db->sql_query("DELETE FROM ". $prefix ."_related WHERE rid='$rid'");
		redirect($admin_file.'.php?op=topicedit&topicid='.$tid);
	}

	function topicmake($topicname, $topicimage, $topictext){
		global $prefix, $db, $admin_file;
		
		$topicname = Fix_Quotes($topicname);
		$topicimage = Fix_Quotes($topicimage);
		$topictext = Fix_Quotes($topictext);
		$db->sql_query("INSERT INTO ". $prefix ."_topics VALUES (NULL,'$topicname','$topicimage','$topictext','0')");
		redirect($admin_file.'.php?op=topicsmanager#Add');
	}

	function topicchange($topicid, $topicname, $topicimage, $topictext, $name, $url){
		global $prefix, $db, $admin_file;
		
		$topicname = Fix_Quotes($topicname);
		$topicimage = Fix_Quotes($topicimage);
		$topictext = Fix_Quotes($topictext);
		$name = Fix_Quotes($name);
		$url = Fix_Quotes($url);
		$topicid = intval($topicid);
		$db->sql_query("UPDATE ". $prefix ."_topics SET topicname='$topicname', topicimage='$topicimage', topictext='$topictext' WHERE topicid='$topicid'");
		if ($name){
			$db->sql_query("INSERT INTO ". $prefix ."_related VALUES (NULL, '$topicid','$name','$url')");
		}
		redirect($admin_file.'.php?op=topicedit&topicid='.$topicid);
	}

	function topicdelete($topicid, $ok=0){
		global $prefix, $db, $ne_config, $admin_file, $topicimage;
		
		// Topic ID
		$topicid = intval($topicid);
		
		if ($ok){
			$row = $db->sql_fetchrow($db->sql_query("SELECT sid FROM ". $prefix ."_stories WHERE topic='$topicid'"));
			$sid = intval($row['sid']);
			// Copyright (c) 2000-2005 by NukeScripts Network
			if ($ne_config['hometopic'] == $topicid){
				ne_save_config("hometopic", "0");
			}
			// Copyright (c) 2000-2005 by NukeScripts Network
			$db->sql_query("delete FROM ". $prefix ."_stories where topic='$topicid'");
			$db->sql_query("delete FROM ". $prefix ."_topics where topicid='$topicid'");
			$db->sql_query("delete FROM ". $prefix ."_related where tid='$topicid'");
			$row2 = $db->sql_fetchrow($db->sql_query("SELECT sid FROM " . $prefix . "_comments WHERE sid='$sid'"));
			$sid = intval($row2['sid']);
			$db->sql_query("DELETE FROM " . $prefix . "_comments WHERE sid='$sid'");
			redirect($admin_file.'.php?op=topicsmanager');
		} else {
			include(NUKE_BASE_DIR."header.php");
			topicsTop(_TOPICSMANAGER);
			$row3 = $db->sql_fetchrow($db->sql_query("SELECT topicimage, topictext FROM ". $prefix ."_topics WHERE topicid='$topicid'"));
			$topicimage = $row3['topicimage'];
			$topictext = $row3['topictext'];
			OpenTable();
			echo '<center><img src="images/topics/'.$topicimage.'" alt="'.$topictext.'" /><br /><br />';
			echo '<strong>'._DELETETOPIC.' '.$topictext.'</strong><br /><br />'._TOPICDELSURE.' <i>'.$topictext.'</i>?<br />'._TOPICDELSURE1.'<br /><br />';
			echo '[ <a href="'.$admin_file.'.php?op=topicsmanager">'._NO.'</a> | <a href="'.$admin_file.'.php?op=topicdelete&topicid='.$topicid.'&ok=1">'._YES.'</a> ]</center>';
			CloseTable();
			include(NUKE_BASE_DIR."footer.php");
		}
	}

	switch($op){
		case "topicsmanager": topicsmanager(); break;
		case "topicedit": topicedit($topicid); break;
		case "topicmake": topicmake($topicname, $topicimage, $topictext); break;
		case "topicdelete": topicdelete($topicid, $ok); break;
		case "topicchange": topicchange($topicid, $topicname, $topicimage, $topictext, $name, $url); break;
		case "relatedsave": relatedsave($tid, $rid, $name, $url); break;
		case "relatededit": relatededit($tid, $rid); break;
		case "relateddelete": relateddelete($tid, $rid); break;
	}
} else {
    include(NUKE_BASE_DIR."header.php");
    topicsTop();
	OpenTable();
    echo "<center><strong>"._ERROR."</strong><br /><br />You do not have administration permission for module \"$module_name\"</center>";
    CloseTable();
    include(NUKE_BASE_DIR."footer.php");
}

?>
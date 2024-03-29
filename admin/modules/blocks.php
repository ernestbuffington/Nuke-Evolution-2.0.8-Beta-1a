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

/*****[CHANGES]**********************************************************
-=[Base]=-
      Nuke Patched                             v3.1.0       06/26/2005
 ************************************************************************/

if(!defined('ADMIN_FILE')) {
   die ("Illegal File Access");
}

global $prefix, $db, $admin_file, $cache;
if(!is_mod_admin('super')) {
    echo "Access Denied";
    die();
}

include(NUKE_INCLUDE_DIR . 'ajax/Sajax.php');

function parse_data($data)
{
    $containers = explode(":", $data);
    foreach($containers AS $container)
    {
        $container = str_replace(")", "", $container);
        $i = 0;
        $lastly = explode("(", $container);
        $values = explode(",", $lastly[1]);
        foreach($values AS $value)
        {
            if($value == '')
            {
                continue;
            }
            $final[$lastly[0]][] = $value;
            $i ++;
        }
    }
    return $final;
}

function update_db($data_array, $col_check)
{
    global $cache, $prefix, $db;
    if (is_array($data_array)) {
        foreach($data_array AS $set => $items)
        {
            $i = 0;
            foreach($items AS $item)
            {
                $sql = "UPDATE " . $prefix . "_blocks SET bposition = '$set', weight = '$i'  WHERE bid = '$item' $col_check";
                $db->sql_query($sql);
                $i++;
            }
        }
    }
    $cache->delete('blocks', 'config');
    $cache->resync();
}
function blocks_update($data)
{
    $data = parse_data($data);
    update_db($data, "AND (bposition = 'l' OR bposition = 'c' OR bposition = 'r' OR bposition = 'd')");
    return 1;
}

function status_update($data) {
    global $prefix, $db, $cache;
    $data = explode(':', $data);
    $bid = $data[0];
    $status = $data[1];
    $status = ($status == 1) ? 0 : 1;
    $sql = "UPDATE " . $prefix . "_blocks SET `active` = '$status' WHERE `bid` = '$bid'";
    $db->sql_query($sql);
    $cache->delete('blocks', 'config');
    $cache->resync();
    return 1;
}
function AddBlock($data) {
    global $cache, $db, $prefix, $admin_file;

    $data['title'] = Fix_Quotes($data['title']);
    $data['headline'] = intval($data['headline']);
    $data['view'] = intval($data['view']);
    if($data['headline'] != 0) {
        $result = $db->sql_query("SELECT sitename, headlinesurl FROM ".$prefix."_headlines WHERE hid='" . $data['headline'] . "'");
        list($title, $data['url']) = $db->sql_fetchrow($result);
        if (empty($data['title'])) {
            $data['title'] = $title;
        }
    }
    if (!isset($data['oldposition']) || empty($data['oldposition'])) {
        $result = $db->sql_query("SELECT weight FROM ".$prefix."_blocks WHERE bposition='" . $data['bposition'] . "' ORDER BY weight DESC");
        list($weight) = $db->sql_fetchrow($result);
        $weight++;
    } else {
        $result = $db->sql_query("SELECT weight FROM ".$prefix."_blocks WHERE bid='" . $data['bid'] . "'");
        $row = $db->sql_fetchrow($result);
        $weight = $row[0];
    }
    $db->sql_freeresult($result);
    $data['btime'] = 0;
    if($data['blockfile'] != '') {
        $data['url'] = '';
        if($data['title'] == '') {
            $data['title'] = str_replace('(block-)|(.php)','',$data['blockfile']);
            $data['title'] = str_replace('_',' ',$data['title']);
        }
    }
    if($data['url'] != '') {
        $data['btime'] = time();
        if(!preg_match('/:///',$data['url'])) { $data['url'] = 'http://'.$data['url']; }
        if(!($content = rss_content($data['url']))) { return false; }
        $data['content'] = $content;
    }
    if (isset($data['view']) && $data['view'] == '6') {
        if (is_array($data['add_groups'])) {
            $data['view'] = "";
            foreach ($data['add_groups'] as $group) {
                $data['view'] .= $group ."-";
            }
        }
    }
    if (!isset($data['oldposition']) || empty($data['oldposition'])) {
       $sql = "INSERT INTO ".$prefix."_blocks (bid, bkey, title, content, url, bposition, weight, active, refresh, time, blanguage, blockfile, view) VALUES (NULL, '', '" . $data['title'] . "', '".Fix_Quotes($data['content'])."', '" . $data['url'] . "', '" . $data['bposition'] . "', '" . $weight . "', '" . $data['active'] . "', '" . $data['refresh'] . "', '" . $data['btime'] . "', '" . $data['blanguage'] . "', '" . $data['blockfile'] . "', '" . $data['view'] . "')";
    } else {
        $data['bposition'] = (!empty($data['bposition'])) ? $data['bposition'] : $data['oldposition'];
        $sql = "UPDATE ".$prefix."_blocks SET bkey='', title='" . $data['title'] . "', content='".Fix_Quotes($data['content'])."', url='" . $data['url'] . "', bposition='" . $data['bposition'] . "', weight='" . $weight . "', active='" . $data['active'] . "', refresh='" . $data['refresh'] . "', time='" . $data['btime'] . "', blanguage='" . $data['blanguage'] . "', blockfile='" . $data['blockfile'] . "', view='" . $data['view'] . "' WHERE bid=".$data['bid'];
    }
    $db->sql_query($sql);
    $cache->delete('blocks', 'config');
    $cache->resync();
    redirect("$admin_file.php?op=blocks");
}
function deleteBlock($bid) {
    global $db, $prefix;
    $db->sql_query("DELETE FROM " . $prefix . "_blocks WHERE bid = '" . $bid . "'");
    return true;
}

function BlocksAdmin() {
    global $prefix, $db, $Sajax, $admin_file;

    define('USE_DRAG_DROP',true);
    global $g2, $element_ids;
    $g2 = 1;
    $element_ids[] = 'l';
    $element_ids[] = 'c';
    $element_ids[] = 'd';
    $element_ids[] = 'r';
    include_once(NUKE_BASE_DIR.'header.php');
    OpenTable();
    echo "<div align=\"center\">\n<a href=\"$admin_file.php?op=blocks\">" . _BLOCK_ADMIN_HEADER . "</a></div>\n";
    echo "<br /><br />";
    echo "<div align=\"center\">\n[ <a href=\"$admin_file.php\">" . _BLOCK_RETURNMAIN . "</a> ]</div>\n";
    CloseTable();
    echo "<br />";
    OpenTable();
    echo "<div align=\"center\">\n";
    echo "<span style=\"background-color : #ff6c6c;\">"._BLOCK_TITLE."</span>&nbsp;-&nbsp;"._BLOCK_INACTIVE."<br />\n";
    echo "<img src=\"images/admin/modules/delete.gif\" border=\"0\" alt=\"\" />&nbsp;-&nbsp;"._BLOCK_LINK_DELETE."<br />\n";
    echo "<img src=\"images/admin/modules/edit.gif\" border=\"0\" alt=\"\" />&nbsp;-&nbsp;"._BLOCK_EDIT."<br /><br />\n";
    echo _BLOCK_ADMIN_NOTE;
    echo "<br /><br />";
    echo "<input type=\"submit\" value=\"Refresh Screen\" onclick=\"window.location.reload()\" />";
    echo "</div>\n";
    CloseTable();
    echo "<br />";
    OpenTable();

    $result = $db->sql_query('SELECT bid, bkey, title, url, bposition, weight, active, blanguage, blockfile, view FROM '.$prefix.'_blocks ORDER BY weight');
    $blocks = array();
    while($row = $db->sql_fetchrow($result)) {
        $blocks[$row['bposition']][] = $row;
    }
    echo "<table border='0' width='100%'>\n";
    echo "<tr><td width='25%' align='left' valign='top'>\n
    <strong>"._BLOCKSADMIN."</strong><br />
    &nbsp;&nbsp;<img src='images/evo/li.gif' border='0' alt=''><a href='".$admin_file.".php?op=newBlock'> "._ADDNEWBLOCK."</a><br />
    </td><td width='25%' align='center' valign='top'>\n";

    echo "<table border='0'>\n";
    echo "<tr><td align='center'>\n";
    echo _LEFTBLOCK;
    echo "<ul id=\"l\" class=\"sortable boxy\">";
    for($i=0,$count=count($blocks['l']);$i<$count;$i++)
    {
      echo '<input type="hidden" id="status_' . $blocks['l'][$i]['bid'] . '" value="' . $blocks['l'][$i]['active'] . '"><li class="' . (($blocks['l'][$i]['active'] == 1) ? "active" : "inactive") . '" id="'.$blocks['l'][$i]['bid'].'" ondblclick="change_status(' . $blocks['l'][$i]['bid'] . ');"><table width="100%"><tr><td width="75%" align="center">'.$blocks['l'][$i]['title'].'</td><td align="right" width="25%"><a href="'.$admin_file.'.php?op=editBlock&amp;bid='.$blocks['l'][$i]['bid'] . '"><img src="images/blocks/edit.gif" border="0" alt="'._EDITBLOCK.'"></a> <a href="javascript:deleteBlock(\'' . $blocks['l'][$i]['bid'] . '\', \'l\');"><img src="images/blocks/delete.gif" border="0" alt=""></a></td></tr></table></li>';
    }
    echo "</ul>";
    echo "</td></tr>\n";
    echo "</table>\n";

    echo "</td><td width='25%' align='center' valign='top'>\n";

    echo "<table border='0'>\n";
    echo "<tr><td align='center'>\n";
    echo _CENTERUP;
    echo "<ul id=\"c\" class=\"sortable boxy\">";
    if (isset($blocks['c']) && is_array($blocks['c'])) {
        for($i=0,$count=count($blocks['c']);$i<$count;$i++)
        {
          echo '<input type="hidden" id="status_' . $blocks['c'][$i]['bid'] . '" value="' . $blocks['c'][$i]['active'] . '"><li class="' . (($blocks['c'][$i]['active'] == 1) ? "active" : "inactive") . '" id="'.$blocks['c'][$i]['bid'].'" ondblclick="change_status(' . $blocks['c'][$i]['bid'] . ');"><table width="100%"><tr><td width="75%" align="center">'.$blocks['c'][$i]['title'].'</td><td align="right" width="25%"><a href="'.$admin_file.'.php?op=editBlock&amp;bid=' . $blocks['c'][$i]['bid'] . '"><img src="images/blocks/edit.gif" border="0" alt="'._EDITBLOCK.'"></a> <a href="javascript:deleteBlock(\'' . $blocks['c'][$i]['bid'] . '\', \'c\');"><img src="images/blocks/delete.gif" border="0" alt=""></a></td></tr></table></li>';
        }
    }
    echo "</ul><br />";
    echo "</td></tr>\n";
    echo "<tr><td align='center'>\n";
    echo _CENTERDOWN;
    echo "<ul id=\"d\" class=\"sortable boxy\">";
    if (isset($blocks['d']) && is_array($blocks['d'])) {
        for($i=0,$count=count($blocks['d']);$i<$count;$i++)
        {
          echo '<input type="hidden" id="status_' . $blocks['d'][$i]['bid'] . '" value="' . $blocks['d'][$i]['active'] . '"><li class="' . (($blocks['d'][$i]['active'] == 1) ? "active" : "inactive") . '" id="'.$blocks['d'][$i]['bid'].'" ondblclick="change_status(' . $blocks['d'][$i]['bid'] . ');"><table width="100%"><tr><td width="75%" align="center">'.$blocks['d'][$i]['title'].'</td><td align="right" width="25%"><a href="'.$admin_file.'.php?op=editBlock&amp;bid=' . $blocks['d'][$i]['bid'] . '"><img src="images/blocks/edit.gif" border="0" alt="'._EDITBLOCK.'"></a> <a href="javascript:deleteBlock(\'' . $blocks['d'][$i]['bid'] . '\', \'d\');"><img src="images/blocks/delete.gif" border="0" alt=""></a></td></tr></table></li>';
        }
    }
    echo "</ul>\n";
    echo "</td></tr>\n";
    echo "</table>\n";

    echo "</td><td width='25%' align='center' valign='top'>\n";
    echo "<table border='0'>\n";
    echo "<tr><td align='center'>\n";
    echo _RIGHTBLOCK;
    echo "<ul id=\"r\" class=\"sortable boxy\">";
    for($i=0,$count=count($blocks['r']);$i<$count;$i++)
    {
      echo '<input type="hidden" id="status_' . $blocks['r'][$i]['bid'] . '" value="' . $blocks['r'][$i]['active'] . '"><li class="' . (($blocks['r'][$i]['active'] == 1) ? "active" : "inactive") . '" id="'.$blocks['r'][$i]['bid'].'" ondblclick="change_status(' . $blocks['r'][$i]['bid'] . ');"><table width="100%"><tr><td width="75%" align="center">'.$blocks['r'][$i]['title'].'</td><td align="right" width="25%"><a href="'.$admin_file.'.php?op=editBlock&amp;bid=' . $blocks['r'][$i]['bid'] . '"><img src="images/blocks/edit.gif" border="0" alt="'._EDITBLOCK.'"></a> <a href="javascript:deleteBlock(\'' . $blocks['r'][$i]['bid'] . '\', \'r\');"><img src="images/blocks/delete.gif" border="0" alt=""></a></td></tr></table></li>';
    }
    echo "</ul>";
    echo "</td></tr>\n";
    echo "</table>\n";

    echo "</td></tr>";
    echo "</table>\n";
    CloseTable();
    include_once(NUKE_BASE_DIR.'footer.php');
}

function block_show($bid) {
    global $prefix, $db, $admin_file;
    $result = $db->sql_query("SELECT bid, bkey, title, content, url, bposition, blockfile, view, refresh, time FROM ".$prefix."_blocks WHERE bid='".$bid."'");
    $row = $db->sql_fetchrow($result);
    define('USE_DRAG_DROP',true);
    global $g2, $element_ids;
    $g2 = 1;
    $element_ids[] = 'l';
    $element_ids[] = 'c';
    $element_ids[] = 'd';
    $element_ids[] = 'r';
    include_once(NUKE_BASE_DIR.'header.php');
    OpenTable();
    echo "<div align=\"center\" class=\"option\">"._BLOCKSADMIN.": "._FUNCTIONS."</div><br /><br />"
    .'[ <a href="'.$admin_file.'.php?op=blocks&amp;change='.$bid.'">'._ACTIVATE.'</a> | <a href="'.$admin_file.'.php?op=blocks&amp;edit='.$bid.'">'._EDIT.'</a> | ';
    if(empty($row['bkey'])) {
        echo '<a href="'.$admin_file.'.php?op=blocks&amp;del='.$bid.'">'._DELETE.'</a> | ';
    }
    echo '<a href="'.$admin_file.'.php?op=blocks">'._BLOCKSADMIN.'</a> ]';
    CloseTable();
    echo '<br /><center>';
    render_blocks($row['bposition'], $row);
    echo '</center>';
    include_once(NUKE_BASE_DIR.'footer.php');
}

function rssfail() {
    DisplayError('<center><strong>'._RSSFAIL.'</strong><br /><br />'._RSSTRYAGAIN.'<br /><br />'._GOBACK.'</center>');
}
function NewBlock($bid='') {
    global $db, $prefix, $admin_file;

    if (!empty($bid)) {
       $edit = $db->sql_fetchrow($db->sql_query("SELECT * FROM " . $prefix . "_blocks WHERE `bid`=".$bid));
    } else {
       list($bid) = $db->sql_fetchrow($db->sql_query("SELECT bid FROM " . $prefix . "_blocks ORDER BY bid DESC LIMIT 1"));
       $bid++;
    }
    include_once(NUKE_BASE_DIR.'header.php');
    OpenTable();
    if (!isset($edit)) {
       echo "<title>"._ADDNEWBLOCK."</title>\n";
    } else {
        echo "<title>"._EDITBLOCK.":".$edit['title']."</title>\n";
    }
    echo '<form name="addblock" method="post" action="'.$admin_file.'.php">';
    echo "<input type=\"hidden\" name=\"op\" value=\"newBlock\">\n";
    $value = (isset($edit)) ? $edit['bid'] : $bid;
    echo "<input type=\"hidden\" name=\"bid\" value=\"" . $value . "\">\n";
    echo "<table border=\"0\" width=\"100%\">\n";
    if (!isset($edit)) {
        echo "<tr><th colspan=\"2\" align=\"center\">"._ADDNEWBLOCK."</th></tr>\n";
    } else {
        echo "<tr><th colspan=\"2\" align=\"center\">"._EDITBLOCK."&nbsp;:&nbsp;".$edit['title']."</th></tr>\n";
    }

    echo "<tr><td>"._TITLE.":</td><td>\n";
    $value = (isset($edit)) ? "value=\"".$edit['title']."\"" : '';
    echo "<input type=\"text\" name=\"title\" size=\"30\" maxlength=\"60\" onkeyup=\"document.title = 'New Block : ' + this.value\" $value /></td></tr>\n";

    echo "<tr><td>"._RSSFILE.":</td><td>\n";
    $value = (isset($edit)) ? "value=\"".$edit['url']."\"" : '';
    echo "<input type=\"text\" name=\"url\" size=\"30\" maxlength=\"200\" $value />&nbsp;&nbsp;\n";
    $headlines[0] = _CUSTOM;
    $res = $db->sql_query("select hid, sitename from ".$prefix."_headlines");
    while (list($hid, $htitle) = $db->sql_fetchrow($res)) {
        $headlines[$hid] = $htitle;
    }
    echo select_box('headline', $value, $headlines)."&nbsp;[ <a href=\"".$admin_file.".php?op=headlines\" target=\"_blank\">Setup</a> ]<br /><span class=\"tiny\">"._SETUPHEADLINES."</span></td></tr>\n";

    echo "<tr><td>"._FILENAME.":</td>\n<td>
        <select name=\"blockfile\">\n
        <option value=\"\" selected=\"selected\">"._NONE."</option>\n";

    $result = $db->sql_query('SELECT blockfile FROM '.$prefix.'_blocks');
    while($row = $db->sql_fetchrow($result)) {
        $allblocks[$row[0]] = 1;
    }
    $value = (isset($edit)) ? $edit['blockfile'] : '';
    $blocksdir = dir('blocks');
    while($func=$blocksdir->read()) {
       if(preg_match('/block-(.*).php$/', $func, $matches)) {
            if(!isset($allblocks[$func]) || $func == $value) {
             $blockslist[] = $func;
            }
        }
    }
    closedir($blocksdir->handle);
    sort($blockslist);
    for ($i=0, $maxi=count($blockslist); $i < $maxi; $i++) {
        if(!empty($blockslist[$i]) && !isset($visblocks[$blockslist[$i]])) {
            $bl = str_replace('(block-)|(.php)','',$blockslist[$i]);
            $bl = str_replace('_',' ',$bl);
            if (!empty($value)) {
                 $checked = ($value == $blockslist[$i]) ? 'SELECTED' : '';
            }
            echo '<option value="'.$blockslist[$i].'" '.$checked.'>'.$bl."</option>\n";
        }
    }
    echo "</select>\n";
    echo "</td></tr>\n";

    echo "<tr><td></td><td><span class=\"tiny\">"._FILEINCLUDE."</span></td></tr>\n";
    echo "<tr><td></td><td><span class=\"tiny\">"._IFRSSWARNING."</span></td></tr>\n";

    $value = (isset($edit)) ? $edit['content'] : '';
    echo "<tr><td>"._CONTENT.":</td><td>\n";
    echo Make_TextArea('content',$value,'addblock');
    echo "</td></tr>\n";
    $value = (isset($edit)) ? $edit['bposition'] : 'l';
    echo '<tr><td>'._POSITION.':</td><td>'.select_box('bposition', $value, array('l'=>_LEFT,'c'=>_CENTERUP,'d'=>_CENTERDOWN,'r'=>_RIGHT)).'</td></tr>';

    if($multilingual) {
        echo '<tr><td>'._LANGUAGE.':</td><td colspan="3">';
        $languages = lang_list();
        echo '<select name="blanguage">';
        echo '<option value=""'.(($currentlang == '') ? ' selected="selected"' : '').'>'._ALL."</option>\n";
        for ($i=0, $j = count($languages); $i < $j; $i++) {
            if($languages[$i] != '') {
                echo '<option value="'.$languages[$i].'"'.(($currentlang == $languages[$i]) ? ' selected="selected"' : '').'>'.ucfirst($languages[$i])."</option>\n";
            }
        }
        echo '</select></td></tr>';
    } else {
        echo '<input type="hidden" name="blanguage" value="" />';
    }
    $value = (isset($edit)) ? $edit['active'] : 1;
    echo '<tr><td>'._ACTIVATE2.'</td><td>'.yesno_option('active', $value)."</td></tr>\n";
    $value = (isset($edit)) ? $edit['refresh'] : 3600;
    echo '<tr><td>'._REFRESHTIME.':</td><td>'.select_box('refresh', $value, array('1800'=>'1/2 '._HOUR,'3600'=>'1 '._HOUR,'18000'=>'5 '._HOURS,'36000'=>'10 '._HOURS,'86400'=>'24 '._HOURS)).'&nbsp;<span class="tiny">'._ONLYHEADLINES."</span></td></tr>\n";
    $value = (isset($edit)) ? $edit['view'] : 0;
    echo '<tr><td>'._VIEWPRIV.'</td><td>';
    switch ($value) {
        case '0':
        case '1':
            $o1 = 'SELECTED';  //All
        break;
        case '2':
            $o2 = 'SELECTED'; //Anon
        break;
        case '3':
            $o3 = 'SELECTED'; //Users
        break;
        case '4':
            $o4 = 'SELECTED';  //Admin
        break;
        default:
            $o6 = 'SELECTED';  //Groups
            $ingroups = explode('-', $value);
        break;
    }
    echo "<select name=\"view\">"
     ."<option value=\"1\" $o1>" . _MVALL . "</option>"
     ."<option value=\"2\" $o2>" . _MVANON . "</option>"
     ."<option value=\"3\" $o3>" . _MVUSERS . "</option>"
     ."<option value=\"4\" $o4>" . _MVADMIN . "</option>"
    ."<option value=\"6\" $o6>"._MVGROUPS."</option>"
     ."</select><br />";
    echo "<span class='tiny'>"._WHATGRDESC."</span><br /><strong>"._WHATGROUPS."</strong> <select name='add_groups[]' multiple size='5'>\n";
    $groupsResult = $db->sql_query("select group_id, group_name from ".$prefix."_bbgroups where group_description <> 'Personal User'");
    while(list($gid, $gname) = $db->sql_fetchrow($groupsResult)) {
        if(@in_array($gid,$ingroups) AND $o6 == 'SELECTED') { $sel = "selected"; } else { $sel = ""; }
        echo "<OPTION VALUE='$gid'$sel>$gname</option>\n";
    }
    echo "</select>\n";
    echo "</td></tr>\n";
    echo "</table><br /><br />\n";
    if (isset($edit)) {
       echo "<input type=\"hidden\" name=\"oldposition\" value=\"" . $edit['bposition'] . "\">\n";
    }
    echo "<input type=\"hidden\" name=\"update\" value=\"1\">\n";
    if (!isset($edit)) {
        echo "<div align=\"center\"><input type=\"submit\" value=\""._CREATEBLOCK."\" /></div>\n";
    } else {
        echo "<div align=\"center\"><input type=\"submit\" value=\""._SAVEBLOCK."\" /></div>\n";
    }
    echo "</form>\n";
    CloseTable();
    include_once(NUKE_BASE_DIR.'footer.php');
}

function BlocksAddScripts() {
    global $Sajax;
    $script = "function change_status(bid) {
            hidden = document.getElementById(\"status_\" + bid);
            elem = document.getElementById(bid);
            var status = hidden.value;
            hidden.value = ((status == 1) ? 0 : 1);
            elem.className = ((status == 1) ? \"inactive\" : \"active\");
            var sendData = bid+\":\"+status;
            x_status_update(sendData, confirm);
            }\n";
   $script .= "function deleteBlock(bid, position) {
            var p = document.getElementById(position);
            var b = document.getElementById(bid);
            p.removeChild(b);
            x_deleteBlock(bid, confirm);
        }\n";
    $script .= "function onDrop() {
            var data = DragDrop.serData('g2');
            x_blocks_update(data, confirm);}\n";
   $script .= "function getSort()
   {
        order = document.getElementById(\"weight\");
      order.value = DragDrop.serData('g1', null);
    }\n";
    $script .= "function showValue()
                {
                  order = document.getElementById(\"weigth\");
                }\n";
    $Sajax->sajax_add_script($script);
}

global $Sajax;
$Sajax = new Sajax();
BlocksAddScripts();
global $g2;
$g2 = 1;
$Sajax->sajax_export("blocks_update", "status_update", "AddBlock", "deleteBlock");
$Sajax->sajax_handle_client_request();

switch($op) {
    case 'blocks':
        BlocksAdmin();
    break;
    case 'editBlock':
    case 'newBlock':
        if (isset($_POST['update'])) {
            AddBlock($_POST);
        }
        $bid = (isset($bid) && is_numeric($bid)) ? intval($bid) : '';
        NewBlock($bid);
    break;
}

?>
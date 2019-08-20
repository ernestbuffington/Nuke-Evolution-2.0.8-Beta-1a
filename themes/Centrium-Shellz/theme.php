<?php
/*=======================================================================
 Nuke-Evolution Basic: Enhanced PHP-Nuke Web Portal System
 =======================================================================*/
/****************************************************************************************/
/* Centrium-Shellz v4.0 Theme                                                           */
/* Designed By: Shellz/The Mortal - www.realmdesignz.com                                */
/*                                                                                      */
/* Centrium-Shellz v4.0 - A public theme designed for Nuke-Evolution v2.0.5 - 2.0.6     */
/* Copyright (c) 2006-2008 By: RealmDesignz.com | All Rights Reserved                   */
/****************************************************************************************/
/* This theme was started by Shellz before her Death in 25.Dec.2005, so I, The Mortal   */
/* thought I would finish the public theme that she started making for Nuke-Evolution   */
/* The original name of the theme was to be called "Centrium", but to remember what she */
/* started, I thought it best to rename it to.... "Centrium-Shellz"                     */
/****************************************************************************************/
/* Updated On: 21 May, 2008 By The Mortal - RealmDesignz.com                            */
/****************************************************************************************/
/*****[CHANGES]**********************************************************
-=[Base]=-
      Nuke Patched                             v3.1.0       09/29/2005
      Theme Management                         v1.0.2       12/14/2005
 ************************************************************************/

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Access Denied');
}

$theme_name = basename(dirname(__FILE__));
/*****[BEGIN]******************************************
 [ Base:    Theme Management                   v1.0.2 ]
 ******************************************************/
include_once(NUKE_THEMES_DIR.$theme_name.'/theme_info.php');
/*****[END]********************************************
 [ Base:    Theme Management                   v1.0.2 ]
 ******************************************************/

/************************************************************/
/* Theme Colors Definition                                  */
/************************************************************/
global $ThemeInfo;
$bgcolor1 = $ThemeInfo['bgcolor1'];
$bgcolor2 = $ThemeInfo['bgcolor2'];
$bgcolor3 = $ThemeInfo['bgcolor3'];
$bgcolor4 = $ThemeInfo['bgcolor4'];
$textcolor1 = $ThemeInfo['textcolor1'];
$textcolor2 = $ThemeInfo['textcolor2'];

/************************************************************/
/* OpenTable Functions                                      */
/************************************************************/
function OpenTable() {
    global $bgcolor1, $bgcolor2, $theme_name;

    echo "<br /><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
    echo "  <tr>\n";
    echo "    <td><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
    echo "      <tr>\n";
    echo "        <td width=\"32\" height=\"34\"><img src=\"themes/".$theme_name."/images/tables/tables_01.gif\" alt=\"\" width=\"32\" height=\"34\" /></td>\n";
    echo "        <td style=\"background-image: url(themes/".$theme_name."/images/tables/tables_02.gif)\"><img src=\"themes/".$theme_name."/images/tables/spacer.gif\" alt=\"\" width=\"1\" height=\"1\" /></td>\n";
    echo "        <td width=\"32\" height=\"34\"><img src=\"themes/".$theme_name."/images/tables/tables_03.gif\" alt=\"\" width=\"32\" height=\"34\" /></td>\n";
    echo "      </tr>\n";
    echo "    </table>\n";
    echo "      <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
    echo "        <tr>\n";
    echo "          <td width=\"32\" style=\"background-image: url(themes/".$theme_name."/images/tables/tables_04.gif)\"><img src=\"themes/".$theme_name."/images/table/spacer.gif\" alt=\"\" width=\"1\" height=\"1\" /></td>\n";
    echo "          <td style=\"background-color: #1c2c39;\">";
}

function OpenTable2() {
    global $bgcolor1, $bgcolor2;

    echo "<table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" align=\"center\"><tr><td class=extras>\n";
    echo "<table border=\"0\" cellspacing=\"1\" cellpadding=\"8\" ><tr><td>\n";
}

function CloseTable() {
    global $theme_name;
    echo "</td>\n";
    echo "          <td width=\"32\" style=\"background-image: url(themes/".$theme_name."/images/tables/tables_06.gif)\"><img src=\"themes/".$theme_name."/images/table/spacer.gif\" alt=\"\" width=\"1\" height=\"1\" /></td>\n";
    echo "        </tr>\n";
    echo "      </table>\n";
    echo "      <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
    echo "        <tr>\n";
    echo "          <td width=\"32\" height=\"35\"><img src=\"themes/".$theme_name."/images/tables/tables_07.gif\" alt=\"\" width=\"32\" height=\"35\" /></td>\n";
    echo "          <td style=\"background-image: url(themes/".$theme_name."/images/tables/tables_08.gif)\"><img src=\"themes/".$theme_name."/images/tables/spacer.gif\" alt=\"\" width=\"1\" height=\"1\" /></td>\n";
    echo "          <td width=\"32\"><img src=\"themes/".$theme_name."/images/tables/tables_09.gif\" alt=\"\" width=\"32\" height=\"35\" /></td>\n";
    echo "        </tr>\n";
    echo "      </table>\n";
    echo "  </td>\n";
    echo "  </tr>\n";
    echo "</table>";
}

function CloseTable2() {
    echo "</td></tr></table></td></tr></table>\n";
}

/************************************************************/
/* Function FormatStory()                                   */
/************************************************************/
function FormatStory($thetext, $notes, $aid, $informant, $informantwrites) {
    if (!empty($notes)) {
        $notes = "<br /><br /><strong>"._NOTE."</strong>&nbsp;<i>$notes</i>\n";
    } else {
        $notes = '';
    }
    if ($aid == $informant) {
        echo "<span class=\"content\" color=\"#505050\">$thetext$notes</span>\n";
    } else {
        if($informantwrites == 0) {
            if(!empty($informant)) {
                if(is_array($informant)) {
                    $boxstuff = "<a href=\"modules.php?name=Your_Account&amp;op=userinfo&amp;username=$informant[0]\">$informant[1]</a> ";
                } else {
                    $boxstuff = "<a href=\"modules.php?name=Your_Account&amp;op=userinfo&amp;username=$informant\">$informant</a> ";
                }
            } else {
                $boxstuff = _ANONYMOUS;
            }
            $boxstuff .= _WRITES." <i>\"$thetext\"</i>$notes\n";
        } else {
            $boxstuff .= "$thetext$notes\n";
        }

        echo "<span class=\"content\" color=\"#505050\">$boxstuff</span>\n";
    }
}

/************************************************************/
/* Function themeheader()                                   */
/************************************************************/
function themeheader() {
    global $user, $userinfo, $cookie, $prefix, $sitekey, $db, $name, $banners, $user_prefix, $ThemeInfo, $theme_name;

    $username = $cookie[1];
    if ($username == "") {
        $username = "Anonymous";
    }	
$uid = $userinfo['user_id']; $username = $userinfo['username'];
$pms = $db->sql_numrows($db->sql_query("SELECT privmsgs_to_userid FROM ".$prefix."_bbprivmsgs WHERE privmsgs_to_userid='$uid' AND (privmsgs_type='5' OR privmsgs_type='1')"));

    if ($username == "") {
        $username = "Anonymous";
    }
    if ($username == "Anonymous") {
        $theuser = "<table style='width: 100%; border: 0px;' cellpadding='0' cellspacing='0'>
                      <tr>
                        <td style='height: 19px; padding-bottom:10px; vertial-align: middle; color: #c3c3c3; font-size: 10px;' align='right' nowrap>Please <a href='modules.php?name=Your_Account' class='lowernav'><strong>Login</strong></a> or <a href='modules.php?name=Your_Account&op=new_user' class='lowernav'>&nbsp;<strong>Register</strong></a>&nbsp;&nbsp;</td>
                      </tr>
                    </table>";
    } else {
        if (is_admin($admin)) {
            $theuser = "<table style='width: 100%; border: 0px;' cellpadding='0' cellspacing='0'>
                          <tr>
                            <td style='height: 19px; padding-bottom:10px; vertial-align: middle; color: #c3c3c3; font-size: 10px;' align='right' nowrap>
                              <a href='modules.php?name=Private_Messages' class='lowernav'> <strong>Private Messages</strong> [ $pms ]&nbsp;<strong>&middot;</strong>&nbsp;</a>
                              <a href='modules.php?name=Your_Account&op=logout' class='lowernav'> <strong>Logout</strong></a></td>
                          </tr>
                        </table>";
        } else {
            $theuser = "<table style='width: 100%; border: 0px;' cellpadding='0' cellspacing='0'>
                          <tr>
                            <td style='height: 19px; padding-bottom:10px; vertial-align: middle; color: #c3c3c3; font-size: 10px;' align='right' nowrap>
                              <a href='modules.php?name=Private_Messages' class='lowernav'> <strong>Private Messages</strong> [ $pms ]&nbsp;<strong>&middot;</strong>&nbsp;</a>
                              <a href='modules.php?name=Your_Account&op=logout' class='lowernav'> <strong>Logout</strong></a></td>
                          </tr>
                        </table>";
        }
    }

    echo "<body>\n";

echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
echo "<tr>\n";
echo "  <td rowspan=\"2\">\n";
echo "    <img src=\"themes/".$theme_name."/images/hd/header_01.gif\" width=\"31\" height=\"180\" alt=\"\" /></td>\n";
echo "  <td style=\"width: 404px; background-image: url(themes/".$theme_name."/images/hd/header_02.gif)\" height=\"19\" /><div style=\"padding-left:80px; padding-top:4px;\"><font color=\"#3babf5\">$username</font></div></td>\n";
echo "          <td rowspan=\"2\" style=\"width: 100%; background-image: url(themes/".$theme_name."/images/hd/header_04.gif)\"><img src=\"themes/".$theme_name."/images/spacer.gif\" alt=\"\" width=\"1\" height=\"1\" /></td>\n";
//BEGIN: BANNER AD SYSTEM
$ads = ads(0);
if(empty($ads)) {
echo "          <td rowspan=\"2\" style=\"width: 100%; background-image: url(themes/".$theme_name."/images/hd/header_04.gif)\"><img src=\"themes/".$theme_name."/images/spacer.gif\" alt=\"\" width=\"1\" height=\"1\" /></td>\n";
} else {
echo "          <td rowspan=\"2\" style=\"width: 100%; background-image: url(themes/".$theme_name."/images/hd/header_04.gif)\">$ads</td>\n";
}
//END: BANNER AD SYSTEM
echo "  <td rowspan=\"2\">\n";
echo "    <img src=\"themes/".$theme_name."/images/hd/header_05.png\" width=\"172\" height=\"180\" alt=\"\" /></td>\n";
echo "</tr>\n";
echo "<tr>\n";
//IMAGE: HEADER LOGO
echo "  <td>\n";
echo "    <img src=\"themes/".$theme_name."/images/hd/logo.png\" width=\"404\" height=\"161\" alt=\"\" /></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
echo "<tr>\n";
echo "  <td colspan=\"2\">\n";
echo "          <object type=\"application/x-shockwave-flash\" data=\"themes/".$theme_name."/images/hd/hnav.swf?link1=" . urlencode($ThemeInfo['link1']) . "&amp;link1text=" . urlencode($ThemeInfo['link1text']) . "&amp;link2=" . urlencode($ThemeInfo['link2']) . "&amp;link2text=" . urlencode($ThemeInfo['link2text']) . "&amp;link3=" . urlencode($ThemeInfo['link3']) . "&amp;link3text=" . urlencode($ThemeInfo['link3text']) . "&amp;link4=" . urlencode($ThemeInfo['link4']) . "&amp;link4text=" . urlencode($ThemeInfo['link4text']) . "&amp;link5=" . urlencode($ThemeInfo['link5']) . "&amp;link5text=" . urlencode($ThemeInfo['link5text']) . "\" width=\"434\" height=\"44\">\n";
echo "            <param name=\"wmode\" value=\"transparent\">\n";
echo "            <param name=\"movie\" value=\"themes/".$theme_name."/images/hd/hnav.swf?link1=" . urlencode($ThemeInfo['link1']) . "&amp;link1text=" . urlencode($ThemeInfo['link1text']) . "&amp;link2=" . urlencode($ThemeInfo['link2']) . "&amp;link2text=" . urlencode($ThemeInfo['link2text']) . "&amp;link3=" . urlencode($ThemeInfo['link3']) . "&amp;link3text=" . urlencode($ThemeInfo['link3text']) . "&amp;link4=" . urlencode($ThemeInfo['link4']) . "&amp;link4text=" . urlencode($ThemeInfo['link4text']) . "&amp;link5=" . urlencode($ThemeInfo['link5']) . "&amp;link5text=" . urlencode($ThemeInfo['link5text']) . "\" />\n";
echo "            </object>\n";
echo "</td>\n";
echo "  <td>";
echo "    <img src=\"themes/".$theme_name."/images/hd/header_08.gif\" width=\"84\" height=\"44\" alt=\"\" /></td>\n";
echo "  <td style=\"width: 100%; background-image: url(themes/".$theme_name."/images/hd/header_09.gif)\"><img src=\"themes/".$theme_name."/images/spacer.gif\" alt=\"\" width=\"100%\" height=\"1\" />$theuser</td>\n";
echo "  <td><img src=\"themes/".$theme_name."/images/hd/header_10.gif\" width=\"43\" height=\"44\" alt=\"\" /></td>\n";
echo "</tr>\n";
echo "</table>";

    echo "\n<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\">\n";
    echo "        <tr valign=\"top\">\n";
    echo "        <td style=\"width: 25px; background-image: url(themes/".$theme_name."/images/leftside.gif)\" valign=\"top\"><img src=\"themes/".$theme_name."/images/spacer.gif\" width=\"25\" height=\"4\" border=\"0\" alt=\"\" /></td>\n";
    echo "        <td valign=\"top\">\n";

    if(blocks_visible('left')) {
            blocks('left');
            echo "    </td>\n";
            echo " <td style=\"width: 5px;\" valign =\"top\"><img src=\"themes/".$theme_name."/images/spacer.gif\" alt=\"\" width=\"5\" height=\"1\" border=\"0\" /></td>\n";
            echo " <td width=\"100%\">\n";
    } else {
            echo "    </td>\n";
            echo " <td style=\"width: 1px;\" valign =\"top\"><img src=\"themes/".$theme_name."/images/spacer.gif\" alt=\"\" width=\"1\" height=\"1\" border=\"0\" /></td>\n";
            echo " <td width=\"100%\">\n";
    }
}

/************************************************************/
/* Function themefooter()                                   */
/************************************************************/
function themefooter() {
    global $index, $user, $userinfo, $cookie, $banners, $prefix, $db, $admin, $adminmail, $nukeurl, $theme_name;

// Banner Ad System in the Middle of the Site

    if(blocks_visible('right') && !defined('ADMIN_FILE')) {
        echo "</td>\n";
        echo "        <td style=\"width: 5px;\" valign=\"top\"><img src=\"themes/".$theme_name."/images/spacer.gif\" alt=\"\" width=\"5\" height=\"1\" /></td>\n";
        echo "       <td style=\"width: 183px;\" valign=\"top\">\n";
        blocks('right');
    }
    echo "        </td>\n";
    echo "        <td style=\"width: 25px; background-image: url(themes/".$theme_name."/images/rightside.gif)\" valign=\"top\"><img src=\"themes/".$theme_name."/images/spacer.gif\" alt=\"\"  width=\"25\" height=\"1\" /></td>\n";
    echo "        </tr>\n";
    echo "</table>\n\n\n";

$ads = ads(2);
echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
echo "  <tr>\n";
echo "    <td rowspan=\"3\">\n";
// DO NOT EDIT, MODIFY, REMOVE OR HIDE CREDIT LINK ON FOOTER
echo "      <a href=\"http://www.realmdesignz.com\"><img src=\"themes/".$theme_name."/images/ft/footer_01.jpg\" alt=\"Theme Designed By: Realm Designz\" width=\"139\" height=\"156\" border=\"0\" title=\"Theme Designed By: Realm Designz\" /></a></td>\n";
echo "    <td rowspan=\"3\" style=\"background-image: url(themes/".$theme_name."/images/ft/footer_02.jpg)\" width=\"50%\" height=\"156\"><img src=\"themes/".$theme_name."/images/spacer.gif\" alt=\"\" width=\"1\" height=\"1\" /></td>\n";
echo "    <td rowspan=\"3\">\n";
echo "      <img src=\"themes/".$theme_name."/images/ft/footer_03.jpg\" width=\"18\" height=\"156\" alt=\"\" /></td>\n";
echo "    <td>\n";
echo "      <img src=\"themes/".$theme_name."/images/ft/footer_05.gif\" width=\"468\" height=\"38\" alt=\"\" /></td>\n";
echo "    <td rowspan=\"3\">\n";
echo "      <img src=\"themes/".$theme_name."/images/ft/footer_07.jpg\" width=\"18\" height=\"156\" alt=\"\" /></td>\n";
echo "    <td rowspan=\"3\" style=\"background-image: url(themes/".$theme_name."/images/ft/footer_08.jpg)\" width=\"50%\" height=\"156\"><img src=\"themes/".$theme_name."/images/spacer.gif\" alt=\"\" width=\"1\" height=\"1\" /></td>\n";
echo "    <td rowspan=\"3\">\n";
// DO NOT EDIT, MODIFY, REMOVE OR HIDE CREDIT LINK ON FOOTER
echo "    <td rowspan=\"3\"><a href=\"http://www.nuke-evolution.com\"><img src=\"themes/".$theme_name."/images/ft/footer_09.jpg\" alt=\"Nuke-Evolution Official Site\" width=\"139\" height=\"156\" border=\"0\" title=\"Nuke-Evolution Official Site\" /></a></td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td style=\"background-image: url(themes/".$theme_name."/images/ft/footer_10.gif)\" width=\"468\" height=\"90\">$ads</td>\n";
echo "  </tr>\n";
echo "  <tr>\n";
echo "    <td><img src=\"themes/".$theme_name."/images/ft/footer_11.gif\" width=\"468\" height=\"28\" alt=\"\" /></td>\n";
echo "  </tr>\n";
echo "</table><br />\n";
//FOOTER MESSAGE: DO NOT REMOVE, EDIT, MODIFY, OR DELETE, MUST STAY INTACT
echo "<center><marquee id=\"marquee\" behavior=\"scroll\" direction=\"up\" scrollamount=\"1\" scrolldelay= \"90\" width=\"430\" height=\"50\" onmouseover='this.stop()' onmouseout='this.start()'><font color=\"#e2e2e2\" class=\"ftrsmall\"><strong><center><a href=\"modules.php?name=Spambot_Killer\" target=\"_blank\">Spambot Killer</a> | <a href=\"modules.php?name=Site_Map\" target=\"_blank\"><strong>Site Map</strong></a><br /><a href=\"rss.php?feed=news\" target=\"_blank\"><img border=\"0\" src=\"images/powered/feed_20_news.png\" width=\"94\" height=\"15\" alt=\"[News Feed]\" title=\"News Feed\" /></a> <a href=\"rss.php?feed=forums\" target=\"_blank\"><img border=\"0\" src=\"images/powered/feed_20_forums.png\" width=\"94\" height=\"15\" alt=\"[Forums Feed]\" title=\"Forums Feed\" /></a> <a href=\"rss.php?feed=downloads\" target=\"_blank\" /><img border=\"0\" src=\"images/powered/feed_20_down.png\" width=\"94\" height=\"15\" alt=\"[Downloads Feed]\" title=\"Downloads Feed\" /></a> <a href=\"rss.php?feed=weblinks\" target=\"_blank\"><img border=\"0\" src=\"images/powered/feed_20_links.png\" width=\"94\" height=\"15\" alt=\"[Web Links Feed]\" title=\"Web Links Feed\" /></a> <a href=\"http://tool.motoricerca.info/robots-checker.phtml?checkreferer=1\" target=\"_blank\"><img border=\"0\" src=\"images/powered/valid-robots.png\" width=\"80\" height=\"15\" alt=\"[Validate robots.txt]\" title=\"Validate robots.txt\" /></a><br /><br />Images And Content Are Copyright Of Their Respective Owners.<br />All Rights Reserved.<br /><br />
<center><strong>PHP-Nuke Copyright &copy; 2008 By: Francisco Burzi.<br /> This is free software, you may redistribute it under <a href=\"http://www.gnu.org/licenses/gpl.txt\">GPL</a>. PHP-Nuke comes with absolutely no warranty, for details, see the <a href=\"http://phpnuke.org/modules.php?name=Commercial_License\">license</a>.<br /><br />
Powered By: <a href=\"http://www.nuke-evolution.com\">Nuke-Evolution</a><br /><br />Centrium-Shellz Theme<br /> Designed By: Shellz/TheMortal<br /><br />Copyright &copy; 2006-2008 <a href=\"http://www.realmdesignz.com\">www.realmdesignz.com</a><br />All Rights Reserved.</center></strong></center></strong></font></marquee></center>\n";
}

/************************************************************/
/* Function themeindex()                                    */
/* This function format the stories on the Homepage         */
/************************************************************/
function themeindex ($aid, $informant, $time, $title, $counter, $topic, $thetext, $notes, $morelink, $topicname, $topicimage, $topictext) {
    global $anonymous, $tipath, $theme_name, $sid;

    $ThemeSel = get_theme();
    if(!empty($topicimage)) {
        if (file_exists("themes/$ThemeSel/images/topics/$topicimage")) {
            $t_image = "themes/$ThemeSel/images/topics/$topicimage";
        } else {
            $t_image = "$tipath$topicimage";
        }
        $topic_img = "<td width=\"25%\" align=\"center\" class=\"extra\"><a href=\"modules.php?name=News&amp;new_topic=".$topic."\"><img src=\"".$t_image."\" border=\"0\" alt=\"$topictext\" title=\"$topictext\" /></a></td>";
    } else {
        $topic_img = "";
    }
    if (!empty($notes)) {
        $notes = "<br /><br /><strong>"._NOTE."</strong> $notes\n";
    } else {
        $notes = "";
    }
    $content = '';
    if ($aid == $informant) {
        $content = "$thetext$notes\n";
    } else {
        if(defined('WRITES')) {
            if(!empty($informant)) {
                if(is_array($informant)) {
                    $content = "<a href=\"modules.php?name=Your_Account&amp;op=userinfo&amp;username=$informant[0]\">$informant[1]</a> ";
                } else {
                    $content = "<a href=\"modules.php?name=Your_Account&amp;op=userinfo&amp;username=$informant\">$informant</a> ";
                }
            } else {
                $content = "$anonymous ";
            }
            $content .= _WRITES." \"$thetext\"$notes\n";
        } else {
            $content .= "$thetext$notes\n";
        }
    }
    $posted = _POSTEDBY." ";
    $posted .= get_author($aid);
    $posted .= " "._ON." $time  ";
    $datetime = substr($morelink, 0, strpos($morelink, "|") - strlen($morelink));
    $morelink = substr($morelink, strlen($datetime) + 2);
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
    echo "  <tr>\n";
    echo "  <td><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
    echo "  <tr>\n";
    echo "  <td style=\"width: 70px; background-image: url(themes/".$theme_name."/images/story/story_01.gif)\" height=\"47\"></td>\n";
    echo "  <td  height=\"47\" style=\"background-image: url(themes/".$theme_name."/images/story/story_02.gif)\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
    echo "  <tr>\n";
    echo "  <td><font class=\"storytitle\"><div style=\"padding-bottom:6px;\"><strong>&nbsp;&nbsp;$title</strong></div></font></td>\n";
    echo "  </tr>\n";
    echo "</table></td>\n";
    echo "  <td style=\"width: 58px; background-image: url(themes/".$theme_name."/images/story/story_03.gif)\" height=\"47\"></td>\n";
    echo "  </tr>\n";
    echo "</table>\n";
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
    echo "  <tr>\n";
    echo "  <td style=\"width: 21px; background-image: url(themes/".$theme_name."/images/story/story_06.gif)\"><img src=\"themes/".$theme_name."/images/story/story_06.gif\" width=\"21\" height=\"2\" alt=\"\" /></td>\n";
    echo "  <td bgcolor=\"#1C2C39\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n";
    echo " <tr>\n";
    echo "      ".$topic_img."\n";
    echo "  <td width=\"75%\" class=\"content\" valign=\"top\">$content<br /><br /><hr /><div align=\"right\">$posted<br /> $datetime $topictext | $morelink</div></td>\n";
    echo "  </tr>\n";
    echo "</table></td>\n";
    echo "  <td style=\"width: 21px; background-image: url(themes/".$theme_name."/images/story/story_08.gif)\"><img src=\"themes/".$theme_name."/images/story/story_08.gif\" width=\"21\" height=\"2\" alt=\"\" /></td>\n";
    echo "  </tr>\n";
    echo "</table>\n";
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
    echo "  <tr>\n";
    echo "  <td width=\"32\"><img src=\"themes/".$theme_name."/images/story/story_09.gif\" width=\"32\" height=\"36\" alt=\"\" /></td>\n";
    echo "  <td height=\"36\" style=\"width: 100%; background-image: url(themes/".$theme_name."/images/story/story_10.gif)\"></td>\n";
    echo "  <td width=\"31\"><img src=\"themes/".$theme_name."/images/story/story_11.gif\" width=\"31\" height=\"36\" alt=\"\" /></td>\n";
    echo "  </tr>\n";
    echo "</table>\n";
    echo "  </td>\n";
    echo "  </tr>\n";
    echo "</table>";
}

/************************************************************/
/* Function themearticle()                                  */
/************************************************************/
function themearticle ($aid, $informant, $datetime, $title, $thetext, $topic, $topicname, $topicimage, $topictext) {
    global $admin, $sid, $tipath, $theme_name;

    $ThemeSel = get_theme();
    if(!empty($topicimage)) {
        if (file_exists("themes/$ThemeSel/images/topics/$topicimage")) {
            $t_image = "themes/$ThemeSel/images/topics/$topicimage";
        } else {
            $t_image = "$tipath$topicimage";
        }
        $topic_img = "<td width=\"25%\" align=\"center\" class=\"extra\"><a href=\"modules.php?name=News&amp;new_topic=".$topic."\"><img src=\"".$t_image."\" border=\"0\" alt=\"$topictext\" title=\"$topictext\" /></a></td>";
    } else {
        $topic_img = "";
    }
    $posted = _POSTEDON." $datetime "._BY." ";
    $posted .= get_author($aid);
    if (!empty($notes)) {
        $notes = "<br /><br /><strong>"._NOTE."</strong> <i>$notes</i>\n";
    } else {
        $notes = "";
    }
    $content = '';
    if ($aid == $informant) {
        $content = "$thetext$notes\n";
    } else {
        if(defined('WRITES')) {
            if(!empty($informant)) {
                if(is_array($informant)) {
                    $content = "<a href=\"modules.php?name=Your_Account&amp;op=userinfo&amp;username=$informant[0]\">$informant[1]</a> ";
                } else {
                    $content = "<a href=\"modules.php?name=Your_Account&amp;op=userinfo&amp;username=$informant\">$informant</a> ";
                }
            } else {
                $content = "$anonymous ";
            }
            $content .= ""._WRITES." <i>\"$thetext\"</i>$notes\n";
        } else {
            $content .= "$thetext$notes\n";
        }
    }
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
    echo "  <tr>\n";
    echo "  <td><table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
    echo "  <tr>\n";
    echo "  <td style=\"width: 70px; background-image: url(themes/".$theme_name."/images/story/story_01.gif)\" height=\"47\"></td>\n";
    echo "  <td  height=\"47\" style=\"background-image: url(themes/".$theme_name."/images/story/story_02.gif)\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
    echo "  <tr>\n";
    echo "  <td><font class=\"storytitle\"><div style=\"padding-bottom:6px;\"><strong>&nbsp;&nbsp;$title</strong></div></font></td>\n";
    echo "  </tr>\n";
    echo "</table></td>\n";
    echo "  <td style=\"width: 58px; background-image: url(themes/".$theme_name."/images/story/story_03.gif)\" height=\"47\"></td>\n";
    echo "  </tr>\n";
    echo "</table>\n";
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
    echo "  <tr>\n";
    echo "  <td style=\"width: 21px; background-image: url(themes/".$theme_name."/images/story/story_06.gif)\"><img src=\"themes/".$theme_name."/images/story/story_06.gif\" width=\"21\" height=\"2\" alt=\"\" /></td>\n";
    echo "  <td bgcolor=\"#1C2C39\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n";
    echo "  <tr>\n";
    echo "      ".$topic_img."\n";
    echo "  <td width=\"75%\" class=\"content\" valign=\"top\">$content<br /><br /><hr /><div align=\"right\">$posted<br /> $datetime $topictext | $morelink</div></td>\n";
    echo "  </tr>\n";
    echo "</table></td>\n";
    echo "  <td style=\"width: 21px; background-image: url(themes/".$theme_name."/images/story/story_08.gif)\"><img src=\"themes/".$theme_name."/images/story/story_08.gif\" width=\"21\" height=\"2\" alt=\"\" /></td>\n";
    echo "  </tr>\n";
    echo "</table>\n";
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
    echo "  <tr>\n";
    echo "  <td width=\"32\"><img src=\"themes/".$theme_name."/images/story/story_09.gif\" width=\"32\" height=\"36\" alt=\"\" /></td>\n";
    echo "  <td height=\"36\" style=\"width: 100%; background-image: url(themes/".$theme_name."/images/story/story_10.gif)\" /></td>\n";
    echo "  <td width=\"31\"><img src=\"themes/".$theme_name."/images/story/story_11.gif\" width=\"31\" height=\"36\" alt=\"\" /></td>\n";
    echo "  </tr>\n";
    echo "</table>\n";
    echo "  </td>\n";
    echo "  </tr>\n";
    echo "</table>";
}
function themecenterbox($title, $content) {
    OpenTable();
    echo '<center><span class="option"><strong>'.$title.'</strong></span></center><br />'.$content;
    CloseTable();
    echo '<br />';
}

function themepreview($title, $hometext, $bodytext='', $notes='') {
    echo '<strong>'.$title.'</strong><br /><br />'.$hometext;
    if (!empty($bodytext)) {
        echo '<br /><br />'.$bodytext;
    }
    if (!empty($notes)) {
        echo '<br /><br /><strong>'._NOTE.'</strong>&nbsp;<i>'.$notes.'</i>';
    }
}

/************************************************************/
/* Function themesidebox()                                  */
/************************************************************/
function themesidebox($title, $content, $bid=0) {
    global $theme_name;

echo "<table width=\"183\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
echo "  <tr>\n";
echo "    <td><table width=\"183\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
echo "  <tr>\n";
echo "    <td bgcolor=\"#1b384a\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
echo "        <tr>\n";
echo "          <td bgcolor=\"\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
echo "              <tr>\n";
echo "                <td width=\"183\" align=\"center\" height=\"47\" style=\"background-image: url(themes/".$theme_name."/images/blocks/bt.gif)\"><div style=\"padding-bottom:14px;\" align=\"center\"><br /><font class=\"blocktitle\">$title</font></div></td>\n";
echo "              </tr>\n";
echo "              <tr>\n";
echo "                <td style=\"background-image: url(themes/".$theme_name."/images/blocks/bm.gif)\"><table width=\"72%\" align=\"center\" border=\"0\" cellspacing=\"2\" cellpadding=\"0\">\n";
echo "                    <tr>\n";
echo "                      <td>$content</td>\n";
echo "                    </tr>\n";
echo "                  </table></td>\n";
echo "              </tr>\n";
echo "              <tr>\n";
echo "                <td height=\"36\" width=\"183\"><img src=\"themes/".$theme_name."/images/blocks/bb.gif\" border=\"0\" alt=\"\" /></td>\n";
echo "              </tr>\n";
echo "            </table></td>\n";
echo "        </tr>\n";
echo "      </table></td>\n";
echo "  </tr>\n";
echo "</table>\n";
echo "</td>\n";
echo "  </tr>\n";
echo "</table>";
}

?>
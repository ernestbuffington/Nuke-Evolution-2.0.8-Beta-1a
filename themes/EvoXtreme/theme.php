<?php
/**********************************************************************
   Nuke Evolution: EvoXtreme Theme
   ===============================
   Designed By   : SgtLegend - www.sgtlegend.com  
   Theme Version : v1.0 (80% Width)
   Copyright     : A private theme for use with nuke-evolution.com
				   
				   Copyright © 2010 SgtLegend | All Rights Reserved
 **********************************************************************/

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Access Denied');
}

$theme_name = basename(dirname(__FILE__));
$more_js = '<meta http-equiv="X-UA-Compatible" content="IE=8" />';

/************************************************************
 [ Theme Management Section                                 ]
 ************************************************************/
include(NUKE_THEMES_DIR.$theme_name.'/theme_info.php');

/************************************************************
 [ Theme Colors Definition                                  ]
 ************************************************************/
global $ThemeInfo;
$bgcolor1 = $ThemeInfo['bgcolor1'];
$bgcolor2 = $ThemeInfo['bgcolor2'];
$bgcolor3 = $ThemeInfo['bgcolor3'];
$bgcolor4 = $ThemeInfo['bgcolor4'];
$textcolor1 = $ThemeInfo['textcolor1'];
$textcolor2 = $ThemeInfo['textcolor2'];

/************************************************************
 [ OpenTable Functions                                      ]
 ************************************************************/
function OpenTable(){
	echo '<div class="tables_hd_wrap">';
	echo '  <div class="tables_hd_right"></div>';
	echo '  <div class="tables_hd_left"></div>';
	echo '  <div class="tables_hd_stretch"></div>';
	echo '</div>';
	echo '<div class="tables_bd_wrap">';
	echo '  <div class="tables_bd_right"></div>';
	echo '  <div class="tables_bd_left"></div>';
	echo '  <div class="tables_bd_stretch">';
}

function OpenTable2(){
    global $bgcolor1, $bgcolor2;

    echo '<table border="0" cellspacing="1" cellpadding="0" align="center"><tr><td class="extras">';
    echo '<table border="0" cellspacing="1" cellpadding="8"><tr><td>';
}

function CloseTable(){
	echo '  </div>';
	echo '</div>';
	echo '<div class="tables_ft_wrap">';
	echo '  <div class="tables_ft_right"></div>';
	echo '  <div class="tables_ft_left"></div>';
	echo '  <div class="tables_ft_stretch"></div>';
	echo '</div>';
}

function CloseTable2(){
    echo '</td></tr></table></td></tr></table>';
}

/************************************************************
 [ Function FormatStory()                                   ]
 ************************************************************/
function FormatStory($thetext, $notes, $aid, $informant){
    global $anonymous;
	
	$notes = (!empty($notes)) ? '<br /><br /><strong>'._NOTE.'</strong> <em>'.$notes.'</em>' : '';
    if ($aid == $informant){
        echo '<span class="content" color="#505050">'.$thetext.$notes.'</span>';
    } else {
        if (defined('WRITES')){
            if (!empty($informant)){
				$boxstuff = (is_array($informant)) ? '<a href="modules.php?name=Your_Account&amp;op=userinfo&amp;username='.$informant[0].'">'.$informant[1].'</a> ' : '<a href="modules.php?name=Your_Account&amp;op=userinfo&amp;username='.$informant.'">'.$informant.'</a> ';
            } else {
                $boxstuff = $anonymous.' ';
            }
            $boxstuff .= _WRITES.' <em>'.$thetext.'</em>'.$notes;
        } else {
            $boxstuff .= $thetext.$notes;
        }
        echo '<span class="content" color="#505050">'.$boxstuff.'</span>';
    }
}

/************************************************************
 [ Function themeheader()                                   ]
 ************************************************************/
function themeheader(){
    global $user, $cookie, $prefix, $sitekey, $db, $name, $banners, $user_prefix, $admin_file, $module_name, $theme_name, $ThemeInfo;

    cookiedecode($user);
    $username = $cookie[1];
    list($uid) = $db->sql_fetchrow($db->sql_query("SELECT user_id FROM ". $user_prefix ."_users WHERE username='$username'"));
    $pms = $db->sql_numrows($db->sql_query("SELECT privmsgs_to_userid FROM ". $prefix ."_bbprivmsgs WHERE privmsgs_to_userid='$uid' AND (privmsgs_type='5' OR privmsgs_type='1')"));
	$username = (empty($username)) ? 'Anonymous' : $username;
	
    if ($username == 'Anonymous'){
        $theuser  = $username.',<br /><br />Please <a href="modules.php?name=Your_Account"><strong>Login</strong></a> or <a href="modules.php?name=Your_Account&amp;op=new_user"><strong>Register</strong></a>';
    } else {
		$theuser .= $username.', [ <a href="modules.php?name=Your_Account&amp;op=logout">Logout</a> ]<br /><br />';
		$theuser .= 'You have (<a href="modules.php?name=Private_Messages">'.$pms.'</a>) Private Messages<br />';
		$theuser .= '<a href="modules.php?name=Profile&amp;mode=editprofile">Edit Profile</a> | ';
		$theuser .= '<a href="modules.php?name=Your_Account">Your Account</a>';
		if (is_admin()){
			$theuser .= ' | <a href="'.$admin_file.'.php">Administration</a>';
		}
    }
	
	// Theme Width
	if ($ThemeInfo['themewidth'] != '990px' && $ThemeInfo['themewidth'] != '80%'){
		switch($ThemeInfo['themewidth']){
			case '990': $width = '990px'; break;
			case '80': $width = '80%'; break;
			default: $width = '80%'; break;
		}
		$ThemeInfo['themewidth'] = $width;
	}
	
    echo '<body>';
	
	echo '<div id="big_wrap">';
	echo '  <!-- Header Start -->';
	echo '  <div id="header_wrap">';
	echo '    <div id="header_logo"></div>';
	echo '    <div id="header_nav">';
	echo '      <ul>';
	echo '        <li class="nav_left"><a href="'.$ThemeInfo['link1'].'">'.$ThemeInfo['link1text'].'</a></li>';
	echo '        <li><a href="'.$ThemeInfo['link2'].'">'.$ThemeInfo['link2text'].'</a></li>';
	echo '        <li><a href="'.$ThemeInfo['link3'].'">'.$ThemeInfo['link3text'].'</a></li>';
	echo '        <li><a href="'.$ThemeInfo['link4'].'">'.$ThemeInfo['link4text'].'</a></li>';
	echo '        <li class="nav_right"><a href="'.$ThemeInfo['link5'].'">'.$ThemeInfo['link5text'].'</a></li>';
	echo '      </ul>';
	echo '    </div>';
	echo '  </div>';
	echo '  <div id="header_post">';
	echo '    <div id="welcome_wrap">';
	echo '      <div class="wtop"></div>';
	echo '      <div class="wbody">';
	echo '        <div>'.$theuser.'</div>';
	echo '      </div>';
	echo '      <div class="wbtm"></div>';
	echo '    </div>';
	echo '    <div id="download_wrap">';
	echo '      <div class="dtop"></div>';
	echo '     <div class="dbody">'.$ThemeInfo['hdmessage'].'</div>';
	echo '    </div>';
	echo '  </div>';
	echo '  <!-- Header End -->';
	echo '  <!-- Body Start -->';
	echo '  <div id="body_wrap">';
	// Blocks [Left|Right]
	if (!blocks_visible('left') && !blocks_visible('right')){
		echo '  <div id="center_wrap_full">';
	} elseif (blocks_visible('right') && defined('ADMIN_FILE')){
		echo '  <div id="blocks_left_wrap">';
		blocks('left');
		echo '  </div>';
		echo '  <div id="center_wrap_left">';
	} elseif (blocks_visible('left') && blocks_visible('right')){
		echo '  <div id="blocks_right_wrap">';
		blocks('right');
		echo '  </div>';
		echo '  <div id="blocks_left_wrap">';
		blocks('left');
		echo '  </div>';
		echo '  <div id="center_wrap">';
	} elseif (blocks_visible('left') && !blocks_visible('right')){
		echo '  <div id="blocks_left_wrap">';
		blocks('left');
		echo '  </div>';
		echo '  <div id="center_wrap_left">';
	} elseif (!blocks_visible('left') && blocks_visible('right')){
		echo '  <div id="blocks_right_wrap">';
		blocks('right');
		echo '  </div>';
		echo '  <div id="center_wrap_right">';
	}
	
	// Detect users screen resolution
	echo "<script type=\"text/javascript\">
	//<![CDATA[
	if (screen.width <= 1152 || screen.height <= 864){
		document.getElementById('big_wrap').style.width = '990px';
	} else {
		document.getElementById('big_wrap').style.width = '".$ThemeInfo['themewidth']."';
	}
	//]]>
	</script>";
}

/************************************************************
 [ Function themefooter()                                   ]
 ************************************************************/
function themefooter(){
    global $user, $cookie, $banners, $prefix, $db, $admin, $adminmail, $nukeurl, $theme_name, $ThemeInfo;
	
	echo '    </div>';
	echo '  </div>';
	echo '  <!-- Body End -->';
	echo '  <!-- Footer Start -->';
	echo '  <div id="footer_wrap">';
	echo '    <div class="fcopyrights">';
	ob_start();
	echo footmsg();
	$contents = ob_get_clean();
	echo $contents;
	echo '    </div>';
	echo '    <div class="copyrights_img" title="EvoXtreme theme designed by SgtLegend, based of an SEO theme by PVMGarage" onclick="window.location=\'http://www.sgtlegend.com\'"></div>';
	echo '  </div>';
	echo '  <!-- Footer End -->';
	echo '</div>';
}

/************************************************************
 [ Function themeindex()                                    ]
 [ This function format the stories on the Homepage         ]
 ************************************************************/
function themeindex($aid, $informant, $time, $title, $counter, $topic, $thetext, $notes, $morelink, $topicname, $topicimage, $topictext){
    global $anonymous, $tipath, $theme_name, $sid, $ThemeSel, $nukeurl;
	
    if (!empty($topicimage)){
		$t_image = (file_exists('themes/'.$ThemeSel.'/images/topics/'.$topicimage)) ? 'themes/'.$ThemeSel.'/images/topics/'.$topicimage : $tipath.$topicimage;
        $topic_img = '<td width="25%" align="center" class="extra"><a href="modules.php?name=News&new_topic='.$topic.'"><img src="'.$t_image.'" border="0" alt="'.$topictext.'" title="'.$topictext.'"></a></td>';
    } else {
        $topic_img = '';
    }
	$notes = (!empty($notes)) ? '<br /><br /><strong>'._NOTE.'</strong> '.$notes : '';
    $content = '';
    if ($aid == $informant){
        $content = $thetext.$notes;
    } else {
        if (defined('WRITES')){
            if (!empty($informant)){
				$content = (is_array($informant)) ? '<a href="modules.php?name=Your_Account&amp;op=userinfo&amp;username='.$informant[0].'">'.$informant[1].'</a> ' : '<a href="modules.php?name=Your_Account&amp;op=userinfo&amp;username='.$informant.'">'.$informant.'</a> ';
            } else {
                $content = $anonymous.' ';
            }
            $content .= _WRITES.' '.$thetext.$notes;
        } else {
            $content .= $thetext.$notes;
        }
    }
    $posted = _POSTEDBY.' ';
    $posted .= get_author($aid);
    $posted .= ' '._ON.' '.$time.' ';
    $datetime = substr($morelink, 0, strpos($morelink, '|')-strlen($morelink));
    $morelink = substr($morelink, strlen($datetime)+2);
	
	echo '<div class="news_hd_wrap">';
	echo '  <div class="news_hd_right"></div>';
	echo '  <div class="news_hd_left"></div>';
	echo '  <div class="news_hd_stretch">'.$title.'</div>';
	echo '</div>';
	echo '<div class="news_bd_wrap">';
	echo '  <div class="news_bd_right"></div>';
	echo '  <div class="news_bd_left"></div>';
	echo '  <div class="news_bd_stretch">';
	echo '    <div class="ntext">'.$thetext.'</div>'.$posted.' '.$datetime.' | '.$morelink .'</div>';
	echo '</div>';
	echo '<div class="news_ft_wrap">';
	echo '  <div class="news_ft_right"></div>';
	echo '  <div class="news_ft_left"></div>';
	echo '  <div class="news_ft_stretch"></div>';
	echo '</div>';
}

/************************************************************
 [ Function themearticle()                                  ]
 ************************************************************/
function themearticle($aid, $informant, $datetime, $title, $thetext, $topic, $topicname, $topicimage, $topictext){
    global $admin, $sid, $tipath, $theme_name;
	
	if (!empty($topicimage)){
		$t_image = (file_exists('themes/'.$ThemeSel.'/images/topics/'.$topicimage)) ? 'themes/'.$ThemeSel.'/images/topics/'.$topicimage : $tipath.$topicimage;
        $topic_img = '<td width="25%" align="center" class="extra"><a href="modules.php?name=News&new_topic='.$topic.'"><img src="'.$t_image.'" border="0" alt="'.$topictext.'" title="'.$topictext.'"></a></td>';
    } else {
        $topic_img = '';
    }
	$notes = (!empty($notes)) ? '<br /><br /><strong>'._NOTE.'</strong> '.$notes : '';
    $content = '';
    if ($aid == $informant){
        $content = $thetext.$notes;
    } else {
        if (defined('WRITES')){
            if (!empty($informant)){
				$content = (is_array($informant)) ? '<a href="modules.php?name=Your_Account&amp;op=userinfo&amp;username='.$informant[0].'">'.$informant[1].'</a> ' : '<a href="modules.php?name=Your_Account&amp;op=userinfo&amp;username='.$informant.'">'.$informant.'</a> ';
            } else {
                $content = $anonymous.' ';
            }
            $content .= _WRITES.' '.$thetext.$notes;
        } else {
            $content .= $thetext.$notes;
        }
    }
	$posted = _POSTEDON.' '.$datetime.' '._BY.' ';
    $posted .= get_author($aid);
	
	echo '<div class="news_hd_wrap">';
	echo '  <div class="news_hd_right"></div>';
	echo '  <div class="news_hd_left"></div>';
	echo '  <div class="news_hd_stretch">'.$title.'</div>';
	echo '</div>';
	echo '<div class="news_bd_wrap">';
	echo '  <div class="news_bd_right"></div>';
	echo '  <div class="news_bd_left"></div>';
	echo '  <div class="news_bd_stretch">';
	echo '    <div class="ntext">'.$thetext.'</div>'.$posted.' '.$datetime.'</div>';
	echo '</div>';
	echo '<div class="news_ft_wrap">';
	echo '  <div class="news_ft_right"></div>';
	echo '  <div class="news_ft_left"></div>';
	echo '  <div class="news_ft_stretch"></div>';
	echo '</div>';
}

/**********************************************************
 [ Centerbox Section                                      ]
 **********************************************************/
function themecenterbox($title, $content){
    OpenTable();
    echo '<center><span class="option"><strong>'.$title.'</strong></span></center><br />'.$content;
    CloseTable();
}

/**********************************************************
 [ Preview Section                                        ]
 **********************************************************/
function themepreview($title, $hometext, $bodytext='', $notes=''){
    echo '<strong>'.$title.'</strong><br /><br />'.$hometext;
    echo (!empty($bodytext)) ? '<br /><br />'.$bodytext : '';
    echo (!empty($notes)) ? '<br /><br /><strong>'._NOTE.'</strong> <em>'.$notes.'</em>' : '';
}

/**********************************************************
 [ Function themesidebox()                                ]
 **********************************************************/
function themesidebox($title, $content, $bid=0){
	global $HTTP_GET_VARS, $module_name;
	
	// News Articles [Ratings]
	if ($module_name == 'News' && $HTTP_GET_VARS['file']){
		echo '<div style="width: 195px;">';
	}
	echo '<div class="blocks_title">'.$title.'</div>';
	echo '<div class="blocks_body">'.$content.'</div>';
	echo '<div class="blocks_footer"></div>';
	// News Articles [Ratings]
	if ($module_name == 'News' && $HTTP_GET_VARS['file']){
		echo '</div>';
	}
}

?>
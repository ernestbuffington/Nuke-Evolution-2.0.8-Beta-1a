<?php
/*=======================================================================
 Nuke-Evolution Basic: Enhanced PHP-Nuke Web Portal System
 =======================================================================*/
 
/************************************************************************
   Nuke-Evolution: Theme Management
   ============================================
   Copyright (c) 2005 by The Nuke-Evolution Team
  
   Filename      : block-Themes.php
   Author        : JeFFb68CAM (www.Evo-Mods.com)
   Version       : 1.0.2
   Date          : 11.27.2005 (mm.dd.yyyy)
                                                                        
   Notes         : Simple block to allow theme selection from homepage
************************************************************************/

if(!defined('NUKE_EVO')) exit;

if (is_user()) {
    $content  = "<center>\n"; 
    $content .= "<form method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "\">\n";
    $content .= "<input type=\"hidden\" name=\"chngtheme\" value=\"1\" />\n";
    $content .= "<br />\n"; 
    $content .= GetThemeSelect('theme', 'user_themes', false, 'onchange="submit();"');
    $content .= "<br />\n"; 
    $content .= "</form>\n"; 
    $content .= "</center>\n";
} else {
    $content  = "<center>\n"; 
    $content .= "<form method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "\">\n";
    $content .= "<br />\n"; 
    $content .= GetThemeSelect('tpreview', 'user_themes', false, 'onchange="submit();"', get_theme(), 0);
    $content .= "<br />\n"; 
    $content .= "</form>\n"; 
    $content .= "</center>\n";
}

?>
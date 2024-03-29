<?php
/*=======================================================================
 Nuke-Evolution Basic: Enhanced PHP-Nuke Web Portal System
 =======================================================================*/
/****************************************************************************
   Nuke-Evolution: Theme Management
   ============================================
   Copyright (c) 2005 by The Nuke-Evolution Team

   Filename      : theme_info.php
   Author        : JeFFb68CAM (www.Evo-Mods.com)
   Version       : 1.0.2
   Date          : 12.20.2005 (mm.dd.yyyy)

   Notes         : Advanced Theme Features for Centrium-Shellz Theme.
****************************************************************************/

if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Access Denied');
}
$current_theme = basename(dirname(__FILE__));

$param_names = array(
			'Link 1 URL',
 			'Link 1 Text',
			'Link 2 URL',
			'Link 2 Text',
			'Link 3 URL',
			'Link 3 Text',
			'Link 4 URL',
			'Link 4 Text',
			'Link 5 URL',
			'Link 5 Text',
			'BG Color 1',
			'BG Color 2',
			'BG Color 3',
			'BG Color 4',
			'Text Color 1',
			'Text Color 2'
			);
$params = array(
			'link1',
			'link1text',
			'link2',
			'link2text',
			'link3',
			'link3text',
			'link4',
			'link4text',
			'link5',
			'link5text',
			'bgcolor1',
			'bgcolor2',
			'bgcolor3',
			'bgcolor4',
			'textcolor1',
			'textcolor2'
			);
$default = array(
			'index.php',
			'HOME',
			'modules.php?name=Downloads',
			'DOWNLOADS',
			'modules.php?name=Forums',
			'FORUMS',
			'modules.php?name=Web_Links',
			'LINKS',
			'modules.php?name=Your_Account',
			'ACCOUNT',
			'#253a49',
			'#253a49',
			'#253a49',
			'#253a49',
			'#FFFFFF',
			'#FFFFFF'
			);
global $ThemeInfo;
$ThemeInfo = LoadThemeInfo($current_theme);

?>
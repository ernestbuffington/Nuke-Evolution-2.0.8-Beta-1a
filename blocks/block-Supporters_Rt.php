<?php
/*=======================================================================
 Nuke-Evolution Basic: Enhanced PHP-Nuke Web Portal System
 =======================================================================*/

/********************************************************/
/* NSN Supporters                                       */
/* By: NukeScripts Network (webmaster@nukescripts.net)  */
/* http://www.nukescripts.net                           */
/* Copyright (c) 2000-2005 by NukeScripts Network         */
/********************************************************/

/*****[CHANGES]**********************************************************
-=[Base]=-
      Nuke Patched                             v3.1.0       07/14/2005
 ************************************************************************/

if(!defined('NUKE_EVO')) exit;

include_once(NUKE_INCLUDE_DIR.'nsnsp_func.php');

$sp_config = spget_configs();
get_lang('Supporters');

global $prefix, $db, $user, $admin, $admin_file, $cache;

$content = "<center>"._SP_SUPPORTEDBY."<br /><br />";
$content .= "<div id=\"scroller_container_3\" style=\"height: 31px; width: 100%;\"><div class=\"jscroller2_right jscroller2_speed-20 jscroller2_mousemove\"><center>\n";
$result = $db->sql_query("SELECT `site_id`, `site_name`, `site_url`, `site_image`, `site_date`, `site_description`, `site_hits` FROM `".$prefix."_nsnsp_sites` WHERE `site_status`>'0' ORDER BY `site_name` DESC");
if ((($image_atts = $cache->load('image_atts', 'nsnsp')) === false) || empty($image_atts)) {
    $image_atts = array();
    while(list($site_id, $site_name, $site_url, $site_image, $site_date, $site_description, $site_hits) = $db->sql_fetchrow($result)) {
        if (substr($site_image, 0, 5) == 'http:') {
            if (evo_site_up($site_image)) {
                list($width, $height, $type, $attr) = @getimagesize($site_image);
            } else {
                $width = $sp_config['max_width'];
                $height = $sp_config['max_height'];
                $type = '';
                $attr = '';
            }
        } else {
           list($width, $height, $type, $attr) = @getimagesize($site_image);
        }
        $image_atts[] = array('site_id' => $site_id, 'site_name' => $site_name, 'site_url' => $site_url, 'site_image' => $site_image, 'site_date' => $site_date, 'site_description' => $site_description,
                              'site_hits' => $site_hits, 'width' => $width, 'height' => $height, 'type' => $type, 'attr' => $attr);
    }
    $db->sql_freeresult($result);
    $cache->save('image_atts', 'nsnsp', $image_atts);
}
for ($i=0, $max=count($image_atts); $i<$max; $i++) {
    $site_id = $image_atts[$i]['site_id'];
    $site_name = $image_atts[$i]['site_name'];
    $site_url = $image_atts[$i]['site_url'];
    $site_image = $image_atts[$i]['site_image'];
    $site_date = $image_atts[$i]['site_date'];
    $site_description = $image_atts[$i]['site_description'];
    $site_hits = $image_atts[$i]['site_hits'];
    $width = $image_atts[$i]['width'];
    $height = $image_atts[$i]['height'];
    $type = $image_atts[$i]['type'];
    $attr = $image_atts[$i]['attr'];
    list($width, $height, $type, $attr) = @getimagesize($site_image);
    if($width > $sp_config['max_width']) { $width = $sp_config['max_width']; }
    if($height > $sp_config['max_height']) { $height = $sp_config['max_height']; }
    $content .= "<a href='modules.php?name=Supporters&amp;op=SPGo&amp;site_id=$site_id'><img src='$site_image' height='$height' width='$width' title='$site_name' alt='$site_name' border='0' /></a><br /><br />\n";
}
$content .="</center></div></div><br />\n";
if($sp_config['require_user'] == 0 || is_user()) { $content .= "[ <a href='modules.php?name=Supporters&amp;op=SPSubmit'>"._SP_BESUPPORTER."</a> ]<br />\n"; }
if(is_admin()) { $content .= "[ <a href='".$admin_file.".php?op=SPMain'>"._SP_GOTOADMIN."</a> ]<br />\n"; }
$content .= "[ <a href='modules.php?name=Supporters'>"._SP_SUPPORTERS."</a> ]</center>\n";

?>
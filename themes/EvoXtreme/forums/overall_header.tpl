{META}

<!-- start mod : Resize Posted Images Based on Max Width -->
<script type="text/javascript">
//<![CDATA[
<!--

var rmw_max_width = {IMAGE_RESIZE_WIDTH}; // you can change this number, this is the max width in pixels for posted images
var rmw_max_height = {IMAGE_RESIZE_HEIGHT}; // you can change this number, this is the max height in pixels for posted images
var rmw_border_1 = '1px solid {T_BODY_LINK}';
var rmw_border_2 = '2px dotted {T_BODY_LINK}';
var rmw_image_title = '{L_RMW_IMAGE_TITLE}';

//-->
//]]>
</script>
<script type="text/javascript" src="{U_RMW_JSLIB}"></script>
<!-- fin mod : Resize Posted Images Based on Max Width -->
<!-- BEGIN switch_enable_pm_popup -->
<script type="text/javascript">
//<![CDATA[
<!--
    if ( {PRIVATE_MESSAGE_NEW_FLAG} )
    {
        window.open('{U_PRIVATEMSGS_POPUP}', '_phpbbprivmsg', 'HEIGHT=225,resizable=yes,WIDTH=400');;
    }
//-->
//]]>
</script>
<!-- END switch_enable_pm_popup -->
<!-- Start add - Advanced time management MOD -->
<!-- BEGIN switch_send_pc_dateTime -->
<script type="text/javascript">
//<![CDATA[
<!-- Start Replace - window.onload = send_pc_dateTime -->
send_pc_dateTime();
<!-- End Replace - window.onload = send_pc_dateTime -->

function send_pc_dateTime() {
    var pc_dateTime = new Date()
    pc_timezoneOffset = pc_dateTime.getTimezoneOffset()*(-60);
    pc_date = pc_dateTime.getFullYear()*10000 + (pc_dateTime.getMonth()+1)*100 + pc_dateTime.getDate();
    pc_time = pc_dateTime.getHours()*3600 + pc_dateTime.getMinutes()*60 + pc_dateTime.getSeconds();

    for ( i = 0; document.links.length > i; i++ ) {
        with ( document.links[i] ) {
            if ( href.indexOf('{U_SELF}') == 0 ) {
                textLink = '' + document.links[i].firstChild.data
                if ( textLink.indexOf('http://') != 0 && textLink.indexOf('www.') != 0 && (textLink.indexOf('@') == -1 || textLink.indexOf('@') == 0 || textLink.indexOf('@') == textLink.length-1 )) {
                    if ( href.indexOf('?') == -1 ) {
                        pc_data = '?pc_tzo=' + pc_timezoneOffset + '&pc_d=' + pc_date + '&pc_t=' + pc_time;
                    } else {
                        pc_data = '&pc_tzo=' + pc_timezoneOffset + '&pc_d=' + pc_date + '&pc_t=' + pc_time;
                    }
                    if ( href.indexOf('#') == -1 ) {
                        href += pc_data;
                    } else {
                        href = href.substring(0, href.indexOf('#')) + pc_data + href.substring(href.indexOf('#'), href.length);
                    }
                }
            }
        }
    }
}
//]]>
</script>
<!-- END switch_send_pc_dateTime -->
<!-- End add - Advanced time management MOD -->

<a name="top"></a>
<div align ="center">
	<a href="{U_INDEX}" class="mainmenu">{L_MINI_INDEX}</a>
	&nbsp;&middot;&nbsp;&nbsp;<a href="{U_SEARCH}" class="mainmenu">{L_SEARCH}</a>
	&nbsp;&middot;&nbsp;&nbsp;<a href="{U_GROUP_CP}" class="mainmenu">{L_USERGROUPS}</a>
	&nbsp;&middot;&nbsp;&nbsp;<a href="{U_PROFILE}" class="mainmenu">{L_PROFILE}</a>
	&nbsp;&middot;&nbsp;&nbsp;<a href="{U_MEMBERLIST}" class="mainmenu">{L_MEMBERLIST}</a>
	&nbsp;&middot;&nbsp;&nbsp;<a href="{U_PRIVATEMSGS}" class="mainmenu">{PRIVATE_MESSAGE_INFO}</a>&nbsp;<br />
	<a href="{U_RANKS}" class="mainmenu">{L_RANKS}</a>
	&nbsp;&middot;&nbsp;&nbsp;<a href="{U_STAFF}" class="mainmenu">{L_STAFF}</a>
	&nbsp;&middot;&nbsp;&nbsp;<a href="{U_STATISTICS}" class="mainmenu">{L_STATISTICS}</a>
	&nbsp;&middot;&nbsp;&nbsp;<a href="{U_RULES}" class="mainmenu">{L_RULES}</a>
	&nbsp;&middot;&nbsp;&nbsp;<a href="{U_FAQ}" class="mainmenu">{L_FAQ}</a>
	&nbsp;&middot;&nbsp;&nbsp;<a href="{U_LOGIN_LOGOUT}" class="mainmenu">{L_LOGIN_LOGOUT}</a>
</div>
<!-- BEGIN boarddisabled -->
  <br /><div align="center"><span class="gen"><strong>{L_BOARD_CURRENTLY_DISABLED}</strong></span></div><br />
<!-- END boarddisabled -->
<!-- Quick Search -->
<!-- BEGIN switch_quick_search -->
<br /><script type="text/javascript">
//<![CDATA[
<!--
function checkSearch()
{
    {switch_quick_search.CHECKSEARCH}
    else
    {
        return true;
    }
}
//-->
//]]>
</script>

<center>
<form name="search_block" method="post" action="{U_SEARCH}" onsubmit="return checkSearch()">
<input type="hidden" name="search_fields" value="all" />
<input type="hidden" name="show_results" value="topics" />
<table width="100%" cellpadding="2" cellspacing="1" border="0">
  <tr>
    <td align="center"><span class="gensmall">
    {switch_quick_search.L_QUICK_SEARCH_FOR} <input class="post" type="text" name="search_keywords" size="15" /> {switch_quick_search.L_QUICK_SEARCH_AT} <select class="post" name="site_search">{switch_quick_search.SEARCHLIST}</select>
    <input class="mainoption" type="submit" value="{L_SEARCH}" /></span></td>
  </tr>
  <tr>
    <td align="center"><a href="{U_SEARCH}" class="gensmall">{switch_quick_search.L_ADVANCED_FORUM_SEARCH}</a></td>
  </tr>
</table>
</form>
</center>
<!-- END switch_quick_search -->
<br />
<script language="javascript" type="text/javascript">
<!--
function refresh_username(selected_username)
{
    <!-- Start replacement - Custom mass PM MOD -->
    if (opener.document.forms['post'].username.value)
    {
        opener.document.forms['post'].username.value = opener.document.forms['post'].username.value + ';' + selected_username;
    }
    else
    {
        opener.document.forms['post'].username.value = selected_username;
    }
    <!-- End replacement - Custom mass PM MOD -->
    opener.focus();
    window.close();
}
//-->
</script>
<table border="0" cellpadding="0" align=center cellspacing="0" width="100%">
  <tr>
   <td><img src="themes/Centrium-Shellz/forums/images/forumop_01.gif" width="25" height="30" alt=""></td> 
   <td width="100%" background="themes/Centrium-Shellz/forums/images/forumop_02.gif"></td>
   <td><img src="themes/Centrium-Shellz/forums/images/forumop_03.gif" width="25" height="30" alt=""></td>
  </tr>
  <tr>
    <td background="themes/Centrium-Shellz/forums/images/forumop_04.gif"></td>
     <td valign="top" bgcolor="#0a233a">
<form method="post" name="search" action="{S_SEARCH_ACTION}">
<table width="100%" border="0" cellspacing="0" cellpadding="10">
    <tr>
        <td><table width="100%" class="forumline" cellpadding="4" cellspacing="1" border="0">
            <tr> 
                <th class="thHead" height="25">{L_SEARCH_USERNAME}</th>
            </tr>
            <tr> 
                <td valign="top" class="row1"><span class="genmed"><br /><input type="text" name="search_username" value="{USERNAME}" class="post" />&nbsp; <input type="submit" name="search" value="{L_SEARCH}" class="liteoption" /></span><br /><span class="gensmall">{L_SEARCH_EXPLAIN}</span><br />
                <!-- BEGIN switch_select_name -->
                <span class="genmed">{L_UPDATE_USERNAME}<br /><select name="username_list">{S_USERNAME_OPTIONS}</select>&nbsp; <input type="submit" class="liteoption" onClick="refresh_username(this.form.username_list.options[this.form.username_list.selectedIndex].value);return false;" name="use" value="{L_SELECT}" /></span><br />
                <!-- END switch_select_name -->
                <br /><span class="genmed"><a href="javascript:window.close();" class="genmed">{L_CLOSE_WINDOW}</a></span></td>
            </tr>
        </table></td>
    </tr>
</table>
</form>
</td>
    <td background="themes/Centrium-Shellz/forums/images/forumop_06.gif"></td>
  </tr>
  <tr>
   <td><img src="themes/Centrium-Shellz/forums/images/forumop_08.gif" width="25" height="29" alt=""></td>
   
    <td background="themes/Centrium-Shellz/forums/images/forumop_09.gif"></td>
   <td><img src="themes/Centrium-Shellz/forums/images/forumop_07.gif" width="25" height="29" alt=""></td>
</tr></table>
<form method="post" action="{S_MODCP_ACTION}">
<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
  <tr> 
    <td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_VIEW_FORUM}" class="nav">{FORUM_NAME}</a></span></td>
  </tr>
</table>
<table border="0" cellpadding="0" align=center cellspacing="0" width="100%">
  <tr>
   <td><img src="themes/Centrium-Shellz/forums/images/forumop_01.gif" width="25" height="30" alt=""></td> 
   <td width="100%" background="themes/Centrium-Shellz/forums/images/forumop_02.gif"></td>
   <td><img src="themes/Centrium-Shellz/forums/images/forumop_03.gif" width="25" height="30" alt=""></td>
  </tr>
  <tr>
    <td background="themes/Centrium-Shellz/forums/images/forumop_04.gif"></td>
     <td valign="top" bgcolor="#0a233a">
  <table width="100%" cellpadding="4" cellspacing="1" border="0" class="forumline">
    <tr> 
      <td class="catHead" colspan="6" align="center" height="28"><span class="cattitle">{L_MOD_CP}</span> 
      </td>
    </tr>
    <tr> 
      <td class="spaceRow" colspan="6" align="center"><span class="gensmall">{L_MOD_CP_EXPLAIN}</span></td>
    </tr>
    <tr> 
      <th width="4%" class="thLeft" nowrap="nowrap">&nbsp;</th>
      <th nowrap="nowrap">&nbsp;{L_TOPICS}&nbsp;</th>
      <th width="8%" nowrap="nowrap">&nbsp;{L_REPLIES}&nbsp;</th>
      <th width="17%" nowrap="nowrap">&nbsp;{L_LASTPOST}&nbsp;</th>
      <th width="5%" nowrap="nowrap">&nbsp;{L_SELECT}&nbsp;</th>
      <th width="10%" class="thRight" nowrap="nowrap">&nbsp;{L_PRIORITY}&nbsp;</th>
    </tr>
    <!-- BEGIN topicrow -->
    <tr> 
      <td class="row1" align="center" valign="middle"><img src="{topicrow.TOPIC_FOLDER_IMG}" width="19" height="18" alt="{topicrow.L_TOPIC_FOLDER_ALT}" title="{topicrow.L_TOPIC_FOLDER_ALT}" /></td>
      <td class="row1">&nbsp;<span class="topictitle">{topicrow.TOPIC_ATTACHMENT_IMG}{topicrow.TOPIC_TYPE}<a href="{topicrow.U_VIEW_TOPIC}" class="topictitle">{topicrow.TOPIC_TITLE}</a></span></td>
      <td class="row2" align="center" valign="middle"><span class="postdetails">{topicrow.REPLIES}</span></td>
      <td class="row1" align="center" valign="middle"><span class="postdetails">{topicrow.LAST_POST_TIME}</span></td>
      <td class="row2" align="center" valign="middle"> 
        <input type="checkbox" name="topic_id_list[]" value="{topicrow.TOPIC_ID}" />
      </td>
<td class="row1" align="center" valign="middle"> 
        <input type="Text" name="topic_cement:{topicrow.TOPIC_ID}" value="{topicrow.TOPIC_PRIORITY}" maxlength="5" size="5" />
      </td>      
    </tr>
    <!-- END topicrow -->
    <tr align="right"> 
      <td class="catBottom" colspan="6" height="29"> {S_HIDDEN_FIELDS} 
        <input type="submit" name="delete" class="liteoption" value="{L_DELETE}" />
        &nbsp; 
        <input type="submit" name="move" class="liteoption" value="{L_MOVE}" />
        &nbsp; 
        <input type="submit" name="lock" class="liteoption" value="{L_LOCK}" />
        &nbsp; 
        <input type="submit" name="unlock" class="liteoption" value="{L_UNLOCK}" />
            <input type="submit" name="cement" class="liteoption" value="{L_PRIORITIZE}" />
      </td>
    </tr>
  </table>
</td>
    <td background="themes/Centrium-Shellz/forums/images/forumop_06.gif"></td>
  </tr>
  <tr>
   <td><img src="themes/Centrium-Shellz/forums/images/forumop_08.gif" width="25" height="29" alt=""></td>
   
    <td background="themes/Centrium-Shellz/forums/images/forumop_09.gif"></td>
   <td><img src="themes/Centrium-Shellz/forums/images/forumop_07.gif" width="25" height="29" alt=""></td>
</tr></table>
  <table width="100%" cellspacing="2" border="0" align="center" cellpadding="2">
  <tr> 
    <td align="left" valign="middle"><span class="nav">{PAGE_NUMBER}</strong></span></td>
    <td align="right" valign="top" nowrap="nowrap"><span class="gensmall">{S_TIMEZONE}</span><br /><span class="nav">{PAGINATION}</span></td>
  </tr>
</table>
</form>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="right">{JUMPBOX}</td>
  </tr>
</table>
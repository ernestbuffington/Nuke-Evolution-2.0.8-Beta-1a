    <tr>
        <th class="thHead" colspan="2">{L_ADD_ATTACH_TITLE}</th>
    </tr>
    <tr>
        <td class="row1" colspan="2"><span class="gensmall">{L_ADD_ATTACH_EXPLAIN}<br />{RULES}</span></td>
    </tr>

    <tr> 
        <td class="row1"><span class="gen"><strong>{L_FILE_NAME}</strong></span></td> 
        <td class="row2"><span class="genmed"><input type="file" name="fileupload" size="40" maxlength="{FILESIZE}" value="" class="post" /></span></td> 
    </tr> 
    <tr> 
        <td class="row1"><span class="gen"><strong>{L_FILE_COMMENT}</strong></span></td> 
        <td class="row2"><textarea name="filecomment" rows="3" cols="35" class="post">{FILE_COMMENT}</textarea>
        <input type="submit" name="add_attachment" value="{L_ADD_ATTACHMENT}" class="liteoption" /></td> 
    </tr>
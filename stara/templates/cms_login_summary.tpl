<!-- BEGIN LOGGEDIN -->
	<table width="100%" border="0" cellspacing="3" cellpadding="0">
		<tr>
			<td class="alright">
		{LOGGEDIN.LOGGEDINAS}:<br/>
	<b>{LOGGEDIN.USERDESCRIPTION}</b> - <a href="{LOGGEDIN.USERLOGOUTHREF}">{LOGGEDIN.LOGOUTTEXT}</a>
			</td>
		</tr>
	</table>
<!-- END LOGGEDIN -->

<!-- BEGIN LOGINSHORTFORM -->

	<form action="{LOGINSHORTFORM.URL}" method="post">
	
	<table border="0" align="right" cellpadding="0" cellspacing="1">
          <tr>
            <td>&nbsp;</td>
            <td class="alright"><img src="templates/images/zalogujsie.gif" width="104" height="21" /></td>
          </tr>
          <tr>
            <td class="alright">{LOGINSHORTFORM.USERTEXT}</td>
            <td><input name="login" type="text" class="pole5" /></td>
          </tr>
          <tr>
            <td class="alright">{LOGINSHORTFORM.PASSWORDTEXT}</td>
            <td><input name="password" type="password" class="pole5" /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pad4px">
				<!--<input class="butt0" type="submit" name="Submit232" value="rejestracja"/>--></td>
                <td align="right">
				<input type="hidden" name="action" value="userLogin" />
				<input class="butt0" type="submit" name="Submit332" value="{LOGINSHORTFORM.LOGIN}"/></td>
              </tr>
            </table></td>
          </tr>
        </table>

	</form>
<!--
				<table border="0" align="right" cellpadding="0" cellspacing="5">

              <tr>
                <td align="right">user name:</td>
                <td><input name="textfield2" type="text" class="pole2" /></td>
              </tr>

              <tr>
                <td align="right">password:</td>
                <td><input name="textfield3" type="text" class="pole2" /></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><table width="155" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="112" class="pad6px"><input class="butt1" type="submit" name="Submit2" value="create account"/></td>
                    <td width="62" align="right"><input class="butt2" type="submit" name="Submit3" value="login"/></td>
                  </tr>
                </table></td>
              </tr>
            </table>
	-->
	
<!-- END LOGINSHORTFORM -->
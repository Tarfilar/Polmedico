<form action="{RECOMMENDFORMURL}" method="post" id="recmodule">
<div class="fl"><img src="{TEMPLATE_PATH}images/{LANG_DIR}nagl_polecnas.gif" width="246" height="50" alt="polmedico" /></div>
<div class="txt1">{RECOMMENDALERT}</div>
<div class="txt1">{RECOMMENDTEXT}</div>
<div class="txt1"><input name="recommend_email" type="text" class="pole1" value="{RECOMMEND_EMAIL_VALUE}" /></div>
<div class="txt1"><input name="recommend_signature" type="text" class="pole1" value="{RECOMMEND_SIGNATURE_VALUE}" /></div>
<div class="txt1"><div class="fr"><input class="but1" type="submit" name="Submit" onClick="javascript: document.getElementById('recmodule').submit();" value="{RECOMMEND_SUBMIT_VALUE}" /></div></div>
<input type="hidden" name="action" value="recommendationSend" />
</form>
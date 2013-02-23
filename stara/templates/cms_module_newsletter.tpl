<form action="{NEWSLETTERFORMURL}" method="post" id="newsletterForm">
<div class="fl"><img src="{TEMPLATE_PATH}images/{LANGDIR}nagl_subskrypcja.jpg" width="246" height="50" alt="polmedico" /></div>
				<div class="txt1">{NEWSLETTERALERT}</div>
				<div class="txt1">{NEWSLETTERTEXT}</div>
				<div class="txt1"><input name="newsletter_email" type="text" class="pole1" onClick="javascript: this.value='';" value="{NEWSLETTER_EMAIL_VALUE}" /></div>
				<div class="txt1">
					<div class="fl"><input class="but1" type="submit" name="Submit" value="{NEWSLETTER_UNSUBMIT_VALUE}" /></div>
					<div class="fr"><input class="but1" type="submit" name="Submit" value="{NEWSLETTER_SUBMIT_VALUE}" onClick="javascript: document.getElementById('newsletterForm').submit();" /></div>
				</div>
<input type="hidden" name="newsletterAction" value="in">
<input type="hidden" name="action" value="newsletterSend">
</form>
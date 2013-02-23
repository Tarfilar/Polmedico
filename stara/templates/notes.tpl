<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{NOTESSITETITLE} - notes</title>

<script language="JavaScript"> 
<!-- 

bname = "firefox";
version = 0;

if(navigator.userAgent.indexOf("Firefox") != -1) {

	var version = navigator.userAgent.indexOf("Firefox")+8;

} else if (navigator.appVersion.indexOf("MSIE") != -1) {
	bname = "IE";
	temp = navigator.appVersion.split("MSIE");
	var version = parseFloat(temp[1]);
}

if (bname == "IE" && version == 6)
	document.write('<link href="{TEMPLATE_PATH}globusie6.css" rel="stylesheet" type="text/css" />');
if (bname == "IE" && version >= 5 && version < 6)
	document.write('<link href="{TEMPLATE_PATH}globusie5.css" rel="stylesheet" type="text/css" />');
else
	document.write('<link href="{TEMPLATE_PATH}globus1.css" rel="stylesheet" type="text/css" />');

//--> 
</script>

<script type="text/javascript">
<!--
	function openWindow (url, width, height) {
		var n = random_num = (Math.round((Math.random()*9)+1));
        window.open(url,n,'width=' + (width+20) + ',height=' + (height+20) + ',resizable=1,scrollbars=yes,menubar=no' );

	}
//-->
</script>
<script type="text/javascript">
	
	function printThePage(){
		self.focus()
		self.print()
	}
	
</script> 
</head>

<body {NOTESPRINT}>
<table width="760" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
	
		{NOTESCONTENT}

	</td>
  </tr>
</table>
</body>
</html>

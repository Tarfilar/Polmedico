<!-- BEGIN ITEM -->
<div id="i{ITEM.ID}" style="width:330px;float:left;{ITEM.PADDING}">

	<div id="pic_{ITEM.I}" style="float: left;">
		<a onFocus="blur()" href="javascript: void(0);">
			<img style="border: 1px solid #2c3169;" width="330" vspace="4" height="128" border="1" src="{ITEM.PICTURE}" alt="" title="kliknij (click)" />
		</a>
	</div>
	<div id="pic_{ITEM.I}" style="float: left;">
		<a onFocus="blur()" href="javascript: void(0);">
			<span style="font-size: larger;color: #2c3169;"><strong>{ITEM.NAME}</strong></span>
		</a>
		<br />
	</div>
	<div id="desc_{ITEM.I}" style="{ITEM.STYLE} width: 330px; float: left;padding-bottom: 12px;padding-top: 12px;">{ITEM.DESCRIPTION}</div>
</div>
<!-- END ITEM -->

<!-- BEGIN ROW -->

<div style="width:690px;">
	{ROW.ITEMS}
	<br /><br />&nbsp;&nbsp;
</div>

<!-- END ROW -->
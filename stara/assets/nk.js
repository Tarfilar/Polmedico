$(document).ready(
function()
{
	$("#nk").mouseenter(function()
	{
		$(this).stop().animate({left: 0}, "normal");
	}).mouseleave(function()
	{
		$(this).stop().animate({left: -205}, "normal");
	});;
	
});
	
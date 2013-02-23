$(document).ready(
function()
{
	$("#fb").mouseenter(function()
	{
		$(this).stop().animate({right: 0}, "normal");
	}).mouseleave(function()
	{
		$(this).stop().animate({right: -205}, "normal");
	});;
	
});
	
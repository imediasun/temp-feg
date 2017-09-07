var value = 0
$( "img" ).live( "click", function() { 
	value +=90;
	$(this).rotate({ animateTo:value})
});
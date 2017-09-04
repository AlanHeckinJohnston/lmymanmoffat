$(document).ready(function(){
		function showPrompt(string,c,additional){
		if (c)
		{
			$('#main').append('<div id="screen"></div><div id="editBox"><div id="editBoxWrapper"><p id="edBoxClose">Close</p>'+string+'</div></div>');
			$('#edBoxClose').click(closeEditBox);
		}
		else
			$('#main').append('<div id="screen"></div><div id="editBox"><div id="editBoxWrapper">'+string+'</div></div>');
		$('#eCategory').val($('#oldTcategory').val());
		
		var editBox = $('#editBox');//grab the edit Box as a whole.
		
		editBox.css({animationName:'open',animationDuration:'0.8s',animationFillMode:'forwards'});//open animation
		

		if (additional != "undefined")
			additional();
	}
	function closeEditBox(){ //define a function to use to close the edit box. 

	var editBox = $('#editBox');							
	var wrapper = $('#editBoxWrapper'); //grab the wrapper inside the box. It exists to make the animation look less trashy.
	var height = editBox.height();
	var width = editBox.width();
	wrapper.height(height);
	wrapper.width(width); //set the width and height to be the same as the editBox's. this can't be done in css as it defeats the animation purpose			
	editBox.css({animationName:'close'});//close up animation
	//clean up elements
	setTimeout(function(){ $('#screen').remove();},600);
	setTimeout(function(){editBox.remove(); wrapper.remove();},800);
}
	$('#click').click(function(){showPrompt('Hello',true,function(){var ye;});});
});
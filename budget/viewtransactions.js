function getTransactions(){
	transactions = [];
	var week = $('#bw').val();
	$.post('getTransactions.php',{week:week},function(data){
		$('#group').css('height','300px');
		for (var i = 0; i<data.length; i++)
		{
			var lines="<p class='li'><span class='am'>Amount</span><span class='desc'>Description:</span></p>";
			for (var x = 0; x<data[i].length; x++)
			{
				var point = data[i][x].indexOf('|');
				var amount = data[i][x].substr(0,point);
				var j = data[i].length-x;
				j--;
				var description = data[i][x].substr(point+1);
				if (j % 2 != 0)
					lines += "<p id=\"p_"+j+"\" class='li'><span class='am'>" + amount + "</span><span class='desc'>" + description + "</span><span class=\"edit\">Edit</span></p>";
				else
					lines += "<p id=\"p_"+j+"\" class='li g'><span class='am'>" + amount + "</span><span class='desc g'>" + description + "</span><span class=\"edit\">Edit</span></p>";

			}
			if (transactions[i])
				transactions[i]+=lines;
			else
				transactions[i]=lines;
		}
		setTransactions();
			
	}, "json");
}
function setTransactions(){
	var category = $('#oldTcategory').children(":selected").attr("id");	
	$('#group').html(transactions[category]);
	$('.edit').each(function(){
		$(this).click(function(){
			var Parent = $(this).parent();
			var descriptionOld = Parent.find('.desc').html();
			var amountOld = Parent.find('.am').html();
			amountOld = amountOld.replace('$','');
			var div = '<div class="editLine"><span class="ledit">Description:</span><span class="redit"><input id="eDesc" value="'+descriptionOld+'"></span>	</div><div class="editLine"><span class="ledit">Amount:</span><span class="redit"><input id="eAmount" value="'+amountOld+'"></span></div><div class="editLine"><span class="ledit">Category:</span><span class="redit"><select id="eCategory">' + categoriesAsOptions() + '</select></div><input id="echange" type="button" value="Change">  OR  <input id="eremove" type="button" value="Remove">';
			showPrompt(div,true,function(){
				var id = $(this).parent().attr('id');//obtain the id. Parse this into the proper number, since the number comes after an underscore.
				id = id.substr(id.indexOf('_')+1);
				var category = $('#oldTcategory').val();//obtain the old category
				var week = $('#bw').val();//obtain what week it is.
		
				
				$('#echange').click(function(){	//send the changed information to the server should the user press the "change" button			
					var description = $('#eDesc').val();
					var amount = $('#eAmount').val();
					var tocategory = $('#eCategory').val();
					$.post('editTransaction.php',{id:id,description:description,amount:amount, week:week, category:category, tocategory:tocategory}, function(){getTransactions(); closeEditBox(); getRemaining();});
					
				});
				$('#eremove').click(function(){//send a request to the server to delete the transaction, should the user press "remove"
					closeEditBox();
					$.post('removeTransaction.php',{id:id,category:category,week:week},function(){getTransactions(); setTimeout(getRemaining,1000);});
				});
				
			}.bind(this));
		
			
		});
	});
}
function categoriesAsOptions(){
	var fs = "";
	for (var i = 0; i < allcats.length; i++)
	{
		fs+='<option>'+allcats[i]+'</option>';		
	}
	return fs;
}		
function showPrompt(string,c,additional){
	if (c)
	{
		$('#oldT').append('<div id="screen"></div><div id="editBox"><div id="editBoxWrapper"><p id="edBoxClose">Close</p>'+string+'</div></div>');
		$('#edBoxClose').click(closeEditBox);
	}
	else
		$('#oldT').append('<div id="screen"></div><div id="editBox"><div id="editBoxWrapper">'+string+'</div></div>');
	$('#eCategory').val($('#oldTcategory').val());

	var editBox = $('#editBox');//grab the edit Box as a whole.

	editBox.css({animationName:'open',animationDuration:'0.8s',animationFillMode:'forwards',borderTop:'2px solid #333333',borderBottom:'2px solid #333333'});//open animation



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
$(document).ready(function(){
	$('#bw').val(week);
	getTransactions();
	$('#oldTcategory').on('change',function(){
		setTransactions();
	});
});
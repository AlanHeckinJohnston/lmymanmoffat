
$(document).ready(function()
	{
		$('#change').val(week);
		$('#bw').val(week);
		var transactions=[];
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
		function getRemaining(week){
			
			$.post('getRemaining.php',{week:week},function(data){
				var keys = data.keys();
				for (var i = 0; i<data.length; i++)
				{
					$('#rm_'+i+'>.remaining_object').html(data[i]);
				}
				//$('#remaining_rm>.remaining_object').html(data['remaining']);
			},'json');
		}
		function submission(){

			var selected_week=$('#change').val();
			var category = $('#category').val();
			var amount = parseFloat($('#amount').val());
			var description = $('#description').val();
			
			if (description == '')
				return;
			var selected_week=$('#change').val();
			if (selected_week < week)
			{

				var string = '<p>Entering older transactions can have unexpected affects on your budget. Are you sure?</p><p><button id="c_yes">Yes</button><button id="c_no">No</button></p>';
				showPrompt(string,false,function(){
				
					$('#c_yes').click(function(){
						
						closeEditBox();
						$.post('newT.php',{week:selected_week,category:category, amount:amount,description:description},function(data){
							if (data=='Yes')
								setTimeout(function(){alert('Transaction successfully added');},800);
							else if (data=='noSes')
								location.reload();
							else
								alert('Transaction was not added!');
							$('#submit').css('visibility','hidden');
							$('#description').val('');
							$('#amount').val('');
							getTransactions();
							setTimeout(getRemaining,1000);
						});
					});
					$('#c_no').click(function(){
						closeEditBox();
						$('#amount').val('');
						$('#description').val('');
					});
				});
			}
			else
			{
				$.post('newT.php',{week:week,category:category, amount:amount,description:description},function(data){
					if (data=='Yes')
						alert('Transaction successfully added');
					else if (data=='noSes')
						location.reload();
					else
						alert('Transaction was not added!');
					$('#submit').css('visibility','hidden');
					$('#description').val('');
					$('#amount').val('');
					setTimeout(getRemaining,1000);
				});
			}
		}	
		function showSubmit(){
			if ($('#amount').val()=='')
				return;
			if ($('#description').val()=='')
				return;
			if ($('#category').val()=='Pick One...')
				return;
			$('#submit').css({visibility:'visible',animationName:'TransactionBox',animationDuration:'2s'});
			$('#po').remove();			
		}

		getRemaining(week); //get the remaining
		
		
		$('#category').on('change',showSubmit)
		$('#amount').on('change',showSubmit);
		$('#category').on('change',showSubmit);
		$('#openTransaction').click(function(){ //open the New Transaction box.
			$('#newT').css('height','auto');
			var auto = $('#newT').css('height');
			$('#newT').css('height','0px');
			$('#newT').animate({height:auto},300,function(){$('#newT').stop()});
			$(this).css({
				animationTimingFunction:'linear',
				animationName:'TransactionBox',
				animationDuration:'0.8s',
				animationFillMode:'forwards',
				animationDirection:'reverse'
			});
			var Text = $('#openTransaction>.text'); //this is with a capital T
			var dist=($(this).width()/4);
			//alert(dist);
			Text.animate({marginLeft:-dist},800);
			setTimeout(function(){$('#openTransaction').css('visibility','hidden')},800);
		});
	});
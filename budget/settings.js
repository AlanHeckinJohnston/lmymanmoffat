$(document).ready(function(){
	var openBlock='n';
	$('.option').each(function(){
		var categoryMode = '%';
		function openClosed(){
			b = $('#' + openBlock);		
			b.css('display','block');
			b.animate({'height':'60px'},800,function(){b.stop();});		
		}
		$(this).click(function(){
			var id = $(this).attr('id');
			var amount = $(this).attr('data-percentage');
			var animated_area = $('#animated_area');
			animated_area.stop();
			if (id != "new_category")
			{
				animated_area.html('<h1 id="head">'+id+'</h1>');
			}
			else
			{
				animated_area.html('<h1 id="head">New Category</h1><p>What should this category be called?</p><div class="floater"><input id="name"></div>What type of category is this?<div class="floater"><select id="choice"><option>Continuous</option><option>Saving</option></select><div id="explanation_wrapper"><p id="explanation">Continuous Categories have money put into them every paycheck, forever or for as long as you need them.</p></div></div>What percentage of your income should be dedicated to this?<div class="floater"><span class="typelabel">%</span><input min="0" class="width" type="number" step="1" id="percentage"></div><div class="floater"><span class="typelabel">$</span><input id="number" step="0.01" class="width" type="number"></div><p style="font-size:18px">These estimates are based off of your income being $'+income+'.<p><button id="save">Add</button></p>');
				$('#percentage').change(function(){
					$('#number').val(($('#percentage').val()/100)*income);
					categoryMode='%';
				});
				$('#number').change(function(){
					$('#percentage').val(Math.round(($('#number').val()/income)*100));
					categoryMode='$';
				});
				$('#save').click(function(){
					var name = $('#name').val();
					var type = $('#choice').val();
					var number;
					if (categoryMode=='%')
					{
						number = $('#percentage').val();
					}
					else
					{
						number = $('#number').val();
					}
					console.log(categoryMode);
					console.log(number);
					if (name != '' && type != '')
					{
						$.post('newcategory.php',{category:name,type:type,value:number,mode:categoryMode},function(data){
							if (data=='yes')
							{
								alert('New Category sucessfully added!');
								openClosed();
								location.reload();
							}
							else if (data=='taken')
							{
								alert('The Category name is taken!');
							}
							
						},'text');
					}
				});
			}
			$('.option').each(function(){
				if (parseInt($(this).css('height')) < 60)
				{
					if ($(this).attr('id') != openBlock)
					{
						$(this).stop();
						$(this).animate({height:'60px'});
					}
				}
			});
			$(this).animate({height:"0px"},800,function(){$(this).stop(); $(this).css('display','none'); openClosed(); openBlock=$(this).attr('id')})
			animated_area.css({top:'-'+animated_area.css('height'),opacity:0});
			var choice = $('#choice');
			
			choice.change(function(){
				if (choice.val()=='Continuous')
					$('#explanation_wrapper').html('<p id="explanation">Continuous Categories are permanent- they make sure your residual spending is in check.</p>');
				else if (choice.val()=='Saving')
					$('#explanation_wrapper').html('<p id="explanation">Savings Categories are temporary. They\'re great for saving money up for big purchases. They don\'t appear in your transactions menu and are intended to be deleted once you are done with them.</p>');
				$('#explanation_wrapper').css({height:$('#explanation').height()+10});
			});
			
			animated_area.animate({top:'10px',opacity:1},1200,function(){$(this).stop();});
		});
	});
});
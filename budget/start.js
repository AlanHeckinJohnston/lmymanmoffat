var information = [];
function stage2()
{
	information['username']=$('#username').val();
	$('#info').animate({'opacity':'0'},800,function(){
			$('#info').html("<p>Great! " +information['username']+ ", what email address can we use to contact you or help you with your passwords?</p><div id='floater'><input id='email'><button id='stage2submit'>Go</button></div>");
			$('#info').animate({opacity:"1"},800);
			$('#stage2submit').click(function(){
			var input = $('#email');
			email = input.val();
			$('.warning').remove();
			if ($('#email').val()=='')
				$('#info').append("<div class='warning'>Please provide an email.</div>");
			else
				$.post('start_pro.php',{email:email},function(data)
				{
					
					if (data=="ye")
						stage3();
					else if (data=="taken")				
						$('#info').append("<div class='warning'>Email is already taken!</div>");
						
				});
		});
	});

}
function stage3()
{
	information['email']=$('#email').val();
		$('#info').animate({'opacity':'0'},800,function(){
			$('#info').html("<p>Alright! Now, onto stuff having to do with your finances.</p>");
			$('#info').animate({opacity:"1"},800);
			var h1 = $('h1');
			h1.animate({left:"-450px"},1000,function(){
				h1.html('Budget Structure');
				h1.animate({left:"0px"},1000);
			});
			setTimeout(stage4,2500);

	});
}
function stage4()
{
	$('#info').animate({'opacity':'0'},800,function(){
			$('#info').html("<p>How long should each budget Interval be? Typically, people align this with how often they are paid.</p><div id='floater'><select id='interval'><option>Weekly</option><option>Biweekly</option><option>Monthly</option><option>Quarterly</option></select><button id='stage4submit'>Go</button></div>");
			$('#info').animate({opacity:"1"},800);
			$('#stage4submit').click(function(){
			var input = $('#interval');
			interval = input.val();
			$('.warning').remove();
			$.post('start_pro.php',{interval:interval},function(){
				stage5();
			});
		});
	});
}
function stage5()
{
	information['interval']=$('#interval').val();
	$('#info').animate({'opacity':'0'},800,function(){
	$('#info').html("<p>Now, let's set the date for your budget to begin. You can set to today, or, you can set it to the past to get a bit of a head start.</p><div id='floater'>Format: dd/mm/yyyy</div><div id='floater'><input maxlength='2' size='2' id='mm'>/<input size='2' maxlength='2' id='dd'>/<input  size='2' maxlength='4' id='yy'><button id='stage5submit'>Go</button></div>");
	var d = new Date();
	$('#dd').val(d.getDate());
	$('#mm').val(d.getMonth()+1);
	$('#yy').val(d.getFullYear());
	$('#info').animate({opacity:"1"},800);
		$('#stage5submit').click(function(){
			var input = $('#interval');
			interval = input.val();
			$('.warning').remove();
			stage6();
			
		});
	});
}	
function stage6()
{
	information['date']=$('#mm').val() + "/" + $('#dd').val() + "/" + $('#yy').val();
	$('#info').animate({'opacity':'0'},800,function(){
		$('#info').html("<p>Saving your information...</p>");
		$('#info').animate({opacity:'1'},800);
	});

	$.post('setup.php',{u:information['username'],e:information['email'],i:information['interval'],d:information['date']},stage7);
}
function stage7()
{
	$('#info').animate({'opacity':'0'},800,function(){
		$('#info').html("<p>An email has been sent to " + email + " with a link to set your password!</p>");
		$('#info').animate({opacity:'1'},800);
	});
	var h1 = $('h1');
	h1.animate({left:"-450px"},1000,function(){
		h1.html('All Done!');
		h1.animate({left:"0px"},1000);
	});
}
$(document).ready(function(){
	$('h1').animate({top:'0px'},1000,function(){$('#info').animate({opacity:'1'},1200);});
	$('.warning').width($('input').width());

	$('#stage1submit').click(function(){
		var input = $('#username');
		name = input.val();
		$('.warning').remove();
		if ($('#username').val()=='')
			$('#info').append("<div class='warning'>Please provide a username.</div>");
		else
			$.post('start_pro.php',{username:name},function(data){
				if (data=="ye")
					stage2();
				else if (data=="taken")			
					$('#info').append("<div class='warning'>Username is already taken!</div>");		
				
				
		});
	});
});
var information = [];
function stage2()
{
	$('#info').animate({'opacity':'0'},800,function(){
			var pass = $('#pass').val();
			$('#info').html("<p>Great! Now, type this password again...</p><div id='floater'><input id='cpass' type='password'><button id='stage2submit'>Go</button></div>");
			$('#info').animate({opacity:"1"},800);
			$('#stage2submit').click(function(){
				var input = $('#cpass');
				email = input.val();
				$('.warning').remove();
				if (pass != $('#cpass').val())
				{
					$('#info').append("<div class='warning'>Passwords do not match!</div>");
					setTimeout(function(){location.reload()},2000);
				}
				else
				{
					$.post('set.php',{password:pass},function(){window.location='https://www.lymanmoffat.com'});
				}
		});
	});
}
$(document).ready(function(){
	$('h1').animate({top:'0px'},1000,function(){$('#info').animate({opacity:'1'},1200);});
	$('.warning').width($('input').width());

	$('#stage1submit').click(function(){
		var input = $('#stage1submit');
		name = input.val();
		stage2();
		
				
		
	});
});
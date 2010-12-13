<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>UnravelTheMusic, coming soon!</title>
	<script type="text/javascript">
		var GB_ROOT_DIR = "http://www.unravelthemusic.com/ci/public/greybox/";
	</script>
	<link rel='shortcut icon' href="../../../favicon.ico" type="image/x-icon" />
	<script src="http://www.unravelthemusic.com/jquery.js" type="text/javascript"></script>
	<script src="http://www.unravelthemusic.com/jquery.form.js" type="text/javascript"></script>
	<link  href="http://www.unravelthemusic.com/ci/style/style_beta.css" rel="stylesheet" type="text/css" />

</head>

<!--Body is Primary Color-->
<body>
<!-- Javascript -->
	<script type="text/javascript">
	$(document).ready(function() {
	

		$('#emailForm').ajaxForm(function(data) {
            
			if (data==0) {
				$('.submit').fadeOut('normal');
				$('.email').fadeOut('normal');
				$('.input').fadeOut('normal', function() { 
					$("p.thanks").fadeIn(2500);
					$("p.thanks").empty();
					$("p.thanks").append("The Email Address field is required.")
					$("p.thanks").fadeOut(1500, function() { 
						$('.submit').fadeIn('normal');
						$('.email').fadeIn('normal');
						$('.input').fadeIn('normal');				
				
					});				
				});			
			}
			
			else if (data==1){
				$('.submit').fadeOut('normal');
				$('.email').fadeOut('normal');
				$('.input').fadeOut('normal', function() { 
					$("p.thanks").fadeIn(2500);
					$("p.thanks").empty();
					$("p.thanks").append("That Email already exists in our mailing list.")
					$("p.thanks").fadeOut(1500, function() { 
						$('.submit').fadeIn('normal');
						$('.email').fadeIn('normal');
						$('.input').fadeIn('normal');				
				
					});				
				});	
            }
            else if (data==2){
				$('.submit').fadeOut('normal');
				$('.email').fadeOut('normal');
				$('.input').fadeOut('normal', function() { 
					$("p.thanks").fadeIn(2500);
					$("p.thanks").empty();
					$("p.thanks").append("That is not a properly formatted email address.")
					$("p.thanks").fadeOut(1500, function() { 
						$('.submit').fadeIn('normal');
						$('.email').fadeIn('normal');
						$('.input').fadeIn('normal');				
				
					});				
				});		
            }
            else if (data==3)
            {
				$('.submit').fadeOut('normal');
				$('.email').fadeOut('normal');
				$('.input').fadeOut('normal', function() {
					$("p.thanks").fadeIn(1500);
					$("p.thanks").empty();
					$("p.thanks").append("Thank You, you will start receiving updates shortly")	
	 				
				});
           }
        });		
				

				
	});  	
	</script>
<!--Body is Primary Color-->
<body>
<!--First Div is Darker Blue-->
	<div id="first">
<!--Second Div is White-->
		<div id="second">
<!--Third Div is Gray Content Box-->
			<div id="third">
<!--Logo Section-->
				<?php
				$logo = array('width' => '400',
						'src' => 'images/logo.png',
						'class' => 'third',
						'alt' => 'Unravel The Music',
						'border' => '0'
					);
				$email = array('src' => 'images/email.png',
						'class' => 'email',
						'alt' => 'email',
						'border' => '0'
					);
				echo(img($logo));
				?>
				
				<p class="third">We're currently under development. In the mean time, let's <span class="third">keep in touch.</span></p>
<!--Above Email Form-->				
				<?=img($email)?> 
				<p class="email">Email <span class="email">Address</span>:</p>
<!--Email Form-->
				
				<form action="form" id="emailForm" method="post" class="form">
				<p class="input"><input type="text" id="email" name="email" /></p>
				<p class="submit"><input type="submit" value="Submit" id="submit" /></p>
				<?=form_close()?>
				<p class="thanks"></p>
			</div>
		</div>
	</div>
<!--Navigation Box at Bottom-->
	<div id="bottom">
		<p class="bottom">
			<?=anchor('../', 'registration', array('title' => 'Home and Email Registration', 'class' => 'links')) . ' | ' .
			anchor('../about', 'about', array('title' => 'About Unravel', 'class' => 'links')) . ' | ' .
			anchor('../blog', 'blog', array('title' => 'Unravel Development Blog', 'class' => 'links')) . ' | ' .
			anchor('../legal', 'privacy policy', array('title' => 'Your Privacy', 'class' => 'links')) . ' | ' .
			'<span class="bottom">' .
			anchor('../contact', 'contact', array('title' => 'Contact Unravel', 'class' => 'contactlink'))
			. '</span>'
			?>
		</p>
	</div>
</body>
</html>
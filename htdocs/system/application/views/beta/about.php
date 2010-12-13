<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>UnravelTheMusic, coming soon!</title>
	<link rel='shortcut icon' href="../../../favicon.ico" type="image/x-icon" />
	<link  href="http://www.unravelthemusic.com/ci/style/style_beta.css" rel="stylesheet" type="text/css" />
</head>
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
				echo(img($logo));
				?>
<!--About Content-->
				<p class="about"><span class="about">Unravel</span> is aiming to be the premier website for fans to identify lyrics, comment on their favorite songs, and dicuss meanings. Built completely from scratch, this easy-to-use website will make it fun for all music lovers to participate in a great community revolving around them. With great features such as a system to rate meanings, multiple ways to search, journals, and more, the site to allow everyone to quickly and easily access information about the songs they want.
				</div>
		</div>
	</div>
<!--Navigation Box at Bottom-->
	<div id="bottom">
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
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-346257-5");
pageTracker._trackPageview();
</script>
</body>
</html>
	</div>
</body> 
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Unravel The Music - <?=$title?></title>
		<?=link_tag('style/reset.css')?>
		<?=link_tag('style/style.css')?>
		
		<link href="<?=base_url().$this->config->item('FAL_assets_front').'/'.$this->config->item('FAL_css');?>/fal_style.css" rel="stylesheet" type="text/css" />
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js" type="text/javascript"></script>
		<script src="<?=base_url().$this->config->item('FAL_assets_shared').'/'.$this->config->item('FAL_js');?>/flash.js" type="text/javascript"></script>

	</head>


	<body>

		<?php
		$logo = array('width' => '900',
				'src' => 'images/logo.png',
				'class' => 'logo',
				'alt' => 'Unravel The Music',
				'border' => '0',
				'align' => 'left'
			);
		$add = array('src' => 'images/add.png',
				'class' => 'addNew',
				'title' => 'Add New',
				'alt' => 'Add New',
				'border' => '0'
			);		
		$email = array('src' => 'images/email.png',
				'class' => 'email',
				'alt' => 'email',
				'border' => '0'
			);
		$ads = array('src' => 'images/ads.jpg',
				'class' => 'ads',
				'alt' => 'ads',
				'border' => '0'
			);
		
		?>
		<!--First Div is Darker Blue-->
		<div id="body-wrapper">
			
				<!--header-->
				<div id="header">
					<?php 
					echo(anchor('home/index', img($logo)));
					?>
					<p class="topLinks">
						<a href="users/register" class="headerLink">Join Up</a> |
						<a href="users/login" class="headerLink"> Login</a> |
						<a href="about" class="headerLink"> About</a>
					</p>
					
				</div>
				<div id="nav">
					<img src='/ci/images/bl-nav.jpg' align="left" />
					<img src='/ci/images/br-nav.jpg' align="right" />
					<p>
					<?
					echo(anchor('#', "Find Songs", array('class' => 'nav_links')));
					if($this->db_session->userdata('user_name') != '')
					{
						echo(anchor('users/edit', 'Edit Profile', array('class' => 'nav_links') ));
						echo(anchor('users/logout', 'Logout', array('class' => 'nav_links')));
					} else {
						echo(anchor('users/login', 'Login', array('class' => 'nav_links')));
						echo(anchor('users/register', 'Register', array('class' => 'nav_links')));
					}
					if($this->freakauth_light->belongsToGroup('powerUser') == '1')
					{
						$pendingArtists = $this->poweruser->countArtists();
						$pendingPictures = $this->poweruser->countPictures();
						$pendingAlbumPictures = $this->poweruser->countAlbumPictures();
						$total = $pendingArtists + $pendingPictures + $pendingAlbumPictures;
						echo(anchor('/manage/', 'Power User Console', array('class' => 'nav_links')) . '<span class="small">(' . $total . ') New Requests</span>');
					}
					?>
					
						
					</p>
					
				</div>
			<div class="inner">
				<div id="middle">
					<div class="rounded_top"><div></div></div>
					<div class="content">
					  

						<!--STAR FLASH MESSAGE-->
						<?php 
						$flash=$this->db_session->flashdata('flashMessage');
					
						if (isset($flash) AND $flash!='')
						{?>
							
							<div id="flashMessage" style="display:none;">
								
								<?=$flash?>
							</div>
						<?php }?>
						<!--END FLASH-->					
					

						<?php require_once($template_contents.'.php'); ?>
						
						<br style="clear:both" />
					</div>
					<div class="rounded_bottom"><div></div></div>
				</div>
				
				<div id="footer">
					<div class="rounded_top"><div></div></div>
					<div class="footerContent">
					<p>(C) Unravel 2008.  All information on this website is copyright their respected owners<br />
					Unravel cannot take responsibility for comments, or lyrics posted by members.</p>
					</div>
					<div class="rounded_bottom_right"><div></div></div>
				</div>

			</div>
			<div id="right">
				<div class="rounded_top"><div></div></div>
				<h4>Support <span>Unravel</span></h4>
				<?=img($ads)?>
				<hr />
				<h4>Search <span>Unravel</span></h4>
				<?
				echo(form_open('search/artist'));
				$defaultArtist = '"...by artist"';
				$defaultAlbum = '"...by album"';
				$defaultSong = '"...by song"';
				$jsArtist = "onclick='clickclear(this, $defaultArtist)' onblur='clickrecall(this,$defaultArtist)'";				
				$jsAlbum = "onclick='clickclear(this, $defaultAlbum)' onblur='clickrecall(this,$defaultAlbum)'";	
				$jsSong = "onclick='clickclear(this, $defaultSong)' onblur='clickrecall(this,$defaultSong)'";				
				echo('<input type="text" name="artist" value="...by artist" id="artist" maxlength="50" size="30" class="rightSearch" ' . $jsArtist . ' /> ');

?>
				</form>
				<h6>Browse Artists by letter<?=anchor('artists/add', img($add))?></h6>
				<?php
				echo(anchor('artists/catalog/0', '#', array('class' => 'browse')) . ' '); 
				for($letter = 'A', $i = 0; $i <= 25; $letter++, $i++ ){
					echo (anchor('artists/catalog/' . $letter, $letter, array('class' => 'browse')) . ' ');
				}
				?>
				<br /><br />
				<?=form_open('search/album')?>
				<?='<input type="text" name="album" value="...by album" id="album" maxlength="50" size="30" class="rightSearch" ' . $jsAlbum . ' />'?>
				</form>
				<h6>Browse Albums by letter</h6>

				<?php
				echo(anchor('albums/catalog/0', '#', array('class' => 'browse')) . ' '); 
				for($letter = 'A', $i = 0; $i <= 25; $letter++, $i++ ){
					echo (anchor('albums/catalog/' . $letter, $letter, array('class' => 'browse')) . ' ');
				}

				?>
				<br /><br />
				<?=form_open('search/song')?>
				<?='<input type="text" name="song" value="...by song" id="song" maxlength="50" size="30" class="rightSearch" ' . $jsSong . ' />'?>
				</form>
				<h6>Browse Songs by letter</h6>

				<?php
				echo(anchor('songs/catalog/0', '#', array('class' => 'browse')) . ' '); 
				for($letter = 'A', $i = 0; $i <= 25; $letter++, $i++ ){
					echo (anchor('songs/catalog/' . $letter, $letter, array('class' => 'browse')) . ' ');
				}
		

				?>
				<hr />
				<div class="beUnravel">
				<h4>Be <span>Unravel</span></h4>
				<ul class="beUnravel">
					<?
					if($this->db_session->userdata('user_name') != '')
					{
						echo("<li class='beUnravel'>" . anchor('users/edit', 'Edit Profile', array('class' => 'beUnravel') ) . "</li>");
						echo("<li class='beUnravel'>" . anchor('users/logout', 'Logout', array('class' => 'beUnravel')) . "</li>");
					} else {
						echo("<li class='beUnravel'>" . anchor('users/login', 'Login', array('class' => 'beUnravel')) . "</li>");
						echo("<li class='beUnravel'>" . anchor('users/register', 'Register', array('class' => 'beUnravel')) . "</li>");
					}
					echo("<li class='beUnravel'>" . anchor('tutorial/submitSong', 'Submit Song Meaning', array('class' => 'beUnravel')) . "</li>");
					echo("<li class='beUnravel'>" . anchor('tutorail/submitLyrics', 'Submit Songs/Lyrics', array('class' => 'beUnravel')) . "</li>");
					echo("<li class='beUnravel'>" . anchor('journals/popular', 'Popular Journals', array('class' => 'beUnravel')) . "</li>");
					echo("<li class='beUnravel'>" . anchor('users/top', 'Top Unravellers', array('class' => 'beUnravel')) . "</li>");
					echo("<li class='beUnravel'>" . anchor('users/new', 'New Unravellers', array('class' => 'beUnravel')) . "</li>");
					echo("<li class='beUnravel'><span>" . anchor('feedback/', 'Give Feedback', array('class' => 'beUnravel')) . "</span></li>");
					?>
				</ul>
				<div class="rounded_bottom_right"><div></div></div>
				</div>
				
			</div>
		
			
		</div>
		
<!--Navigation Box at Bottom-->
<script type="text/javascript">
function clickclear(thisfield, defaulttext) {
if (thisfield.value == defaulttext) {
thisfield.value = "";
}
}
function clickrecall(thisfield, defaulttext) {
if (thisfield.value == "") {
thisfield.value = defaulttext;
}
}
</script>

</body>
</html>


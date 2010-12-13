<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
$memcache = new Memcache;
$memcache->pconnect('localhost', 11211) or $memcache = false;
?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta name="keywords" content="Unravel The Music, unravelthemusic.com, song meanings, lyrics, popular song meanings " />
		<meta name="title" content="UnravelTheMusic.com" />
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" /> 
		<title>Unravel The Music - <?=$title?></title>
		<?php
			$url = "assets/css/public/";	
			$jsUrl = "assets/js/public/";
		?>		
		<script src="<?=base_url() . $jsUrl?>jquery.js" type="text/javascript"></script>
		<script src="<?=base_url() . $jsUrl?>jquery.cookies.min.js" type="text/javascript"></script>
		<?php
				if($this->dx_auth->is_logged_in())
		{
			echo('<script src="http://www.unravelthemusic.com/assets/js/public/unravel-00000010.min.js" type="text/javascript"></script>');
		} else {
			echo('<script src="http://www.unravelthemusic.com/assets/js/public/noUser-00000004.min.js" type="text/javascript"></script>');
		}
		?>
		
		<?=link_tag($url . 'style-00000028.css')?>
		<!--[if lt IE 8]>
			<?=link_tag($url . 'ie-style.css')?>	
		<![endif]-->		

		<!--[if lte IE 6]>
		<?=link_tag($url . 'ie6-style.css')?>
		<![endif]-->
	</head>
	<body>
		<?php
		$logo = array(
				'src' => 'assets/images/public/logo.png',
				'height' => '67px',
				'class' => 'logo',
				'alt' => 'Unravel The Music',
			);
		$ads = array('src' => 'assets/images/public/ads.png',
				'class' => 'ads',
				'alt' => 'ads',
			);
		$footerDiv = array('src' => 'assets/images/public/footer_div.png',
				'alt' => '*',
				'class' => 'footerDivider'
			);		
		?>
		<!--Header div contains logo and login/search -->
		<div id="header">
			<div id="headerContent">
				<?php 
				echo(anchor('home/index', img($logo)));
				if(!$this->dx_auth->is_logged_in())			
				{
					?>

					<div id="loginContainer">
						<?php
						$attributes = array(
								'id' => 'login_form',
							);
						echo(form_open('users/login', $attributes));			
						?>
						<div id="loginLabels">
		
							<label for="username" class="usernameLabel">Username or Email:</label>
							<label for="password" class="passwordLabel">Password:</label>					
						</div>
						<input type="submit" class="loginButton" value="" />
						<div class="login">										
							<input type="text" name="username" id="username" class="s" value="" style="border: 0" />
						</div>
						<div class="login">
							<input type="password" name="password" id="password" class="s" value="" style="border: 0" />
						</div>
						
						</form>
					</div>
				<?php
				}
				?>		
			</div><!-- end container div -->
		</div><!-- end login div -->
		<!-- Nav is green bar -->
		<div id="nav">
			<div id="navContent">
				<p class="navLinks">
					<?
					echo(anchor('/home/index', "HOME", array('class' => 'links')));
					echo(anchor('/journals/index', "JOURNALS", array('class' => 'links')));
					echo(anchor('/community/index', "COMMUNITY", array('class' => 'links')));
					echo(anchor('/blog/index', "OFFICIAL BLOG", array('class' => 'links')));
					echo(anchor('/report/bug', "REPORT ISSUE", array('class' => 'links')));
					?>
					<span class="rightLinks">
						<?php						
						if($this->dx_auth->is_logged_in())
						{
							echo(anchor('users/view/' . $this->session->userdata('DX_username'), 'MY PROFILE', array('class' => 'links') ));
							if($this->dx_auth->is_role('admin'))
							{
								echo(anchor('admin/home', 'Admin', array('class' => 'links')));
							}
							if($this->dx_auth->is_role(array('admin', 'poweruser')))
							{
								$total = $memcache->get('PUInfo');
								if($total == null)
								{
									$pendingArtists = $this->poweruser->countArtists();
									$pendingPictures = $this->poweruser->countPictures();
									$pendingAlbumPictures = $this->poweruser->countAlbumPictures();
									$total = $pendingArtists + $pendingPictures + $pendingAlbumPictures;
									//$total = $pendingPictures + $pendingAlbumPictures;
									$memcache->set('PUInfo', $total, 0, 100);
								}
								echo(anchor('/manage/', 'POWER USER (' . $total . ')', array('class' => 'links')));
							}
							echo(anchor('users/logout', 'LOGOUT', array('class' => 'links')));
						} else {
							echo(anchor('users/register', 'REGISTER', array('class' => 'links')));
						}
		
						?>
					</span>
				</p>
			</div>
		</div>	

			
		<div id="wrapper">
			<div id="content">
				<!--STAR FLASH MESSAGE-->
				<?php 
				$flash=$this->session->flashdata('flashMessage');		
				if (isset($flash) AND $flash!='')
				{?>			
					<div id="flashMessage">				
					<?=$flash?>
					</div>
				<?php }?>
				<!--END FLASH-->					
				<?=$content?>			
			</div>
			<div id="right">
				<?php
				if(1==2)
				{
				?>
				<div id="rightAds">
					<script type="text/javascript"><!--
						google_ad_client = "pub-9570825177650183";
						/* unravel-big_box */
						google_ad_slot = "1347904167";
						google_ad_width = 300;
						google_ad_height = 250;
						//--><!--
						</script>
						<script type="text/javascript"
						src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
					</script>
				
				</div>
				<?php
				}
				?>
				<div id="rightSearch">
					<h2>Search Unravel</h2>
					<hr class="rightHr" />
					<div class="searchForm">
					<?
					echo(form_open('search/artists'));
						$defaultArtist = '"by artist..."';
						$defaultAlbum = '"by album..."';
						$defaultSong = '"by song..."';			
						echo('<div class="searchBox"><input type="text" name="artist" value="by artist..." id="artist" maxlength="100" style="border: 0;" class="rightSearch"/> </div><input type="submit" class="goButton" value="" />');
						?>
					</form>
					</div>
					
					<?php
					echo(anchor('artists/catalog/0', '#', array('class' => 'browse')) . ' '); 
					for($letter = 'A', $i = 0; $i <= 25; $letter++, $i++ )
					{
						echo (anchor('artists/catalog/' . $letter, $letter, array('class' => 'browse')) . ' ');
					}
					?>
					<hr class="rightHr" />
					
					<div class="searchForm">
					<?=form_open('search/albums')?>
						<?='<div class="searchBox"><input type="text" name="album" value="by album..." id="album" maxlength="100" style="border: 0;" class="rightSearch" /></div><input type="submit" class="goButton" value="" />'?>
					</form>
					</div>
					<?php
					echo(anchor('albums/catalog/0', '#', array('class' => 'browse')) . ' '); 
					for($letter = 'A', $i = 0; $i <= 25; $letter++, $i++ )
					{
						echo (anchor('albums/catalog/' . $letter, $letter, array('class' => 'browse')) . ' ');
					}
					?>
					<hr class="rightHr" />
					<div class="searchForm">
					<?=form_open('search/songs')?>
						<?='<div class="searchBox"><input type="text" name="song" value="by song..." id="song" maxlength="100" style="border: 0;" class="rightSearch" /></div><input type="submit" class="goButton" value="" />'?>
					</form>
					</div>
					<?php
					echo(anchor('songs/catalog/0', '#', array('class' => 'browse')) . ' '); 
					for($letter = 'A', $i = 0; $i <= 25; $letter++, $i++ )
					{
						echo (anchor('songs/catalog/' . $letter, $letter, array('class' => 'browse')) . ' ');
					}
					?>
				</div>
				<div id="rightTopArtists">
					<h2>Popular Artists</h2>
					<hr class="rightHrArtists" />
					<table class="artistTable">
						<tr>
							<?php	
							$cover = base_url() . 'assets/images/public/cover.png';							
							$topArtist = $memcache->get('topArtists');
							if($topArtist == null)
							{
								$this->db->order_by('viewcount', 'desc');
								$this->db->where('viewcount_month', date('m'));
								$query = $this->db->get('artists', 5);
								
								$result = $query->result();
								if($query->num_rows() > 2)
								{
									$rand_keys = array_rand($result, 3);
									$topArtists = array();
									$topArtists[] = $result[$rand_keys[0]]->artist;
									$topArtists[] = $result[$rand_keys[1]]->artist;
									$topArtists[] = $result[$rand_keys[2]]->artist;		
									$i = 0;
								} else {
									$topArtists[] = 'Refreshing Database';
								}
								$topArtist = null;
								foreach($topArtists as $artist)
								{
									$topArtist .= "<td class='artistBox" . $i . "'>";
									$i++;
									$image = $this->images->loadTopPicture(url_title($artist));
									if($image != null)
									{
										$img = 'http://static.unravelthemusic.com/artists/' . $image;
									} else {
										$img = base_url() . 'assets/images/public/blank_artist.png';
									}
									
									$artistImg = array(
											'src' => $cover,
											'style' => 'background:url(' . $img . ') no-repeat; background-position: center center',
											'class' => 'artistPic',
											'alt' => $artist,
										);
									$topArtist .= (anchor('artists/view/'. url_title($artist), img($artistImg)));
									$length = strlen($artist);
									if($length > 15)
									{
										$topArtist .= anchor('artists/view/'. url_title($artist), substr($artist, 0, 15) . '...', array('class' => 'rightLinkSmall')) . '</td>';
									} else {
										$topArtist .= anchor('artists/view/'. url_title($artist), $artist, array('class' => 'rightLinkSmall')) . '</td>';
									}									
								}
								$memcache->set('topArtists', $topArtist, 0, 21600);
							}
							echo $topArtist;
					
							?>
						</tr>
					</table>
				</div>
				<div id="rightSmalls">
					<div id="rightFavorites">
						<h2 class="right">User Favorites</h2>
						<hr class="rightHrSmall" />
						<table class="favoritesTable">
							<tr>
								<td class="favoriteSongs">
									<?=anchor('songs/favorites', 'Songs', array('class' => 'favoriteLinks'))?>
								</td>
								<td class="favoriteAlbums">
									<?=anchor('albums/favorites', 'Albums', array('class' => 'favoriteLinks'))?>
								</td>
							</tr>
							<tr>
								<td class="favoriteArtists">
									<?=anchor('artists/favorites', 'Artists', array('class' => 'favoriteLinks'))?>
								</td>
								<td class="favoriteJournals">
									<?=anchor('journals/favorites', 'Journals', array('class' => 'favoriteLinks'))?>
								</td>
							</tr>
						</table>
					</div>
					<div id="rightRandom">
						<h2 class="right">Random</h2>
						<hr class="rightHrSmall" />
						<table class="favoritesTable">
							<tr>
								<td class="favoriteSongs">
									<?=anchor('songs/random', 'Songs', array('class' => 'favoriteLinks'))?>
								</td>
								<td class="favoriteAlbums">
									<?=anchor('albums/random', 'Albums', array('class' => 'favoriteLinks'))?>
								</td>
							</tr>
							<tr>
								<td class="favoriteArtists">
									<?=anchor('artists/random', 'Artists', array('class' => 'favoriteLinks'))?>
								</td>
								<td class="favoriteJournals">
									<?=anchor('journals/random', 'Journals', array('class' => 'favoriteLinks'))?>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div id="mostDiscussed">
					<h2>Today's Most Discussed Songs</h2>
					<hr class="rightHrDiscussed" />
					<?php

					$discussedSongs = $memcache->get('discussedSongs');

					if($discussedSongs == null)
					{						
						date_default_timezone_set('America/Chicago');
						$commentType = 'comments_yesterday';
						if(date('H') > 9)
						{
							$commentType = 'comments_today';
						}
						$this->db->order_by($commentType, 'desc');
						$this->db->join('artists', 'artists.artist_id = songs.artist_id');
						$this->db->join('albums', 'albums.album_id = songs.album_id');
						$comments = $this->db->get('songs', 5);
						
						$row = $comments->row();
						if($row->$commentType == 0)
						{
							$this->db->order_by('comments_all', 'desc');
							$this->db->join('artists', 'artists.artist_id = songs.artist_id');
							$this->db->join('albums', 'albums.album_id = songs.album_id');
							$comments = $this->db->get('songs', 5);						
						}
						$i = 0;
						$ds = null;
						foreach($comments->result() as $song) {
							$discussedSongs .= ($i+1) . '. ' . anchor('artists/view/' . $song->artist_seo_name, $song->artist, array('class' => 'rightLink')) . ' - ' . anchor('songs/view/' . $song->artist_seo_name . '/' . $song->album_seo_name . '/' . $song->song_seo_name, $song->song, array('class' => 'rightLink'));
							if($i != 4) {
								$discussedSongs .= "<hr class='rightHrDiscussed' />";
							}
							$i++;
						}
						$memcache->set('discussedSongs', $discussedSongs, 0, 1200);
					}
					echo($discussedSongs);						
					?>
				</div>
				<div id="popularJournals">
					<h2>Popular Journal Entries</h2>
					<?php
					$pJournals = null;
					$pJournals = $memcache->get('pJournals');
					if($pJournals == null)
					{						
						date_default_timezone_set('America/Chicago');
						$this->db->order_by('comments_today');
						$this->db->where('comment_day', date('d'));
						$journals = $this->db->get('journals', 5);
						
						if($journals->num_rows() == 0)
						{
							$this->db->order_by('created_on', 'desc');
							$journals = $this->db->get('journals', 5);
						
						
						}
						
						$i = 0;
						
						foreach($journals->result() as $journal)
						{
							$pJournals .= '<hr class="rightHrDiscussed" />';
							$pJournals .= ($i+1) . '. ' . anchor('users/view/' . $journal->user, $journal->user, array('class' => 'rightLink')) . ' - ' . anchor('journals/view/' . $journal->user . '/' . $journal->journal_id, substr($journal->title, 0, 30), array('class' => 'rightLink'));
							$i++;
						}
						$memcache->set('pJournals', $pJournals, 0, 1800);
					}
					echo($pJournals);								
					?>
                </div>
				<div id="rightNewUsers">
					<h2>New Users</h2>
					<hr class="rightHrArtists" />
					<table class="userTable">
						<tr>
							<?php	
							$newUsers = null;
							$newUsers = $memcache->get('newUsers');
							if($newUsers == null)
							{							
								$this->db->order_by('users.id', 'desc');
								$this->db->join('user_profile', 'users.id = user_profile.user_id');
								$query = $this->db->get('users', 3);
								$i = 0;
								foreach($query->result() as $user)
								{
									$newUsers .= "<td class='userBox" . $i . "'>";
									$i++;
									if($user->avatar != '')
									{
										$img = 'http://static.unravelthemusic.com/users/' . $user->avatar;
									} else {
										$img = 'http://www.unravelthemusic.com/assets/images/public/blank_user.png';
									}
									$userImg = array(
											'src' => $cover,
											'class' => 'artistPic',
											'style' => 'background:url(' . $img . ')  no-repeat; background-position: center center',
											'alt' => $user->username,
										);
									$newUsers .= anchor('users/view/'. $user->username, img($userImg));
									$newUsers .= anchor('users/view/'. $user->username, $user->username, array('class' => 'rightLinkSmall')) . '</td>';
								
								}
								$memcache->set('newUsers', $newUsers, 0, 600);
							}
							echo($newUsers);
							?>
						</tr>
					</table>
				</div>	
			</div>
		</div>
		<br style="clear:both" />
	</div>
		<div id="footer">
			<div id="footerContent">
				<div class="footerSearch">
					<div class="footerSearchMenu">
					<span class="footerSearchLinkSelected" id="songs">Songs</span>
					<span class="footerSearchLink" id="artists">Artists</span>
					<span class="footerSearchLink" id="albums">Albums</span>
					<?php
					/*
					<span class="footerSearchLink" id="journals">Journals</span>
					<span class="footerSearchLink" id="users">Users</span>
					*/
					?>
					</div>
					<?=form_open('search/songs', array('class' => 'footerSearchForm'))?>
					<?php
					$search = array(
						'name' => 'song',
						'class' => 'footerSearchInput',
						);
						$submit = array(
						'name' => 'submit',
						'class' => 'footerButton',
						);
					?>
					<div class="footerSearchBox"><?=form_input($search)?></div>
					<?=form_submit($submit)?>
					<?=form_close()?>
				</div>
				<p class="footerLeft">Unravel © 2008-2009</p>
				<p class="footerRight"><?=anchor('about', 'About', array('class' => 'footerLink'))?> <?echo(img($footerDiv))?> <?=anchor('advertising', 'Advertising', array('class' => 'footerLink'))?> <?echo(img($footerDiv))?> <?=anchor('privacy', 'Privacy', array('class' => 'footerLink'))?> <?echo(img($footerDiv))?> <?=anchor('legal', 'Legal', array('class' => 'footerLink'))?></p>
			</div>
		</div>
		<?php
		if(!$this->dx_auth->is_logged_in())
		{
		?>
			<div class="loginController">
				<div class="overlay" style=""></div>
				<div class="loginPopup" style="">
					<div class="popupRegister">
					
					</div>
					<div class="popupLogin">
						<p>To login, enter your information below</p>
						<hr class="popupHr" />
						<?php
						$attributes = array(
							'id' => 'login_form',
							'class' => 'form'
						);
						echo(form_open('users/login', $attributes));			
							?>
							<input type="submit" class="loginButton2" value="" />
							<fieldset>
								<ul>
									<li>
										<label class="popup" for="username">Username:</label>
										<input type="text" name="username" id="username" class="s2" />
									</li>
									<li>
										<label class="popup" for="password">Password:</label>	
										<input type="password" name="password" id="password" class="s2" />
									</li>
								</ul>
							</fieldset>
						
						</form>
					</div>

					<br clear="both" />
					<a href="/#" class='closeOverlay' onclick="return false;">Close</a>
				</div>
			</div>		
		<?php
		}
		?>


<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-7603357-2");
pageTracker._trackPageview();
} catch(err) {}</script>
<script type="text/javascript">

	jQuery(document).ready(function(){
		jQuery('.s, .s2').click(function(){
			jQuery.cookies.set('unravel_location', '<?=$this->uri->uri_string()?>');
		});
		
		jQuery('.footerSearchLink, .footerSearchLinkSelected').click(function() {
			var id = (this.id);
			jQuery('.footerSearchForm').attr('action', 'http://www.unravelthemusic.com/search/' + id);
			var strLen = id.length;
			idShort = id.slice(0,strLen-1); 			
			jQuery('.footerSearchInput').attr('name', idShort);
			jQuery('.footerSearchLinkSelected').attr('class', 'footerSearchLink');
			jQuery('#'+id).attr('class', 'footerSearchLinkSelected');
		});	
		
	});
</script>
	</body>
</html>


<?php

	$this->load->helper('cookie');
		$left = array(
				'src' => 'assets/images/public/hdr_lt_edge.png',
				'border' => '0',
				'alt' => 'left border',
				'align' => 'left'
			);
		$right = array(
				'src' => 'assets/images/public/hdr_rt_edge.png',
				'border' => '0',
				'alt' => 'right border',
				'align' => 'right'
			);
		$divide = array(
				'src' => 'assets/images/public/gray_divider.png',
				'border' => '0',
				'alt' => '*',
				'class' => 'graydivider'
			);
		$adlinks = array(
	            		'src' => 'assets/images/public/adlinks.png',
	            		'border' => '0',
						'alt' => 'ads ads',
	            		'class' => 'adlinks'
	            		);
?>
	<div class="feedHeader">
		<?=img($left)?>
		<?=img($right)?>
		<?php
		$linkHeader = array('class' => 'feedHeaderLinks');
		$user = false;
		if($this->dx_auth->is_logged_in())
		{
			$user = true;
		}
		switch($feedFilter) {
			case 'all':?>
				<p class='activeFeed'>All Updates</p>
				<span class='feedLinks'>
					<?php if($user) {
					//echo(anchor('home/', 'Tagged', $linkHeader) . img($divide));
					}?>
					<?=anchor('home/artists', 'New Artists', $linkHeader) . img($divide)?>
					<?=anchor('home/albums', 'New Albums', $linkHeader) . img($divide)?>
					<?=anchor('home/meanings', 'New Meanings', $linkHeader) . img($divide)?>
					<?=anchor('home/news', 'Site News', $linkHeader)?></span>
			<?php
				break;
			case 'news':?>
				<p class='activeFeed'>News</p>
				<span class='feedLinks'>
					<?=anchor('home/all', 'All Updates', $linkHeader) . img($divide)?>				
					<?php if($user) {
					//echo(anchor('home/tagged', 'Tagged', $linkHeader) . img($divide));
					}?>
					<?=anchor('home/artists', 'New Artists', $linkHeader) . img($divide)?>
					<?=anchor('home/albums', 'New Albums', $linkHeader) . img($divide)?>
					<?=anchor('home/meanings', 'New Meanings', $linkHeader)?></span>	
				<?php
				break;
			case 'newArtists':?>
				<p class='activeFeed'>New Artists</p>
				<span class='feedLinks'>
					<?=anchor('home/all', 'All Updates', $linkHeader) . img($divide)?>				
					<?php if($user) {
					//echo(anchor('home/tagged', 'Tagged', $linkHeader) . img($divide));
					}?>
					<?=anchor('home/albums', 'New Albums', $linkHeader) . img($divide)?>
					<?=anchor('home/meanings', 'New Meanings', $linkHeader) . img($divide)?>
					<?=anchor('home/news', 'Site News', $linkHeader)?></span>
				<?php
				break;
			case 'newAlbums':?>
				<p class='activeFeed'>New Albums</p>
				<span class='feedLinks'>
					<?=anchor('home/all', 'All Updates', $linkHeader) . img($divide)?>				
					<?php if($user) {
					//echo(anchor('home/tagged', 'Tagged', $linkHeader) . img($divide));
					}?>
					<?=anchor('home/artists', 'New Artists', $linkHeader) . img($divide)?>
					<?=anchor('home/meanings', 'New Meanings', $linkHeader) . img($divide)?>
					<?=anchor('home/news', 'Site News', $linkHeader)?></span>
				<?php
				break;
			case 'newMeanings':?>
				<p class='activeFeed'>New Meanings</p>
				<span class='feedLinks'>
					<?=anchor('home/all', 'All Updates', $linkHeader) . img($divide)?>				
					<?php if($user) {
					echo(anchor('home/tagged', 'Tagged', $linkHeader) . img($divide));
					}?>
					<?=anchor('home/artists', 'New Artists', $linkHeader) . img($divide)?>
					<?=anchor('home/albums', 'New Albums', $linkHeader) . img($divide)?>
					<?=anchor('home/news', 'Site News', $linkHeader)?></span>
				<?php
				break;
			case 'tagged':?>
				<p class='activeFeed'>Tagged</p>
				<span class='feedLinks'>
					<?=anchor('home/all', 'All Updates', $linkHeader) . img($divide)?>				
					<?=anchor('home/artists', 'New Artists', $linkHeader) . img($divide)?>
					<?=anchor('home/albums', 'New Albums', $linkHeader) . img($divide)?>
					<?=anchor('home/meanings', 'New Meanings', $linkHeader) . img($divide)?>				
					<?=anchor('home/news', 'Site News', $linkHeader)?></span>
				<?php
				break;
		}
?>

	</div>
	<?php
	if(!get_cookie('unravel_banner', TRUE))
	{
	?>
	<div class="spotlightBanner">
		<img src="/assets/images/public/close_gray.png" class="bannerClose" />
		
		<h4 class="bannerSpotlightHeader"><img src="/assets/images/public/spotlight_mag_gray.png" /> Spotlight Artist: <?=anchor('artists/view/Greg-Laswell', 'Greg Laswell', array('class' => 'bannerSpotlightHeader'))?></h4>
		<p class="bannerText">
			<img src='http://static.unravelthemusic.com/artists/Greg-Laswell/Greg-Laswell_thumb.jpg' class="bannerSpotlightImg" />
			<?=anchor('artists/view/Greg-Laswell', 'Greg Laswell', array('class' => 'bannerText'))?>
			 is an up and coming alternative and acoustic artists with music contributions in the television series Grey's Anatomy and several movies.  Greg is making waves with an upcoming EP <i><?=anchor('spotlight/interview/Greg-Laswell', 'Covers', array('class' => 'bannerText'))?></i> and a full studio album.  
			Check out his <?=anchor('artists/view/Greg-Laswell', 'Spotlight page', array('class' => 'bannerText'))?> for his <?=anchor('spotlight/bio/Greg-Laswell', 'biography', array('class' => 'bannerText'))?> and <?=anchor('spotlight/interview/Greg-Laswell', 'interview', array('class' => 'bannerText'))?>.
		</p>
	</div>
	<?php
	}
	?>
	<div class="expcol">
		<?php
		$expanded = false;
		if($feedView == 1)
		{
			$expanded = true;
		}
		if($expanded == true)
		{
			?>
			<span class="notSelected">
				<a href="" class="notSelectedLink1" id="collapse" onclick="return false">Collapsed</a>
			</span>			
			<span class="selected">
				<a href="" class="selectedLink1" id="expand" onclick="return false">Expanded</a>
			</span>

			<?php
		} else {
			?>
			<span class="selected">
				<a href="" class="selectedLink2" id="collapse" onclick="return false">Collapsed</a>
			</span>			
			<span class="notSelected">
				<a href="" class="notSelectedLink2" id="expand" onclick="return false">Expanded</a>
			</span>

			<?php
		}
		?>
	</div>	
<br />
	<div class="feed">
	

		
		<ul id="feedList">

		<?php
				$previousPic = NULL;
				$pictureLimit = 0;
				$i = 1;
				$endDiv = false;

				if($feedList == null)
				{			
					
					$feed = null;
					foreach($albums->result() as $album) 
					{
						if($i == 40)
						{
							$feed .= '</ul>';
							$endDiv = true;
							
							$moreFeed = false;
							if($this->dx_auth->is_logged_in() && $feedView != 0)
							{
								$moreFeed = true;
							}
							if(!$this->dx_auth->is_logged_in() && !get_cookie('unravel_feedView', TRUE))
							{
								$moreFeed = true;
							}
							if($moreFeed == false)
							{
								$feed .= '<div class="moreFeed" style="display: block">';
							} else {
								$feed .= '<div class="moreFeed" style="display: none">';
							}
							$feed .= '<ul id="feedList">';
							
						}
						if($i == 10) {

							$feed .= "<li class='ads'>";
						
							$feed .= "<script type=\"text/javascript\"><!--
							google_ad_client = \"pub-9570825177650183\";
							/* unravel-first_links */
							google_ad_slot = \"4114230978\";
							google_ad_width = 468;
							google_ad_height = 15;
							//-->
							</script>
							<script type=\"text/javascript\"
							src=\"http://pagead2.googlesyndication.com/pagead/show_ads.js\">
							</script>";
							
							$feed .= '<hr /></li>';
							$i++;
						
						}
						if($i == 20) {
							$feed .= "<li class='ads'>";
							
							$feed .= "<script type=\"text/javascript\"><!--
							google_ad_client = \"pub-9570825177650183\";
							/* unravel-second_links */
							google_ad_slot = \"3062993690\";
							google_ad_width = 468;
							google_ad_height = 15;
							//-->
							</script>
							<script type=\"text/javascript\"
							src=\"http://pagead2.googlesyndication.com/pagead/show_ads.js\">
							</script>";
							
							$feed .= '<hr /></li>';
							$i++;
						}
						
						if($album->type == 0 && ($feedFilter == 'all' || $feedFilter == 'news' || $feedFilter == 'tagged'))
						{ 
						  $feed .= '<li class="maint"><p class="siteNews">Site News</p>: ' . $album->info . '...' . anchor('blog/view/' . $album->related_id, "(more)") . '<hr /></li>';
						  $i++;
						}
						elseif($album->type == 1 && ($feedFilter == 'all' || $feedFilter == 'newAlbums' || $feedFilter == 'tagged'))
						{
						  $feed .= '<li class="newAlbum">' . 
						  anchor('users/view/' . $album->username, ucfirst($album->username)) . 
						  " added the album " .
						  anchor('albums/view/' . $album->artist_seo_name . '/' . $album->album_seo_name, $album->album) . 
						  " by " . 
						  anchor('artists/view/' . $album->artist_seo_name, $album->artist) .
						  '<hr /></li>';
						  $i++;
						} 
						elseif($album->type == 2 && ($feedFilter == 'all' || $feedFilter == 'newMeanings' || $feedFilter == 'tagged'))
						{
							$feed .= '<li class="newMeaning">' .
								anchor('users/view/' . $album->username, ucfirst($album->username)) . 
								' posted a ' . 
								anchor('/songs/view/' . $album->artist_seo_name . '/' . $album->album_seo_name . '/' . $album->song_seo_name . '/#' . $album->related_id, 'new song meaning') . 
								' for ' . 
								anchor('songs/view/' . $album->artist_seo_name . '/' . $album->album_seo_name . '/' . $album->song_seo_name, '"' . $album->song . '"') . 
								' by ' . 
								anchor('artists/view/' . $album->artist_seo_name, $album->artist) . '.';
								if($feedView != 0)
								{

									$feed .= '<p class="quote">';
									if($album->artist_picture && $album->artist_picture_verified > 0 && $pictureLimit < 8 && $previousPic != $album->artist_seo_name)
									{
										$picture = $album->artist_picture;
										$filename = substr($picture, 0, -4);
										$extension = substr($picture, -4);

										$picture = $filename . '_thumb' . $extension;	
										$feed .= anchor('artists/view/' . $album->artist_seo_name, '<img src="http://static.unravelthemusic.com/artists/' . $picture . '" align="left" class="feedArtistPic" />');
										$pictureLimit++;
										$previousPic = $album->artist_seo_name;
									}									
									
									$feed .= '"' . $album->info . '..." ' . anchor('/songs/view/' . $album->artist_seo_name . '/' . $album->album_seo_name . '/' . $album->song_seo_name . '/#' . $album->related_id, '(more)') . "</p>";
								} else {
									$feed .= '<p class="quote" style="display:none;">"' . $album->info . '..." ' . anchor('/songs/view/' . $album->artist_seo_name . '/' . $album->album_seo_name . '/' . $album->song_seo_name . '/#' . $album->related_id, '(more)') . "</p>";
								}
								$feed .= '<hr /></li>';
								$i++;
						}
						elseif($album->type == 3 && ($feedFilter == 'all' || $feedFilter == 'newArtists' || $feedFilter == 'tagged'))
						{
							$feed .= '<li class="newArtist">' . 
								anchor('users/view/' . $album->username, ucfirst($album->username)) . 
								' added a new artist: ' .
								anchor('artists/view/' . $album->artist_seo_name, $album->artist) .
								'<hr /></li>';
								$i++;
						}
						?><?php
					}
					if($endDiv)
					{
						$feed .= '</div>';
					}
					echo $feed;
					$memcache->set('feed' . $feedFilter . $feedView, $feed, 0, 600);
					
					
				} else {
					echo $feedList;
				}
		?></ul>
	</div>
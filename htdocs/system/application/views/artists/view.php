<?php
if($verified == 0) {
	$row = $query->row();
	echo('This artists has been added to our database but has yet to be verified. <br /> Please try back later');
	if($this->dx_auth->is_role(array('admin', 'poweruser')))
	{
		echo('<br />' . anchor('manage/artists/confirm/' . $row->artist_id, 'Confirm ' . $artist . ' Artist<br />'));
		echo(anchor('manage/artists/remove/' . $row->artist_id, 'Remove  ' . $artist . ' Artist<br />'));
	}
} elseif($verified == -1) {
	echo('This artists has been permanently removed from our database, please contact our support staff for further questions.');
} else {
	echo('<div class="artistInfo">');	
		if ($picVerified == 1)
		{
			$artistPic = array(
						'src' => "http://static.unravelthemusic.com/artists/" . $artistPicture,
						'class' => 'artistPic',
						'align' => 'left'
					);
			echo(img($artistPic));
		} else {
			$artistPic = array(
						'src' => base_url() . 'assets/images/public/blank_artist.png',
						'class' => 'artistPic',
						'align' => 'left'
					);
			echo(img($artistPic));		
		}
		$tagArtist = "<span class='tagArtist' id='" . $artist_seo_name . "' ></span>";
		$tagArtist2 = "<span class='tagArtist' id='" . $artist_seo_name . "' style='display:none'></span>";
		$untagArtist = "<span class='untagArtist' id='" . $artist_seo_name . "'></span>";
		$untagArtist2 = "<span class='untagArtist' style='display:none' id='" . $artist_seo_name . "'></span>";
		if($watching == false)
		{					
			echo("<h3 class='artistName'>" . anchor('artists/view/' . $artist_seo_name, $artist) . "</h3><div class='tags'>" . $tagArtist . $untagArtist2. "</div><br />");
		} else {	  	
			echo("<h3 class='artistName'>" . anchor('artists/view/' . $artist_seo_name, $artist) . "</h3><div class='tags'>" . $untagArtist . $tagArtist2 . "</div><br />");
		}
					$list = array(
					'src' => base_url() . 'assets/images/public/view_all_tracks.png',
					'class' => 'viewalltracksImg'
				);
		echo(anchor('songs/catalog/' . $artist_seo_name, img($list)) . '<br />');
		if($this->dx_auth->is_logged_in())
		{
			if($artistPicture == null || $picVerified == -1)
			{
				$upload = array(
					'src' => base_url() . 'assets/images/public/add_artist_image.png',
					'class' => 'viewalltracksImg'
					);
				?>
				<a href="/#" class="showForm"><?=img($upload)?></a>
				<div class="uploadFormHidden">
				
					<?php echo form_open_multipart('artists/upload_picture/'. $artist_seo_name);?>

						<input type="file" name="userfile" size="20" />

						<br />

						<input type="submit" value="upload" />

					</form>
				</div>
		 
				<?php 
			}
		}
		?>
	</div>

	<?php
	$addAlbumImage = array(
			'src' => base_url() . 'assets/images/public/add.png',
			'class' => 'addAlbumImg'
		);
	?>
	<table class="studioAlbumsInfo"><tr><td><h2 class="body">Studio Albums</h2></td><td class="commentsThisMonth"><?=anchor('albums/add/' . $artist_seo_name, 'Add album' . img($addAlbumImage))?></td></tr></table>
	<?php
	if($areAlbums == false)
	{
		echo("<br /><br />No albums have been added for " . $artist . " yet<br />
		add one " . anchor('albums/add/' . $artist_seo_name, 'now'));

	} else {
		?>
		<div class="albumHolder">
			<?php
			$hiddenAlbums = false;
			$noAlbumCover = base_url() . 'assets/images/public/blank_album.png';
			$i = 0;
			foreach($query->result() as $albums)
			{
				if($i == 4)
				{
				echo('</div>');
				echo('<div class="hiddenAlbums">');
				$hiddenAlbums = true;
				$i++;
				} else {
				$i++;
				}
				?>
				<div class='albumBlock'><?php
					if($albums->album_picture_verified == 1) {
						$filename = substr($albums->album_picture, 0, -4);
						$extension = substr($albums->album_picture, -4);
	
						$albumPicture = $filename . '_thumb' . $extension;
						echo anchor('albums/view/' . $albums->artist_seo_name . "/" . $albums->album_seo_name,  '<img src="http://static.unravelthemusic.com/albums/' . $albumPicture . '" class="albumPic" />');
					} else {
						echo anchor('albums/view/' . $albums->artist_seo_name . "/" . $albums->album_seo_name,  '<img src="' . $noAlbumCover . '" class="albumPic"  align="left" />');
						
					}
						echo '<br />' . anchor('albums/view/' . $albums->artist_seo_name . "/" . $albums->album_seo_name, $albums->album, array('class' => 'albumName'));
						if($albums->release_date != '0000')
						{
							echo('<br /><span class="releaseYear">Release Year: ' . $albums->release_date . '</span>');
						}
					
					?>
				</div>
			<?php
			}

			?>
		</div><br clear="both" /><?php
			if($hiddenAlbums == true)
			{
				$arrayButtonRight = array(
								'src' => base_url() . 'assets/images/public/arrow_button_right.png',
								'class' => 'arrowButtonRight'
					);
				$arrayButtonLeft = array(
								'src' => base_url() . 'assets/images/public/arrow_button_left.png',
								'class' => 'arrowButtonLeft'
					);					
				echo('<br clear="both" /><a href="/#" class="expandAlbums">See more' . img($arrayButtonRight) . '</a>');
				echo('<a href="/#" class="hideAlbums">' . img($arrayButtonLeft) . 'Hide albums</a>');
			}
	}//end there actually is an album there.
		if(!empty($similarArtists))
		{
			?>
			<br clear="both" />
			<h2 class="body">Similar Artists</h2>
			<div class="albumHolder">
				<?php
				
				if ($random == 0)
				{
						echo("<div class='albumBlock'>");
						
						if($similarArtists[0]['picture'] != null)
						{
							echo ("<img src=http://static.unravelthemusic.com/artists/" . $similarArtists[0]['picture'] . ' class="similarPics" />');
						}
						echo('<br />' . anchor('artists/view/' . url_title($similarArtists[0]['artist']), $similarArtists[0]['artist'], array('class' => 'albumName')));
						echo("</div>");
									
					
					
				} else {
					foreach($random as $element)
					{
						echo("<div class='albumBlock'>");
						if($similarArtists[$element]['picture'] != null)
						{
							$picture = array(
									'src' => "http://static.unravelthemusic.com/artists/" . $similarArtists[$element]['picture'],
									'class' => 'similarPics',
									'width' => '100',
									'height' => '100'
								);
							echo (anchor('artists/view/' . url_title($similarArtists[$element]['artist']),  img($picture)));
						} else {
							echo (anchor('artists/view/' . url_title($similarArtists[$element]['artist']), "<img src='http://www.unravelthemusic.com/assets/images/public/blank_artist.png' class='similarPics'/>"));
							
						}
						echo('<br />' . anchor('artists/view/' . url_title($similarArtists[$element]['artist']), $similarArtists[$element]['artist'], array('class' => 'albumName')));
						
						echo("</div>");
					}
				}
			?></div><br clear="both" /><?php
		}
	?>
	<h2 class="body">Top Tracks</h2>
	<div class="subNavTracks">
		<div class="thisMonthTab">
			<?=anchor('/#', 'This Month', array('class' => 'thisMonthTabActive'))?>
		</div>
		<div class="allTimeTab">
			<?=anchor('/#', 'All-Time', array('class' => 'allTimeTabInactive'))?>
		</div>
		
	</div>
	<hr class="topTracksHr" />
	<div class="topMonth">
		<table class="topMonthList">
		
			<?php
			foreach($topMonth->result() as $top)
			{
				echo('<tr class="topList"><td>' . anchor('songs/view/' . $artist_seo_name . '/' . $top->album_seo_name . '/' . $top->song_seo_name, $top->song) . ', on ' . anchor('albums/view/' . $artist_seo_name . '/' . $top->album_seo_name, $top->album) . '</td><td class="commentsThisMonth">' . $top->comments_all . ' comments</td></tr><tr><td colspan="2"><hr /></td></tr>');
			
			}
			
			?>
		</table>
	</div>
	<div class="topAll">
		<table class="topAllList">
		
			<?php
			foreach($topAll->result() as $top)
			{
				echo('<tr class="topList"><td>' . anchor('songs/view/' . $artist_seo_name . '/' . $top->album_seo_name . '/' . $top->song_seo_name, $top->song) . ', on ' . anchor('albums/view/' . $artist_seo_name . '/' . $top->album_seo_name, $top->album) . '</td><td class="commentsThisMonth">' . $top->comments_all . ' comments</td></tr><tr><td colspan="2"><hr /></td></tr>');
			
			}
			
			?>
		</table>
	</div>	
	<?php 

	if($this->dx_auth->is_role(array('admin', 'poweruser')))
	{
		echo(anchor('artists/edit/' . $artist_seo_name, 'Edit ' . $artist) . "<br />");
	}
}

?>
<script src="http://www.unravelthemusic.com/assets/js/public/jquery.form.js" type="text/javascript"></script>

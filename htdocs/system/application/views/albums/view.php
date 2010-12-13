<?php
$spam = array(
		'src' => 'assets/images/public/spam.png',
		'alt' => 'Spam',
		'class' => 'spam',
		'title' => 'Mark This Song as Spam',
		'border' => 0
	);
$duplicate = array(
		'src' => 'assets/images/public/dupe.png',
		'alt' => 'Duplicate',
		'class' => 'duplicate',
		'title' => 'Mark This Song as a Duplicate',
		'border' => 0
    ); 
$list = array(
			'src' => base_url() . 'assets/images/public/view_all_tracks.png',
			'class' => 'viewalltracksImg'
		);
	
	
if($this->dx_auth->is_role(array('admin', 'poweruser')))
{
	?>
	<script>
	function confirmDelete(delUrl) {
	  if (confirm("Are you sure you want to delete")) {
		document.location = delUrl;
	  }
	}
	</script>
	<?php
	$jsConfirm = 'onclick="return confirm(\'Are you sure you want to delete?\')"';
	echo(anchor('admin/albums/remove/' . $album_id, 'Remove ' . $album, $jsConfirm) . "<br /><br />");
	echo(anchor('albums/edit/' . url_title($artist) . '/' . url_title($album), 'Edit this album') . "<br /><br />");
}


		
	
//$row is from the songs database

//hasPicture is from the albums database 
//if we get nothing from the songs database we know there are no songs current for this album
if($query->num_rows() == 0)
{ 
  //since we have no songs we will tell the user they can add one, all vars have to be from albums database
  echo('no songs have been added for this album add one ');
  echo(anchor('songs/add/' . $artist . '/' . $album, 'now!'));
} else {
//else everything is fine and there is at least one song in the database for this album

	echo('<div class="artistInfo">');	
		?>
		<span class="report">
			Report Song:
			<?=anchor('report/album/' . $album_id . '/spam', img($spam))?>
			<?=anchor('report/album/' . $album_id . '/duplicate', img($duplicate))?>
		</span>		
		<?php
		if ($picVerified == 1)
		{					
			$filename = substr($albumPicture, 0, -4);
			$extension = substr($albumPicture, -4);

			$albumPictureThumb = $filename . '_thumb' . $extension;
			$albumPic = array(
						'src' => "http://static.unravelthemusic.com/albums/" . $albumPictureThumb,
						'class' => 'artistPic',
						'align' => 'left'
					);
			echo(img($albumPic));
		} else {
			$albumPic = array(
						'src' => base_url() . 'assets/images/public/blank_album.png',
						'class' => 'artistPic',
						'align' => 'left'
					);
			echo(img($albumPic));		
		}
		$tagArtist = "<span class='tagArtist' id='" . $artist_seo_name . "' ></span>";
		$tagArtist2 = "<span class='tagArtist' id='" . $artist_seo_name . "' style='display:none'></span>";
		$untagArtist = "<span class='untagArtist' id='" . $artist_seo_name . "'></span>";
		$untagArtist2 = "<span class='untagArtist' style='display:none' id='" . $artist_seo_name . "'></span>";
		if($watchingArtist == false)
		{					
			echo("<h3 class='artistName'>" . anchor('artists/view/' . $artist_seo_name, $artist) . "</h3><div class='tags'>" . $tagArtist . $untagArtist2. "</div><br />");
		} else {	  	
			echo("<h3 class='artistName'>" . anchor('artists/view/' . $artist_seo_name, $artist) . "</h3><div class='tags'>" . $untagArtist . $tagArtist2 . "</div><br />");
		}

		echo(anchor('songs/catalog/' . $artist_seo_name, img($list)) . '<br />');
		if($this->dx_auth->is_logged_in())
		{
			if($albumPicture == null || $picVerified == -1)
			{
				$upload = array(
					'src' => base_url() . 'assets/images/public/add_album_image.png',
					'class' => 'viewalltracksImg',
					'alt' => 'Add an Album Picture'
					);
				?>
				<span class="showForm"><?=img($upload)?></span>
				<div class="uploadFormHidden">
				
					<?php echo form_open_multipart('albums/upload_picture/'. $artist_seo_name . '/' . $album_seo_name);?>

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

	<div class="albumInfo">
		<?php
		
		$tagAlbum = "<span class='tagAlbum' id='" . $artist_seo_name . "/" . $album_seo_name ."'></span>";
		$tagAlbum2 = "<span class='tagAlbum' style='display:none' id='" . $artist_seo_name . "/" . $album_seo_name ."'></span>";
		$untagAlbum = "<span class='untagAlbum' id='" . $artist_seo_name . "/" . $album_seo_name ."'></span>";
		$untagAlbum2 = "<span class='untagAlbum' style='display:none' id='" . $artist_seo_name . "/" . $album_seo_name ."'></span>";		
		?>
		<h2>Now Viewing:</h2><h3><?=$album?> </h3>
		<?php	
		if($watchingAlbum == false)
		{
		    echo("<div class='tags'>" . $tagAlbum . $untagAlbum2. "</div>");
		} else {
		    echo("<div class='tags'>" . $untagAlbum . $tagAlbum2. "</div>");
		}	
		
		?>
	</div>

	<div class="albumViewSongs">

      <?php 
	  if($locked == 0)
	  {
		echo(anchor('songs/add/' . $artist_seo_name . '/' . $album_seo_name, 'Add new song for this album'));
	  }
	  ?>
       <table class="albumView">   
		<tr>
			<td>Song</td>
			<td class="albumViewCount">Number of Comments</td>
		</tr>
      <?php foreach($query->result() as $row): ?>

		<tr class="albumViewSongHover">
			<td class="albumViewSong">
				<?php
				echo anchor('songs/view/' . $artist_seo_name . "/" . $album_seo_name . "/" . $row->song_seo_name, $row->song);
				echo "</td><td class='albumViewCount'>" . $row->comments_all . '</std>';
		?>
		</lr>
      <?php endforeach; ?>
	  </table>

	</div>
<?php
}
?>
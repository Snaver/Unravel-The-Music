<br /><h1>Search Unravel</h1><br />
<div class="subNavSearch">
	<div class="artistTab">
		<?=anchor('search/artists/' . $search, 'Artists', array('class' => 'artistTabInactive'))?>
	</div>
	<div class="albumTab">
		<div class="albumTabActive">Albums</div>
	</div>
	<div class="songTab">
		<?=anchor('search/songs/' . $search, 'Songs', array('class' => 'songTabInactive'))?>
	</div>
	<div class="journalTab">
		<?=anchor('search/journals/' . $search, 'Journals', array('class' => 'journalTabInactive'))?>
	</div>
	<div class="userTab">
		<?=anchor('search/users/' . $search, 'Users', array('class' => 'userTabInactive'))?>
	</div>
	<div class="forumTab">
		<?=anchor('search/forums/' . $search, 'Forums', array('class' => 'forumTabInactive'))?>
	</div>
</div>

<hr class="topTracksHr" />
<div class="searchResults">
	<div class="searchForm">
<?php
	echo(form_open('search/albums'));
	echo('<div class="searchBoxLong"><input type="text" name="album" value="' . $search . '" id="album" maxlength="100" style="border: 0;" class="rightSearch" /> </div>');
	echo('<input type="submit" name="submit" class="goButton" value="" style="margin-top: 9px;" />');
	echo(form_close());
	echo('<br /><br /><br />');
	
	if($noSearch == false)
	{
		if($results->num_rows() > 0)
		{
			foreach($results->result() as $row)
			{
				?>
				<table>
				<tr><td>
			
					<?php
					$img['class'] = 'searchPicture';
					if($row->artist_picture != null && $row->artist_picture_verified == 1)
					{
						$filename = substr($row->artist_picture, 0, -4);
						$extension = substr($row->artist_picture, -4);

						$img['src'] = 'http://static.unravelthemusic.com/artists/' . $filename . '_thumb' . $extension;	

					} else {
						$img['src'] = base_url() . 'assets/images/public/blankartist.png';
					}	
					echo(img($img) . '</td><td class="searchRight">');
					echo('<h3>' . anchor('albums/view/' . $row->artist_seo_name . '/' . $row->album_seo_name, $row->album) . '</h3>');
					echo('<p class="searchArtist">' . anchor('artists/view/' . $row->artist_seo_name, $row->artist) . '</p>');
					//echo('<p class="searchTracks">' . 'x tracks' . '</p>');

					?>
					
					</td></tr>
				</table><hr />
			
				<?php
			}
			if($links != '')
			{
				echo('Page: ' . $links);
			}
		} else {
			echo('<h3>No results found</h3>');
		}
		
	}

?></div>
</div>

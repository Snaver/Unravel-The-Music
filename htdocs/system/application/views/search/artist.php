<br /><h1>Search Unravel</h1><br />
<div class="subNavSearch">
  <div class="artistTab">
    <div class="artistTabActive">Artists</div>
  </div>
  <div class="albumTab">
    <?=anchor('search/albums/' . $search, 'Albums', array('class' => 'albumTabInactive'))?>
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
	echo(validation_errors());
	echo(form_open('search/artists'));
	echo('<div class="searchBoxLong"><input type="text" name="artist" value="' . $search . '" id="artist" maxlength="200" style="border: 0;" class="searchPage" /> </div>');
	echo('<input type="submit" name="submit" class="goButton" value="" style="margin-top: 9px;"/>');
	echo(form_close());
	echo('</div>');

	if($searched == true) 
	{

		if($exactMatch == true)
		{
			echo('<br /><br /><br /><hr />');
			echo('<h2 class="artistResults">Exact Match</h2><br />');
			?>
			<table>
			<tr><td>
			<?php
			
			$row = $results->row();
			$albumCount = $results->num_rows();
			$img['class'] = 'searchPicture';
			if($row->artist_picture != null && $row->artist_picture_verified == 1)
			{
				$filename = substr($row->artist_picture, 0, -4);
				$extension = substr($row->artist_picture, -4);

				$img['src'] = 'http://static.unravelthemusic.com/artists/' . $filename . '_thumb' . $extension;
				
			} else {
				$img['src'] = base_url() . 'assets/images/public/blank_artist.png';
			}	
			echo(anchor('artists/view/' . $row->artist_seo_name, img($img)));
			echo('</td><td class="searchRight">');
			echo('<h3>' . anchor('artists/view/' . $row->artist_seo_name, $row->artist) . '</h3><br />');

			
			$i = 0;
			$break = true;
			if($results->num_rows() == 1 && $row->album != '')
			{
				echo('Studio Album: ');
			} elseif ($results->num_rows() > 1)
			{
				echo('Studio Albums: ');
			} else {
				$break = false;
			}
			
			if($row->album != '')
			{
				foreach($results->result() as $albums)
				{
					
					
					if($i==4)
					{
						echo anchor('albums/view/' . $albums->artist_seo_name . "/" . $albums->album_seo_name, substr($albums->album, 0, 25));
						break;
					} else {
						echo anchor('albums/view/' . $albums->artist_seo_name . "/" . $albums->album_seo_name, substr($albums->album, 0, 25)) . ', ';
						$i++;
					}
				} 
			if($break == true)
			{
				echo("<br />");
			}



			}//end there actually is an album there.
			if(!empty($similarArtists))
			{
				$numOfSimilar = count($similarArtists);
				if($numOfSimilar == 1)
				{
					echo('Similar Artist: ');
				} else {
					echo('Similar Artists: ');
				}
				if($numOfSimilar == 3)
				{
					echo(anchor('artists/view/' . url_title($similarArtists[0]['artist']), $similarArtists[0]['artist']) . ', ');
					echo(anchor('artists/view/' . url_title($similarArtists[1]['artist']), $similarArtists[1]['artist']) . ', ');
					echo(anchor('artists/view/' . url_title($similarArtists[2]['artist']), $similarArtists[2]['artist']));
				} else if($numOfSimilar == 2) {
					echo(anchor('artists/view/' . url_title($similarArtists[0]['artist']), $similarArtists[0]['artist']) . ', ');
					echo(anchor('artists/view/' . url_title($similarArtists[1]['artist']), $similarArtists[1]['artist']));
				} else {
					echo(anchor('artists/view/' . url_title($similarArtists[0]['artist']), $similarArtists[0]['artist']));
				}					
			
			}
			?>
					</td></tr>
				</table><hr />
				
				
			<?php			
		} else if($spellingMatch == true) {
			echo('<br /><br /><br /><h2 class="artistResults">Did you Mean?</h2>');
			foreach($spelling->result() as $row)
			{
				$img['class'] = 'searchPicture';
				if($row->artist_picture != null && $row->artist_picture_verified == 1)
				{
					$filename = substr($row->artist_picture, 0, -4);
					$extension = substr($row->artist_picture, -4);

					$img['src'] = 'http://static.unravelthemusic.com/artists/' . $filename . '_thumb' . $extension;
					
				} else {
					$img['src'] = base_url() . 'assets/images/public/blank_artist.png';
				}	
				echo(anchor('artists/view/' . $row->artist_seo_name, img($img)));
				echo('</td><td class="searchRight">');
				echo('<h3>' . anchor('artists/view/' . $row->artist_seo_name, $row->artist) . '</h3><br /><hr />');
			
			}
		}
		if($partialMatch == true)
		{
			if($exactMatch == true)
			{
				echo('<br /><h2 class="artistResults">More Results</h2><br />');
			} else {
				echo('<br /><br /><br /><h2 class="artistResults">Results</h2><br />');
			}
				foreach($otherResults->result() as $match)
				{
					echo('<table><tr><td>');
					$img['class'] = 'searchPicture';
					if($match->artist_picture != null && $match->artist_picture_verified == 1)
					{
						$picture = $match->artist_picture;
						$filename = substr($match->artist_picture, 0, -4);
						$extension = substr($match->artist_picture, -4);

						$img['src'] = 'http://static.unravelthemusic.com/artists/' . $filename . '_thumb' . $extension;
						
					} else {
						$img['src'] = base_url() . 'assets/images/public/blank_artist.png';
					}				
					echo(anchor('artists/view/' . $match->artist_seo_name, img($img)));
					echo('</td><td class="searchRight">');
					echo('<h3>' . anchor('artists/view/' . $match->artist_seo_name, $match->artist) . '</h3>');
					echo('</td></tr></table><hr />');
				}
		}
		?>
			
			
		<?php
		if($links != '')
		{
			echo('Page: ' . $links);
		}
		if($exactMatch == false && $partialMatch == false && $spellingMatch == false)
		{
			echo("<br /><br /><br /><div class='exact'>No results found for: " . $search . "</div>");
			?><h5>Want to help out Unravel and add this artist?</h5><?php
			echo(anchor('artists/add', 'Add ' . $search . ' now!'));
		}
	}
?>
</div>
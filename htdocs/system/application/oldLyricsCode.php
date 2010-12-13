//songs/view.php	
	if(empty($lyricsRow->lyrics) OR $lyricsRow->lyrics_verified == -1)
		{
			echo('<p>No lyrics have been added for this song yet</p>');
			echo(anchor('songs/lyrics/add/' . $row->song_id, '<p>Submit Lyrics Now</p>'));
		}
		else if(!empty($lyricsRow->lyrics) && $lyricsRow->lyrics_verified == 1)
		{
				echo('<p>Wrong lyrics? Report These Lyrics</p>');
				if($lyricsRow->lyrics_reported >5) 
				{
					echo("<div class='important'>WARNING: these lyrics have been reported as possibly inaccurate</div>");
				}
				echo('<p>' . $lyricsRow->lyrics . '</p>');
		} else {
				echo('The lyrics for this song are being reviewed by our moderation team');
		}
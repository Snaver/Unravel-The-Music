<h3>Verify/Remove Lyrics Suggestions</h3>
<?=anchor('manage/', 'Back to Power User Console');?>
<hr /><br />
<?php
	foreach ($query->result() as $row) :
				$artist = $row->artist;
				$album = $row->album;
				$song = $row->song;
				$lyrics = $row->lyrics;
				$user = $row->submitted_by;
		?>
	<br />
				<?php
				echo("Artist: " . $artist . ' <br />Album: ' . $album . ' <br />Song: ' . $song . '<br />Submitted by: ' . $user . '<br /><br />Lyrics: ' . $lyrics . '<br />');
				echo(anchor('manage/lyrics/confirm/' . $row->lyrics_id, 'verify') . ' '  . anchor('manage/lyrics/remove/' . $row->lyrics_id, 'remove'));
				?>
					<hr />
	
			
		<?php
	endforeach;
	
?>

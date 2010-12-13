<h3>Verify/Remove Lyrics Suggestions</h3>
<?=anchor('manage/', 'Back to Power User Console');?>
<hr /><br />
<?php
	foreach ($query->result() as $row) :
				$artist = $row->artist;
				$album = $row->album;
				$user = $row->created_by;
		?>
	<br />
				<?php
				echo("Artist: " . $artist . ' <br />Album: ' . $album . '<br />Submitted by: ' . $user . '<br /><br />');
				echo(anchor('manage/albums/clean/' . $row->album_id, 'clean') . ' '  . anchor('manage/albums/remove/' . $row->album_id, 'remove'));
				?>
					<hr />
	
			
		<?php
	endforeach;
	
?>

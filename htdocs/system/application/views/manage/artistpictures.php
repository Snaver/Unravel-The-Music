<h3>Verify/Remove New Picture Suggestions</h3>
<?=anchor('manage/', 'Back to Power User Console');?>
<ul>
<?php
	foreach ($query->result() as $row) :
				$picture = $row->artist_picture;
				$filename = substr($picture, 0, -4);
				$extension = substr($picture, -4);

				$thumb = $filename . '_thumb' . $extension;

		echo("<li>");
		echo("<img src='http://static.unravelthemusic.com/artists/" . $thumb . "' />");
		echo($row->artist . " " . anchor('manage/artistpictures/confirm/' . $row->artist_id, 'verify') . ' '  . anchor('manage/artistpictures/remove/' . $row->artist_id, 'remove'));
		echo("</li>");
	endforeach;
?>
</ul>
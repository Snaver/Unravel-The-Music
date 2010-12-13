<h3>Verify/Remove New Picture Suggestions</h3>
<?=anchor('manage/', 'Back to Power User Console');?>
<ul>
<?php
	foreach ($query->result() as $row) :
				$filename = substr($row->album_picture, 0, -4);
				$extension = substr($row->album_picture, -4);

				$thumb = $filename . '_thumb' . $extension;

		echo("<li>");
		echo("<img src='http://static.unravelthemusic.com/albums/" . $thumb . "' />");
		echo($row->artist . ' - ' . $row->album . " " . anchor('manage/albumpictures/confirm/' . $row->album_id, 'verify') . ' '  . anchor('manage/albumpictures/remove/' . $row->album_id, 'remove'));
		echo("</li>");
	endforeach;
?>
</ul>
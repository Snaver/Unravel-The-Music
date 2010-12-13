<h3>Verify/Remove New Artist Suggestions</h3>
<?=anchor('manage/', 'Back to Power User Console');?>
<ul>
<?php
	if(isset($error))
	{
		echo($error);
	} else {
		$i = 0;
		foreach ($query->result() as $row) :

			
			echo("<li><br />");
			echo($inApi[$i] . "<br />");
			echo(anchor('artists/edit/' . $row->artist_seo_name, 'Edit this artist information') . "<br />");
			$i++;
			echo($row->artist . " " . anchor('manage/artists/confirm/' . $row->artist_id, 'verify') . ' '  . anchor('manage/artists/remove/' . $row->artist_id, 'remove') . " <b>Alternate Names:</b> " . $row->artist_alternate);
			echo("<br />Research this artist: " . anchor('http://www.google.com/search?hl=en&q=' . $row->artist . '&btnG=Google+Search&aq=f&oq=', 'Google'));
			if($row->artist_website != '0' && $row->artist_website != 'http://www.')
			{
				 echo(' ' . anchor($row->artist_website, 'Website'));
			}
			echo("</li>");
		endforeach;
	}
?>
</ul>
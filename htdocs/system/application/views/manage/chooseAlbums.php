<h3>Selecting albums to add to the database</h3>
<?=anchor('manage/', 'Back to Power User Console');?>
<h6>Please check the wikipedia page for STUDIO ALBUMS ONLY!</h6>
Links<br /><?=anchor('http://en.wikipedia.org/wiki/Template:' . $artist, 'Albums by' . $artist)?>
<br /><?=anchor('http://en.wikipedia.org/wiki/'. $artist, $artist . '\'s wikipedia page')?>
<br /><br />
<h2>Albums that SHOULD NOT BE ADDED</h2>
<ul>
	<li>Live albums</li>
	<li>Compilations</li>
	<li>Remix albums</li>
	<li>EPs and LPs (Will go in Other Songs)</li>
	<li>Other Songs (This will be added automatically</li>
</ul>
<b>Please do your research and don't make me go back and fix your lazyness</b>

<br />

<?php

	$hidden = array('final' => 'true', 'artist_id' => $artistId, 'songs' => serialize($songs));
	echo(form_open('manage/artists/albums', '', $hidden));
	foreach ($albums as $row)
	{


		$data = array(
			'name'        => url_title($row['album']),
			'id'          => 'album',
			'value'       => serialize($row['album'] .'**'. $row['release_date']),
			'checked'     => FALSE,
			'style'       => 'margin:10px',
		);
		echo form_checkbox($data);

			
		echo($row['album'] . "<br />");
	}
	echo(form_submit('submit', 'submit'));
	echo(form_close());
?>

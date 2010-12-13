<?php
foreach($query->result() as $song)
{
	echo(anchor('songs/view/' . $song->artist_seo_name . '/' . $song->album_seo_name . '/' . $song->song_seo_name, $song->song) . '<br />');

}
?>
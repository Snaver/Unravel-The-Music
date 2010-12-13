<?php
foreach($query->result() as $album)
{
	echo(anchor('albums/view/' . $album->artist_seo_name . '/' . $album->album_seo_name, $album->album) . '<br />');

}
?>
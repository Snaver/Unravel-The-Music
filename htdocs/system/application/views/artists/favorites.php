<?php
foreach($query->result() as $artist)
{
	echo(anchor('artists/view/' . $artist->artist_seo_name, $artist->artist) . '<br />');

}
?>
<div class="list">
<?php
$i = 3;
echo('<ul>');
	echo('<li class="listWhite"><span class="report"><h4>Comments</h4></span><h4>Song Title</h4></li>');
foreach($query->result() as $song)
{
	if($i%2 == 1)
	{
		echo('<li class="listWhite">');
	} else {
		echo('<li class="listGreen">');
	}
	echo('<span class="report">' . $song->comments_all . '</span>');
	echo(anchor('songs/view/' . $song->artist_seo_name . '/' . $song->album_seo_name . '/' . $song->song_seo_name, $song->song));
	echo('</li>');

	$i++;



}
?>
</ul>
</div>
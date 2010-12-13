<h1>Returning songs beginning with <?=strtoupper($letter)?></h1>

	<div class="albumViewSongs">
    <ul>
<?php
foreach($results->result() as $row):
?>
	<li class="albumViewSong">
		
		<?=anchor('songs/view/' . $row->artist_seo_name . '/' . $row->album_seo_name . '/' . $row->song_seo_name, $row->song) . ' by ' . anchor('artists/view/' . $row->artist_seo_name, $row->artist)?>
	</li>
<?php endforeach; ?>
</ul>
<?=$links?>
</div>
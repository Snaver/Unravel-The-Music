<h1>Returning albums beginning with <?=strtoupper($letter)?></h1>
	<div class="albumViewSongs">
    <ul>
<?php
foreach($results->result() as $row):
?>
	<li class="albumViewSong">
		<?=anchor('albums/view/' . $row->artist_seo_name . '/' . $row->album_seo_name, $row->album) . ' by ' . anchor('artists/view/' . $row->artist_seo_name, $row->artist)?>
	</li>
<?php endforeach; ?>
</ul>
<?=$links?>
</div>
<h1>Viewing artists beginning with <?=strtoupper($letter)?></h1>

<h6>Can't find what you are looking for?</h6>
<?=anchor('artists/add', 'Add an artist Now')?>
<br /><br />
	<div class="albumViewSongs">
    <ul>
<?php
foreach($results->result() as $row):
?>
	<li class="albumViewSong">
		<?=anchor('artists/view/' . $row->artist_seo_name, $row->artist)?>
	</li>
<?php endforeach; ?>
</ul>
<?=$links?>
</div>
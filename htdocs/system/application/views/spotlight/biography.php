<?php
$artist = $artist->row();
$artist = $artist->artist;
$spotlight = $spotlight->row();
?>
<h1><?=$artist?>'s Biography</h1>
<div class="spotlightContent">
<?=$spotlight->bio?>
<br /><br />
Visit <?=anchor('artists/view/' . url_title($artist), $artist)?>'s Spotlight Page

</div>
<h3><span>Power User</span> Console</h3>

<p>Follow a link below to verify new artists and approve images for bands and albums</p>
<p>If you have not done so please read the full guidelines for approving images and bands</p>

<p> Here are some quick pointers</p>
<ul>
	<li>If it is obviously spam do not approve it</li>
	<li>If it is a duplicate (please check) remove it</li>
	<li>If it contains graphic language or images remove it</li>
</ul>

<?=anchor('manage/artists/', 'Manage Artist Suggestions') . ' - ' . $pendingArtists . ' pending requests.' ?><br />
<?=anchor('manage/artistpictures/', 'Manage Artist Pictures') . ' - ' . $pendingPictures . ' pending requests.' ?><br />
<?=anchor('manage/albumpictures/', 'Manage Album Pictures') . ' - ' . $pendingAlbumPictures . ' pending requests.' ?><br />
<?=anchor('manage/albums/', 'Manage Questionable Albums') . ' - ' . $pendingQuestionable . ' pending questionable albums.' ?><br />
<?=anchor('manage/lyrics/', 'Manage Lyric Suggestions') . ' - ' . $pendingLyrics . ' pending new lyrics.'?><br />
<?php

//anchor('manage/lyrics/', 'Manage Pending Lyrics') . ' - ' . $pendingLyrics . ' pending requests.' 

?>
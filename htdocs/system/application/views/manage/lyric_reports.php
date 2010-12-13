<h3>Manage Lyric Reports</h3>
<?=anchor('manage/', 'Back to Power User Console');?>
<ul>
<?php
	if(isset($error))
	{
		echo($error);
	} else {
		foreach($query->result() as $row)
		{
			echo('<div class="lyrics">');
			echo('<h3>' . $row->song . '</h3><br />' . anchor('manage/lyric_reports/clean/' . $row->lyrics_id, 'clean lyrics') . ' ' . anchor('lyrics/edit/' . $row->song_id, 'edit') . '<br />' . $row->lyrics);
			echo('</div>');
		
		}
	}
?>
</ul>
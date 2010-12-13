<p>Unravel is proud to bring you our most popular meanings</p>
<table border = 1>
<?php foreach($query->result() as $row): ?>
  <tr>
	<td>
	  <h3><?=$row->artist?></h3>
	</td>
	<td>
	  <p><?=anchor('songs/view/' . $row->artist . '/' . $row->album . '/' . $row->song, 'view song ' . $row->song . ' by ' . $row->artist)?></p>
	</td>
	<td>
	   Meaning by <?=$row->author?><br />
	   <?php
		$offensive = array(
		  'src' => 'images/delete.png',
		  'alt' => 'Report this meaning as offensive',
		  'class' => 'reporting',
		  'title' => 'Report this meaning as offensive',
		  'border' => 0
		  );
	   echo($row->title . anchor('meanings/report/offensive/' . $row->meaning_id, img($offensive)) . '<br /><br />' . $row->body);
	   ?>
	</td> 
	<td>
		<?php
		$voteup = array(
		  'src' => 'images/voteup.png',
		  'alt' => 'Vote Up',
		  'class' => 'voting',
		  'title' => 'Vote Up',
		  'border' => 0
		  );
		$votedown = array(
		  'src' => 'images/votedown.png',
		  'alt' => 'Vote Down',
		  'class' => 'voting',
		  'title' => 'Vote Down',
		  'border' => 0
		  );
		
		echo(anchor('meanings/like/' . $row->meaning_id, img($voteup)));
		$votes = $row->rating_up - $row->rating_down;
		if($votes -1)
		{
			echo('+' . $votes);
		} else {
			echo('-' . $votes);
		}
		echo(anchor('meanings/dislike/' . $row->meaning_id, img($votedown)));
		?>
  </tr>
  <?php endforeach; ?>
</table>


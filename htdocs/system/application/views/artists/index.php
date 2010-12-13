
    <?=anchor('/artists/add', "Suggest an artist")?>
	<br /><br />
	<h3>Browse Artists by letter</h3>

	<?php
	for($letter = 'A', $i = 0; $i <= 25; $letter++, $i++ ){
		echo (anchor('artists/catalog/' . $letter, $letter) . ', ');
	}
	?>
	<br />
	<h2>Top 5 viewed artists on Unravel</h2>
    <table border = 1>
    <?php foreach($query->result() as $row): ?>
      <tr>
        <td>
          <h3><?=$row->artist?></h3>
        </td>
        <td>
          <p><?=anchor('artists/view/' . $row->artist, 'view albums by ' . $row->artist)?></p>
        </td>
        <td>
           image of band!  
        </td>
		<td>
			<?=$row->viewcount?>
		</td>
      </tr>
      <?php endforeach; ?>
    </table>


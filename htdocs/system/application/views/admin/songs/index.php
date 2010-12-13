<table>
<th>Song</th><th>Duplicate Count</th><th>Spam Count</th><th colspan=2>Action</th>
<?php
	foreach ($query->result() as $row) :
	?>
		<tr>
			<td width = "200px">
				<?=anchor('songs/view/' . $row->artist_seo_name . '/' . $row->album_seo_name . '/' . $row->song_seo_name, $row->song)?> 
			</td><td>
				<?=$row->duplicate?>
			</td><td>
				<?=$row->spam?>
			</td>
				
			<td width='150px'>

				<?=anchor('admin/songs/clean/' . $row->song_id, 'clean counts')?>
			</td><td>
				<?=anchor('admin/songs/remove/' . $row->song_id, 'remove Song')?>
		    
			</td>
		</tr>
<?php
	endforeach;
	?>
</table>
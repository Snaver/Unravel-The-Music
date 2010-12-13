<table>
		<th>Title</th><th>Author</th><th>Body</th><th>Report Count</th><th colspan=2>Action</th>
<?php 
	foreach ($query->result() as $row) :
?>
		<tr>
			<td width = "200px">
				<?=$row->title?>
			</td>
			<td>
				<?=$row->author?>
			</td>
			<td>
				<?=$row->body?>
			</td>
			<td>
				<?=$row->report?>
			</td>
			
			
			<td width='100px'>

				<?=anchor('admin/meanings/clean/' . $row->meaning_id, 'clean counts')?>
		    </td>
			<td>
				<?=anchor('admin/meanings/remove/' . $row->meaning_id, 'remove Meaning and all replies')?>
		    
		    </td></tr>
		<?php
	    endforeach;

		
		?>
</table>
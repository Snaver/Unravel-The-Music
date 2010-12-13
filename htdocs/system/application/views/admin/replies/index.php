<table>
	<th>Title</th><th>Author</th><th>Body</th><th>Report Count</th><th>Meaning Count</th><th colspan=3>Action</th>
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
		<td>
		    <?=$row->meaning?>
		</td>
			
			
		<td width='110px'>

		    <?=anchor('admin/replies/clean/' . $row->reply_id, 'clean counts')?>
		</td>
		<td width='110px'>
			| <?=anchor('admin/replies/remove/' . $row->reply_id, 'remove reply')?>
		</td>
		<td>
			| <?=anchor('admin/replies/split/' . $row->reply_id, 'split into meaning')?>
		</td>
	</tr>
	<?php    
	endforeach;
	?>
</table>
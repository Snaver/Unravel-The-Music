
<table>
<th>Album</th><th>Duplicate Count</th><th>Spam Count</th><th>Questionable Status</th><th colspan=2>Action</th>
<?php	
	    foreach ($query->result() as $row) :
	?>
<tr><td width = "200px">
<?=$row->album?></td><td>
<?=$row->duplicate?></td><td>
<?=$row->spam?></td><td>
<?=$row->questionable?></td>
<td width='150px'>
<?php
if($row->questionable == 1)
{
	echo anchor('admin/albums/cleanQuestionable/' . $row->album_id, 'clean questionable');
} else {
	echo anchor('admin/albums/clean/' . $row->album_id, 'clean counts');
}
?>
</td><td>
<?=anchor('admin/albums/remove/' . $row->album_id, 'remove album')?>
</td></tr>
<?php
	    endforeach;
?>
</table>
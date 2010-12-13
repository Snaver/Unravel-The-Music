<script>
function confirmDelete(delUrl) {
  if (confirm("Are you sure you want to delete")) {
    document.location = delUrl;
  }
}
</script>

<?php
	$this->load->helper('form');
	echo(form_open('admin/artists/index'));
$options = array(
                  'artist_id desc'  => 'Artist Id (Desc)',
				  'artist_id asc'  => 'Artist Id (Asc)',
                  'artist desc'    => 'Artist Name (Desc)',
				   'artist asc'    => 'Artist Name (Asc)',
				    'viewcount desc'    => 'Viewcount (Desc)',
					'viewcount asc'    => 'Viewcount (Asc)',
					'verified desc'    => 'Verified(Desc)',
					'verified asc'    => 'Verified(Asc)',
                );


echo form_dropdown('view', $options, 'artistId');	
echo form_submit('mysubmit', 'Submit Post!');
echo('<br />');
$jsConfirm = 'onclick="return confirm(\'Are you sure you want to delete?\')"'
?>
<table>
	
	<?php
	foreach($query->result() as $artist)
	{
		echo('<tr><td>');
		echo($artist->artist);
		echo('</td><td style="padding-left: 10px;">');
		echo('verified:' . $artist->verified);
		echo('</td><td style="padding-left: 10px;">');
		echo('viewcount:' . $artist->viewcount);
		echo('</td><td style="padding-left: 10px;">');
		echo(anchor('users/view/' . $artist->artist_created_by, $artist->artist_created_by));
		echo('</td><td style="padding-left: 10px;">');
		echo(anchor('admin/artists/delete/' . $artist->artist_id, 'delete', $jsConfirm));
		echo('</td><td style="padding-left: 50px;">');
		echo(anchor('artists/edit/' . $artist->artist_seo_name, 'edit'));
	
	
		echo('</td></tr><tr><td>&nbsp</td></tr>');
	
	}
?>
	
</table>
<?php
echo $links;	
?>
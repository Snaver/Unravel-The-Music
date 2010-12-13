
				
<?php


$attributes = array('class' => 'form');

$hidden = array('artist_id' => set_value('artist_id', $artist_id), 'oldArtist' => url_title($artist));

echo form_open('artists/edit', $attributes, $hidden);
$artistField = array(
			  'name'        => 'artist',
			  'id'          => 'artist',
			  'value'       => 	set_value('artist', $artist),
			  'maxlength'   => '80',
			  'size'        => '30',
			);	
$websiteField = array(
			  'name'        => 'website',
			  'id'          => 'website',
			  'value'       => 	set_value('website', $website),
			  'maxlength'   => '80',
			  'size'        => '30',
			);			
$wikiField = array(
			  'name'        => 'wiki',
			  'id'          => 'wiki',
			  'value'       => 	set_value('wiki', $wiki),
			  'maxlength'   => '80',
			  'size'        => '30',
			);	
$createdByField = array(
			  'name'        => 'createdBy',
			  'id'          => 'createdBy',
			  'value'       => 	set_value('createdBy', $createdBy),
			  'maxlength'   => '80',
			  'size'        => '30',
			);	
$picVerifiedField = array(
			  'name'        => 'picVerified',
			  'id'          => 'picVerified',
			  'value'       => 	set_value('picVerified', $picVerified),
			  'maxlength'   => '80',
			  'size'        => '30',
			);	

		?>	
	<div class="important">
		<?=validation_errors()?>
	</div>
	<fieldset>
		<legend>Submit an Artist</legend>
		<ul>
			<li>
				<label>Artist Name</label>		
				<?=form_input($artistField)?>
			</li>
			<li>
				<label>Verification Status</label>
				<?=$verified?>
			</li>
			<li>
				<label>Artist Website</label>
				<?=form_input($websiteField)?>
			</li>
			<li>
				<label>Artist Wiki Page</label>
				<?=form_input($wikiField)?>
			</li>			
				<label>Created By</label>
				<?=form_input($createdByField)?>
			</li>
				<label>Picture</label>
				<?php
				if($picture != null)
				{
					echo('<img src="http://static.unravelthemusic.com/artists/' . $picture . '" />');
				}
				?>
			</li>
			<li>
				<label>Picture Verified (-1 to allow for a new upload)</label>
				<?=form_input($picVerifiedField)?>
			</li>
		</ul>
	</fieldset>
<?php
    echo form_submit('submit edit', 'Submit');
    echo form_close();    
?>
<?php

$attributes = array('class' => 'form');

$hidden = array('album_id' => set_value('album_id', $album_id), 'artist' => $artist, 'oldAlbum' => $album);

echo form_open('albums/edit', $attributes, $hidden);
$albumField = array(
			  'name'        => 'album',
			  'id'          => 'album',
			  'value'       => 	set_value('album', $album),
			  'maxlength'   => '80',
			  'size'        => '30',
			);	
$questionableField = array(
			  'name'        => 'questionable',
			  'id'          => 'questionable',
			  'value'       => 	set_value('questionable', $questionable),
			  'maxlength'   => '80',
			  'size'        => '30',
			);			
$lockedField = array(
			  'name'        => 'locked',
			  'id'          => 'locked',
			  'value'       => 	set_value('locked', $locked),
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
$releaseYearField = array(
			'name'			=> 'releaseYear',
			'id'			=> 'id',
			'value'			=> set_value('releaseYear', $releaseYear),
			'maxlength'		=> '10',
			'size'			=> '20',
			);
		?>	
	<div class="important">
		<?=validation_errors()?>
	</div>
	<fieldset>
		<legend>Submit an Artist</legend>
		<ul>
			<li>
				<label>Album</label>		
				<?=form_input($albumField)?>
			</li>
			<li>
				<label>Questionable Status</label>
				<?=form_input($questionableField)?>
			</li>
			<li>
				<label>Locked Status</label>
				<?=form_input($lockedField)?>
			</li>			
				<label>Created By</label>
				<?=form_input($createdByField)?>
			</li>
			<li>
				<label>Release Year</label>
				<?=form_input($releaseYearField)?>
			</li>
			<li>
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
    echo form_submit('submit', 'Submit');
    echo form_close();    
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js" type="text/javascript"></script>
	<div class="important">
		<?=validation_errors()?>
	</div>
    <?php
$meaning = array(
			'name' => 'body',
			'id' => 'body',
			'maxlength' => '500',
			'rows' => '7',
			'cols' => '40');
	$song_id = $this->uri->segment(3);
    echo form_open('meanings/add/' . $song_id, array('class' => 'form'));
	
    ?>
	<fieldset>
		<legend>New Meaning Form</legend>
		<ol>
			<li>
				<label>Title</label>
				<?=form_input('title')?>
			</li>
			<li>
				<label>Body</label>
				<?=form_textarea($meaning)?>
			</li>
		</ol>
	
	<?php
    echo form_submit('submit meaning', 'Submit New Meaning');
    echo form_close();
      
    ?>
    </fieldset>

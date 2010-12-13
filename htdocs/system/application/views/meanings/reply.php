<?php

$reply = array(
			'name' => 'body',
			'id' => 'body',
			'maxlength' => '500',
			'rows' => '7',
			'cols' => '40');         

     $parent_id = $this->uri->segment(3);

    echo form_open('meanings/submit/reply/' . $parent_id, array('class' => 'form'));
	
    ?>
	<fieldset>
	<legend>Reply to x meaning</legend>
	<ol>
		<li>
			<label>Title</label>
			<?=form_input('title')?>
		</li>
		<li>
			<label>Body</label>
			<?=form_textarea($reply)?>
		</li>
	</ol>
	</fieldset>
	<?php
    echo form_submit('submit meaning', 'Submit New Meaning');
    echo form_close();
      
    ?>


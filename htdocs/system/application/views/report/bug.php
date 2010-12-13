Found a bug? Want a new feature?
Let us know!
<?php
$this->load->helper('form');
	$form = array(
		'class' => 'form'
		);
	$summary = array(
				'width' => '80%',
				'value' => set_value('summary'),
				'name'	=> 'summary'
		);
	$description = array(
				'rows'	=> '5',
				'cols'	=> '70',
				'name'	=> 'description',
				'value'	=> set_value('description')
		);
echo(form_open('report/bug/', $form));
?>
<?=validation_errors()?>
<fieldset>
	<ol>
		<li>
			<label>Summary</label>
			<?=form_input($summary)?>
		</li>
		<li>
			<label>Description</label>
			<?=form_textarea($description)?>
		</li>
	</ol>
</fieldset>
<?=form_submit('submit', 'submit')?>
<?=form_close()?>
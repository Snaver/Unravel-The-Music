<?php
$submitButton = array(
			'name' => 'submit',
			'class' => 'goButton'
			);

	?>
	<h3>Search Unravel</h3>
<?=form_open('search/artist', array('class' => 'form'))?>
<fieldset>
	<ul>
		<li>
			<label>Search by Artist</label>
			<span class="searchBox"><input type="text" name="artist" value="<?=set_value('artist')?>" id="artist" maxlength="50" size="30" class="rightSearch" /></span>
			<?=form_submit($submitButton)?>
		</li>
	</ul>
</fieldset>
</form>
<hr />
<?=form_open('search/album', array('class' => 'form'))?>
<fieldset>
	<ul>
		<li>

			<label>Search by Album</label>
			<span class="searchBox"><input type="text" name="album" value="<?=set_value('album')?>" id="album" maxlength="50" size="30" class="rightSearch" /></span>
			<?=form_submit($submitButton)?>
		</li>
	</ul>
</fieldset>
</form>
<hr />
<?=form_open('search/song', array('class' => 'form'))?>
<fieldset>
	<ul>
		<li>
			<label>Search by Song</label>
			<span class="searchBox"><input type="text" name="song" value="<?=set_value('song')?>" id="song" maxlength="50" size="30" class="rightSearch" /></span>
			<?=form_submit($submitButton)?>
		</li>
	</ul>
</fieldset>
</form>
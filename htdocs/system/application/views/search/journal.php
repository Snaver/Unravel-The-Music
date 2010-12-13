<br /><h1>Search Unravel</h1><br />
<div class="subNavSearch">
	<div class="artistTab">
		<?=anchor('search/artists/' . $search, 'Artists', array('class' => 'artistTabInactive'))?>
	</div>
	<div class="albumTab">
		<?=anchor('search/albums/' . $search, 'Albums', array('class' => 'albumTabInactive'))?>
	</div>
	<div class="songTab">
		<?=anchor('search/songs/' . $search, 'Songs', array('class' => 'songTabInactive'))?>
	</div>
	<div class="journalTab">
		<div class="journalTabActive">Journals</div>
	</div>
	<div class="userTab">
		<?=anchor('search/users/' . $search, 'Users', array('class' => 'userTabInactive'))?>
	</div>
	<div class="forumTab">
		<?=anchor('search/forums/' . $search, 'Forums', array('class' => 'forumTabInactive'))?>
	</div>
</div>

<hr class="topTracksHr" />
<div class="searchResults">
	<div class="searchForm">
<?php
	echo(form_open('search/journals'));
	echo('<div class="searchBoxLong"><input type="text" name="journal" value="' . $search . '" id="journal" maxlength="100" style="border: 0;" class="rightSearch" /> </div>');
	echo('<input type="submit" name="submit" class="goButton" value="" style="margin-top: 9px;" />');
	echo(form_close());
	echo('<br /><br /><br />');
	
	if($noSearch == false)
	{
		if($results->num_rows() > 0)
		{
			echo('search returned ' . $totalResults . ' results');		
			foreach($results->result() as $row)
			{
				?>
				<table>
				<tr><td>
			
					<?php
			
					$img['class'] = 'searchPicture';
					if($row->avatar != '')
					{
							$img['src'] =  'http://static.unravelthemusic.com/users/' . $row->avatar;
					} else {
						$img['src'] = 'http://www.unravelthemusic.com/assets/images/public/blank_user.png';
					}							

					echo(img($img) . '</td><td class="searchRight">');
					echo('<h3>' . anchor('journals/view/' . $row->user . '/' . $row->journal_id, ucfirst($row->title)) . '</h3>');
					echo('<p class="searchArtist">' . anchor('journals/view/' . $row->user, $row->user) . '</p>');
					$queryPos = strpos($row->body,$search);
					echo(substr($row->body, $queryPos, 30));
					//echo('<p class="searchTracks">' . 'x tracks' . '</p>');

					?>
					
					</td></tr>
				</table><hr />
			
				<?php
			}
			if($links != '')
			{
				echo('Page: ' . $links);
			}
		} else {
			echo('<h3>No results found</h3>');
		}
		
	}

?></div>
</div>

<h1>User Journals</h1>
<br /><br />
<h2>Favorite Journals</h2>
<br />
<div class="albumHolder">
	<?php
	foreach($query->result() as $journal)
	{
		echo('<div class="albumBlock">');
		if($journal->avatar != null)
		{
			echo(anchor('/journals/view/' . $journal->journal_id, '<img src="http://static.unravelthemusic.com/users/' . $journal->avatar . '" />') . '<br />');
		} else {
			echo(anchor('/journals/view/' . $journal->journal_id, '<img src="' . base_url() . 'assets/images/public/blank_user.png" />') . '<br />');
		}
		echo(anchor('journals/view/' . $journal->journal_id, $journal->journal_id . "'s Journal") . '<br />');
		echo('</div>');
	}
?>
<br clear="both" />
<?php
echo anchor('journals/favorites', 'view more favorite journals');
?>
</div>
<br /><br />
<?=anchor('journals/view/' . $this->session->userdata('DX_username'), 'Add a new journal post')?><br />
<h2>Newest Journals</h2><br />
<?php
foreach($newest->result() as $newJournal)
{
	echo('<span class="report">Created by:' . anchor('journals/view/' . $newJournal->user, $newJournal->user) . '<br />Created On: ' . $newJournal->created_on . '</span>');
	$pos = strpos($newJournal->title, ' ');

	if($pos > 18 || $pos == false)
	{
		echo('<div class="journalTitle">' . anchor('journals/view/' . $newJournal->user . '/' . $newJournal->journal_id, '<h1 class="journalTitle">' . ucfirst(substr($newJournal->title, 0, 17)) . '...' . '</h1>', array('class' => 'journalTitle')) . '</div>');
	} else {
		echo('<div class="journalTitle">' . anchor('journals/view/' . $newJournal->user . '/' . $newJournal->journal_id, '<h1 class="journalTitle">' . ucfirst(substr($newJournal->title, 0, 35)) . '...' . '</h1>', array('class' => 'journalTitle')) . '</div>');	
	}
	echo('<hr class="journalTitleHr" />');
	echo('<div class="journalEntry">');
	$length = strlen($newJournal->body);
	if($length <= 500)
	{
		echo($newJournal->body);
		echo(anchor('journals/view/' . $newJournal->user . '/' . $newJournal->journal_id, '<br />View Journal Entry') . '<br />');
	} else {
		echo(substr($newJournal->body, 0, 500) . '</p>' . anchor('journals/view/' . $newJournal->user . '/' . $newJournal->journal_id, '<br />View More') . '<br />');
	}
	echo('<br />Comments: ' . anchor('journals/view/' . $newJournal->user . '/' . $newJournal->journal_id, $newJournal->totalComments));
	echo('</div>');
}
?>

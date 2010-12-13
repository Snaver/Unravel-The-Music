<h1>Favorite Journals</h1>
<br /><br />
<?php
foreach($query->result() as $journal)
{
	echo(anchor('journals/view/' . $journal->journal_id, $journal->journal_id . "'s Journal") . '<br />');

}
?>
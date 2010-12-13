<?php
class JournalModel extends Model {


	function __construct()
	{
		// Call the Model constructor
		parent::Model();
	}
	
	function favorites($limit = 10)
	{
		return $this->db->query("SELECT journal_id, COUNT(watched_journals.user_id) as users, avatar  FROM watched_journals join user_profile on user_profile.username = watched_journals.journal_id GROUP BY journal_id ORDER BY users desc LIMIT 10" . $limit);
	
	}
	
	function loadNewest($limit = 5)
	{
		$this->db->select('journals.title, journals.body, journals.journal_id, journals.user, journals.created_on, count(journal_comments.title) as totalComments', FALSE);
		$this->db->order_by('journal_id', 'desc');
		$this->db->join('journal_comments', 'journal_comments.journal_id = journals.journal_id', 'left');
		$this->db->group_by('journals.journal_id');		
		return $this->db->get('journals', $limit);
	}
	
	function loadNewestByUser($user)
	{
		$this->db->select('journals.title, journals.body, journals.journal_id, journals.user, journals.created_on, count(journal_comments.title) as totalComments', FALSE);
		$this->db->where('journals.user', $user);
		$this->db->order_by('journal_id', 'desc');
		$this->db->join('journal_comments', 'journal_comments.journal_id = journals.journal_id', 'left');
		$this->db->group_by('journals.journal_id');		
		return $this->db->get('journals', 1);	
	}
	
	function getJournals($user, $limit = 10)
	{
		$this->db->select('journals.title, journals.body, journals.journal_id, journals.user, journals.created_on, count(journal_comments.title) as totalComments', FALSE);
		$this->db->where('user', $user);
		$this->db->order_by('journals.created_on', 'desc');
		$this->db->join('journal_comments', 'journal_comments.journal_id = journals.journal_id', 'left');
		$this->db->group_by('journals.journal_id');
		return $this->db->get('journals', $limit);	

	}
	
	function loadComments($journalId)
	{
		$this->db->where('journal_id', $journalId);
		return $this->db->get('journal_comments');
	}
	
	function getEntry($user, $journalId)
	{
		$this->db->where('user', $user);
		$this->db->where('journal_id', $journalId);
		return $this->db->get('journals');
	
	}
	
	function newJournalComment($insert, $journalId, $comments, $commentsToday = 0)
	{
		$comments++;
		$commentsToday++;
		date_default_timezone_set('America/Chicago');
		$data = array(
			'comments' => $comments,
			'comments_today' => $commentsToday,
			'comment_day' => date('d'),
			);
		$this->db->where('journal_id', $journalId);
		$this->db->update('journals', $data);
		
		$this->db->insert('journal_comments', $insert);
	
	}
	function newJournalCommentSelf($insert, $journalId, $comments)
	{
		$comments++;
		$data = array(
			'comments' => $comments,
			);
		$this->db->where('journal_id', $journalId);
		$this->db->update('journals', $data);
		
		$this->db->insert('journal_comments', $insert);
	
	}
	
	function search($search, $limit, $offset)
	{
		$this->db->like('title', $search);
		$this->db->or_like('body', $search);
		$this->db->order_by('comments_today', 'desc');
		$this->db->join('user_profile', 'user_profile.username = journals.user');
		return $this->db->get('journals', $limit, $offset);
	
	}
	
	function countSearch($search)
	{
		$this->db->like('title', $search);
		$this->db->or_like('body', $search);
		$result = $this->db->get('journals');
		return $result->num_rows();
	}
}

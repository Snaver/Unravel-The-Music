<?php
class CommentModel extends Model {


    function __construct()
    {
        // Call the Model constructor
        parent::Model();
    }

	function load($songId)
	{
		$this->db->order_by('comment_id', 'desc');
		$this->db->where('song_id', $songId);
		return $this->db->get('comments');
	
	}
 
}
?>

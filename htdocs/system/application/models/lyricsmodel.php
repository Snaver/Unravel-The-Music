<?php
class LyricsModel extends Model {


    function LyricsModel()
    {
        // Call the Model constructor
        parent::Model();
    }
	
	function checkExists($songId)
	{
		$this->db->where('song_id', $songId);
		$query = $this->db->get('lyrics');
		return $query;
	
	}
	
	function load($songId)
	{
		$this->db->where('song_id', $songId);
		return $this->db->get('lyrics');
	}
	
	function submitLyrics($lyrics, $songId, $created_by)
	{
		$insert = array(
               'lyrics' => $lyrics,
               'song_id' => $songId,
			   'submitted_by' => $created_by
            );
		$this->db->insert('lyrics', $insert);
	}
}
?>

<?php
class MeaningModel extends Model {


    function MeaningModel()
    {
        // Call the Model constructor
        parent::Model();
    }
    
	function load($song)
	{
		
		$this->db->where('song_id', $song);
		return $this->db->get('meanings');	
	
	}
	
	function getVotes()
	{
		$this->db->where('user_id', $this->session->userdata('DX_user_id'));
		return $this->db->get('votes');
	
	}
	
    function verifyMeaning($id)
    {
      $this->db->where('meaning_id', $id);
      $this->db->join('songs', 'songs.song_id = meanings.song_id');
      $this->db->join('albums', 'albums.album_id = songs.album_id');
      $this->db->join('artists', 'artists.artist_id = songs.artist_id');
      
      return $this->db->get('meanings');
    }
	function verifyReply($id)
	{
      $this->db->where('reply_id', $id);
	  $this->db->join('meanings', 'meanings.meaning_id = meaning_replies.parent_id');
      $this->db->join('songs', 'songs.song_id = meanings.song_id');
      $this->db->join('albums', 'albums.album_id = songs.album_id');
      $this->db->join('artists', 'artists.artist_id = songs.artist_id');
      
		return $this->db->get('meaning_replies');
	}
	
	function getReplies($songId)
	{
		$this->db->where('song_id', $songId);
		return $this->db->get('meaning_replies');
	
	}
    
    function verifyNoVote($userId, $meaningId)
    {
      $this->db->where('user_id', $userId);
      $this->db->where('meaning_id', $meaningId);
      $results = $this->db->get('votes');
      if($results->num_rows() > 0) {
        return $results;
      } else {
        return false;
      }      
    }
            
    function vote($userId, $meaningId, $vote, $songId)
    {
      $this->db->where('meaning_id', $meaningId);
      $meaningResult = $this->db->get('meanings');
      $meaningsRow = $meaningResult->row();
      if($vote == 'like') {
        $vote = 1;
        $rating = $meaningsRow->rating_up;
        $rating++;
		$count = $rating - $meaningsRow->rating_down;
        $insert = array(
                 'rating_up' => $rating
           );
        $this->db->where('meaning_id', $meaningId);

        $this->db->update('meanings', $insert); 
      } else {
        $vote = 0;
        $rating = $meaningsRow->rating_down;
        $rating++;
		$count = $meaningsRow->rating_up - $rating;
        $insert = array(
                 'rating_down' => $rating
           );
        $this->db->where('meaning_id', $meaningId);

        $this->db->update('meanings', $insert);     
      }
      $voteTable = array(
                'user_id' => $userId,
                'meaning_id' => $meaningId,
				'song_id' => $songId,
                'vote' => $vote
                );
      $this->db->insert('votes', $voteTable);
	  return $count;
    }
 	function verifyNoReport($id, $userId)
	{
		$this->db->where('user_id', $userId);
		$this->db->where('meaning_id', $id);


		$results = $this->db->get('reports');
		if($results->num_rows() > 0) {
			return $results;
		} else {
			return false;
		}      
    }    
    function report($userId, $id, $reports, $type = 0)
    {
		$data['report'] = $reports++;
        $this->db->where('meaning_id', $id);
        $this->db->update('meanings', $data); 

		$reportTable = array(
                    'user_id' => $userId,
                    'meaning_id' => $id,
                    'type' => '0'
                    );      
        
        $this->db->insert('reports', $reportTable);
	}		

}
?>

<?php
class Usermodel_unravel extends Model {


    function Usermodel_unravel()
    {
        // Call the Model constructor
        parent::Model();
    }
    	
	function getPoints($username)
	{
		$this->db->where('username', $username);
		$this->db->select('karma');
		$query = $this->db->get('users');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			return $row->karma;
		} else {
			return 'Error';
		}
	}
	
	function loadComments($user)
	{
		$this->db->select('title, songs.song, author, artist, meaning_id, album, song_seo_name, album_seo_name,
		artist_seo_name, meanings.created_on, body, rating_up, rating_down');
		$this->db->where('meanings.author', $user);
		$this->db->join('songs', 'songs.song_id = meanings.song_id');
		$this->db->join('artists', 'artists.artist_id = meanings.artist_id');
		$this->db->join('albums', 'albums.album_id = meanings.album_id');
		$this->db->order_by('meanings.created_on', 'desc');
		$query = $this->db->get('meanings', 5);
		if($query->num_rows() > 0)
		{
			return $query;
		} else {
			return NULL;
		}
	}	
	
	function loadTitle($points)
	{
		$this->db->where('karma <=', $points);
		$this->db->order_by('karma', 'desc');
		$query = $this->db->get('titles');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			return $row->title;
		} else {
			return 'Error';
		}
	
	}
	
	function feedFilter($feedType)
	{
		$id = $this->session->userdata('DX_user_id');
		$this->db->where('id', $id);
		$data = array(
				'feed_filter' => $feedType
			);
		$this->db->update('users', $data);
	
	}
	
	function feedView($view)
	{
		$id = $this->session->userdata('DX_user_id');
		$this->db->where('id', $id);
		$data = array(
				'feed_view' => $view
			);
		$this->db->update('users', $data);
	}
	
	function givePoints($author, $give)
	{
		$this->db->where('username', $author);
		$query = $this->db->get('users');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$points = $row->karma;
			$points = $points + $give;
			$update = array(
				'karma' => $points
				);
			$this->db->where('username', $author);
			$this->db->update('users', $update);
		} else {
			//user doesn't exist may do something in the future
		}
		
	}
	function takePoints($author, $take)
	{
		$this->db->where('username', $author);
		$query = $this->db->get('users');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$points = $row->karma;
			$points = $points - $take;
			$update = array(
				'karma' => $points
				);
			$this->db->where('username', $author);
			$this->db->update('users', $update);
		} else {
			//user doesn't exist may do something in the future
		}
		
	}	
	function lastPostTime()
	{
		$id = $this->session->userdata('DX_user_id');
		$this->db->where('id', $id);
		$this->db->select('last_post');
		$query = $this->db->get('users');
		 $row = $query->row();
		 return $row->last_post;
	
	}
	
	function updatePostTime()
	{
		$id = $this->session->userdata('DX_user_id');
		$this->load->helper('date');
		$now = now();	
		$data = array(
				'last_post' => $now
			);
		$this->db->where('id', $id);
		$this->db->update('users', $data);
	}
	
	function report($username)
	{
		$this->db->where('username', $username);
		$query = $this->db->get('users');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$data['report'] = $row->report + 1;
			$this->db->where('username', $username);
			$this->db->update('users', $data);
			return true;
		} else {
			return false;
		}
		
	}
	
	function block($blocker, $blockee)
	{
		$this->db->where('blocker', $blocker);
		$this->db->where('blockee', $blockee);
		$query = $this->db->get('user_block');
		if($query->num_rows == 0)
		{
			$data['blocker'] = $blocker;
			$data['blockee'] = $blockee;
			$this->db->insert('user_block', $data);
			return true;
		
		} else {
			return false;
		}
	
	}
	
	function unblock($blocker, $blockee)
	{
		$this->db->where('blocker', $blocker);
		$this->db->where('blockee', $blockee);
		$query = $this->db->get('user_block');
		if($query->num_rows == 1)
		{
			
			$this->db->where('blocker', $blocker);
			$this->db->where('blockee', $blockee);
			$query = $this->db->delete('user_block');
			return true;
		} else {
			return false;
		}
	
	}	
	
	function checkBlocked($blocker, $blockee)
	{
		$this->db->where('blocker', $blocker);
		$this->db->where('blockee', $blockee);
		$query = $this->db->get('user_block');		
		if($query->num_rows == 1)
		{
			return true;
		} else {
			return false;
		}
	}
	
	function blockList($blocker)
	{
		$this->db->where('blocker', $blocker);
		$query = $this->db->get('user_block');
		if($query->num_rows > 0)
		{
			foreach($query->result() as $blocked)
			{
				$array[] = $blocked->blockee;
				$this->session->set_userdata('blockList', $array);
			}
		} else {
			$array[] = null;
			$this->session->set_userdata('blockList', $array);
		}
	
	}
	
	function checkFriends($userId, $friend)
	{
		$this->db->where("(relating_user_id = $userId and related_user_id = $friend) OR (relating_user_id = $friend and related_user_id = $userId)");
		$query = $this->db->get('user_relationships');
		if($query->num_rows() > 0)
		{
			return true;
		} else {
			return false;
		}
	}
	
	function addFriend($userId, $friend)
	{
		$data = array(
				'relating_user_id' => $userId,
				'related_user_id' => $friend
			);
		$this->db->insert('user_relationships', $data);
	}
	
	function removeFriend($userId, $friend)
	{
		$this->db->where("(relating_user_id = $userId and related_user_id = $friend) OR (relating_user_id = $friend and related_user_id = $userId)");
		$this->db->delete('user_relationships');
	}
}
?>

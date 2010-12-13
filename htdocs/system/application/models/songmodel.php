<?php
class SongModel extends Model {


    function SongModel()
    {
        // Call the Model constructor
        parent::Model();

    }
	
	function loadBasic($songId)
	{
		$this->db->where('song_seo_name', $songId);
		$this->db->or_where('song_id', $songId);
		return $this->db->get('songs');	
	
	}
	
    function load($songId)
	{
		$this->db->join('albums', 'albums.album_id = songs.album_id');
		$this->db->join('artists', 'artists.artist_id = songs.artist_id');
		if(is_int((int)$songId) AND (int)$songId != 0)
		{
			$this->db->where('song_id', $songId);
		} else {
			$this->db->where('song_seo_name', $songId);
		}
		return $this->db->get('songs');
		
	
	}
	function loadExtended($song, $album, $artist)
	{
	    $this->db->join('albums', 'albums.album_id = songs.album_id');
	    $this->db->join('artists', 'artists.artist_id = songs.artist_id');
	    $this->db->where('song_seo_name', $song);
	    $this->db->where('albums.album_seo_name', $album);	
		$this->db->where('artists.artist_seo_name', $artist);
		return $this->db->get('songs');
	
	}
	function loadAlbumSongs($albumId)
	{
		$this->db->where('songs.album_id', $albumId);
		return $this->db->get('songs');
		
	}

	function loadList($artist)
	{
		$this->db->where('songs.artist_id', $artist);
		$this->db->join('albums', 'albums.album_id = songs.album_id');
		$this->db->join('artists', 'artists.artist_id = songs.artist_id');
		$this->db->order_by('song', 'asc');
		return $this->db->get('songs');
	}
	
	function loadByLetter($letter, $num, $offset)
	{
		if($letter == '0') {
			$this->db->where("song REGEXP '^[0-9]'");
			$this->db->order_by('song', 'ASC');
			$this->db->join('artists', 'artists.artist_id = songs.artist_id');
			$this->db->join('albums', 'albums.album_id = songs.album_id');
			return $this->db->get('songs', $num, $offset);
		} else {
			$this->db->like('song', $letter, 'after');
			$this->db->order_by('song', 'ASC');
			$this->db->join('artists', 'artists.artist_id = songs.artist_id');
			$this->db->join('albums', 'albums.album_id = songs.album_id');
			return $this->db->get('songs', $num, $offset);
		}
		
	}
	
	function loadTopSongsThisMonth($artist_id)
	{
		$this->db->join('albums', 'albums.album_id = songs.album_id');
		$this->db->where('songs.artist_id', $artist_id);
		$this->db->order_by('comments_month', 'desc');
		return $this->db->get('songs', 10);
	
	}
	
	function loadTopSongsAll($artist_id)
	{
		$this->db->join('albums', 'albums.album_id = songs.album_id');
		$this->db->where('songs.artist_id', $artist_id);
		$this->db->order_by('comments_all', 'desc');
		return $this->db->get('songs', 10);
	}
	
	function getTotalForLetter($letter)
	{
		$this->db->like('song', $letter, 'after');
		return $this->db->count_all_results('songs');
	}
	
	function checkSpelling($search)
    {
		$checks = array();
		//if the length of the submitted information is greater than 3 then start here
		if(strlen($search) > 3) {
			//first we create a variablewith the last 4 letters
			$first = substr($search, -4, 4);
			//second we create a variable with the first 4 letters
			$second = substr($search, 0, 4);

			//this like or or_like allows us to check the db for matches that contain the information in the vars
			$this->db->like('song', $first);
			$this->db->or_like('song', $second);
		} else {
			//if the string lenght is less than 3 we start here
			//check the first 2 chars
			$first = substr($search, 0, 2);
			$this->db->like('song', $first);
		}//end else
		//check the database with the like statements
		$this->db->join('artists', 'artists.artist_id = songs.artist_id');
		$this->db->join('albums', 'albums.album_id = songs.album_id');
		return $this->db->get('songs');            
    }//end function
	
	function newMeaning($id, $comments, $commentsMonth, $commentsAll)
	{
		date_default_timezone_set('America/Chicago');
		$comments++;
		$commentsMonth++;
		$commentsAll++;
		$data = array(
			'comments_today' => $comments,
			'comments_month' => $commentsMonth,
			'month' => date('m'),
			'comments_all' => $commentsAll
			);
		$this->db->where('song_id', $id);
		$this->db->update('songs', $data);
	
	}
	function search($search, $limit, $offset)
	{
		$this->db->where('song', $search);
		$this->db->order_by('artists.viewcount', 'desc');
		$this->db->join('artists', 'artists.artist_id = songs.artist_id');
		$this->db->join('albums', 'albums.album_id = songs.album_id');
		return $this->db->get('songs', $limit, $offset);
	
	}
	
	function countSearch($search)
	{
		$this->db->where('song', $search);
		$result = $this->db->get('songs');
		return $result->num_rows();
	}
	
	function addAsin($songId, $asin)
	{
		$data = array(
			'song_ASIN' => $asin
			);
		$this->db->where('song_id', $songId);
		$this->db->update('songs', $data);
	}
}
?>

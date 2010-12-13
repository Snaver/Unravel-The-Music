<?php
class Basicmodel extends Model {


	function Basicmodel()
	{
		// Call the Model constructor
		parent::Model();
	}

	function getArtistId($name)
	{
	  
	  $this->db->where('artist_seo_name', $name);
	  $results = $this->db->get('artists');
	  $row = $results->row();
	  return $row;
	  
	}

	function getArtistName($id)
	{
	  
	  $this->db->where('artist_id', $id);
	  $results = $this->db->get('artists');
	  $row = $results->row();
	  return $row;
	  
	}
	
	function getArtistNameByLetter($letter, $num, $offset)
	{
		$this->db->like('artist', $letter, 'after');
		$this->db->where('verified !=', '-1');
		$this->db->order_by('artist', 'ASC');
		$results = $this->db->get('artists', $num, $offset);
		
		return $results;
	}
		  
	function getAlbumId($name, $artist_id)
	{
	  $this->db->where('album_seo_name', $name);
	  $this->db->where('artist_id', $artist_id);
	  $resultsAlbum = $this->db->get('albums');
	  $row = $resultsAlbum->row();
	  return $row;
	}


	
	function getAlbumsByArtist($artist_seo_name)
	{
		$this->db->join('albums', 'albums.artist_id = artists.artist_id', 'left');
		$this->db->where('artist_seo_name', $artist_seo_name);
		$query = $this->db->get('artists');
		return $query;
	
	}
	
	function verifyAlbum($id)
	{
		$this->db->where('album_id', $id);
		$results = $this->db->get('albums');
		$row = $results->row();
		return $row;
	}
	
	function getAlbumNameByLetter($letter, $num, $offset)
	{
		$this->db->like('album', $letter, 'after');
		$this->db->order_by('album', 'ASC');
		$this->db->join('artists', 'artists.artist_id = albums.artist_id');
		$results = $this->db->get('albums', $num, $offset);
		
		return $results;
	}	
	function getSongNameByLetter($letter, $num, $offset)
	{
		$this->db->like('song', $letter, 'after');
		$this->db->order_by('song', 'ASC');
		$this->db->join('artists', 'artists.artist_id = songs.artist_id');
		$this->db->join('albums', 'albums.album_id = songs.album_id');
		$results = $this->db->get('songs', $num, $offset);
		
		return $results;
	}
	
	function getSongInfo($id) {
		
		$this->db->where('song_id', $id);
		$this->db->join('albums', 'albums.album_id = songs.album_id');
		$this->db->join('artists', 'artists.artist_id = songs.artist_id');
		
		$results = $this->db->get('songs');
		return $results;
	
	}

}
?>

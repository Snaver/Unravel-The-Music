<?php
class AlbumModel extends Model {


	function __construc()
	{
		// Call the Model constructor
		parent::Model();
	}
	
	function load($album, $artist_id)
	{

	  $this->db->where('album_seo_name', $album);
	  $this->db->where('artist_id', $artist_id);
	  $results = $this->db->get('albums');
	  $row = $results->row();
	  return $row;   
	}
	
	function loadFull($album, $artist)
	{
		$this->db->join('artists', 'artists.artist_id = albums.artist_id');
		$this->db->where('albums.album_seo_name', $album);
		$this->db->where('artists.artist_seo_name', $artist);
		return $this->db->get('albums');

	}	
	function loadExtended($album)
	{
		$this->db->join('artists', 'artists.artist_id = albums.artist_id');
		$this->db->where('album', $album);
		return $this->db->get('albums');
	}	

	function loadExtendedById($album, $id)
	{
		$this->db->where('album', $album);
		$this->db->where('albums.artist_id', $id);
		$this->db->join('artists', 'artists.artist_id = albums.artist_id');		
		return $this->db->get('albums');
	}	
	
	function loadByLetter($letter, $num, $offset)
	{
		if($letter == '0')
		{
			$this->db->where("album REGEXP '^[0-9]'");
			$this->db->join('artists', 'artists.artist_id = albums.artist_id');
			$this->db->order_by('album', 'ASC');
			return $this->db->get('albums', $num, $offset);
		} else {
			$this->db->like('album', $letter, 'after');
			$this->db->order_by('album', 'ASC');
			$this->db->join('artists', 'artists.artist_id = albums.artist_id');
			return $this->db->get('albums', $num, $offset);
		}
	}
	
	function uploadPic($artist, $album, $albumId, $ext)
	{
		$this->db->where('album_id', $albumId);
		$data = array(
					'album_picture' => $artist . '/' . $album . $ext,
					'picture_uploader' => $this->session->userdata('DX_username'),
					'album_picture_verified' => '0'
				);

		$this->db->update('albums', $data);
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
			$this->db->like('album', $first);
			$this->db->or_like('album', $second);
		} else {
			//if the string lenght is less than 3 we start here
			//check the first 2 chars
			$first = substr($search, 0, 2);
			$this->db->like('album', $first);
		}//end else
		//check the database with the like statements
		$this->db->join('artists', 'artists.artist_id = albums.artist_id');
		return $this->db->get('albums');            
    }//end function
	
	function checkExists($albumSeoName, $artistId)
	{
		$this->db->where('album_seo_name', $albumSeoName);
		$this->db->where('artist_id', $artistId);
		$results = $this->db->get('albums');
		return $results->num_rows();
	
	}
	
	function addNew($data)
	{
		$this->db->insert('albums', $data);
	
	}
	
	function search($search, $limit, $offset)	
	{
		$this->db->like('album', $search);
		$this->db->join('artists', 'artists.artist_id = albums.artist_id');
		$this->db->order_by('viewcount', 'desc');
		return $this->db->get('albums', $limit, $offset);
	
	}
	
	function countSearch($search)
	{
		$this->db->like('album', $search);
		$result = $this->db->get('albums');
		return $result->num_rows();
	}
}
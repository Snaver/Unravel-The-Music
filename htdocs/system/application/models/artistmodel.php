<?php
class ArtistModel extends Model {


	function ArtistModel()
	{
		// Call the Model constructor
		parent::Model();
	}
	
	function load($artist)
	{
		$this->db->where('artist_seo_name', $artist);

		return $this->db->get('artists');
	}

	function loadById($id)
	{
		$this->db->where('artist_id', $id);
		
		return $this->db->get('artists');
		
	}

	function loadByLetter($letter, $num, $offset)
	{
		if($letter == '0')
		{
			$this->db->where("artist REGEXP '^[0-9]'");
			$this->db->where('verified', 1);
			$this->db->order_by('artist', 'ASC');
			return $this->db->get('artists', $num, $offset);
			
		} else {
			$this->db->like('artist', $letter, 'after');
			$this->db->where('verified', 1);
			$this->db->order_by('artist', 'ASC');
			return $this->db->get('artists', $num, $offset);
		}
	}
	
	function loadAlbums($artist_seo_name)
	{
		$this->db->join('albums', 'albums.artist_id = artists.artist_id', 'left');
		$this->db->where('artist_seo_name', $artist_seo_name);
		$this->db->order_by('release_date', 'DESC');
		return $this->db->get('artists');	
	}

	function loadNewestAlbum($artist_seo_name)
	{
		$this->db->join('albums', 'albums.artist_id = artists.artist_id', 'left');
		$this->db->where('artist_seo_name', $artist_seo_name);
		$this->db->order_by('release_date', 'DESC');
		return $this->db->get('artists', 1);	
	}
	
	function getTotalForLetter($letter)
	{
		$this->db->like('artist', $letter, 'after');
		$this->db->where('verified', 1);
		return $this->db->count_all_results('artists');
	}
	
	function uploadPic($artist, $artistId, $ext)
	{
		$this->db->where('artist_id', $artistId);
		$data = array(
					'artist_picture_verified' => '0',
					'artist_picture' => $artist . '/' . $artist . $ext,
					'picture_uploader' => $this->session->userdata('DX_username'),
				);
		$this->db->update('artists', $data);
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
			$this->db->like('artist', $first);
			$this->db->or_like('artist', $second);
			$this->db->like('artist', $search);
		} else {
			//if the string lenght is less than 3 we start here
			//check the first 2 chars
			$first = substr($search, 0, 2);
			$this->db->like('artist', $first);
		}//end else
		//check the database with the like statements
		$this->db->order_by('viewcount', 'desc');
		return $this->db->get('artists', 3);            
    }//end function
	
	function addToViewcount($artistId, $views, $month)
	{

		$this->load->helper('date');
		$now = now() + 120;
		if($month == date('m'))
		{
			$views++;
			$data = array(
			'viewcount' => $views,
			'viewcount_expires' => $now
			);
		} else {
			$data = array(
			'viewcount' => '1',
			'viewcount_month' => date('m'),
			'viewcount_expires' => $now
			);
		}
		$this->db->where('artist_id', $artistId);
		$this->db->update('artists', $data);
	}
	
	function search($search, $limit, $offset)	
	{
		$this->db->like('artist', $search);
		$this->db->where('artist <>', $search);
		$this->db->where('verified <>', '-1');
		$this->db->order_by('viewcount', 'desc');
		return $this->db->get('artists', $limit, $offset);
	
	}
	
	function countSearch($search)
	{
		$this->db->like('artist', $search);
		$this->db->where('verified', '1');
		$result = $this->db->get('artists');
		return $result->num_rows();
	}
	
	function loadTour($artistId)
	{
		$this->db->where('artist_id', $artistId);
		$this->db->order_by('date', 'asc');
		return $this->db->get('tour_dates');
	}
}

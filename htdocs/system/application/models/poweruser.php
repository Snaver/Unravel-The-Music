<?php
class Poweruser extends Model {
    function Poweruser()
    {
        // Call the Model constructor
        parent::Model();
    }
	function countArtists()
	{
		$this->db->where('verified', 0);
		$query = $this->db->get('artists');
		return $query->num_rows();
	}
	
	function countPictures()
	{
		$this->db->where('artist_picture_verified', 0);
		$this->db->where('artist_picture !=', '');
		$query = $this->db->get('artists');
		return $query->num_rows();
	}
	
	function countAlbumPictures()
	{
		$this->db->where('album_picture_verified', 0);
		$this->db->where('album_picture !=', '');
		$query = $this->db->get('albums');
		return $query->num_rows();
	}
	function countQuestionable()
	{
		$this->db->where('questionable', 1);
		$query = $this->db->get('albums');
		return $query->num_rows();
	}
	
	function countLyrics()
	{
		$this->db->where('verified', 0);
		$query = $this->db->get('lyrics');
		return $query->num_rows();
	}
}
?>
<?php
class SpotlightModel extends Model {


	function __construc()
	{
		// Call the Model constructor
		parent::Model();
	}
	
	function load($artistId)
	{
		$this->db->where('artist_id', $artistId);
		return $this->db->get('spotlight_artists');
	}
}
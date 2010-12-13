<?php
class AdvertisingModel extends Model {


	function __construc()
	{
		// Call the Model constructor
		parent::Model();
	}
	
	function submit($insert)
	{
		$this->db->insert('advertising', $insert);
	
	}
}
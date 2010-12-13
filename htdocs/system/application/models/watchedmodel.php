<?php
class WatchedModel extends Model {


	function WatchedModel()
	{
		// Call the Model constructor
		parent::Model();
	}
	
	function checkExists($type, $otherId, $user)
	{
		$this->db->where($type . '_id', $otherId);
		$this->db->where('user_id', $user);
		$query = $this->db->get('watched_' . $type . 's');
		if($query->num_rows() == 0)
		{
			return false;
		} else {
			return true;
		}
	
	}
	function watch($type, $otherId, $user)
	{
		$data = array(
		$type . '_id' => $otherId,
		'user_id' => $user
		);
		$this->db->insert('watched_' . $type . 's', $data);
	
	}
	
	function unwatch($type, $otherId, $user)
	{
		$data = array(
		$type . '_id' => $otherId,
		'user_id' => $user
		);
		$this->db->delete('watched_' . $type . 's', $data);
	
	}
	
}
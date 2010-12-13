<?php
class User_Profile extends Model 
{
	function __construct()
	{
		parent::Model();

		$this->_prefix = $this->config->item('DX_table_prefix');		
		$this->_table = $this->_prefix.$this->config->item('DX_user_profile_table');
	}
	
	function create_profile($user_id, $username)
	{
		$this->db->set('user_id', $user_id);
		$this->db->set('username', $username);
		return $this->db->insert($this->_table);
	}

	function get_profile_field($user_id, $fields)
	{
		$this->db->select($fields);
		$this->db->where('user_id', $user_id);
		return $this->db->get($this->_table);
	}

	function get_profile($user_id)
	{
		$this->db->where('user_id', $user_id);
		return $this->db->get($this->_table);
	}
	
	function get_profile_by_username($username)
	{
		$this->db->where('user_profile.username', $username);
		$this->db->join('users', 'users.username = user_profile.username');
		return $this->db->get($this->_table);
	
	}

	function set_profile($user_id, $data)
	{
		$this->db->where('user_id', $user_id);
		return $this->db->update('user_profile', $data);
	}

	function delete_profile($user_id)
	{
		$this->db->where('user_id', $user_id);
		return $this->db->delete($this->_table);
	}
}

?>
<?php
class Backend extends Controller
{
       function __construct()
       {
		parent::Controller();
		
		$this->load->library('Table');
		$this->load->library('Pagination');
		$this->load->library('DX_Auth');
		
		$this->load->helper('form');
		
		// Protect entire controller so only admin, 
		// and users that have granted role in permissions table can access it.
		$this->dx_auth->check_uri_permissions();
	}
	
	function index()
	{
		$this->users();
	}
	

}
?>
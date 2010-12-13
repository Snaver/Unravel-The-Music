<?php
class Home extends Controller
{
	// Used for registering and changing password form validation


	function __construct()
	{
		parent::Controller();
		$this->dx_auth->check_uri_permissions();
		$this->load->library('Form_validation');

	}
	
	function index()
	{
		$data = null;
		$this->template->write('title', 'admin home page');
		$this->template->write_view('content', 'admin/home/index', $data, true);
		$this->template->render();
	
	
	}
}
?>
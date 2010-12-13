<?php

class Community extends Controller {

	function __construct()
	{
		parent::Controller();
	}

	function index()
	{
		$data = null;
		$this->template->write('title', 'Community Home');
		$this->template->write_view('content', 'community/index', $data, true);
		$this->template->render();	
	}


}
  

?>

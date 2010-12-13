<?php
class About extends Controller {
	
	function __construct()
	{
		parent::Controller();
		
		//$this->output->enable_profiler();	
	}
	
	function index()
	{
		$data = null;
		$this->template->write('title', 'About Unravel');
		$this->template->write_view('content', 'about/index', $data, true);
		$this->template->render();
	
	}
}
?>
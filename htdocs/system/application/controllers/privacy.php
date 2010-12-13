<?php
class Privacy extends Controller {
	
	function __construct()
	{
		parent::Controller();
		
		//$this->output->enable_profiler();	
	}
	
	function index()
	{
		$data = null;
		$this->template->write('title', 'Unravel\'s Privacy Information');
		$this->template->write_view('content', 'privacy/index', $data, true);
		$this->template->render();
	
	}
}
?>
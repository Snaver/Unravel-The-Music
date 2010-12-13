<?php
class Legal extends Controller {
	
	function __construct()
	{
		parent::Controller();
		
		//$this->output->enable_profiler();	
	}
	
	function index()
	{
		$data = null;
		$this->template->write('title', 'Unravel\'s Legal Information');
		$this->template->write_view('content', 'legal/index', $data, true);
		$this->template->render();
	
	}
}
?>
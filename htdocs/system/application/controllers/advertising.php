<?php
class Advertising extends Controller {
	
	function __construct()
	{
		parent::Controller();
		
		//$this->output->enable_profiler();	
	}
	
	function index()
	{
		$data = null;
		$this->template->write('title', 'Unravel\'s Advertising Information');
		$this->template->write_view('content', 'advertising/index', $data, true);
		$this->template->render();
	
	}
	
	function submit()
	{

		if($this->input->post('submit'))
		{
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules('who', 'Who you are', "trim|xss_clean|max_length[100]|required");
			$this->form_validation->set_rules('email', 'E-mail', "trim|xss_clean|valid_email|required");
			$this->form_validation->set_rules('tellme', 'Description', "required|max_length[6000]|trim|xss_clean");	
			$this->form_validation->set_rules('budget', 'Budget', "trim|xss_clean");			
			if ($this->form_validation->run() == FALSE)
			{
				$this->template->write('title', 'Errors when submitting bug report');
				$this->template->write_view('content', 'advertising/index', $data, true);
				$this->template->render();
			} else {
				$insert['who'] = $this->input->post('who');
				$insert['email'] = $this->input->post('email');
				$insert['desc'] = $this->input->post('tellme');
				$insert['budget'] = $this->input->post('budget');
				$this->load->model('AdvertisingModel');
				$this->AdvertisingModel->submit($insert);
				$this->session->set_flashdata('flashMessage', 'Thank you for your inquiry');
				redirect('/home');
			}

		} else {
			$this->template->write('title', 'Report a bug');
			$this->template->write_view('content', 'report/bug', $data, true);
			$this->template->render();
		}
		
	}
}
?>
<?php

class Report extends Controller {

	function __construct()
	{
		parent::Controller();
		$this->dx_auth->check_uri_permissions();
	}
  
	function meaning() {
		$data = null;
		$id = $this->session->userdata("DX_user_id");
		$this->load->model('reportmodel');    
		$this->load->model('basicmodel');
		$this->load->model('MeaningModel');
		$results = $this->MeaningModel->verifyMeaning($this->uri->segment(3));
		$row2 = $results->row();
		$song = $this->basicmodel->getSongInfo($row2->song_id);
		$row = $song->row();
		$noReport = $this->reportmodel->verifyNoReport($this->uri->segment(3), $id, 'meaning_id');
		if($noReport != false) {
			$this->session->set_flashdata('flashMessage', 'You have already reported this meaning.');
			redirect('songs/view/' . $row->artist . '/' . $row->album . '/' . $row->song);
		} else {
			$this->reportmodel->report($id, $this->uri->segment(3), 'meanings', 'meaning_id', 'report');
			$this->session->set_flashdata('flashMessage', 'Thank You. The admin team has been notified');
			redirect('songs/view/' . $row->artist_seo_name . '/' . $row->album_seo_name . '/' . $row->song_seo_name);
		}
	}
  
	function reply() {
		$data = null;
		$id = $this->session->userdata("DX_user_id");
		$this->load->model('reportmodel');    
		$this->load->model('basicmodel');
		$this->load->model('MeaningModel');
		$results = $this->MeaningModel->verifyReply($this->uri->segment(3));  
		$row2 = $results->row();
		$song = $this->basicmodel->getSongInfo($row2->song_id);
		$row = $song->row();
		$noReport = $this->reportmodel->verifyNoReport($this->uri->segment(3), $id, 'reply_id');
		if($noReport != false) {
			$this->session->set_flashdata('flashMessage', 'You have already reported this reply.');
			redirect('songs/view/' . $row->artist . '/' . $row->album . '/' . $row->song);
		} else {
			if($this->uri->segment(4) == 'meaning') {
				$type = 'meaning';
			} else {
				$type = 'report';
			}
			$this->reportmodel->report($id, $this->uri->segment(3), 'meaning_replies', 'reply_id', $type);
			$this->session->set_flashdata('flashMessage', 'Thank You. The admin team has been notified');
			redirect('songs/view/' . $row->artist_seo_name . '/' . $row->album_seo_name . '/' . $row->song_seo_name);
		}
	}
  
	function song() {
		$data = null;
		$id = $this->session->userdata("DX_user_id");
		$this->load->model('reportmodel');    
		$this->load->model('SongModel');
		$song = $this->SongModel->load($this->uri->segment(3));
		$row = $song->row();
		if(isset($row->song_id)) {
			$noReport = $this->reportmodel->verifyNoReport($this->uri->segment(3), $id, 'song_id');
			if($noReport != false) {
				$this->session->set_flashdata('flashMessage', 'You have already reported this song.');
				redirect('songs/view/' . $row->artist_seo_name . '/' . $row->album_seo_name . '/' . $row->song_seo_name);
			} else {
				if($this->uri->segment(4) == 'duplicate') {
					$type = 'duplicate';
				} elseif($this->uri->segment(4) == 'spam') {
					$type = 'spam';
				} else {
					$data['error'] = 'You can\'t report that, songs can only be reported as spam or duplicate';
					$this->load->view('404', $data);
				}
				$this->reportmodel->report($id, $this->uri->segment(3), 'songs', 'song_id', $type);
				$this->session->set_flashdata('flashMessage', 'Thank You. The admin team has been notified');
				redirect('songs/view/' . $row->artist_seo_name . '/' . $row->album_seo_name . '/' . $row->song_seo_name);
			}
		} else {
			$this->session->set_flashdata('flashMessage', 'That song does not exist in our database.');
			redirect('artists');
		}
	}
  
    function album() {
		$data = null;
		$id = $this->session->userdata("DX_user_id");
		$this->load->model('reportmodel');    
		$this->load->model('basicmodel');
		$this->load->model('MeaningModel');
		$results = $this->basicmodel->verifyAlbum($this->uri->segment(3));
		if(isset($results->album_id)) {
	
			$noReport = $this->reportmodel->verifyNoReport($this->uri->segment(3), $id, 'album_id');
			if($noReport != false) {
				$this->session->set_flashdata('flashMessage', 'You have already reported this album.');
				redirect('artists');
			} else {
				if($this->uri->segment(4) == 'duplicate') {
					$type = 'duplicate';
				} elseif($this->uri->segment(4) == 'spam') {
					$type = 'spam';
				} else {
					$data['error'] = 'You can\'t report that, songs can only be reported as spam or duplicate';
					$this->load->view('404', $data);
				}
				$this->reportmodel->report($id, $this->uri->segment(3), 'albums', 'album_id', $type);
				$this->session->set_flashdata('flashMessage', 'Thank You. The admin team has been notified');
				redirect('artists');
			}
		} else {
			$this->session->set_flashdata('flashMessage', 'That album does not exist in our database.');
			redirect('artists');
		}
	}
	
	function bug() {
		$data = null;
		if($this->input->post('submit'))
		{
			$this->load->model('usermodel_unravel');
			$this->load->helper('date');
			$now = now();	
			$expires = $this->usermodel_unravel->lastPostTime() + 45;
			if($expires < $now)
			{				
				$this->load->library('form_validation');
				$this->form_validation->set_rules('summary', 'Summary', "trim|xss_clean|max_length[255]|required");
				$this->form_validation->set_rules('description', 'Description', "required|max_length[6000]|journal|trim|xss_clean");		
				if ($this->form_validation->run() == FALSE)
				{
					$this->template->write('title', 'Errors when submitting bug report');
					$this->template->write_view('content', 'report/bug', $data, true);
					$this->template->render();
				} else {
					$this->usermodel_unravel->updatePostTime();
					$this->load->model('ReportModel');
					$this->ReportModel->submitBug($this->input->post('summary'), $this->input->post('description'));
					$this->session->set_flashdata('flashMessage', 'Thank you for your report');
					redirect('/home');
				}
			} else {
				$response['result'] = 'fail';
				$wait = $expires - $now;
				$this->session->set_flashdata('flashMessage', 'You must wait another ' . $wait . ' seconds before posting again.');
				redirect('report/bug');
			}
		} else {
			$this->template->write('title', 'Report a bug');
			$this->template->write_view('content', 'report/bug', $data, true);
			$this->template->render();
		}
	}
  
}
?>
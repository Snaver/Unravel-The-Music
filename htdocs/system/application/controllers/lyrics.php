<?php

class Lyrics extends Controller {

	function __construct()
	{
		parent::Controller();
		//$this->output->enable_profiler();
		
	}
	
	function expire()
	{
		$this->dx_auth->check_uri_permissions();
		$lyricId = $this->uri->segment(3);
		$this->db->where('lyrics_id', $lyricId);
		$query = $this->db->get('lyrics');
		if($query->num_rows() > 0)
		{
			$this->db->where('lyrics_id', $lyricId);
			$this->db->set('expires', '0');
			$this->db->update('lyrics');
			$response['result'] = 'success';
			print json_encode($response);			
		} else {
			$response['result'] = 'fail';
			$response['message'] = 'lyrics don\'t exist';
			print json_encode($response);		
		
		}
	}
	
	function report()
	{
		$this->dx_auth->check_uri_permissions();
		$data = null;
		$id = $this->session->userdata("DX_user_id");
		$this->load->model('reportmodel');   
		$this->load->model('LyricsModel');
		$songId = $this->uri->segment(3);
		$query = $this->LyricsModel->load($songId);
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$noReport = $this->reportmodel->verifyNoReport($row->lyrics_id, $id, 'lyrics_id');
			if($noReport != false) {
				$response['result'] = 'fail';
				$response['message'] = 'You have already reported these lyrics';
				print json_encode($response);	
			} else {	
				$this->reportmodel->report($id, $row->lyrics_id, 'lyrics', 'lyrics_id', 'report');
				$response['result'] = 'success';
				print json_encode($response);			
			}
		} else {
			$response['result'] = 'fail';
			$response['message'] = 'These lyrics do not exist';
			print json_encode($response);	
		}
	}
	
	function edit()
	{
		$this->dx_auth->check_uri_permissions();
		$data = null;
		$this->load->model('LyricsModel');
		$songId = $this->uri->segment(3);
		$query = $this->LyricsModel->load($songId);	
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$data['lyrics'] = $row->lyrics;
			$data['songId'] = $row->song_id;
			if($this->input->post('submit'))
			{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('lyrics', 'Lyrics', 'required');
				if ($this->form_validation->run() == FALSE)
				{
					$this->template->write('title', 'Edit Lyrics - Errors in Form');
					$this->template->write_view('content', 'lyrics/edit', $data, true);
					$this->template->render();				
				} else {
					$set['lyrics'] = $this->input->post('lyrics');
					$this->db->where('song_id', $row->song_id);
					$this->db->update('lyrics', $set);
					redirect('manage/lyric_reports');
				
				}
			
			
			} else {
				$this->template->write('title', 'editing lyrics');
				$this->template->write_view('content', 'lyrics/edit', $data, true);
				$this->template->render();
			
			}
		
		
		} else {
			$data['error'] = 'lyrics not found';
			$this->template->write('title', 'lyrics don\'t exist');
			$this->template->write_view('content', '404', $data, true);
			$this->template->render();
		}
	
	}
	
	function add()
	{
		$this->dx_auth->check_uri_permissions();
		$data = null;
		$songId = $this->uri->segment(3);
		$this->load->model('SongModel');
		$this->load->model('LyricsModel');
		$query = $this->SongModel->load($songId);
		if($query->num_rows() > 0  && $this->input->post('lyrics'))
		{
			$row = $query->row();
			$lyrics = $this->LyricsModel->load($songId);
			if($lyrics->num_rows() == 0)
			{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('lyrics', 'Lyrics', 'trim|required|lyrics|xss_clean');
				if ($this->form_validation->run() == FALSE)
				{			
					$this->session->set_flashdata('flashMessage', validation_errors());
					redirect('songs/view/' . $row->artist_seo_name . '/' . $row->album_seo_name . '/' . $row->song_seo_name);
				} else {
					$this->load->library('LyricsPrep');
					$cleanLyrics = $this->lyricsprep->addBreaks($this->input->post('lyrics'));
					
					$data = array(
							'song_id'	=> $row->song_id,
							'expires'	=> '9999999999',
							'lyrics'	=> $cleanLyrics,
							'verified'	=> '0',
							'submitted_by'	=> $this->session->userdata('DX_username')
						);
					$this->db->insert('lyrics', $data);
					$this->session->set_flashdata('flashMessage', 'Thank-you, your submission will be reviewed shortly.');
					redirect('songs/view/' . $row->artist_seo_name . '/' . $row->album_seo_name . '/' . $row->song_seo_name);					
				}
			
			} else {
				$this->session->set_flashdata('flashMessage', 'This song already has lyrics');
				redirect('songs/view/' . $row->artist_seo_name . '/' . $row->album_seo_name . '/' . $row->song_seo_name);
			}
		} else {
			$this->template->write('title', 'This song id does not exist');
			$this->template->write_view('content', '404', $data, true);
			$this->template->render();
		}
	
	
	
	}
}
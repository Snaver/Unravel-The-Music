<?php

class Journals extends Controller {

	function Journals()
	{
		parent::Controller();
		
		$this->load->scaffolding('albums');
	}

	function index()
	{
		$this->load->model('JournalModel');
		$data['query'] = $this->JournalModel->favorites('10');
		$data['newest'] = $this->JournalModel->loadNewest('5');
		$this->template->write('title', 'Journals Home');
		$this->template->write_view('content', 'journals/index', $data, TRUE);
		$this->template->render();
	}
	
	function view()
	{
		$this->load->model('usermodel_unravel');
		$user = $this->uri->segment(3);
		$journal = $this->uri->segment(4);
		$this->db->where('users.username', $user);
		$this->db->join('user_profile', 'user_profile.user_id = users.id');
		$data['userQuery'] = $this->db->get('users');
		$data['hideInput'] = false;
		//$this->output->enable_profiler();
		if($data['userQuery']->num_rows() > 0)
		{
			$data['comments'] = null;
			$data['commentNum'] = true;
			$this->load->model('JournalModel');
			$row = $data['userQuery']->row();
			if($journal != '')
			{
				$data['commentNum'] = false;
				$data['hideInput'] = true;
				
				$data['journalQuery'] = $this->JournalModel->getEntry($row->username, $journal);
				$data['comments'] = $this->JournalModel->loadComments($journal);
				
				if($data['journalQuery']->num_rows() > 0)
				{
					$this->template->write('title', $row->username . '\'s journal entry');
					$this->template->write_view('content', 'journals/view', $data, TRUE);
					$this->template->render();
				} else {
					$this->session->set_flashdata('flashMessage', 'This user has no entry with this ID');
					redirect('journals/view/'. $row->username);
				}
			
			} else {
				$data['journalQuery'] = $this->JournalModel->getJournals($row->username, '10');
				$this->template->write('title', 'Viewing ' . $row->username . '\'s journal page');
				$this->template->write_view('content', 'journals/view', $data, TRUE);
				$this->template->render();
			}
		} else {
			$data['error'] = 'No user exists by this name';
			$this->template->write('title', 'User not found');
			$this->template->write_view('content', '404', $data, TRUE);
			$this->template->render();
		}
	}
	
	function add()
	{
		$this->dx_auth->check_uri_permissions();
		$this->load->model('usermodel_unravel');
		$this->load->helper('date');
		$now = now();	
		$expires = $this->usermodel_unravel->lastPostTime() + 45;
		if($expires < $now)
		{		
			$this->load->library('form_validation');
			$this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[50]|lyrics|xss_clean');
			$this->form_validation->set_rules('body', 'Body', 'trim|required|max_length[5000]|journal|xss_clean');
			$this->form_validation->set_error_delimiters('', ' ');
			if ($this->form_validation->run() == FALSE)
			{
				$response['result'] = 'fail';
				$response['message'] = validation_errors();
				print json_encode($response);
			}
			else
			{	
				$this->load->library('JournalPrep');
				$cleanBody = $this->journalprep->cleanFormInput($this->input->post('body'));
				$data = array(
						'user' => $this->session->userdata('DX_username'),
						'created_on' => date('Y-m-d G:i:s'),
						'edited_on' => null,
						'title' => $this->input->post('title'),
						'body' => $cleanBody,
					);
				$this->db->insert('journals', $data);
				
				$this->usermodel_unravel->updatePostTime();
				
				$response['result'] = 'success';
				$response['user'] = $this->session->userdata('DX_username');
				$response['title'] = $this->input->post('title');
				$response['body'] = $cleanBody;
				$response['id'] = $this->db->insert_id();
				$response['createdOn'] = date('Y-m-d G:i:s');
				print json_encode($response);

			}
		} else {
			$response['result'] = 'fail';
			$wait = $expires - $now;
			$response['message'] = 'You must wait another ' . $wait . ' seconds.' ;
			print json_encode($response);		
		}
	}
	
	function favorites()
	{
		$this->load->model('JournalModel');
		$data['query'] = $this->JournalModel->favorites('10');
		$this->template->write('title', 'Favorite journals chosen by users');
		$this->template->write_view('content', 'journals/favorites', $data, true);
		$this->template->render();
		
	
	}
	
	function random()
	{
		$this->db->order_by('', 'random');
		$this->db->where('verified', 1); 
		$query = $this->db->get('artists', 1);
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			redirect('artists/view/' . $row->artist_seo_name);
		} else {
			$data['error'] = 'we have big problems';
			$this->template->write('title', 'Big time boo-boo');
			$this->template->write_view('content', '404', $data, true);
			$this->template->render();
		}
		
	}
	
	function addComment()
	{
		//this is for adding a meaning to a song
		$this->load->model('JournalModel');
		$this->load->model('usermodel_unravel');
		//must be logged in.
		$this->dx_auth->check_uri_permissions();
		
		$this->load->helper('date');
		$now = now();	
		$expires = $this->usermodel_unravel->lastPostTime() + 45;
		if($expires < $now)
		{
			$journalId = $this->uri->segment(3);
			$this->db->where('journal_id', $journalId);
			$query = $this->db->get('journals');
			if($query->num_rows() == 1) {
			
				$author = $this->session->userdata('DX_username');
				
				$this->load->library('form_validation');
				$this->form_validation->set_rules('title', 'Title', 'trim|required|lyrics|max_length[80]|xss_clean');
				$this->form_validation->set_rules('body', 'Body', 'trim|lyrics|xss_clean|max_length[1000]|required');
				if ($this->form_validation->run() == FALSE)
				{
					$response['result'] = 'fail';
					$response['message'] = validation_errors();
					print json_encode($response);
				} else {	

					$row = $query->row();
					
					$insert['author'] = $author;	
					date_default_timezone_set('America/Chicago');
					$insert['created_on'] = date('Y-n-d G:i:s');
					$insert['journal_id'] = $row->journal_id;
					$insert['title'] = $this->input->post('title');
					$insert['body'] = $this->input->post('body');
					//submit to db
					if($row->user != $this->session->userdata('DX_username'))
					{
						if($row->comment_day == date('d'))
						{
							$this->JournalModel->newJournalComment($insert, $journalId, $row->comments, $row->comments_today);
						} else {
							$this->JournalModel->newJournalComment($insert, $journalId, $row->comments);
						}
					}
					$this->JournalModel->newJournalCommentSelf($insert, $journalId, $row->comments);
					$this->usermodel_unravel->updatePostTime();
		
					$response['result'] = 'success';
					$response['title'] = $this->input->post('title');
					$response['body'] = $this->input->post('body');
					$response['author'] = $author;
					$response['createdOn'] = date('Y-n-d G:i:s');
					print json_encode($response);
					

				}
			} else {
				$response['result'] = 'fail';
				$response['message'] = 'Journal does not exist';
				print json_encode($response);
			}
		} else {
			$response['result'] = 'fail';
			$wait = $expires - $now;
			$response['message'] = 'You must wait another ' . $wait . ' seconds.' ;
			print json_encode($response);			
		}
  
	}
	
}
  

?>

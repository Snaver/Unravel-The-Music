<?php

class Meanings extends Controller {

	function Meanings()
	{
		parent::Controller();

		$this->load->scaffolding('meanings');
	}

	function index()
	{
		//no index as of right now
	}
	
	function like()
	{
		$this->dx_auth->check_uri_permissions();
		$id = $this->session->userdata("DX_user_id");
		$this->load->model('usermodel_unravel');
		$this->load->model('MeaningModel');
		$meaningId = $this->uri->segment(3);
		$results = $this->MeaningModel->verifyMeaning($meaningId);
		$row = $results->row();
		if($results != false) 
		{
			if(strtolower($row->author) != strtolower($this->session->userdata('DX_username')))
			{
				$votes = $this->MeaningModel->verifyNoVote($id, $row->meaning_id);
				if($votes != false)
				{
					$rowVotes = $votes->row();
					if($rowVotes->vote == 1) {
						$vote = 'like';
					} else {
						$vote = 'dislike';
					}
					$response['message'] = 'fail';
					$response['error'] = 'you voted, ' . $vote . ' for ' . $row->author . '\'s post already.';
					print json_encode($response);					
				} else {
					$this->usermodel_unravel->givePoints($row->author, 1);
					$count = $this->MeaningModel->vote($id, $row->meaning_id, 'like', $row->song_id);
					$response['message'] = 'success';
					$response['newCount'] = $count;
					print json_encode($response);					
				}
			} else {
				$response['message'] = 'fail';
				$response['error'] = 'You can\'t vote for yourself';
				print json_encode($response);					
			}
		} else {
			//this meaning doesn't exists so redirect them home
			$response['message'] = 'fail';
			$response['error'] = 'you voted for a meaning that doesn\'t exist';
			print json_encode($response);					
		}
	}
  
	function dislike()
	{
		$this->dx_auth->check_uri_permissions();
		$id = $this->session->userdata("DX_user_id");
		$this->load->model('usermodel_unravel');
		$this->load->model('MeaningModel');
		$meaningId = $this->uri->segment(3);

		$results = $this->MeaningModel->verifyMeaning($meaningId);
		$row = $results->row();
		if($results != false) {
			if(strtolower($row->author) != strtolower($this->session->userdata('DX_username')))
			{
				$votes = $this->MeaningModel->verifyNoVote($id, $row->meaning_id);
				if($votes != false)
				{
					$rowVotes = $votes->row();
					if($rowVotes->vote == 1) {
						$vote = 'like';
					} else {
						$vote = 'dislike';
					}
					$response['message'] = 'fail';
					$response['error'] = 'you voted, ' . $vote . ' for ' . $row->author . '\'s post already.';
					print json_encode($response);	
				} else {
					$this->usermodel_unravel->takePoints($row->author, 1);				
					$count = $this->MeaningModel->vote($id, $row->meaning_id, 'dislike', $row->song_id);
					$response['message'] = 'success';
					$response['newCount'] = $count;
					print json_encode($response);	
				}
			} else {
				$response['message'] = 'fail';
				$response['error'] = 'You can\'t vote for yourself';
				print json_encode($response);	
			}				
		} else {
		//this meaning doesn't exists so redirect them to artists
			$response['message'] = 'fail';
			$response['error'] = 'you voted for a meaning that doesn\'t exist';
			print json_encode($response);	
		}  
	}
	function view()
	{
		//we need the id of the meaning for the where
		$this->db->join('songs', 'songs.song_id = meanings.song_id');
		$this->db->join('albums', 'albums.album_id = songs.album_id');
		$this->db->join('artists', 'artists.artist_id = songs.artist_id');
		//query the meanings table
		$this->db->where('meaning_id', $this->uri->segment(3));
		$data['query'] = $this->db->get('meanings');
    
		//load the view page for viewing meanings
		$this->template->write('title', 'Viewing Meaning');
		$this->template->write_view('content', 'meanings/view', $data, true);
		$this->template->render();
    
	}
	
	function add()
	{
		//this is for adding a meaning to a song
		$this->load->model('submitmodel');
		$this->load->model('HomeModel');
		$this->load->model('usermodel_unravel');
		//must be logged in.
		$this->dx_auth->check_uri_permissions();

		$this->load->helper('date');
		$now = now();	
		$expires = $this->usermodel_unravel->lastPostTime() + 45;
		if($expires < $now)
		{
			
			$song = $this->uri->segment(3);
			$this->db->join('albums', 'albums.album_id = songs.album_id');
			$this->db->join('artists', 'artists.artist_id = songs.artist_id');
			$this->db->where('song_id', $song);
			$query = $this->db->get('songs');
			if($query->num_rows() == 1) {
			
				$author = $this->session->userdata('DX_username');
				$insert['created_on'] = date('Y-n-d G:i:s');
				$insert['author'] = $author;	    

				$this->load->library('form_validation');
				$this->form_validation->set_rules('title', 'Title', 'trim|required|lyrics|xss_clean');
				$this->form_validation->set_rules('body', 'Body', 'trim|lyrics|xss_clean|required');
				if ($this->form_validation->run() == FALSE)
				{
					$response['result'] = 'fail';
					$response['message'] = validation_errors();
					print json_encode($response);
				} else {	

					$row = $query->row();

					//if it's new it will come through here
					//list the fields for meanings
					$fields = $this->db->list_fields('meanings');
					//exclude id
					
					$str2 = htmlentities($this->input->post('body'));
					$order   = array("\r\n", "\n", "\r");
					$replace = '<br />';
					// Processes \r\n's first so they aren't converted twice.
					
					$insert['body'] =  str_replace($order, $replace, $str2);	
					
					$excluded_fields = array('meaning_id', 'album_id', 'artist_id', 'author', 'created_on', 'rating_up', 'rating_down', 'song_id', 'body');
					$insert['song_id'] = $row->song_id;
					$insert['album_id'] = $row->album_id;
					$insert['artist_id'] = $row->artist_id;
					//submit to db and feed
					$this->submitmodel->submit("meanings", $fields, $insert, $excluded_fields);
					
					$meaningId = $this->db->insert_id();
					$this->HomeModel->addCommentToFeed($row->album, $row->artist, $row->song, $meaningId, substr($this->input->post('body'), 0 , 280), $author);
					$this->load->model('MeaningModel');
					$this->MeaningModel->vote($this->session->userdata('DX_user_id'), $meaningId, 'like', $row->song_id);
					$this->load->model('SongModel');
					date_default_timezone_set('America/Chicago');
					if(date('m') == $row->month)
					{
						$this->SongModel->newMeaning($row->song_id, $row->comments_today, $row->comments_month, $row->comments_all);
					} else {
						$this->SongModel->newMeaning($row->song_id, $row->comments_today, 0, $row->comments_all);
					}
		
					$this->usermodel_unravel->updatePostTime();
					
					$response['result'] = 'success';
					$response['title'] = $this->input->post('title');
					$response['body'] = $insert['body'];
					$response['author'] = $author;
					$response['createdOn'] = date('Y-n-d G:i:s');
					print json_encode($response);
					

				}
			} else {
				$response['result'] = 'fail';
				$response['message'] = 'Song does not exist';
				print json_encode($response);
			}
		} else {
			$response['result'] = 'fail';
			$wait = $expires - $now;
			$response['message'] = 'You must wait another ' . $wait . ' seconds.' ;
			print json_encode($response);		
		}
  
	}
	
	function edit()
	{
		$meaningId = $this->uri->segment(3);
		//make sure they are logged in
		$this->dx_auth->check_uri_permissions();
		
		//query the db to see if this is really a meaning
		$this->db->where('meaning_id', $meaningId);
		$query = $this->db->get('meanings');
		if($query->num_rows() == 0) {
			$response['result'] = 'fail';
			$response['message'] = 'No meaning exists by this name' ;
			print json_encode($response);
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'Title', 'trim|required|lyrics|xss_clean');
		$this->form_validation->set_rules('body', 'Body', 'trim|lyrics|xss_clean|required');
		if ($this->form_validation->run() == FALSE)
		{
			$response['result'] = 'fail';
			$response['message'] = validation_errors();
			print json_encode($response);
		} else {	
			//make sure that this is their own meaning and not someone elses
			$row = $query->row();
			if($this->session->userdata('DX_username') != $row->author)
			{
				$response['result'] = 'fail';
				$response['message'] = 'You are not the other' ;
				print json_encode($response);
			} else {
				//if all is good then update
				$body = $this->input->post('body');
				$title = $this->input->post('title');
				$data = array (
						'title' => $title,
						'body' => $body
					);
				$this->db->where('meaning_id', $meaningId);
				$this->db->update('meanings', $data);
				$response['result'] = 'success';
				$response['title'] = $this->input->post('title');
				$response['body'] = $this->input->post('body');
				print json_encode($response);
			}
		}
	}
	
	function reply()
	{
		//allows users to reply to other meanings
		
		//must be logged in
		$this->dx_auth->check_uri_permissions();
		
		//set the data variable
		$data['parent_id'] = $this->uri->segment(3);
		
		//the parent id is the id we are replying to.
		$this->db->where('meaning_id', $data['parent_id']);
		//we have to do this so we can get the song_id from the parent's meaning
		$data['query'] = $this->db->get('meanings');
		
		//load the meanings reply view
		$this->template->write('title', 'Replying to a meaning');
		$this->template->write_view('content', 'meanings/reply', $data, true);
		$this->template->render();
	}
  
	function submit()
	{
		$this->load->model('submitmodel');
		//this is how we manage all of the submits from add/edit/reply
		//must be logged in.
		$this->dx_auth->check_uri_permissions();
		
		$author = $this->session->userdata('DX_username');
		$insert['created_on'] = date('Y-n-d G:i:s');
		$insert['author'] = $author;
		  
		//we need to decide how to handle the comment
		if($this->uri->segment(3) == 'reply') 
		{
			$this->db->join('songs', 'songs.song_id = meanings.song_id');
			$this->db->join('albums', 'albums.album_id = songs.album_id');
			$this->db->join('artists', 'artists.artist_id = songs.artist_id');

			$this->db->where('meaning_id', $this->uri->segment(4));
			$query = $this->db->get('meanings');
			if($query->num_rows() != 1) 
			{

			} else {
				$row = $query->row();
				//if url is reply it will come through here
				//list the fields for meaning replies
				$fields = $this->db->list_fields('meaning_replies');
				//exclude id
				
				$str2 = htmlentities($this->input->post('body'));
				$order   = array("\r\n", "\n", "\r");
				$replace = '<br />';
				// Processes \r\n's first so they aren't converted twice.
				
				$insert['body'] =  str_replace($order, $replace, $str2);				
				$insert['parent_id'] = $this->uri->segment(4);
				$insert['song_id'] = $row->song_id;
				$insert['album_id'] = $row->album_id;
				$insert['artist_id'] = $row->artist_id;				
				$excluded_fields = array('reply_id', 'album_id', 'artist_id', 'author', 'created_on', 'parent_id', 'song_id', 'body');

				//submit to db and feed
				$this->submitmodel->submit("meaning_replies", $fields, $insert, $excluded_fields);
				
				$this->load->model('SongModel');
				$this->SongModel->newMeaning($row->song_id, $row->comments_today, $row->comments_month, $row->comments_all);
					
				//redirect the user back to the song
				redirect('/songs/view/' . $row->artist_seo_name . '/' . $row->album_seo_name . '/' . $row->song_seo_name);      
			}		
		}

	}
	
	function report()
	{
		$this->dx_auth->check_uri_permissions();
		$id = $this->uri->segment(3);
		$userId = $this->session->userdata('DX_user_id');
		$this->load->model('MeaningModel');
		$query = $this->MeaningModel->verifyMeaning($id);
		if($query->num_rows() == 1)
		{
			$report = $this->MeaningModel->verifyNoReport($id, $userId);
			$row = $query->row();
			if($report == false)
			{
				
				$report = $row->report;

				$this->MeaningModel->report($userId, $id, $report, '0');
				
				$this->session->set_flashdata('flashMessage', 'Thank you for your report');
				redirect('/songs/view/' . $row->artist_seo_name . '/' . $row->album_seo_name . '/' . $row->song_seo_name);
			} else {
				$this->session->set_flashdata('flashMessage', 'You have already reported this meaning.');
				redirect('/songs/view/' . $row->artist_seo_name . '/' . $row->album_seo_name . '/' . $row->song_seo_name);			
			}
		} else {
			$data['error'] = 'Meaning does not exist';
			$this->template->write('title', 'Meaning Does not exist');
			$this->template->write_view('content', '404', $data, true);
			$this->template->render();
		}
	
	
	}
}

?>

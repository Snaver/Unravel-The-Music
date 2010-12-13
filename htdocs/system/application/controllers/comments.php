<?php
class Comments extends Controller {

	function __construct()
	{
		parent::Controller();

	}
	function add()
	{
		//this is for adding a meaning to a song
		$this->load->model('submitmodel');
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
				
				$this->load->library('form_validation');
				$this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[75]|xss_clean');
				$this->form_validation->set_rules('body', 'Body', 'trim|max_length[500]|xss_clean|required');
				if ($this->form_validation->run() == FALSE)
				{
					$response['result'] = 'fail';
					$response['message'] = validation_errors();
					print json_encode($response);
				} else {	

					$row = $query->row();

					//if it's new it will come through here
					//list the fields for meanings
					$fields = $this->db->list_fields('comments');
					//exclude id
					$excluded_fields = array('meaning_id', 'album_id', 'artist_id', 'author', 'created_on', 'rating_up', 'rating_down', 'song_id');
					$insert['author'] = $author;	
					$insert['created_on'] = date('Y-n-d G:i:s');
					$insert['song_id'] = $row->song_id;
					$insert['album_id'] = $row->album_id;
					$insert['artist_id'] = $row->artist_id;
					//submit to db and feed
					$this->submitmodel->submit("comments", $fields, $insert, $excluded_fields);
			
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
					$response['body'] = $this->input->post('body');
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
}
?>
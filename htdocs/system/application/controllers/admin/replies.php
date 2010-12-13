<?php

class Replies extends Controller
{	

    function __construct()
    {
        parent::Controller();

        //only 'admin' and 'superadmin' can manage users
        
		$this->dx_auth->check_uri_permissions();
    	
    }


    function index()
    {
		$this->db->where('report > 0');
		$this->db->orwhere('meaning > 0');
	    $data['query'] = $this->db->get('meaning_replies');
	   
		$this->template->write('title', 'Meaning Reports');
		$this->template->write_view('content', 'admin/replies/index', $data, true);
		$this->template->render();	   
    }
	function clean()
	{
		$id = $this->uri->segment(4);
		$data = array(
					'report' => '0',
				);

		$this->db->where('reply_id', $id);
		$this->db->update('meaning_replies', $data);
		redirect('admin/replies'); 			
	}
	
	function remove()
	{
		$id = $this->uri->segment(4);
		$this->db->where('reply_id', $id);
		$this->db->delete('meaning_replies'); 
		
		redirect('admin/replies'); 	
	}
	function split()
	{
		$id = $this->uri->segment(4);
		$this->db->where('reply_id', $id);
		$results = $this->db->get('meaning_replies');
		$row = $results->row();
		
		
		$data = array(
					'body' => $row->body,
					'song_id' => $row->song_id,
					'created_on' => $row->created_on,
					'author' => $row->author,
					'title' => $row->title,
					'report' => '0'
				);
		$this->db->insert('meanings', $data);
		
		$this->db->where('body', $row->body);
		$this->db->join('songs', 'songs.song_id = meanings.song_id');
		$this->db->join('albums', 'albums.album_id = songs.album_id');
		$this->db->join('artists', 'artists.artist_id = songs.artist_id');
		$results2 = $this->db->get('meanings');
		$updateInfo = $results2->row();
		$update = array(
					'body' => 'This reply was determined to be a meaning and has been moved<br />Please only post comments and replies to meanings.<br />Click' . anchor('songs/view/' . $updateInfo->artist_seo_name . '/' . $updateInfo->album_seo_name . '/' . $updateInfo->song_seo_name . '/#' . $updateInfo->meaning_id, " Here") . ' to view this meaning.<br />',
					'meaning' => '0',
					'moved' => '1'
					);
		$this->db->where('reply_id', $id);
		$this->db->update('meaning_replies', $update);
		redirect('admin/replies');
	}
}

?>
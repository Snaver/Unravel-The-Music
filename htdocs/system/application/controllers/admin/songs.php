<?php

class Songs extends Controller
{	

    function __construct()
    {
        parent::Controller();

        //only 'admin' and 'superadmin' can manage users
        
		$this->dx_auth->check_uri_permissions();
    	
    }


    function index()
    {
		$data = null;
		$this->db->join('artists', 'artists.artist_id = songs.artist_id');
		$this->db->join('albums', 'albums.album_id = songs.album_id');
		$this->db->where('songs.duplicate > 0');
		$this->db->orwhere('songs.spam > 0');
	    $data['query'] = $this->db->get('songs');

	        
		$this->template->write('title', 'Album repors');
		$this->template->write_view('content', 'admin/songs/index', $data, true);
		$this->template->render();
		       
    }
	
	function clean()
	{
		$id = $this->uri->segment(4);
		$data = array(
					'duplicate' => '0',
					'spam' => '0'
				);

		$this->db->where('song_id', $id);
		$this->db->update('songs', $data);
		redirect('admin/songs'); 			
	}
	
	function remove()
	{
		$id = $this->uri->segment(4);
		$this->db->where('song_id', $id);
		$this->db->delete('songs'); 
		redirect('admin/songs'); 	
	}
}

?>
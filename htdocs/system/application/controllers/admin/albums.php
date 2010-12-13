<?php

class Albums extends Controller
{	

    function __construct()
    {
        parent::Controller();

        //only 'admin' and 'superadmin' can manage users
        
		$this->dx_auth->check_uri_permissions();
    	
    }


    function index()
    {
	    $this->db->orderby('album');
		$this->db->where('duplicate > 0');
		$this->db->or_where('spam > 0');
		$this->db->or_where('questionable', 1);
	    $data['query'] = $this->db->get('albums');
 
	        
		$this->template->write('title', 'Album repors');
		$this->template->write_view('content', 'admin/albums/index', $data, true);
		$this->template->render();
		       
    }

	function clean()
	{
		$id = $this->uri->segment(4);
		$data = array(
					'duplicate' => '0',
					'spam' => '0'
					
				);
		
		$this->db->where('album_id', $id);
		$this->db->update('albums', $data);
		redirect('admin/albums'); 			
	}
	
	function cleanQuestionable()
	{
		$id = $this->uri->segment(4);
		$data = array(
					'questionable' => '0',
					'locked' => '0'
				);
		
		$this->db->where('album_id', $id);
		$this->db->update('albums', $data);
		redirect('admin/albums'); 	
	
	}
	
	function remove()
	{
		$id = $this->uri->segment(4);
		$this->db->where('album_id', $id);
		$this->db->delete('albums'); 
		
		$this->db->where('album_id', $id);
		$this->db->delete('songs');
		
		redirect('admin/albums'); 	
	}
}

?>
<?php

class Meanings extends Controller
{	

    function __construct()
    {
        parent::Controller();

        //only 'admin' and 'superadmin' can manage users
        
		$this->dx_auth->check_uri_permissions();
    	
    }


    function index()
    {		
	    $this->db->orderby('meaning_id');
		$this->db->where('report > 0');
	    $data['query'] = $this->db->get('meanings', 15);
	 
		$this->template->write('title', 'Meaning Reports');
		$this->template->write_view('content', 'admin/meanings/index', $data, true);
		$this->template->render();
    }
	
	function clean()
	{
		$id = $this->uri->segment(4);
		$data = array(
					'report' => '0',
				);

		$this->db->where('meaning_id', $id);
		$this->db->update('meanings', $data);
		redirect('admin/meanings'); 			
	}
	
	function remove()
	{
		$id = $this->uri->segment(4);
		$this->db->where('meaning_id', $id);
		$this->db->delete('meanings'); 
		
		$this->db->where('parent_id', $id);
		$this->db->delete('meaning_replies');
		
		redirect('admin/meanings'); 	
	}
	
}

?>
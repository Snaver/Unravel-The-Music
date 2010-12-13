<?php

class Artists extends Controller
{	

    function __construct()
    {
        parent::Controller();

        //only 'admin' and 'superadmin' can manage users
        
		$this->dx_auth->check_uri_permissions();
    	
    }
	
	function index()
	{
		$this->load->library('pagination');

		$config['base_url'] = 'http://www.unravelthemusic.com/admin/artists/index/';
		$config['total_rows'] = $this->db->count_all('artists');

		$config['uri_segment'] = 4;
		$config['per_page'] = '50';
		$config['num_links'] = 4;

		$this->pagination->initialize($config);
		$data['links'] = $this->pagination->create_links();
		$view = 'artist_id';
		$direction = 'desc';
		if($this->input->post('view'))
		{
			$pieces = explode(" ", $this->input->post('view'));
			$view = $pieces[0];
			$direction = $pieces[1];
		}
		$this->db->order_by($view, $direction);
		$data['query'] = $this->db->get('artists', $config['per_page'], $this->uri->segment(4));
	
		$this->template->write('title', 'Artists');
		$this->template->write_view('content', 'admin/artists/index', $data, true);
		$this->template->render();
	
	}
	
	function delete()
	{
		
		$id = $this->uri->segment(4);
		$this->db->where('artist_id', $id);
		$query = $this->db->get('artists');
		if($query->num_rows() == 1)
		{
			$row = $query->row();
			
			$this->db->where('artist_seo_name', $row->artist_seo_name);
			$this->db->delete('feed');
			
			$data = array(
				'verified' => '-1'
				);
			$this->db->where('artist_id', $id);
			$this->db->update('artists', $data);
			
			$this->db->where('artist_id', $id);
			$this->db->delete('albums');
			
			$this->db->where('artist_id', $id);
			$this->db->delete('songs');
			
			$this->db->where('artist_id', $id);
			$this->db->delete('meanings');
			
			$this->db->where('artist_id', $id);
			$this->db->delete('meaning_replies');
		} else {
			$data['title'] = 'error';
			$data['error'] = 'artist not found';
			$this->template->load('template_main', '404', $data);
		}
		echo('deleted');
	}
}
?>
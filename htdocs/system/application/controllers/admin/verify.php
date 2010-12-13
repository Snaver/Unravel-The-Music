<?php

class Verify extends Controller
{	

    function Verify()
    {
        parent::Controller();

        //only 'admin' and 'superadmin' can manage users
        $this->freakauth_light->check('admin');

        
        $this->_container = $this->config->item('FAL_template_dir').'template_admin/container';
    	
    }

     
    function index()
    {
		$data['heading']='Verify New Artists';
		$data['action']='Just an example';

	    
          $this->db->where('verified', '0');
    $this->db->orderby('artist');
    $data['query'] = $this->db->get('artists', 10);
    $data['content']='<table>';
    foreach ($data['query']->result() as $row) :
	    $data['content'].='<tr><td width = "200px">';
	    $data['content'].=$row->artist . "</td><td width='150px'>";

	    $data['content'].=" " . anchor('admin/verify/confirm/' . $row->artist_id, 'verify');
	    $data['content'].="</td><td>";
	    $data['content'].=" " . anchor('admin/verify/remove/' . $row->artist_id, 'remove');
	    
	    $data['content'].='</td></tr>';
    endforeach;
    $data['content'].='</table>';
    $data['page'] = $this->config->item('FAL_template_dir').'template_admin/verify';
        
	$this->load->vars($data);
	$this->load->view($this->_container);
	       
    }

     
    function confirm()
    {
      $id = $this->uri->segment(4);
      $data = array('verified' => '1');
      $this->db->update('artists', $data, array('artist_id' => $id));
      redirect('admin/verify');
    }
    
    function remove()
    {
      $id = $this->uri->segment(4);
      $data = array('verified' => '-1');
      $this->db->update('artists', $data, array('artist_id' => $id));
      redirect('admin/verify'); 
    }

}
?>
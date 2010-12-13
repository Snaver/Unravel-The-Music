<?php

class Artists extends Controller {

  function Artists()
  {
    parent::Controller();
    
    //$this->load->scaffolding('artists');
  
  
  }

  function index()
  {
    $data['title'] = "Artists";
    $this->db->where('verified', '1');
    $this->db->orderby('name');
    $data['query'] = $this->db->get('artists', 10);
    
    $this->load->view('artists_index', $data);
    
    $flash=$this->db_session->flashdata('flashMessage');

  }
  
  function view($id)
  {
    $data['title'] = "Artist view ";
    $data['heading'] = "Viewing albums by ";
    $this->db->where('name', $this->uri->segment(3));
    $data['queryArtists'] = $this->db->get('artists');
    $this->db->where('artist', $this->uri->segment(3));
    $data['query'] = $this->db->get('albums');

    $this->load->view('artists_view', $data);
    
  }
  
  function watch()
  {
    $this->freakauth_light->check();
    $this->load->model('submitmodel');
    $id = $this->db_session->userdata('id');
    $query = $this->usermodel->getUserById($id);
    
  	//now we get personalized
		if ($query->num_rows() == 1)
    {
      $exists = $this->submitmodel->verifyExists($this->uri->segment(3), 'artists');
      if($exists == true) {
        $data['user_profile']= $this->freakauth_light->_getUserProfile($id);
      	$data['user_profile'] = array_slice($data['user_profile'], -2, 1);

      	if(empty($data['user_profile']['artists']))
         {
          $insert = array(
                  'artists' => $this->uri->segment(3)
                  );
            
          $this->db->where('id', $id);
          $this->db->update('auth_user_profile', $insert); 
         } else {
          $artists_temp = array();
          $artists_temp = explode(', ', $data['user_profile']['artists']);
          //before we add the new artists we must check to see if they are already watching it
          foreach($artists_temp as $check) {
            if ($check == $this->uri->segment(3)) {
            redirect('/artists/view/' . $this->uri->segment(3));
            }
          }
          $artists_temp[] = $this->uri->segment(3);
          $artists = implode(", ", $artists_temp);
          
          $insert = array(
               'artists' => $artists
            );
            
          $this->db->where('id', $id);
          $this->db->update('auth_user_profile', $insert); 
         }
      } else {

        redirect('/artists');
      
      }
    } else {
      echo("you must be logged into to watch an artist");
    }
  }
  
  function add()
  {
    if($this->uri->segment(3) == 'Thank You')
    {
      echo ('Thank you for your submission, it will be reviewed by our team.');
    }
    $this->freakauth_light->check();
    $data['title'] = "Add an artist";
    unset($_POST);
    $this->load->view('artists_add', $data);
  }
  function submit()
  {
    //find all the fields in the artists table and put them in a var
    $fields = $this->db->list_fields('artists');

    //fields we won't be adding anything to
    $excluded_fields = array('viewcount','verified','id');
    //inserting viewcount and verified as constant zeroes
    $insert['viewcount'] = '0';
    $insert['verified'] = '0';
    
    //load our model so we can test and insert
    $this->load->model('submitmodel');
    //check if this is a final post or not
    //if it is not then we must test
    if($this->input->post('final') == 'false') {
      //create an array with all of the posted information
      $artists_data = array ('username' => $this->db_session->userdata("user_name"),
                            'artist' => $this->input->post("name"),
                            'created_on' => date('Y-n-d G:i:s')
                          );
      //insert the posted data into artists_tmp
      $this->db->insert('artists_tmp', $artists_data);
      
      //we will run verifyNew to make sure there are no exact matches
      $this->submitmodel->verifyNew($this->input->post('name'), 'artists', '', '');
      //test for any partial matches
      $checks = $this->submitmodel->verifySpelling($this->input->post('name'), 'artists');
      //if we return anything we must load the view
      if(count($checks) > 0) {
        //include data information and database results
        $data['name'] = $this->input->post('name');
        $data['checks'] = $checks;
        //load the view
        $this->load->view('artists_check', $data);
      }//end checks count
      
    }//end input->post('final') check
      //the post has made it this far or this is after a resubmit on partial check page
      //check to make sure the form wasn't altertered on the partial check page
      $this->submitmodel->checkTmp($this->input->post('name'));
      //finally insert it
      $this->submitmodel->submit("artists", $fields, $insert, $excluded_fields);
      
      //redirect to the add page with a thank you note.
      redirect('artists/add/Thank You');
  }

  




}

?>

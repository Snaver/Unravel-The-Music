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
  
  function report()
  {
    $this->freakauth_light->check();
    $id = $this->db_session->userdata('id');
    $this->load->model('reportmodel');
    $resultsReport = $this->reportmodel->verify($this->uri->segment(4), 'meanings');
    if($resultsReport->num_rows() == 0) {
    
    $data['error'] = 'This meaning does not exist';
    $this->template->load('template_main', '404', $data);
    }

    $row = $resultsReport->row();
    
    $reports = $this->reportmodel->verifyNoReport($id, $row->id, '', $this->uri->segment(3));
    if($reports != false) {
      redirect('/songs/view/' . $row->artist . '/' . $row->album . '/' . $row->song_name);
    } else {
    
      $this->reportmodel->report($id, $this->uri->segment(4), $this->uri->segment(3), $row, 'meanings');
      redirect('/songs/view/' . $row->artist . '/' . $row->album . '/' . $row->song_name);
    }
  }
  
  function like()
  {
    $this->freakauth_light->check();
    $id = $this->db_session->userdata('id');
    
    $this->load->model('meaningmodel');
    $results = $this->meaningmodel->verifyMeaning($this->uri->segment(3));
    $row = $results->row();
    if($results != false) {
      $votes = $this->meaningmodel->verifyNoVote($id, $row->id);
      if($votes != false)
      {
        $rowVotes = $votes->row();
        if($rowVotes->vote == 1) {
        $vote = 'like';
        } else {
        $vote = 'dislike';
        }
        echo('you voted, ' . $vote . ' for ' . $row->author . '\'s post already.');
      } else {
        $this->meaningmodel->vote($id, $row->id, 'like');
        redirect('/songs/view/' . $row->artist . '/' . $row->album . '/' . $row->song_name);
      }
    } else {
    //this meaning doesn't exists so redirect them to artists
    redirect('/artists');
    }
  }
  
  function dislike()
  {
    $this->freakauth_light->check();
    $id = $this->db_session->userdata('id');
    
    $this->load->model('meaningmodel');
    $results = $this->meaningmodel->verifyMeaning($this->uri->segment(3));
    $row = $results->row();
    if($results != false) {
      $votes = $this->meaningmodel->verifyNoVote($id, $row->id);
      if($votes != false)
      {
        $rowVotes = $votes->row();
        if($rowVotes->vote == 1) {
        $vote = 'like';
        } else {
        $vote = 'dislike';
        }
        echo('you voted, ' . $vote . ' for ' . $row->author . '\'s post already.');
      } else {
        $this->meaningmodel->vote($id, $row->id, 'dislike');
        redirect('/songs/view/' . $row->artist . '/' . $row->album . '/' . $row->song_name);
      }
    } else {
    //this meaning doesn't exists so redirect them to artists
    redirect('/artists');
    }  
  }
  function view()
  {
    //for viewing a meaning, this is the permanent link view
    
    //set data variables
    $data['title'] = "Viewing meanings for...";
    $data['heading'] = "Viewing meanings for...";
    $data['date'] = "Meaning Post Date: ";
    
    //we need the id of the meaning for the where
    $this->db->select('*');
    $this->db->from('meanings');
    $this->db->join('songs', 'songs.song_id = meanings.song_id');
    $this->db->join('albums', 'albums.album_id = songs.album_id');
    $this->db->join('artists', 'artists.artist_id = songs.artist_id');
    //query the meanings table
    $this->db->where('meaning_id', $this->uri->segment(3));
    $data['query'] = $this->db->get('meanings');
    
    //load the view page for viewing meanings
    $this->template->load('template_main', 'meanings/view', $data);
    
  }
  function add()
  {
    //this is for adding a meaning to a song
    //must be logged in
    $this->freakauth_light->check();

    //set data variable
    $data['title'] = "Add a comment";
    $data['song_id'] = $this->uri->segment(3);
    
    //set the where to id
    $this->db->where('song_id', $data['song_id']);
    //we need to query the database because we need song information for FK in the
    //meanings table
    $data['query'] = $this->db->get('songs');
    
    //load the add view
    $this->template->load('template_main', 'meanings/add', $data);
  
  }
  function edit()
  {
    $this->freakauth_light->check();
  
  }
  function reply()
  {
    //allows users to reply to other meanings
    
    //must be logged in
    $this->freakauth_light->check();
    
    //set the data variable
    $data['title'] = "Add a reply";
    $data['parent_id'] = $this->uri->segment(3);
    
    //the parent id is the id we are replying to.
    $this->db->where('meaning_id', $data['parent_id']);
    //we have to do this so we can get the song_id from the parent's meaning
    $data['query'] = $this->db->get('meanings');
    
    //load the meanings reply view
    $this->template->load('template_main', 'meanings/reply', $data);
  }
  
  function submit()
  {
    $this->load->model('submitmodel');
    //this is how we manage all of the submits from add/edit/reply
    //must be logged in.
    $this->freakauth_light->check();
    
    $author = $this->db_session->userdata('user_name');
    $insert['created_on'] = date('Y-n-d G:i:s');
    $insert['author'] = $author;
      
    //we need to decide how to handle the comment
    if($this->uri->segment(3) == 'new')
    {
      $song = $this->uri->segment(4);
      $this->db->select('*');
      $this->db->from('songs');
      $this->db->join('albums', 'albums.album_id = songs.album_id');
      $this->db->join('artists', 'artists.artist_id = songs.artist_id');
      $this->db->where('song_id', $song);
      $query = $this->db->get();
      if($query->num_rows() != 1) {
      $data['error'] = 'song does not exist';
      $this->load->view('404', $data);
      
      }
      $row = $query->row();

      //if it's new it will come through here
      //list the fields for meanings
      $fields = $this->db->list_fields('meanings');
      //exclude id
      $excluded_fields = array('meaning_id', 'author', 'created_on', 'rating_up', 'rating_down', 'song_id');
      $insert['song_id'] = $row->song_id;
      //submit to db and feed
      $this->submitmodel->submit("meanings", $fields, $insert, $excluded_fields);
      $this->submitmodel->addToFeed('2', $this->input->post('album'), $this->input->post('artist'), $this->input->post('song_name'));
      redirect('/songs/view/' . $row->artist . '/' . $row->album . '/' . $row->song);
    } else if($this->uri->segment(3) == 'reply') 
    {
      $this->db->where('meaning_id', $this->uri->segment(4);
      $query = $this->db->get('meanings');
      if($query->num_rows() != 1) {
      $data['error'] = '<br />sorry meaning does not exist';
      $this->load->view('404', $data);
      
      } else {
        $row = $query->row();
        //if url is reply it will come through here
        //list the fields for meaning replies
        $fields = $this->db->list_fields('meaning_replies');
        //exclude id
        $insert['parent_id'] = $this->uri->segment(4);
        $insert['song_id'] = $row->song_id
        $excluded_fields = array('reply_id', 'author', 'created_on', 'parent_id', 'song_id');
  
        
        //submit to db and feed
        $this->submitmodel->submit("meaning_replies", $fields, $insert, $excluded_fields);
        //redirect the user back to the song
        redirect('/songs/view/' . $row->artist . '/' . $row->album . '/' . $row->song);      
      }
      
      

       
    }

  }

}

?>

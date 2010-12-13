<?php
class Submitmodel extends Model {


    function Submitmodel()
    {
        // Call the Model constructor
        parent::Model();
    }
    //this function allows us to check if the posted name is the same one that was posted and checked
    //the origional time though
    function checkTmp($name)
    {
      //this is the where statement for the posted artist name
      $this->db->where('artist', $name);
      
      //query the artists_tmp table
      $data['query'] = $this->db->get('artists_tmp');
      
      //we need to use this information now so put it into row6
      $row6 = $data['query']->row();
      
      //if the number of rows returned is 0 then we know they changed the form
      if($data['query']->num_rows() < 1) {
        //let them know we know they changed it and exit the script(for now)
        echo('you changed the form! that makes me very mad!  Your username has been stored!');
        exit;
      } else {
        //if they didn't change it, delete the record from artists_tmp and proceed
        $this->db->delete('artists_tmp', array('id' => $row6->id)); 
      }
    
    }
    //this function allows us to take input from any page and with that information insert it
    //into the database
    function submit($table, $data, $insert, $excluded_fields)
    {
      foreach ($data as $field):
        if( ! in_array($field,$excluded_fields))
        {
          $insert[$field] = $this->input->post($field);
        }
      endforeach;

      //insert insert into database
      $this->db->insert($table, $insert); 
          
    }
    
    //this function allows us to know if the submitted item is new or already in the database
    //only searches for exact matches
    function verifyNew($name, $table, $artist, $album)
    {
      //begin our switch
      switch($table) {
      //if it is an artists that was submitted we will check here
      case 'artists':
        $this->db->where('artist', $name);
        $query = $this->db->get($table);
        if($query->num_rows() > 0) {
		$row = $query->row();
        redirect('artists/view/' . $row->artist_seo_name);
        }
        break;
      case 'songs':
        //if it was a song we will check here
        $this->db->where('song', $name);
        $this->db->where('artist_id', $artist);
        $this->db->where('album_id', $album);
        $newSongQuery = $this->db->get('songs');
        if($newSongQuery->num_rows() > 0) {
			$row = $newSongQuery->row();
        redirect('songs/view/' . $row->artist_seo_name . '/' . $row->album_seo_name . '/' . $row->song_seo_name);
        exit;
        }
        break;
      case 'albums':
        //if it was an album we will check here
        $this->db->join('artists', 'artists.artist_id = albums.artist_id');
        $this->db->where('album', $name);
        $this->db->where('albums.artist_id', $artist);
        $newAlbumQuery = $this->db->get($table);
        if($newAlbumQuery->num_rows() > 0) {
        $row = $newAlbumQuery->row();

          
        redirect('albums/view/' . $row->artist_seo_name . '/' . $row->album_seo_name);
        }
        break;
      }
    }
    //this function allows us to check and see if maybe there was just a misspelling in the submission
    function verifySpelling($search, $table, $extra)
    {
      $checks = array();
      //if the length of the submitted information is greater than 3 then start here
      if(strlen($search) > 3) {
        //first we create a variablewith the last 4 letters
        $first = substr($search, -4, 4);
        //second we create a variable with the first 4 letters
        $second = substr($search, 0, 4);
        if(!empty($extra))
        {
          $this->db->where('artist', $extra);
        }
        //this like or or_like allows us to check the db for matches that contain the information in the vars
        $this->db->like('name', $first);
        $this->db->or_like('name', $second);
      } else {
        if(!empty($extra))
        {
          $this->db->where('artist', $extra);
        }
        //if the string lenght is less than 3 we start here
        //check the first 2 chars
        $first = substr($search, 0, 2);
        $this->db->like('name', $first);
      }//end else
        //check the database with the like statements
        $data['spellQuery1'] = $this->db->get($table);      
        if($data['spellQuery1']->num_rows() > 0) {
          foreach ($data['spellQuery1']->result() as $row5)
          {
            //insert the name(s) into an array
            $checks[] = $row5->name;
          }//end foreach
        } else {
        $checks[0] = "no results";
        }
        if(count($checks[0]) > 0) {
        //if something is in checks we return checks else do nothing
        return $checks;
      
        } else {
        
        }
      
    }//end function
    
 

}
?>

<?php

class Songs extends Controller {

	function Songs()
	{
		parent::Controller();
    	//$this->output->enable_profiler();
		//$this->load->scaffolding('artists');
  
  
	}

	function index()
	{
	    //need to do something here
		$this->db->join('songs', 'songs.song_id = meanings.song_id');
	    $this->db->join('artists', 'artists.artist_id = songs.artist_id');
	    $this->db->join('albums', 'albums.album_id = songs.album_id');
		
		$this->db->where('rating_up > 1');
		$this->db->orderby('rand()');
	    $data['query'] = $this->db->get('meanings', 10);
		$this->template->write('title', 'Songs');
		$this->template->write_view('content', 'songs/index', $data, true);
		$this->template->render();
  
	}
  
	function add()
	{
	    $this->load->model('submitmodel');
		$this->load->model('ArtistModel');
		$this->load->model('AlbumModel');
		$this->load->model('LyricsModel');
	    //this is for adding a meaning to a song
	    //must be logged in
	    $this->dx_auth->check_uri_permissions();

	    //set data variable
	    $data['title'] = "Add a new song";
		$artist_seo_name = $this->uri->segment(3);
		$album_seo_name = $this->uri->segment(4);
		//make sure they at least entered a artist and album
		if(empty($artist_seo_name) || empty($album_seo_name)) {
			die("you must specifiy an album and an artist");
		}
		
  
		$albumId = $this->AlbumModel->loadFull($album_seo_name, $artist_seo_name);

		if($albumId->num_rows() > 0)
		{
			$albumId = $albumId->row();
			if($albumId->verified == 1)
			{
				if($albumId->locked != 1)
				{
					if(isset($_POST['song']))
					{
						$this->load->library('form_validation');
						$this->form_validation->set_rules('song', 'Song', 'trim|required|lyrics|xss_clean');			
											
						if($this->form_validation->run() == FALSE)
						{
							$data['artist'] = $albumId->artist;
							$data['album'] = $albumId->album;
							$data['artist_seo_name'] = $albumId->artist_seo_name;
							$data['album_seo_name'] = $albumId->album_seo_name;
							$this->template->write('title', 'Error in the form');
							$this->template->write_view('content', 'songs/add', $data, true);
							$this->template->render();
						} else {
							// we need to replace the line break code with breaks so when it is pulled from the database
							//$this->load->library('lyricsPrep');
							//$lyrics = $this->lyricsPrep->addBreaks($this->input->post('lyrics'));
							unset($_POST['lyrics']);
						
							//list the fields for songs
							$fields = $this->db->list_fields('songs');
							//exclude id and others
							$excluded_fields = array('song_id', 'created_on', 'created_by', 'artist_id', 'album_id', 'song_seo_name');
							$created_by = $this->session->userdata('DX_username');
							date_default_timezone_set('America/Chicago');
							$insert['created_on'] = date('Y-n-d G:i:s');
							$insert['created_by'] = $created_by;
							
							$insert['song_seo_name'] = url_title($this->input->post('song', TRUE));
							$insert['artist_id'] = $albumId->artist_id;
							$insert['album_id'] = $albumId->album_id;
							
							//verify this is a new song     

							$this->submitmodel->verifyNew($this->input->post('song'), 'songs', $albumId->artist_id, $albumId->album_id);
							
							$this->submitmodel->submit("songs", $fields, $insert, $excluded_fields); 
							
							$this->db->where('song', $this->input->post('song'));
							$this->db->where('album_id', $albumId->album_id);
							$this->db->where('artist_id', $albumId->artist_id);
							$songInfo = $this->db->get('songs');
							$song = $songInfo->row();

							redirect('songs/view/' . $albumId->artist_seo_name . '/' . $albumId->album_seo_name . '/' . url_title($this->input->post('song')));	 
						}
					} else {
						$data['artist'] = $albumId->artist;
						$data['album'] = $albumId->album;
						$data['artist_seo_name'] = $albumId->artist_seo_name;
						$data['album_seo_name'] = $albumId->album_seo_name;						
						$this->template->write('title', 'Add a songs');
						$this->template->write_view('content', 'songs/add', $data, true);
						$this->template->render();
					}
				} else {
					if($albumId->questionable == 1)
					{
						$this->session->set_flashdata('flashMessage', 'No songs can be added until the validity of this album has been determined');
					} else {
						$this->session->set_flashdata('flashMessage', 'No more songs can be added to this album');
					}
					redirect('artists/view/' . $albumId->artist_seo_name);
				}
			} else {
				$data['error'] = 'This artist hasn\'t been verified yet, please try again later';
				$this->template->write('title', $this->lang->line('error-notVerified'));
				$this->template->write_view('content', '404', $data, true);
				$this->template->render();
			}					
		} else { 
			$data['error'] = 'Your URL seems to be broken.  Better get that checked out.';
			$this->template->write('title', '404 Not Found');
			$this->template->write_view('content', '404', $data, true);
			$this->template->render();		
		}


	}
  
	function catalog()
	{
		$this->load->library('pagination');

		$letter = $this->uri->segment(3);
		$offset = $this->uri->segment(4);
		$data['letter'] = substr($letter, 0, 1);
		if(mb_strlen($letter) == 1)
		{
			if (!ereg('^[A-Za-z0-9]$', $data['letter'])) {
				redirect('songs/');
			}
			
			$this->load->model('SongModel');
			$config['per_page'] = '50';
			$config['num_links'] = 6;
			$data['results'] = $this->SongModel->loadByLetter($data['letter'], $config['per_page'], $offset);
		
			$config['base_url'] = base_url() . '/songs/catalog/' . $data['letter'] . '/';
			$config['uri_segment'] = 4;
			$config['first_link'] = 'First';
			$config['last_link'] = 'Last';
		
			$config['total_rows'] = $this->SongModel->getTotalForLetter($data['letter']);
		

			$this->pagination->initialize($config);
		
			$data['links'] = $this->pagination->create_links();	
		
			$this->template->write('title', 'Songs beginning with ' . $letter);
			$this->template->write_view('content', 'songs/catalog', $data, true);
			$this->template->render();
		} else {
			$artist = $this->uri->segment(3);
			$this->load->model('ArtistModel');
			$this->load->model('SongModel');
			$query = $this->ArtistModel->load($artist);
			
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$artist = $row->artist_id;
				$data['query'] = $this->SongModel->loadList($artist);
				
				$this->template->write('title', 'All songs by ' . $row->artist);
				$this->template->write_view('content', 'songs/list', $data, true);
				$this->template->render();
			
			} else {

				$data['error'] = $this->lang->link('error-doesNotExist');
				$this->template->write('title', $this->lang->line('error-doesNotExist'));
				$this->template->write_view('content', '404', $data, true);
				$this->template->render();
			}		
		
		}
	}

	
	function random()
	{
	
		$max = $this->db->count_all('songs');
		$id = rand(1, $max);
		$id2 = rand(1, $max);
		$id3 = rand(1, $max);
		$this->db->join('artists', 'artists.artist_id = songs.artist_id');
		$this->db->join('albums', 'albums.album_id = songs.album_id');
		$this->db->where('song_id', $id);
		$query = $this->db->get('songs', 1);
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			redirect('songs/view/' . $row->artist_seo_name . '/' . $row->album_seo_name . '/' . $row->song_seo_name);
		} else {
			$this->db->join('artists', 'artists.artist_id = songs.artist_id');
			$this->db->join('albums', 'albums.album_id = songs.album_id');
			$this->db->where('song_id', $id2);
			$query = $this->db->get('songs', 1);
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				redirect('songs/view/' . $row->artist_seo_name . '/' . $row->album_seo_name . '/' . $row->song_seo_name);			
			} else {
			
				$data['error'] = 'we have big problems';
				$this->template->write('title', 'I\'m afraid we are crashing');
				$this->template->write_view('content', '404', $data, true);
				$this->template->render();
			}
		}
		
	}	
	
	function untag()
	{
	    $this->dx_auth->check_uri_permissions();
	    $this->load->model('ArtistModel');
		$this->load->model('AlbumModel');
		$this->load->model('SongModel');
	    $id = $this->session->userdata("DX_user_id");
		
	    $artist = $this->uri->segment(3);
	    $album = $this->uri->segment(4);
	    $song = $this->uri->segment(5);

	    $query = $this->ArtistModel->load($artist);
	    if($query->num_rows() > 0)
	    {
			$artistId = $query->row();
			if($artistId->verified == 0)
			{
				echo('This artist hasn\'t been verified yet, please try again later');
				exit;
			}         
			$albumId = $this->AlbumModel->load($album, $artistId->artist_id);

			if(isset($albumId->album_id))
			{
				$data['query'] = $this->SongModel->load($song);

				if($data['query']->num_rows() > 0)
				{
					$song = $data['query']->row();
					
					$this->load->model('WatchedModel');
					$this->WatchedModel->unwatch('song', $song->song_id, $id);
					$response['message'] = 'success';
					print json_encode($response);   
				} else {
					$response['message'] = 'fail';
					$response['error'] = $this->lang->line('error-songDoesNotExist');
					print json_encode($response); 
				}

			} else {
				$response['message'] = 'fail';
				$response['error'] = $this->lang->line('error-albumDoesNotExist');
				print json_encode($response); 
		}
	} else {
		$response['message'] = 'fail';
		$response['error'] = $this->lang->line('error-doesNotExist');
		print json_encode($response); 
	}
      		 
  }
      
	function view()
	{
		$id = $this->session->userdata("DX_user_id"); 
	    $artist = $this->uri->segment(3);
	    $album = $this->uri->segment(4);
	    $song = $this->uri->segment(5);
		$this->load->model('SongModel');
		$this->load->model('ArtistModel');
		$this->load->model('MeaningModel');
		$this->load->model('LyricsModel');
		$this->load->model('CommentModel');
		$this->load->model('usermodel_unravel');

		$data['query'] = $this->SongModel->loadExtended($song, $album, $artist);

		if($data['query']->num_rows() > 0)
		{
			$songId = $data['query']->row();
			$this->load->helper('cookie');			
			$this->load->helper('date');
			if(!get_cookie('unravel_' . $songId->artist_id) && $songId->viewcount_expires < now())
			{
				$this->ArtistModel->addToViewcount($songId->artist_id, $songId->viewcount, $songId->viewcount_month);
				$cookie = array(
								   'name'   => $songId->artist_id,
								   'value'  => $songId->artist_id,
								   'expire' => '86500',
							   );

				set_cookie($cookie); 				
			}
			
			//get lyrics
			if($songId->verified == 0)
			{
				echo('This artist hasn\'t been verified yet, please try again later');
				exit;
			}   					
			if($songId->song_ASIN == NULL)
			{
				$request="http://ecs.amazonaws.com/onca/xml?Service=AWSECommerceService&AWSAccessKeyId=AKIAIOL67SVPNTUF7M4A&Operation=ItemSearch&SearchIndex=DigitalMusic&Keywords=" . urlencode($songId->artist . ' ' . $songId->song);				$response = @file_get_contents($request);				
				if($response == null)
				{
					$data['asin'] = null;
				} else {
				
					$xml = @simplexml_load_string($response);
					$asin =  $xml->Items->Item->ASIN;
					$this->SongModel->addAsin($songId->song_id, (string)$asin);
					$data['asin'] = (string)$asin;
				}
			} else {
				$data['asin'] = $songId->song_ASIN;
			}
			$lyrics = $this->LyricsModel->load($songId->song_id);
			$request = true;
			$expires = false;
			$this->load->helper('date');
			$now = now();				
			$request = false;
			if($lyrics->num_rows() > 100)
			{
				date_default_timezone_set('America/Chicago');
				$lyricsRow = $lyrics->row();


				$timeLeft = $lyricsRow->expires - $now;
				//echo ('expires in: ' . $timeLeft . ' seconds');
				if($timeLeft > 0)
				{
					$request = false;
					
				} else {
					$expires = true;
				}
			}
			if($request == true)
			{
				$data['lyricsVerified'] = 1;
				$ctx = stream_context_create(array(
					'http' => array(
						'timeout' => 3
						)
					)
				); 
				$wsdl = "http://lyricwiki.org/server.php?wsdl";

				try {
					if(!@file_get_contents($wsdl, 0, $ctx)) {
						throw new SoapFault('Server', 'No WSDL found at ' . $wsdl);
					}
					$client = new SoapClient($wsdl);					 
					$artist = $songId->artist; 
					$song = $songId->song; 
					$result = $client->getSong($artist, $song);
					if($result->url != '' OR $result->url != NULL)
					{
						if(file_exists ($result->url))
						{
							$file = file_get_contents($result->url);
							$start = 22 + strpos($file, 'div class=\'l');
							$stop = strpos($file, 'NewPP') - $start - 9;
							$lyrics = substr($file, $start, $stop);
						} else {
							$lyrics = 'Not found';
						}
					} else {
						$lyrics = 'Not found';
					}
					$this->load->library('LyricsPrep');
					
					$lyrics = $this->lyricsprep->addBreaks($lyrics);
					if($lyrics != 'Not found')
					{
						$twoDays = $now + 31536000;
						$this->db->set('lyrics', $lyrics);
						$this->db->set('expires', $twoDays);
						
						//echo('<br />Updating the Database');
						
						if($expires == true)
						{
							$this->db->where('lyrics_id', $lyricsRow->lyrics_id);
							$this->db->update('lyrics');
							//echo('updating lyrics_id row');
						} else {
							$this->db->set('song_id', $songId->song_id);
							$this->db->insert('lyrics');
							//echo('setting song id');
						}
					}
					
					$data['lyrics'] = $lyrics;
				} catch (SoapFault $e) {
					if($expires == true) {
						$data['lyrics'] = $lyricsRow->lyrics;
					} else {
						$data['lyrics'] = 'We temporarily have no lyrics for this artist<br />Please try back in a few minutes';
					}
				}
						
						
				 
			} else {
				//if($lyricsRow->expires != 0)
				if(1 == 2)
				{
					$data['lyricsId'] = $lyricsRow->lyrics_id;
					$data['lyricsVerified'] = $lyricsRow->verified;
				} else {
					$data['lyricsId'] = null;
					$data['lyricsVerified'] = 1;
				}
				//$data['lyrics'] = $lyricsRow->lyrics;
				$data['lyrics'] = NULL;
			}

			$this->load->model('usermodel_unravel');


			//we need some of that info to get the meanings for this song
			if($this->dx_auth->is_logged_in())
			{
				$this->usermodel_unravel->blockList($this->session->userdata('DX_username'));
				$this->load->model('WatchedModel');
				$data['watchingSong'] = $this->WatchedModel->checkExists('song', $songId->song_id, $id);
				$data['watchingArtist'] = $this->WatchedModel->checkExists('artist', $songId->artist_id, $id);
				$data['watchingAlbum'] = $this->WatchedModel->checkExists('album', $songId->album_id, $id);
			} else {
				$data['blockList'] = null;
				$data['watchingSong'] = false;
				$data['watchingArtist'] = false;
				$data['watchingAlbum'] = false;			
			}
			//query the meanings table
			$data['meaningsQuery'] = $this->MeaningModel->load($songId->song_id);
			$data['meaningCount'] = $data['meaningsQuery']->num_rows();
			/* Used to create a new sorted multidimensional array for top x songs */
			$topMeanings = array();

			$i = 0;
			foreach($data['meaningsQuery']->result() as $newArray) {

				$topMeanings[] = array('id' => $newArray->meaning_id,
				'votes' => $newArray->rating_up-$newArray->rating_down,
				'key' => $i);
				++$i;
			}

			
			//function used to sort a multi-dimensional array
			function msort($array, $id='votes') {
				$temp_array = array();
				while(count($array)>0) {
					$lowest_id = 0;
					$index=0;
					foreach ($array as $item) {
						if ($item[$id]<$array[$lowest_id][$id]) {
							$lowest_id = $index;
						}
						$index++;
					}
					$temp_array[] = $array[$lowest_id];
					$array = array_merge(array_slice($array, 0,$lowest_id), array_slice($array, $lowest_id+1));
				}
				return $temp_array;
			}	//end function
			
			$data['topMeanings'] = array_reverse((msort($topMeanings)));	
			/* end new sorted multi	*/
			
			$numRows = $data['meaningsQuery']->num_rows();

			if($numRows == 0) {
				$data['numberOfTop'] = 0;
			}
			if($numRows < 3  && $numRows > 0) {
				$data['numberOfTop'] = 1;
			} elseif ($numRows >= 3 && $numRows <= 6) {
				$data['numberOfTop'] = 2;
			} elseif ($numRows > 6) {
				$data['numberOfTop'] = 3;
			}	
			$data['userVotes'] = null;
			if($numRows > 0 && $this->dx_auth->is_logged_in())
			{
				$userVotes = $this->MeaningModel->getVotes($songId->song_id);
				foreach($userVotes->result() as $row)
				{
					$data['userVotes'][$row->meaning_id] = $row->vote;
				}
			}
			$data['replyQuery'] = $this->MeaningModel->getReplies($songId->song_id);
			$data['commentQuery'] = $this->CommentModel->load($songId->song_id);
			$data['commentCount'] = $data['commentQuery']->num_rows();

			$this->template->write('title', 'Viewing ' . $songId->song);
			$this->template->write_view('content', 'songs/view', $data, true);
			$this->template->render();
		}//no songs
		 else {
			 $data['error'] = 'There is something wrong with your URL, it appears to be broken.';
			$this->template->write('title', '404 - Not Found');
			$this->template->write_view('content', '404', $data, true);
			$this->template->render();
		}//end songs else

	}//end view

	function tag()
	{
	    $this->dx_auth->check_uri_permissions();
	    $this->load->model('ArtistModel');
		$this->load->model('AlbumModel');
		$this->load->model('SongModel');
	    $id = $this->session->userdata("DX_user_id");
		
	    $artist_seo_name = $this->uri->segment(3);
	    $album_seo_name = $this->uri->segment(4);
	    $song_seo_name = $this->uri->segment(5);
	    
	    $query = $this->ArtistModel->load($artist_seo_name);
	    if($query->num_rows() > 0)
	    {
			$artistId = $query->row();
			if($artistId->verified == 0)
			{
				echo('This artist hasn\'t been verified yet, please try again later');
				exit;
			}         
			$albumId = $this->AlbumModel->load($album_seo_name, $artistId->artist_id);

			if(isset($albumId->album_id))
			{
				$data['query'] = $this->SongModel->loadExtended($song_seo_name, $albumId->album_seo_name, $artistId->artist_seo_name);

				if($data['query']->num_rows() > 0)
				{
					$song = $data['query']->row();
					
					$this->load->model('WatchedModel');
					$exists = $this->WatchedModel->checkExists('song', $song->song_id, $id);
					if($exists == false)
					{
						$this->WatchedModel->watch('song', $song->song_id, $id);
					}
					$response['message'] = 'success';
					print json_encode($response);   
				} else {
					$response['message'] = 'fail';
					$response['error'] = $this->lang->line('error-songDoesNotExist');
					print json_encode($response);  
				}

			} else {
				$response['message'] = 'fail';
				$response['error'] = $this->lang->line('error-albumDoesNotExist');
				print json_encode($response); 
			}
		} else {
			$response['message'] = 'fail';
			$response['error'] = $this->lang->line('error-doesNotExist');
			print json_encode($response); 
		}
	}
	
	function favorites()
	{
		$query = $this->db->query("SELECT song_id, COUNT(user_id) as users FROM watched_songs GROUP BY song_id ORDER BY users desc LIMIT 10");
		foreach($query->result() as $song)
		{
			$this->db->or_where('song_id', $song->song_id);
		}
		$this->db->join('albums', 'albums.album_id = songs.album_id');
		$this->db->join('artists', 'artists.artist_id = songs.artist_id');
		$data['query'] = $this->db->get('songs');
		$this->template->write('title', 'Favorite songs as chosen by users');
		$this->template->write_view('content', 'songs/favorites', $data, true);
		$this->template->render();
		
	
	}	  

}

?>

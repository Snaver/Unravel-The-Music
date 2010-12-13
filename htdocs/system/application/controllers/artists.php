<?php

class Artists extends Controller {

	function __construct()
	{
		parent::Controller();
		//$this->output->enable_profiler();	
	}

	function index()
	{
		$this->db->where('verified', '1');
		$this->db->orderby('viewcount', 'desc');
		$data['query'] = $this->db->get('artists', 5);

		$this->template->write('title', 'Artists Home');
		$this->template->write_view('content', 'artists/index', $data, true);
		$this->template->render();
	}
  
	function catalog()
	{
		$this->load->library('pagination');

		$letter = $this->uri->segment(3);
		$offset = $this->uri->segment(4);
		
		$data['letter'] = substr($letter, 0, 1);
	
		if (!ereg('^[A-Za-z0-9]$', $data['letter'])) {
			redirect('artists/');
		}
		
		$this->load->model('ArtistModel');
		$config['per_page'] = '100';
		$data['results'] = $this->ArtistModel->loadByLetter($data['letter'], $config['per_page'], $offset);
		
		$config['base_url'] = base_url() . '/artists/catalog/' . $data['letter'] . '/';
		$config['uri_segment'] = 4;
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['num_links'] = 6;
		$config['total_rows'] = $this->ArtistModel->getTotalForLetter($data['letter']);

		$this->pagination->initialize($config);
		$data['links'] = $this->pagination->create_links();	

		$this->template->write('title', 'Artists beginning with ' . $letter);
		$this->template->write_view('content', 'artists/catalog', $data, true);
		$this->template->render();
	}
	
	function favorites()
	{
		$query = $this->db->query("SELECT artist_id, COUNT(user_id) as users FROM watched_artists GROUP BY artist_id ORDER BY users desc LIMIT 10");
		$i = 0;
		foreach($query->result() as $artist)
		{
			$this->db->or_where('artist_id', $artist->artist_id);
		}
		$data['query'] = $this->db->get('artists');
		
		$this->template->write('title', 'Favorite artists chosen by users');
		$this->template->write_view('content', 'artists/favorites', $data, true);
		$this->template->render();
		
	
	}
	
	function view()
	{
		$data['featuredImage'] = false;
		$memcache = new Memcache;
		$memcache->connect('localhost', 11211) or $memcache = false;
		$key = md5($this->uri->uri_string());
		$this->load->model('usermodel_unravel');
		$this->load->model('ArtistModel');
		//if no artist is set redirect them to the index page
		if($this->uri->segment(3) == '') {
			redirect('artists/index');
		}
				
		//query the db by the seo name and get albums for that artist
		$query = $this->ArtistModel->load($this->uri->segment(3));
		
	
		
		if($query->num_rows() > 0) {
			$row = $query->row();
			if($row->verified == 0) {
				$data['query'] = $query;
				$data['title'] = "Unverified artist: " . $row->artist;
				$data['artist'] = $row->artist;
				$data['verified'] = $row->verified;

			} else {
			
				$this->load->helper('cookie');			
				$this->load->helper('date');
				if(!get_cookie('unravel_' . $row->artist_id) && $row->viewcount_expires < now())
				{
					$this->ArtistModel->addToViewcount($row->artist_id, $row->viewcount, $row->viewcount_month);
					$cookie = array(
					                   'name'   => $row->artist_id,
					                   'value'  => $row->artist_id,
					                   'expire' => '86500',
					               );

					set_cookie($cookie); 				
				}
				$keyRandom = md5('lastFmRandom' . $this->uri->uri_string());
				$keyArray = md5('lastFmArray' . $this->uri->uri_string());
				$lastFM = $memcache->get($keyArray);
				if($lastFM == null)
				{						
					//last.fm similar artists
					if(!$xml = @simplexml_load_file('http://ws.audioscrobbler.com/2.0/?method=artist.getsimilar&artist=' . $row->artist . '&api_key=535577a7d5742bd12e157c4689c0d031&limit=6'))
					{
						$xml_array = null;
					} else {
						$xml_array = array();
						foreach($xml->xpath('similarartists/artist/name') as $artist)
						{

							$results = $this->ArtistModel->load(url_title($artist));
							if($results->num_rows() > 0)
							{
								$rowSimilar = $results->row();
								$name = $artist;
								if(strtolower($row->artist_seo_name) != strtolower((string) url_title($name)))
								{
									if($rowSimilar->artist_picture_verified == 1)
									{
										$picture = $rowSimilar->artist_picture;
										$filename = substr($picture, 0, -4);
										$extension = substr($picture, -4);

										$picture = $filename . '_thumb' . $extension;							
										$xml_array[] =  array('artist' => (string) $name, 'picture' => $picture);
									} else {
										$xml_array[] =  array('artist' => (string) $name, 'picture' => NULL);
									}
								}
							}
						}
						
						if(count($xml_array) >= 1 && count($xml_array) <= 3)
						{
							$rand_keys = array_rand($xml_array, count($xml_array));
						} else if(count($xml_array) >= 4) {
							$rand_keys = array_rand($xml_array, 4);
						} else if(count($xml_array) == 0) {
							$rand_keys = NULL;
						}
						$data['random'] = $rand_keys;
						$data['similarArtists'] = $xml_array;
						$memcache->set($keyRandom, $rand_keys, 0, 86400);
						$memcache->set($keyArray, $xml_array, 0, 86400);
					}
				}	else {
					$data['random'] = $memcache->get($keyRandom);
					$data['similarArtists'] = $lastFM;
				}
				//set variables to be passed
				$this->load->model('WatchedModel');
				$id = $this->session->userdata("DX_user_id");
				$data['watching'] = $this->WatchedModel->checkExists('artist', $row->artist_id, $id);
				$data['verified'] = $row->verified;
				$data['id'] = $row->artist_id;
				$data['artist'] = $row->artist;
				$data['artist_seo_name'] = $row->artist_seo_name;
				//check to see if the artist is unverified to set the title
				if($row->featured == 0)
				{
					$data['query'] = $this->ArtistModel->loadAlbums($this->uri->segment(3));
				} else {
					$this->load->model('SongModel');
					$data['query'] = $this->ArtistModel->loadNewestAlbum($this->uri->segment(3));
					$album = $data['query']->row();
					$data['songs'] = $this->SongModel->loadAlbumSongs($album->album_id);
				}
				$row = $data['query']->row();
				
				$this->load->model('SongModel');
				$data['topMonth'] = $this->SongModel->loadTopSongsThisMonth($row->artist_id);
				$data['topAll'] = $this->SongModel->loadTopSongsAll($row->artist_id);
				//since it is set the title 
	
				$data['picVerified'] = $row->artist_picture_verified;
				
				//artist is verified so check if they have a picture
				if($row->artist_picture != null)
				{
					$picture = $row->artist_picture;
					$filename = substr($row->artist_picture, 0, -4);
					$extension = substr($row->artist_picture, -4);

					$data['artistPicture'] = $filename . '_thumb' . $extension;
				} else {
					$data['artistPicture'] = null;
				}	
				
				//check if there are any albums returned for foreach loop in view
				$data['areAlbums'] = false;
				if(isset($row->album))
				{
					$data['areAlbums'] = true;
				}
			
			}//end verified else check		
			if($row->verified == 1 && $row->featured == 1) {
				$this->db->where('artist_id', $row->artist_id);
				$query = $this->db->get('spotlight_artists');
				$featured = $query->row();
				$data['featuredImage'] = site_url() . 'assets/images/public/featured_artist.jpg';
				$data['bio'] = $featured->bio_summary;
				$data['interview'] = $featured->interview_summary;
				$data['web1'] = $featured->website_1;
				$data['web2'] = $featured->website_2;
				$data['web3'] = $featured->website_3;
				$data['influences'] = $featured->influences;
				$data['dates'] = $this->ArtistModel->loadTour($row->artist_id);
				$this->template->write('title', "Viewing featured artist " . $row->artist);
				$this->template->write_view('content', 'artists/featured', $data, true);
				$this->template->render();	
				
			} else {
				$this->template->write('title', "Viewing albums by " . $row->artist);
				$this->template->write_view('content', 'artists/view', $data, true);
				$this->template->render();
			}	
		} else {
			//no results so throw them to 404 page
			$data['error']="Artist Not Found";
			$this->template->write('title', $this->lang->line('error-doesNotExist'));
			$this->template->write_view('content', '404', $data, true);
			$this->template->render();
			  
		} 
	}
  
	function upload_picture()
	{
		$this->dx_auth->check_uri_permissions();
		$artist_seo_name = $this->uri->segment(3);
		$this->load->model('ArtistModel');
		$query = $this->ArtistModel->load($artist_seo_name);
		if($query->num_rows() > 0)
		{
			$artistId = $query->row();
			if($artistId->artist_picture_verified != 1)
			{		
				if(is_dir("/var/www/static.unravelthemusic.com/htdocs/artists/" . $artist_seo_name))
				{
					$d = dir("/var/www/static.unravelthemusic.com/htdocs/artists/" . $artist_seo_name);
				} else {
					mkdir("/var/www/static.unravelthemusic.com/htdocs/artists/" . $artist_seo_name, 0777);
					$d = dir("/var/www/static.unravelthemusic.com/htdocs/artists/" . $artist_seo_name);
				}
				$config['upload_path'] = $d->path;
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size']	= '512';
				$config['max_width']  = '1024';
				$config['max_height']  = '768';
				
				$this->load->library('upload', $config);
			
				if ( ! $this->upload->do_upload())
				{
					$this->session->set_flashdata('flashMessage', $this->upload->display_errors());
					redirect('artists/view/' . $artist_seo_name);

				}	
				else
				{
					
					$results = $this->upload->data();
					if(strtoupper($results['file_ext']) == strtoupper('.jpeg'))
					{
						$results['file_ext'] = '.jpg';
					}					
					$filename = $results['file_path'] . $artist_seo_name . $results['file_ext'];
					rename($results['full_path'], $filename);
					
					$configResize['image_library'] = 'gd2';
					$configResize['source_image'] = $filename;
					$configResize['create_thumb'] = TRUE;
					$configResize['maintain_ratio'] = TRUE;
					$configResize['width'] = 150;
					$configResize['height'] = 125;

					$this->load->library('image_lib', $configResize);

					$this->image_lib->resize();		
					if ( ! $this->image_lib->resize())
					{
						echo $this->image_lib->display_errors();
					}
					
					$this->ArtistModel->uploadPic($artist_seo_name, $artistId->artist_id, $results['file_ext']);



					redirect('artists/view/' . $artistId->artist_seo_name);
				}
			} else {
				$data['error'] = 'You really had to try to get here, therefore you IP, username, and e-mail have all been logged';
				$this->template->write('title', 'Logged');
				$this->template->write_view('content', '404', $data, true);
				$this->template->render();
					
			}
		} else {
			$data['error'] = "no artist by this name";
			$this->template->write('title', $this->lang->line('error-doesNotExist'));
			$this->template->write_view('content', '404', $data, true);
			$this->template->render();
		
		}
		
	} 
	
	function tag()
	{
		$this->dx_auth->check_uri_permissions();
		$this->load->model('ArtistModel');
		$id = $this->session->userdata("DX_user_id");
		//should be removed for ArtistModel type check
		$query = $this->ArtistModel->load($this->uri->segment(3));
		if($query->num_rows() > 0) 
		{
			$artistRow = $query->row();
		

			$this->load->model('WatchedModel');
			$result = $this->WatchedModel->checkExists('artist', $artistRow->artist_id, $id);
			if($result == false)
			{
				$this->WatchedModel->watch('artist', $artistRow->artist_id, $id);
				$response['message'] = 'success';
				print json_encode($response);
			} else {
				$response['message'] = 'fail';
				$response['error'] = 'You have already tagged this artist.';
				print json_encode($response);			
			}
	
	        
		} else {
			$response['message'] = 'fail';
			$response['error'] = $this->lang->line('error-doesNotExist');
			print json_encode($response);
		}
	}
  
	function untag()
	{
		$this->dx_auth->check_uri_permissions();
		$this->load->model('ArtistModel');
		$id = $this->session->userdata("DX_user_id");
		//should be removed for ArtistModel type check
		$query = $this->ArtistModel->load($this->uri->segment(3));
		
		if($query->num_rows() > 0) 
		{
			$artistId = $query->row();
			$this->load->model('WatchedModel');
			$this->WatchedModel->unwatch('artist', $artistId->artist_id, $id);
			$response['message'] = 'success';
			print json_encode($response);   
		} else {
			$response['message'] = 'fail';
			$response['error'] = 'This artist does not exist in our database.';
			print json_encode($response);

		}
	}
  
	function add()
	{
		$this->dx_auth->check_uri_permissions();
		$id = $this->session->userdata("DX_user_id");
		$query = $this->dx_auth->get_profile_field($id, 'notify_by_default');
	
		if ($query->num_rows() == 1)
		{
			$row = $query->row();
			$data['checked'] = $row->notify_by_default;
		}
		if($this->input->post('artist'))
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('artist', 'Artist', 'trim|required|callback_artist_check|artist|xss_clean');
			$this->form_validation->set_rules('alternate', 'Alternate Spelling', 'trim|artist|xss_clean');
			$this->form_validation->set_rules('website', 'Website', 'trim|required|xss_clean|prep_url');
			$this->form_validation->set_rules('wikiPage', 'Wikipedia Page', 'trim|xss_clean');
			if ($this->form_validation->run() == FALSE)
			{
				$this->template->write('title', 'Add an Artist - Errors in Form');
				$this->template->write_view('content', 'artists/add', $data, true);
				$this->template->render();
			}
			else
			{		    
				//load our model so we can test and insert
				$this->load->model('submitmodel');
		    
				//check if this is a final post or not
				//if it is not then we must test
				if($this->input->post('final') == 'false') 
				{
					
					//find all the fields in the artists table and put them in a var
					$fields = $this->db->list_fields('artists');

					//fields we won't be adding anything to
					$excluded_fields = array('viewcount','verified','artist_id', 'artist_picture', 'artist_seo_name', 'artist_alternate', 'artist_website', 'artist_created_by', 'notify');
					//inserting viewcount and verified as constant zeroes
					$insert['artist_seo_name'] = url_title($this->input->post('artist'));
					$insert['artist_picture'] = null;
					$insert['viewcount'] = '0';
					$insert['verified'] = '0';
					$insert['artist_alternate'] = $this->input->post('alternate');
					$insert['artist_website'] = $this->input->post('website');
					$insert['artist_created_by'] = $this->session->userdata('DX_username');
					if(isset($_POST['notify'])) 
					{
						$insert['notify'] = 1;
					} else {
						$insert['notify'] = 0;
					}

					//create an array with all of the posted information
					$artists_data = array ('username' => $this->session->userdata("DX_username"),
		                            'artist' => $this->input->post("artist"),
									'artist_seo_name' => url_title($this->input->post('artist')),
		                            'created_on' => date('Y-n-d G:i:s')
						);
					//insert the posted data into artists_tmp
					$this->db->insert('artists_tmp', $artists_data);
		      
					//we will run verifyNew to make sure there are no exact matches
					$this->submitmodel->verifyNew($this->input->post('artist'), 'artists', '', '');
		      
					//will probalby get back to this but I need to cover the basics again
					//and this will probably get reworked anyways
					/*
					//test for any partial matches
					$checks = $this->submitmodel->verifySpelling($this->input->post('artist'), 'artists', '');
					//if we return anything we must load the view
					if(count($checks) > 0) {
					//include data information and database results
					$data['name'] = $this->input->post('artist');
					$data['checks'] = $checks;
					//load the view
					$this->template->load('template_main', 'artists_check', $data);
					}//end checks count
					*/
				}//end input->post('final') check
				//the post has made it this far or this is after a resubmit on partial check page
				//check to make sure the form wasn't altertered on the partial check page
				$this->submitmodel->checkTmp($this->input->post('artist'));
				//finally insert it
				$this->submitmodel->submit("artists", $fields, $insert, $excluded_fields);
		      
				//redirect to the add page with a thank you note.
				$this->session->set_flashdata('flashMessage', $this->lang->line('addThankYou'));
				redirect('artists/add');
			}		
		} else {

			$data['title'] = "Add an artist";
			$this->template->write('title', 'Add an Artist');
			$this->template->write_view('content', 'artists/add', $data, true);
			$this->template->render();
		}
		

	}
	
	function edit()
	{
		$this->dx_auth->check_uri_permissions();
		if($this->uri->segment(3))
		{
			$artist = $this->uri->segment(3);
		} else {
			$artist = $this->input->post('oldArtist');
		}
		$this->db->where('artist_seo_name', $artist);
		$query = $this->db->get('artists');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$data['artist_id'] = $row->artist_id;
			$data['artist'] = $row->artist;
			$data['verified'] = $row->verified;
			$data['website'] = $row->artist_website;
			$data['wiki'] = $row->wiki_page;
			$data['createdBy'] = $row->artist_created_by;
			$picture = $row->artist_picture;
			if($picture != null)
			{
			list($filename, $extension) = explode(".", $picture);

			$data['picture'] = $filename . '_thumb' . $extension;	
			} else {
			$data['picture'] = null;
			}
			$data['picVerified'] = $row->artist_picture_verified;
			
			//check to see if the form has posted
			if($this->input->post('artist_id'))
			{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('artist_id', 'Artist Id', 'required|integer');
				$this->form_validation->set_rules('artist', 'Artist', 'trim|required|callback_artist_check|artist|xss_clean');
				$this->form_validation->set_rules('picVerified', 'PicVerified', 'integer|required|trim');
				$this->form_validation->set_rules('website', 'Website', 'trim|xss_clean|prep_url');
				$this->form_validation->set_rules('wiki', 'Wiki', 'trim|xss_clean');
				$this->form_validation->set_rules('createdBy', 'Created By', 'trim|required|xss_clean|alpha_dash');
				if ($this->form_validation->run() == FALSE)
				{
					$data['picture'] = null;
					$this->template->write('title', 'Edit Artist - Errors in Form');
					$this->template->write_view('content', 'artists/edit', $data, true);
					$this->template->render();
				}
				else
				{		
					if(substr($this->input->post('wiki'), 0, 29) == 'http://en.wikipedia.org/wiki/')
					{
						$wiki = $this->input->post('wiki');
					} else {
						$wiki = 'http://en.wikipedia.org/wiki/' . $this->input->post('wiki');
					}
					$data = array(
						'artist' 			=> $this->input->post('artist'),
						'artist_seo_name' 	=> url_title($this->input->post('artist')),
						'artist_website' 			=> $this->input->post('website'),
						'artist_picture_verified' => $this->input->post('picVerified'),
						'artist_created_by'	=> $this->input->post('createdBy'),
						'wiki_page' => $wiki
						);
					$this->db->where('artist_id', $this->input->post('artist_id'));
					$this->db->update('artists', $data);
					redirect('artists/view/' . url_title($this->input->post('artist')));
				}
			} else {
				$this->template->write('title', 'Editing artist: ' . $row->artist);
				$this->template->write_view('content', 'artists/edit', $data, true);
				$this->template->render();	
			}
		} else {
			$data['error'] = 'artist does not exist';
			$this->template->write('title', $this->lang->line('error-doesNotExist'));
			$this->template->write_view('content', '404', $data, true);
			$this->template->render();	
		}
	
	
	}
	
	function random()
	{
		$max = $this->db->count_all('artists');
		$id = rand(1, $max);
		$id2 = rand(1, $max);
		$id3 = rand(1, $max);
		$this->db->where('verified', 1);
		$this->db->where('artist_id', $id);
		$query = $this->db->get('artists', 1);
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			redirect('artists/view/' . $row->artist_seo_name);
		} else {
			$this->db->where('verified', 1);
			$this->db->where('artist_id', $id2);
			$query = $this->db->get('artists', 1);
			$query = $this->db->get('artists', 1);
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				redirect('artists/view/' . $row->artist_seo_name);			
			} else {
				$data['error'] = 'we have big problems';
				$this->template->write('title', 'we have big time problems');
				$this->template->write_view('content', '404', $data, true);
				$this->template->render();	
			}
		}
		
	}
	
	function artist_check($str)
	{
		if ($str == 'Enter an artist.' || $str == 'admin' || $str == 'administrator' || $str == 'powerUser')
		{
			$this->form_validation->set_message('artist_check', 'Invalid  arist name');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
}
?>

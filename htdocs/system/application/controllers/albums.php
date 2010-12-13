<?php

class Albums extends Controller {

	function __construct()
	{
		parent::Controller();
		//$this->output->enable_profiler();
		
	}

	function index()
	{
		$this->db->join('artists', 'artists.artist_id = albums.artist_id');
		$data['query'] = $this->db->get('albums', 10);

		$this->template->write('title', 'Albums');
		$this->template->write_view('content', 'albums/index', $data, TRUE);
		$this->template->render();
	}

	function catalog()
	{
		$this->load->library('pagination');
	
		$letter = $this->uri->segment(3);
		$data['letter'] = substr($letter, 0, 1);
		if (!ereg('^[A-Za-z0-9]$', $data['letter'])) {
			redirect('albums/');

		}
		$this->load->model('AlbumModel');
		$config['per_page'] = '100';
		$config['num_links'] = 6;
		$data['results'] = $this->AlbumModel->loadByLetter($data['letter'], $config['per_page'],$this->uri->segment(4));
	
		$config['base_url'] = base_url() . '/albums/catalog/' . $data['letter'] . '/';
		$config['uri_segment'] = 4;
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
	
		$this->db->like('album', $data['letter'], 'after');
		$config['total_rows'] = $this->db->count_all_results('albums');
		
		$this->pagination->initialize($config);
		
		$data['links'] = $this->pagination->create_links();	

		$this->template->write('title', 'Viewing albums starting with ' . $data['letter']);
		$this->template->write_view('content', 'albums/catalog', $data, TRUE);
		$this->template->render();
	}
  
	function view()
	{
		$artist_seo_name = $this->uri->segment(3);
		$album_seo_name = $this->uri->segment(4);
		$this->load->model('AlbumModel');
		$this->load->model('ArtistModel');		
		$this->load->model('WatchedModel');
		$id = $this->session->userdata("DX_user_id");
		$albumId = $this->AlbumModel->loadFull($album_seo_name, $artist_seo_name);

		if($albumId->num_rows() > 0)
		{
			$albumId = $albumId->row();
			if($albumId->verified == 0)
			{
				$data['error'] = $this->lang->line('error-notVerified');
				$this->template->write('title', $this->lang->line('error-notVerified'));
				$this->template->write_view('content', '404', $data, TRUE);
				$this->template->render();
			}     			
			$data['watchingArtist'] = $this->WatchedModel->checkExists('artist', $albumId->artist_id, $id);	
			$data['watchingAlbum'] = $this->WatchedModel->checkExists('album', $albumId->album_id, $id);
			$data['artist'] = $albumId->artist;
			$data['album'] = $albumId->album;
			$data['album_id'] = $albumId->album_id;
			$data['artist_seo_name'] = $albumId->artist_seo_name;
			$data['album_seo_name'] = $albumId->album_seo_name;
			$data['locked'] = $albumId->locked;
			$this->load->model('SongModel');
			$data['query'] = $this->SongModel->loadAlbumSongs($albumId->album_id);
			if($data['query']->num_rows() > 0)
			{
				$row = $data['query']->row();
				if($albumId->album_picture != null)
				{
					$data['albumPicture'] = $albumId->album_picture;

				} else {
					$data['albumPicture'] = null;
				}
				$data['picVerified'] = $albumId->album_picture_verified;
				$this->template->write('title', 'Viewing songs in the album ' . $albumId->album);
				$this->template->write_view('content', 'albums/view', $data, TRUE);
				$this->template->render();
			} else {
				$this->session->set_flashdata('flashMessage', $this->lang->line('error-noSongsAddedYet'));
				redirect('songs/add/' . $artist_seo_name . '/' . $album_seo_name);
			}
		}//album id check
		else {
			$this->session->set_flashdata('flashMessage', $this->lang->line('error-albumDoesNotExist'));
			redirect('artists/view/' . $artist_seo_name);
		}
	}
	
	function edit()
	{
		$this->dx_auth->check_uri_permissions();
		if($this->uri->segment(3) && $this->uri->segment(4))
		{
			$artist = $this->uri->segment(3);
			$album = $this->uri->segment(4);
		} else {
			$artist = url_title($this->input->post('artist'));
			$album = url_title($this->input->post('oldAlbum'));
		}
	
		$this->load->model('AlbumModel');
		$query = $this->AlbumModel->loadFull($album, $artist);
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$data['album_id'] = $row->album_id;
			$data['artist'] = $row->artist;
			$data['album'] = $row->album;
			$data['releaseYear'] = $row->release_date;;
			$data['questionable'] = $row->questionable;
			$data['locked'] = $row->locked;
			$data['createdBy'] = $row->artist_created_by;
			$picture = $row->album_picture;
			if($picture != null)
			{
				$filename = substr($picture, 0, -4);
				$extension = substr($picture, -4);

				$data['picture'] = $filename . '_thumb' . $extension;	
			} else {
				$data['picture'] = null;
			}
			$data['picVerified'] = $row->album_picture_verified;
			
			//check to see if the form has posted
			if($this->input->post('submit'))
			{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('album_id', 'Album Id', 'required|integer');
				$this->form_validation->set_rules('album', 'Album', 'trim|required|album|xss_clean');
				$this->form_validation->set_rules('picVerified', 'PicVerified', 'integer|required|trim');
				$this->form_validation->set_rules('releaseYear', 'Release Year', 'integer|required|exact_length[4]');
				$this->form_validation->set_rules('questionable', 'Questionable', 'trim|xss_clean|integer|exact_length[1]');
				$this->form_validation->set_rules('locked', 'Locked', 'trim|xss_clean|integer|exact_length[1]');
				$this->form_validation->set_rules('createdBy', 'Created By', 'trim|required|xss_clean|alpha_dash');
				if ($this->form_validation->run() == FALSE)
				{
					$data['picture'] = null;
					$this->template->write('title', 'Edit Album - Errors in Form');
					$this->template->write_view('content', 'albums/edit', $data, true);
					$this->template->render();
				}
				else
				{		
					$data = array(
							'album' 			=> $this->input->post('album'),
							'album_seo_name' 	=> url_title($this->input->post('album')),
							'release_date' 			=> $this->input->post('releaseYear'),
							'questionable'		=> $this->input->post('questionable'),
							'locked'			=> $this->input->post('locked'),
							'album_picture_verified' => $this->input->post('picVerified'),
							'created_by'	=> $this->input->post('createdBy')
						);
					$this->db->where('album_id', $this->input->post('album_id'));
					$this->db->update('albums', $data);
					redirect('albums/view/' . $row->artist_seo_name . '/' . url_title($this->input->post('album')));
				}
			} else {
				$this->template->write('title', 'Editing album: ' . $row->album);
				$this->template->write_view('content', 'albums/edit', $data, true);
				$this->template->render();	
			}
		} else {
			$data['error'] = 'artist-album combination does not exist';
			$this->template->write('title', $this->lang->line('error-doesNotExist'));
			$this->template->write_view('content', '404', $data, true);
			$this->template->render();	
		}
	
	
	}
  
	function add()
	{
		$this->dx_auth->check_uri_permissions();
		$this->load->library('form_validation');
		$this->form_validation->set_rules('year', 'Year', "trim|xss_clean|exact_length[4]|numeric|required");
		$this->form_validation->set_rules('album', 'Album', "required|album|trim|xss_clean");
		
		$this->load->model('ArtistModel');
	    $data['title'] = "Add an Album";
	    $artist = $this->uri->segment(3);
	    //don't let the user suggest nothing
	    if(empty($artist)) {
	      die("you must specifiy an artist.");
	    }
	    $query = $this->ArtistModel->load($artist);
	    if($query->num_rows() > 0)
	    {
			$artistId = $query->row();
			if($artistId->verified == 1)
			{
				$data['artist_seo_name'] = $artistId->artist_seo_name;
				$data['artist'] = $artistId->artist;
				$data['id'] = $artistId->artist_id;
				if($this->input->post('album', TRUE))
				{
					if ($this->form_validation->run() == FALSE)
					{
						$this->template->write('title', 'Errors when adding artist');
						$this->template->write_view('content', 'albums/add', $data, TRUE);
						$this->template->render();
					} else {
						$userAlbum = $this->input->post('album');
					    $this->load->model('AlbumModel');

					    $insert['artist_id'] = $artistId->artist_id;
					    $insert['release_date'] = $this->input->post('year');
						$insert['album'] = $this->input->post('album');
						$insert['album_seo_name'] = url_title($this->input->post('album'));
						$insert['created_by'] = $this->session->userdata('DX_username');
						$insert['album_picture'] = NULL;
						$insert['questionable'] = 1;
						$questionable = 1;
					
						if(!$this->AlbumModel->checkExists($insert['album_seo_name'], $insert['artist_id']))
						{
							
							$this->AlbumModel->addNew($insert);
							//begin soap api call
							$wsdl = "http://lyricwiki.org/server.php?wsdl";
							$ctx = stream_context_create(array(
								'http' => array(
									'timeout' => 3
									)
								)
							);

							try {
								if(!@file_get_contents($wsdl, 0, $ctx)) {
									throw new SoapFault('Server', 'No WSDL found at ' . $wsdl);
								}
								$client = new SoapClient($wsdl);					 
								$result = $client->getArtist($artistId->artist); 
								foreach($result['albums'] as $album)
								{
									
									if($album->album == $userAlbum)
									{
										$this->load->model('AlbumModel');
										$query = $this->AlbumModel->loadExtended($userAlbum);
										if($query->num_rows() > 0)
										{
											$row = $query->row();
											$questionable = 0;
											
											$data = array(
														'questionable' => 0
													);
											$this->db->where('album_id', $row->album_id);
											$this->db->update('albums', $data);											
											foreach($album->songs as $song)
											{
											
												$data = array(
													'album_id' => $row->album_id,
													'artist_id' => $row->artist_id,
													'song' => $song,
													'song_seo_name' => url_title($song),
													);
												$this->db->insert('songs', $data);										
											}
										}
									}
								}
										
							
							
							} catch (SoapFault $e) {
								echo('no api');
							}//end soap api call

							if($questionable == 0)
							{
								$this->load->model('HomeModel');
								$this->HomeModel->addAlbumToFeed($artistId->artist, $userAlbum);
							}							
							
							redirect('albums/view/' . $artistId->artist_seo_name . '/' . url_title($this->input->post('album')));
						} else {
							redirect('albums/view/' . $artistId->artist_seo_name . '/' . url_title($this->input->post('album')));
						}
						
					   
					}
				} else {

						$this->template->write('title', 'Add an album');
						$this->template->write_view('content', 'albums/add', $data, TRUE);
						$this->template->render();		
				
				}
			} else {
				$data['error'] = $this->lang->line('error-notVerified');
				$this->template->write('title', $this->lang->line('error-notVerified'));
				$this->template->write_view('content', '404', $data, TRUE);
				$this->template->render();	
			}
		} else {
			$data['error'] = $this->lang->line('error-doesNotExist');
			$this->template->write('title', $this->lang->line('error-doesNotExist'));
			$this->template->write_view('content', '404', $data, TRUE);
			$this->template->render();	
		}

	}
	
	function upload_picture()
	{
		$this->dx_auth->check_uri_permissions();
		$artist = $this->uri->segment(3);
		$album = $this->uri->segment(4);
		$this->load->model('ArtistModel');
		$this->load->model('AlbumModel');
		$query = $this->ArtistModel->load($artist);
		if($query->num_rows() > 0)
		{
			$artistId = $query->row();
			$albumId = $this->AlbumModel->load($album, $artistId->artist_id);
			if(isset($albumId->album_id))
			{
				if($albumId->album_picture_verified != 1)
				{
					if(is_dir("/var/www/static.unravelthemusic.com/htdocs/albums/" . $artist))
					{
						$d = dir("/var/www/static.unravelthemusic.com/htdocs/albums/" . $artist);
					} else {
						mkdir("/var/www/static.unravelthemusic.com/htdocs/albums/" . $artist, 0777);
						$d = dir("/var/www/static.unravelthemusic.com/htdocs/albums/" . $artist);
					}
					$config['upload_path'] = $d->path;
					$config['allowed_types'] = 'gif|jpg|png';
					$config['max_size']	= '512';
					$config['max_width']  = '1024';
					$config['max_height']  = '768';
					
					$this->load->library('upload', $config);
				
					if ( ! $this->upload->do_upload())
					{

						$this->session->set_userdata('error', $this->upload->display_errors());
						redirect('albums/view/' . $artistId->artist_seo_name . '/' . $albumId->album_seo_name);

					}	
					else
					{
						
						$results = $this->upload->data();
						if(strtoupper($results['file_ext']) == strtoupper('.jpeg'))
						{
							$results['file_ext'] = '.jpg';
						}
						$filename = $results['file_path'] . $album . $results['file_ext'];
						rename($results['full_path'], $filename);
						
						$configResize['image_library'] = 'gd2';
						$configResize['source_image'] = $filename;
						$configResize['create_thumb'] = TRUE;
						$configResize['maintain_ratio'] = false;
						$configResize['width'] = 120;
						$configResize['height'] = 120;

						$this->load->library('image_lib', $configResize);

						$this->image_lib->resize();		
						if ( ! $this->image_lib->resize())
						{
							echo $this->image_lib->display_errors();
						}
						$this->AlbumModel->uploadPic($artist, $album, $albumId->album_id, $results['file_ext']);

			


						redirect('albums/view/' . $artistId->artist_seo_name . '/' . $albumId->album_seo_name);
					}
				} else {
					$data['error'] = $this->lang->line('loggedIP');
					$this->template->write('title', $this->lang->line('loggedIP'));	
					$this->template->write_view('content', '404', $data, true);
					$this->template->render();
				}
			} else {
				$data['error'] = $this->lang->line('error-albumDoesNotExist');
				$this->template->write('title', $this->lang->line('error-albumDoesNotExist'));	
				$this->template->write_view('content', '404', $data, true);
				$this->template->render();
			}
		} else {
			$data['error'] = $this->lang->line('error-doesNotExist');
			$this->template->write('title',$this->lang->line('error-doesNotExist'));	
			$this->template->write_view('content', '404', $data, true);
			$this->template->render();
		}
		
	} 
	function random()
	{
		$max = $this->db->count_all('albums');
		$id = rand(1, $max);
		$id2 = rand(1, $max);
		$id3 = rand(1, $max);
		$this->db->join('artists', 'artists.artist_id = albums.artist_id');
		$this->db->where('album_id', $id);
		$query = $this->db->get('albums', 1);
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			redirect('albums/view/' . $row->artist_seo_name . '/' . $row->album_seo_name);
		} else {
			$this->db->join('artists', 'artists.artist_id = albums.artist_id');
			$this->db->where('album_id', $id2);
			$query = $this->db->get('albums', 1);		
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				redirect('albums/view/' . $row->artist_seo_name . '/' . $row->album_seo_name);			
			
			} else {
			
				$data['error'] = 'we have big problems';
				
				$this->template->write('title', 'We have a big boo-boo');	
				$this->template->write_view('content', '404', $data, true);
				$this->template->render();
			}
		}

	}

	function tag()
	{
		$this->dx_auth->check_uri_permissions();
		$this->load->model('ArtistModel');
		$this->load->model('AlbumModel');
		$id = $this->session->userdata("DX_user_id");
		//should be removed for ArtistModel type check
		$query = $this->ArtistModel->load($this->uri->segment(3));
		if($query->num_rows() > 0) 
		{
			$artistRow = $query->row();
			$albumRow = $this->AlbumModel->load($this->uri->segment(4), $artistRow->artist_id);
			if(isset($albumRow->album_id))
			{
				$this->load->model('WatchedModel');
				$result = $this->WatchedModel->checkExists('album', $albumRow->album_id, $id);
				if($result == false)
				{
					$this->WatchedModel->watch('album', $albumRow->album_id, $id);
					$response['message'] = 'success';
					print json_encode($response);					
				} else {
					$response['message'] = 'fail';
					$response['error'] = 'You have already tagged this album.';
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
  
	function untag()
	{
		$this->dx_auth->check_uri_permissions();
		$this->load->model('ArtistModel');
		$this->load->model('AlbumModel');
		$id = $this->session->userdata("DX_user_id");
		//should be removed for ArtistModel type check
		$query = $this->ArtistModel->load($this->uri->segment(3));
		if($query->num_rows() > 0) 
		{
			$artistRow = $query->row();
			$albumRow = $this->AlbumModel->load($this->uri->segment(4), $artistRow->artist_id);
			if(isset($albumRow->album_id))
			{		
				$this->load->model('WatchedModel');
				$this->WatchedModel->unwatch('album', $albumRow->album_id, $id);
				$response['message'] = 'success';
				print json_encode($response);		
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
		$query = $this->db->query("SELECT album_id, COUNT(user_id) as users FROM watched_albums GROUP BY album_id ORDER BY users desc LIMIT 10");
		foreach($query->result() as $album)
		{
			$this->db->or_where('album_id', $album->album_id);
		}
		$this->db->join('artists', 'artists.artist_id = albums.artist_id');
		$data['query'] = $this->db->get('albums');
			$this->template->write('title', 'Favorite albums chosen by users');	
			$this->template->write_view('content', 'albums/favorites', $data, true);
			$this->template->render();
		
	
	}	
}
  

?>

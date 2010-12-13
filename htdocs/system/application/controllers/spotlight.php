<?php

class Spotlight extends Controller {

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
	
	function interview()
	{
		$artist = $this->uri->segment(3);
		$memcache = new Memcache;
		$memcache->connect('localhost', 11211) or $memcache = false;
		$interview = $memcache->get($artist . 'spotlightInterview');		
		
		if($interview == null)
		{		
			$this->load->model('ArtistModel');
			$query = $this->ArtistModel->load($artist);
			
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$artistId = $row->artist_id;
				$this->load->model('SpotlightModel');
				$spotlight = $this->SpotlightModel->load($artistId);
				
				if($spotlight->num_rows() > 0)
				{	
					$spotlight = $spotlight->row();
					$data['artist'] = $row->artist;
					$data['interview'] = $spotlight->interview;
										
					
					$memcache->set($artist . 'spotlightInterview', $data, 0, 30000);
					$this->template->write('title', $row->artist . "'s Spotlight Interview");
					$this->template->write_view('content', 'spotlight/interview', $data, true);
					$this->template->render();
				} else {
					redirect('/artists/view/' . $row->artist_seo_name);
				}
			} else {
				$data['error'] = 'This artist could not be found';
				$this->template->write('title', 'Could not find artist');
				$this->template->write_view('content', '404', $data, true);
				$this->template->render();		
			}	
		} else {
			$data['artist'] = $interview['artist'];
			$data['interview'] = $interview['interview'];
			$this->template->write('title', $data['artist'] . "'s Spotlight Interview");
			$this->template->write_view('content', 'spotlight/interview', $data, true);
			$this->template->render();			
		}
	}
	
	function bio()
	{
		$artist = $this->uri->segment(3);
		
		$this->load->model('ArtistModel');
		$query = $this->ArtistModel->load($artist);
		
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$artistId = $row->artist_id;
			$this->load->model('SpotlightModel');
			$spotlight = $this->SpotlightModel->load($artistId);
			
			if($spotlight->num_rows() > 0)
			{	
				$data['artist'] = $query;
				$data['spotlight'] = $spotlight;
				$this->template->write('title', $row->artist . "'s Biography");
				$this->template->write_view('content', 'spotlight/biography', $data, true);
				$this->template->render();
			} else {
				redirect('/artists/view/' . $row->artist_seo_name);
			}
		} else {
			$data['error'] = 'This artist could not be found';
			$this->template->write('title', 'Could not find artist');
			$this->template->write_view('content', '404', $data, true);
			$this->template->render();		
		}		
	}
	
	function albums()
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
			if($row->featured == 0)
			{
				redirect('/artists/view/' . $row->artist_seo_name);
			}
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
				$data['query'] = $this->ArtistModel->loadAlbums($this->uri->segment(3));

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
			$this->template->write('title', "Viewing albums by " . $row->artist);
			$this->template->write_view('content', 'artists/view', $data, true);
			$this->template->render();
			
		} else {
			//no results so throw them to 404 page
			$data['error']="Artist Not Found";
			$this->template->write('title', $this->lang->line('error-doesNotExist'));
			$this->template->write_view('content', '404', $data, true);
			$this->template->render();
			  
		} 	
	
	
	}
}
?>

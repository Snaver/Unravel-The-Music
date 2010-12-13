<?php
	
class Search extends Controller {

	function __construct()
	{
		parent::Controller();
		//$this->output->enable_profiler(TRUE); 		
	}
	
	function index()
	{
		$this->template->write('title', 'Search Unravel');
		$this->template->write_view('content', 'search/index', $data, true);
		$this->template->render();
	}
	function artists()
	{
		$data['search'] = null;
		$data['searched'] = false;
		
		$this->load->library('pagination');
		$offset = $this->uri->segment(4);
		$config['per_page'] = '15';			
		$_POST['uri'] = $this->uri->segment(3);		
		if($this->input->post('artist') || $this->input->post('uri'))
		{
			$searchPar = $this->input->post('artist');
			if(!$this->input->post('artist'))
			{
				$searchPar = str_replace('%20', ' ', $this->input->post('uri'));
			}
			//load form_validation class
			$this->load->library('form_validation');

			//set the rules for lyrics
			$this->form_validation->set_rules('artist', 'Artist', 'trim|xss_clean|artist|disallowed_words|min_length[3]');
			$this->form_validation->set_rules('uri', 'Artist', 'trim|xss_clean|artist|min_length[3]');
			
			//run the validation
			if($this->form_validation->run() == FALSE)
			{
				$this->template->write('title', 'Search Unravel: Artists');
				$this->template->write_view('content', 'search/artist', $data, true);
				$this->template->render();
			} else {

				$data['areAlbums'] = false;
				$this->load->model('ArtistModel');
				$data['search'] = $searchPar;
				$data['searched'] = true;
				
				$data['exactMatch'] = false;
				$data['partialMatch'] = false;
				$data['spellingMatch'] = false;
				$data['links'] = null;
				$data['results'] = $this->ArtistModel->loadAlbums(url_title($searchPar));
				if($data['results']->num_rows() > 0)
				{
					$data['exactMatch'] = true;
				} else {
					$otherResults = $this->ArtistModel->search($searchPar, $config['per_page'], $offset);
					if($otherResults->num_rows() > 0)
					{
						$data['partialMatch'] = true;
						$data['otherResults'] = $otherResults;
					}
					$config['base_url'] = base_url() . '/search/artists/' . $searchPar . '/';
					$config['uri_segment'] = 4;
					$config['first_link'] = 'First';
					$config['last_link'] = 'Last';
					$config['num_links'] = 6;
					$data['totalResults'] = $this->ArtistModel->countSearch($searchPar);
					$config['total_rows'] = $data['totalResults'];				
					$this->pagination->initialize($config);
					$data['links'] = $this->pagination->create_links();						
				}
				if($data['exactMatch'] == false)
				{
					$data['spelling'] = $this->ArtistModel->checkSpelling($searchPar);
					if($data['spelling']->num_rows() > 0)
					{
						$data['spellingMatch'] = true;
					}
				
				}
				
							

				//last.fm similar artists
				if(!$xml = @simplexml_load_file('http://ws.audioscrobbler.com/2.0/?method=artist.getsimilar&artist=' . $searchPar . '&api_key=535577a7d5742bd12e157c4689c0d031&limit=6'))
				{
					$xml_array = null;
					$data['similarArtists'] = $xml_array;
				} else {
					$xml_array = array();
					foreach($xml->xpath('similarartists/artist/name') as $artist)
					{
						if(strtolower($searchPar) != strtolower((string) url_title($artist)))
						{
							$xml_array[] =  array('artist' => (string) $artist);
						}
					}
					$data['similarArtists'] = $xml_array;	
				}
				
					
				$this->template->write('title', 'Search results for: ' . $searchPar);
				$this->template->write_view('content', 'search/artist', $data, true);
				$this->template->render();
			}
		} else {
			$this->template->write('title', 'Search Unravel: Artists');
			$this->template->write_view('content', 'search/artist', $data, true);
			$this->template->render();
		}
	}
	
	function albums()
	{
		$this->load->library('pagination');
		$offset = $this->uri->segment(4);
		$config['per_page'] = '15';	
		$data['search'] = '';
		$data['title'] = 'Search Unravel: Albums';
		$_POST['uri'] = $this->uri->segment(3);
		if($this->input->post("album") || $this->input->post('uri'))
		{
			
			$searchPar = $this->input->post("album");
			if(!$this->input->post('album'))
			{
				$searchPar = str_replace('%20', ' ', $this->input->post('uri'));
			}
			$data['noSearch'] = true;
			$data['title'] = 'Search results for: ' . $searchPar;
			//load form_validation class
			$this->load->library('form_validation');

			//set the rules for lyrics
			$this->form_validation->set_rules('album', 'Album', 'trim|xss_clean|album|disallowed_words|min_length[3]');
			$this->form_validation->set_rules('uri', 'Album', 'trim|xss_clean|album|disallowed_words|min_length[3]');
			
			//run the validation
			if($this->form_validation->run() == FALSE)
			{
				$data['search'] = $searchPar;
				$this->template->write('title', 'Search Unravel: Albums');
				$this->template->write_view('content', 'search/album', $data, true);
				$this->template->render();
			} else {
				$data['noSearch'] = false;
				$this->load->model('AlbumModel');

				$data['search'] = $searchPar;
				$data['results'] = $this->AlbumModel->search($searchPar, $config['per_page'], $offset);
				
				$config['base_url'] = base_url() . '/search/albums/' . $searchPar . '/';
				$config['uri_segment'] = 4;
				$config['first_link'] = 'First';
				$config['last_link'] = 'Last';
				$config['num_links'] = 6;
				$data['totalResults'] = $this->AlbumModel->countSearch($searchPar);
				$config['total_rows'] = $data['totalResults'];				
				$this->pagination->initialize($config);
				$data['links'] = $this->pagination->create_links();						
		
				$this->template->write('title', 'Search Results for :' . $searchPar);
				$this->template->write_view('content', 'search/album', $data, true);
				$this->template->render();
			}
		} else {
			$data['noSearch'] = true;		
		
			$this->template->write('title', 'Search Unravel: Albums');
			$this->template->write_view('content', 'search/album', $data, true);
			$this->template->render();		
		}

	}
	
	function songs()
	{

		$this->load->library('pagination');
		$offset = $this->uri->segment(4);
		$config['per_page'] = '15';
		$data['search'] = '';
		$data['title'] = 'Search Unravel: Songs';
		$data['noSearch'] = true;
		if($this->input->post("song") || $this->uri->segment(3))
		{	
			if(!$this->input->post("song"))
			{
				$_POST['uri'] = $this->uri->segment(3);
				$searchPar = str_replace('%20', ' ', $this->uri->segment(3));
			} else {
				
				
				$searchPar = $this->input->post("song");
			}

			$data['title'] = 'Search results for: ' . $searchPar;
			//load form_validation class
			$this->load->library('form_validation');

			//set the rules for lyrics
			$this->form_validation->set_rules('song', 'Song', 'trim|xss_clean|song|disallowed_words|min_length[3]');
			$this->form_validation->set_rules('uri', 'Song', 'trim|xss_clean|song|disallowed_words|min_length[3]');
	
			//run the validation
			if($this->form_validation->run() == FALSE && !$this->uri->segment(3))
			{
	
				$this->template->write('title', 'Search Unravel: Songs');
				$this->template->write_view('content', 'search/song', $data, true);
				$this->template->render();
			} else {
				$data['noSearch'] = false;
				$this->load->model('SongModel');	
				$data['search'] = $searchPar;
	
				$data['results'] = $this->SongModel->search($searchPar, $config['per_page'], $offset);
				
				$config['base_url'] = base_url() . '/search/songs/' . $searchPar . '/';
				$config['uri_segment'] = 4;
				$config['first_link'] = 'First';
				$config['last_link'] = 'Last';
				$config['num_links'] = 6;
				$data['totalResults'] = $this->SongModel->countSearch($searchPar);
				$config['total_rows'] = $data['totalResults'];

				$this->pagination->initialize($config);
				$data['links'] = $this->pagination->create_links();					
				
				$this->template->write('title', 'Search results for: ' . $searchPar);
				$this->template->write_view('content', 'search/song', $data, true);
				$this->template->render();
			}
		} else {
				
		$this->template->write('title', 'Search Unravel: Songs');
		$this->template->write_view('content', 'search/album', $data, true);
		$this->template->render();
		}
	}
	
	function journals()
	{
		$this->load->library('pagination');
		$offset = $this->uri->segment(4);
		$config['per_page'] = '15';
		$data['search'] = '';
		$data['title'] = 'Search Unravel: Journals';
		$data['noSearch'] = true;
		if($this->input->post("journal") || $this->uri->segment(3))
		{	
			if(!$this->input->post("journal"))
			{
				$_POST['uri'] = $this->uri->segment(3);
				$searchPar = str_replace('%20', ' ', $this->uri->segment(3));
			} else {
				
				
				$searchPar = $this->input->post("journal");
			}
			//load form_validation class
			$this->load->library('form_validation');

			//set the rules for lyrics
			$this->form_validation->set_rules('journal', 'Song', 'trim|xss_clean|song|disallowed_words|min_length[3]');
			$this->form_validation->set_rules('uri', 'Album', 'trim|xss_clean|album|disallowed_words|min_length[3]');
	
			//run the validation
			if($this->form_validation->run() == FALSE && !$this->uri->segment(3))
			{
	
				$this->template->write('title', 'Search Unravel: Journals');
				$this->template->write_view('content', 'search/journal', $data, true);
				$this->template->render();
			} else {
				$data['noSearch'] = false;
				$this->load->model('JournalModel');	
				$data['search'] = $searchPar;
	
				$data['results'] = $this->JournalModel->search($searchPar, $config['per_page'], $offset);
				
				$config['base_url'] = base_url() . '/search/songs/' . $searchPar . '/';
				$config['uri_segment'] = 4;
				$config['first_link'] = 'First';
				$config['last_link'] = 'Last';
				$config['num_links'] = 6;
				$data['totalResults'] = $this->JournalModel->countSearch($searchPar);
				$config['total_rows'] = $data['totalResults'];

				$this->pagination->initialize($config);
				$data['links'] = $this->pagination->create_links();					
				
				$this->template->write('title', 'Search results for: ' . $searchPar);
				$this->template->write_view('content', 'search/journal', $data, true);
				$this->template->render();
			}
		} else {
				
		$this->template->write('title', 'Search Unravel: Songs');
		$this->template->write_view('content', 'search/journal', $data, true);
		$this->template->render();
		}

	
	}
}
?>
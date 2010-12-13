<?php

//////////////////////////////////////////////////
/*            Feed Information ///////////////////
* Type 0 is a system warning/update///////////////
* Type 1 is a new album has been added////////////
* Type 2 is a song that has been commented on/////
* Type 3 is a song that has been added ///////////
*/////////////////////////////////////////////////
class Home extends Controller {
       function __construct()
       {
		parent::Controller();
		//$this->output->enable_profiler();
		$this->load->library('DX_Auth');
		
	}
	
	function index()
	{
		$data['memcache'] = new Memcache;
		$data['memcache']->pconnect('localhost', 11211) or $memcache = false;
		//$this->output->cache(60);
		$this->load->model('dx_auth/UserModel', 'user');
		$this->load->model('homemodel');
		$data['title'] = "Home";

		if($this->dx_auth->is_logged_in())
		{
			$query = $this->user->get_user_by_id($this->session->userdata('DX_user_id'));
			//now we get personalized

			$row = $query->row();
			$data['feedFilter'] = $row->feed_filter;
			$data['feedView'] = $row->feed_view;
			if($data['feedFilter'] == 'tagged')
			{
				$data['feedList'] = null;
				$data['feedList'] = $data['memcache']->get('feed' . $data['feedFilter'] . $row->feed_view);
				if($data['feedList'] == null)
				{			
					$data['albums'] = $this->homemodel->getTagged();
				}
			} else {
				$data['feedList'] = null;
				$data['feedList'] = $data['memcache']->get('feed' . $data['feedFilter'] . $row->feed_view);
				if($data['feedList'] == null)
				{
					$data['albums'] = $this->homemodel->getFeedNoUser();
				}
			}
			//$this->output->enable_profiler();
			$this->template->write('title', 'Home');
			$this->template->write_view('content', 'home/index', $data, TRUE);
			$this->template->render();
			
			
		} else {
			$data['feedFilter'] = 'all';
			$this->load->helper('cookie');
			if(get_cookie('unravel_feedView'))
			{
				$data['feedView'] = 0;
			} else {
				$data['feedView'] = 1;
			}
			
			//this would be generic feed here
			$data['feedList'] = null;
			$data['feedList'] = $data['memcache']->get('feed' . $data['feedFilter'] . $data['feedView']);
			if($data['feedList'] == null)
			{
				$data['albums'] = $this->homemodel->getFeedNoUser();
			}
			$this->template->write('title', 'Home');
			$this->template->write_view('content', 'home/index', $data, TRUE);
			$this->template->render();
		}
		
		

		//$this->load->view('home/index', $data);
		//$this->output->enable_profiler(TRUE);
	}
	
	function all()
	{
		if($this->dx_auth->is_logged_in())
		{
			$this->load->model('usermodel_unravel');
			$this->usermodel_unravel->feedFilter('all');
			redirect('home');
		} else {
			$this->load->helper('cookie');
			if(get_cookie('unravel_feedView'))
			{
				$data['feedView'] = 0;
			} else {
				$data['feedView'] = 1;
			}	
			$data['feedFilter'] = 'all';
			$data['memcache'] = new Memcache;
			$data['memcache']->pconnect('localhost', 11211) or $memcache = false;
			$this->load->model('homemodel');
			$data['feedList'] = null;
			$data['feedList'] = $data['memcache']->get('feed' . $data['feedFilter'] . $data['feedView']);
			if($data['feedList'] == null)
			{
				$data['albums'] = $this->homemodel->getFeedNoUser();
			}
			
		
			$this->template->write('title', 'All Updates');
			$this->template->write_view('content', 'home/index', $data, TRUE);
			$this->template->render();
		}
	
	}
	
	function artists()
	{
		if($this->dx_auth->is_logged_in())
		{
			$this->load->model('usermodel_unravel');
			$this->usermodel_unravel->feedFilter('newArtists');
			redirect('home');
		} else {
			$this->load->helper('cookie');
			if(get_cookie('unravel_feedView'))
			{
				$data['feedView'] = 0;
			} else {
				$data['feedView'] = 1;
			}	
			$data['feedFilter'] = 'newArtists';
			$data['memcache'] = new Memcache;
			$data['memcache']->pconnect('localhost', 11211) or $memcache = false;
			$this->load->model('homemodel');
			$data['feedList'] = null;
			$data['feedList'] = $data['memcache']->get('feed' . $data['feedFilter'] . $data['feedView']);
			if($data['feedList'] == null)
			{			
				$data['albums'] = $this->homemodel->getFeedSmall('3');
			}
			
		
			$this->template->write('title', 'New Artists');
			$this->template->write_view('content', 'home/index', $data, TRUE);
			$this->template->render();
		}
	
	}
	function albums()
	{
		if($this->dx_auth->is_logged_in())
		{
			$this->load->model('usermodel_unravel');
			$this->usermodel_unravel->feedFilter('newAlbums');
			redirect('home');
		} else {
			$this->load->helper('cookie');
			if(get_cookie('unravel_feedView'))
			{
				$data['feedView'] = 0;
			} else {
				$data['feedView'] = 1;
			}
			$data['feedFilter'] = 'newAlbums';
			$data['memcache'] = new Memcache;
			$data['memcache']->pconnect('localhost', 11211) or $memcache = false;
			$this->load->model('homemodel');
			$data['feedList'] = null;
			$data['feedList'] = $data['memcache']->get('feed' . $data['feedFilter'] . $data['feedView']);
			if($data['feedList'] == null)
			{			
				$data['albums'] = $this->homemodel->getFeedSmall('1');
			}
			
			$this->template->write('title', 'New Albums');
			$this->template->write_view('content', 'home/index', $data, TRUE);
			$this->template->render();
		}
	
	}
	function meanings()
	{
		if($this->dx_auth->is_logged_in())
		{
			$this->load->model('usermodel_unravel');
			$this->usermodel_unravel->feedFilter('newMeanings');
			redirect('home');
		} else {
			$this->load->helper('cookie');
			if(get_cookie('unravel_feedView'))
			{
				$data['feedView'] = 0;
			} else {
				$data['feedView'] = 1;
			}
			$data['feedFilter'] = 'newMeanings';
			$data['memcache'] = new Memcache;
			$data['memcache']->pconnect('localhost', 11211) or $memcache = false;	
			$this->load->model('homemodel');
			$data['feedList'] = null;
			$data['feedList'] = $data['memcache']->get('feed' . $data['feedFilter'] . $data['feedView']);
			if($data['feedList'] == null)
			{			
				$data['albums'] = $this->homemodel->getFeedSmall('2');
			}
			
			$this->template->write('title', 'New Meanings');
			$this->template->write_view('content', 'home/index', $data, TRUE);
			$this->template->render();
		}
	
	}
	function news()
	{
		if($this->dx_auth->is_logged_in())
		{
			$this->load->model('usermodel_unravel');
			$this->usermodel_unravel->feedFilter('news');
			redirect('home');
		} else {
			$data['feedFilter'] = 'news';
			$this->load->helper('cookie');
			if(get_cookie('unravel_feedView'))
			{
				$data['feedView'] = 0;
			} else {
				$data['feedView'] = 1;
			}
			$data['memcache'] = new Memcache;
			$data['memcache']->pconnect('localhost', 11211) or $memcache = false;	
			$this->load->model('homemodel');
			$data['feedList'] = null;
			$data['feedList'] = $data['memcache']->get('feed' . $data['feedFilter'] . $data['feedView']);
			if($data['feedList'] == null)
			{
				$data['albums'] = $this->homemodel->getFeedSmall('0');
			}
			
			$this->template->write('title', 'Unravel News');
			$this->template->write_view('content', 'home/index', $data, TRUE);
			$this->template->render();
		}
	
	}
	
	function tagged()
	{
		$this->dx_auth->check_uri_permissions();
		
		$this->load->model('usermodel_unravel');
		$this->usermodel_unravel->feedFilter('tagged');
		redirect('home');
	}
	
	function expand()
	{
		if($this->dx_auth->is_logged_in())
		{
			$this->load->model('usermodel_unravel');
			$this->usermodel_unravel->feedView('1');
		} 
	
	}
	
	function collapse()
	{
		
		if($this->dx_auth->is_logged_in())
		{
			$this->load->model('usermodel_unravel');
			$this->usermodel_unravel->feedView('0');
			
			
		}
	
	}
	
}
?>

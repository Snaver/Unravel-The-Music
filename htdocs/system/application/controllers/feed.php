<?php
class Feed extends Controller 
{

    function __construct()
    {
        parent::Controller();

    }
    
    function index()
    {	
       
        
		header("Content-Type: application/rss+xml");
		 $this->load->model('BlogModel');
		$this->load->helper('xml');	
        $data['encoding'] = 'utf-8';
        $data['feed_name'] = 'Unravel The Music Official Blog';
        $data['feed_url'] = 'http://www.unravelthemusic.com/blog';
        $data['page_description'] = 'Unravel The Music Official Blog';
        $data['page_language'] = 'en-us';
        $data['creator_email'] = 'Drew [dot] town [at] unravelThemusic [period] com';
        $data['posts'] = $this->BlogModel->load('all');    
        
        $this->load->view('feed/rss', $data);
    }
}
?>
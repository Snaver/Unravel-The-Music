<?php

class Blog extends Controller {

	function __construct()
	{
		parent::Controller();
		//$this->output->enable_profiler();
		
	}

	function index()
	{
		$this->load->model('BlogModel');
		$data['results'] = $this->BlogModel->load('music');
		$this->template->write('title', 'Unravel Blog');
		$this->template->write_view('content', 'blog/index', $data, true);
		$this->template->render();	
	}
	
	function site()
	{
		$this->load->model('BlogModel');
		$data['results'] = $this->BlogModel->load('site');
		$this->template->write('title', 'Unravel Blog');
		$this->template->write_view('content', 'blog/site', $data, true);
		$this->template->render();		
	
	}
	
	function view()
	{
		$memcache = new Memcache;
		$memcache->pconnect('localhost', 11211) or $memcache = false;
		$post = $memcache->get('blog' . $this->uri->segment(3));
		if($post == null)
		{
			$this->load->model('BlogModel');
			$post = $this->uri->segment(3);

			if((int)$post != 0 && (int)$post < 15)
			{
				$data['query'] = $this->BlogModel->loadLegacyEntry($post);
			} else {
				$data['query'] = $this->BlogModel->loadEntry($post);
			}
			
			$data['comments'] = $this->BlogModel->loadComments($post);
			if($data['query']->num_rows() > 0)
			{
				$row = $data['query']->row();
				$data['row'] = $row;

				
				$memcache->set('blog' . $this->uri->segment(3), $row, 0, 3000);
				
				$this->template->write('title', 'Unravel Blog - ' . $row->title);
				$this->template->write_view('content', 'blog/view', $data, true);
				$this->template->render();			
			} else {
				$data['error'] = 'Blog post not found';
				$this->template->write('title', 'Blog Post Not Found');
				$this->template->write_view('content', '404', $data, true);
				$this->template->render();			
			}
		} else {
				$data['row'] = $post;
				$this->load->model('BlogModel');
				$data['comments'] = $this->BlogModel->loadComments($post->post_id);
				$this->template->write('title', 'Unravel Blog - ' . $post->title);
				$this->template->write_view('content', 'blog/view', $data, true);
				$this->template->render();			
		}
	}
	
	function add()
	{
		$data = null;
		$this->dx_auth->check_uri_permissions();
		if($this->input->post('submit'))
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[500]|lyrics|xss_clean');
			$this->form_validation->set_rules('body', 'Body', 'trim|required|max_length[50000]|journal|xss_clean');
			$this->form_validation->set_rules('summary', 'Summary', 'trim|required|max_length[500]|xss_clean');
			if ($this->form_validation->run() == FALSE)
			{
			$this->template->write('title', 'Error in your post');
			$this->template->write_view('content', 'blog/add', $data, true);
			$this->template->render();	
			}
			else
			{	
		
				$this->load->library('JournalPrep');
				$cleanBody = $this->journalprep->cleanFormInput($this->input->post('body'));

				$insert['title'] = $this->input->post('title');
				$insert['seo_title'] = url_title($this->input->post('title'));
				$insert['body'] = $cleanBody;
				$insert['summary'] = $this->input->post('summary');
				$insert['blog'] = $this->input->post('blog');
				$insert['category_id'] = $this->input->post('category');
				$this->load->model('BlogModel');
				$blogId = $this->BlogModel->insert($insert);
				
				if(isset($_POST['news']))
				{
		
					$this->load->model('HomeModel');
					$this->HomeModel->addNewsToFeed(substr('<b>' . $this->input->post('title') . ': </b>' . str_replace('<br />', '',$insert['summary']), 0 , 143), $this->session->userdata('DX_username'), $blogId, 'Greg Laswell');
					
				
				}
				
				redirect('blog/index');
			}
		
		} else {
			$this->template->write('title', 'Add a new blog post');
			$this->template->write_view('content', 'blog/add', $data, true);
			$this->template->render();	
		
		}
	}
	
	function edit()
	{
		$this->dx_auth->check_uri_permissions();
		$post = $this->uri->segment(3);
	
	}
	function addComment()
	{
		//this is for adding a meaning to a song
		$this->load->model('BlogModel');
		$this->load->model('usermodel_unravel');
		//must be logged in.
		$this->dx_auth->check_uri_permissions();
		
		$this->load->helper('date');
		$now = now();	
		$expires = $this->usermodel_unravel->lastPostTime() + 45;
		if($expires < $now)
		{
			$postId = $this->uri->segment(3);
			$DB2 = $this->load->database('blog', TRUE);
			$DB2->where('post_id', $postId);
			$query = $DB2->get('posts');
			if($query->num_rows() == 1) {
			
				$author = $this->session->userdata('DX_username');
				
				$this->load->library('form_validation');
				$this->form_validation->set_rules('title', 'Title', 'trim|required|lyrics|max_length[80]|xss_clean');
				$this->form_validation->set_rules('body', 'Body', 'trim|lyrics|xss_clean|max_length[1000]|required');
				if ($this->form_validation->run() == FALSE)
				{
					$response['result'] = 'fail';
					$response['message'] = validation_errors();
					print json_encode($response);
				} else {	

					$row = $query->row();
					
					$insert['author'] = $author;	
					date_default_timezone_set('America/Chicago');
					$insert['created_on'] = date('Y-n-d G:i:s');
					$insert['post_id'] = $row->post_id;
					$insert['title'] = $this->input->post('title');
					$insert['body'] = $this->input->post('body');
					//submit to db

	
					$this->BlogModel->newBlogComment($insert, $postId);
			
					

					$this->usermodel_unravel->updatePostTime();
		
					$response['result'] = 'success';
					$response['title'] = $this->input->post('title');
					$response['body'] = $this->input->post('body');
					$response['author'] = $author;
					$response['createdOn'] = date('Y-n-d G:i:s');
					print json_encode($response);
					

				}
			} else {
				$response['result'] = 'fail';
				$response['message'] = 'Post does not exist';
				print json_encode($response);
			}
		} else {
			$response['result'] = 'fail';
			$wait = $expires - $now;
			$response['message'] = 'You must wait another ' . $wait . ' seconds.' ;
			print json_encode($response);			
		}
  
	}
	
	function category() {
		$category = str_replace("-", " ", $this->uri->segment(3));
		if($category == null)
		{
			$this->load->model('BlogModel');
			$data['query'] = $this->BlogModel->loadCategories();
				$this->template->write('title', 'Unravel Blog - Categories');
				$this->template->write_view('content', 'blog/categories', $data, true);
				$this->template->render();			
		} else {		
			$this->load->model('BlogModel');
			$query = $this->BlogModel->loadCat($category);
			
			if($query->num_rows() > 0)
			{
				$data['results'] = $query;
				$this->template->write('title', 'Unravel Blog');
				$this->template->write_view('content', 'blog/index', $data, true);
				$this->template->render();	
			} else {
				$data['error'] = 'No posts have been made in this category';
				$this->template->write('title', 'No posts in this category');
				$this->template->write_view('content', '404', $data, true);
				$this->template->render();
			}
		}	
	}
	
}
?>

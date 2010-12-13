<?php
class BlogModel extends Model {


	function BlogModel()
	{
		// Call the Model constructor
		parent::Model();
	}
	
	function load($blog)
	{
		$DB2 = $this->load->database('blog', TRUE);
		if($blog != 'all')
		{
			$DB2->where('blog', $blog); 
		}
		
		
		$DB2->order_by('post_id', 'desc');
		$DB2->join('categories', 'categories.category_id = posts.category_id');
		return $DB2->get('posts', 10);
	}
	
	function loadCat($category)
	{
		$DB2 = $this->load->database('blog', TRUE); 
		$DB2->where('categories.category', $category);
		$DB2->order_by('post_id', 'desc');
		$DB2->join('categories', 'categories.category_id = posts.category_id');
		return $DB2->get('posts', 10);
	}	
	
	function loadCategories()
	{
		$DB2 = $this->load->database('blog', TRUE);
		return $DB2->get('categories');
	}
	
	function loadEntry($post)
	{
		$DB2 = $this->load->database('blog', TRUE); 
		$DB2->where('seo_title', $post);
		return $DB2->get('posts', 1);
	}
	
	function loadLegacyEntry($post)
	{
		$DB2 = $this->load->database('blog', TRUE); 
		$DB2->where('post_id', $post);
		return $DB2->get('posts', 1);
	}
		
	function loadComments($post)
	{
		$DB2 = $this->load->database('blog', TRUE); 
		$DB2->where('title', $post);
		return $DB2->get('comments');		
	}
	function insert($insert)
	{
		$insert['author'] = $this->session->userdata('DX_username');
		$insert['created_on'] = date('Y-m-d G:i:s');
		$DB2 = $this->load->database('blog', TRUE);
		$DB2->insert('posts', $insert);
		return $DB2->insert_id();
	}
	function newBlogComment($insert, $postId)
	{
		$DB2 = $this->load->database('blog', TRUE);
		
		$DB2->insert('comments', $insert);
	
	}	

}

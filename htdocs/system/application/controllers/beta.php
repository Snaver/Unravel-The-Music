<?php


class Beta extends Controller {
	function Beta()
	{
		parent::Controller();
    
		//$this->load->scaffolding('artists');
		$this->load->library('FAL_front', 'fal_front');
		$this->_container = $this->config->item('FAL_template_dir').'template/container';
	}
	function index()
	{
		//displays the view
		$data['fal'] = $this->fal_front->register();
		if($data['fal'] == 'success')
		{
			$this->db_session->set_flashdata('flashMessage', 'Please check your e-mail for your activation link.');
			redirect('home');		
		}
		$data['title'] = 'Welcome';
		$this->load->view('beta/index', $data);
	}
	function thanks() {
		$this->load->view('beta/thanks');
	}
	function about()
	{
		$this->load->view('beta/about');
	
	}
	function contact()
	{
		$this->load->view('beta/contact');
	
	}
	function legal()
	{
		$this->load->view('beta/legal');
	}
	function form()
	{
		function validEmail($email)
		{
		   $isValid = true;
		   $atIndex = strrpos($email, "@");
		   if (is_bool($atIndex) && !$atIndex)
		   {
		      $isValid = false;
		   }
		   else
		   {
		      $domain = substr($email, $atIndex+1);
		      $local = substr($email, 0, $atIndex);
		      $localLen = strlen($local);
		      $domainLen = strlen($domain);
		      if ($localLen < 1 || $localLen > 64)
		      {
		         // local part length exceeded
		         $isValid = false;
		      }
		      else if ($domainLen < 1 || $domainLen > 255)
		      {
		         // domain part length exceeded
		         $isValid = false;
		      }
		      else if ($local[0] == '.' || $local[$localLen-1] == '.')
		      {
		         // local part starts or ends with '.'
		         $isValid = false;
		      }
		      else if (preg_match('/\\.\\./', $local))
		      {
		         // local part has two consecutive dots
		         $isValid = false;
		      }
		      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
		      {
		         // character not valid in domain part
		         $isValid = false;
		      }
		      else if (preg_match('/\\.\\./', $domain))
		      {
		         // domain part has two consecutive dots
		         $isValid = false;
		      }
		      else if
		(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
		                 str_replace("\\\\","",$local)))
		      {
		         // character not valid in local part unless 
		         // local part is quoted
		         if (!preg_match('/^"(\\\\"|[^"])+"$/',
		             str_replace("\\\\","",$local)))
		         {
		            $isValid = false;
		         }
		      }
		      if ($isValid && !(checkdnsrr($domain,"MX") || 
				checkdnsrr($domain,"A")))
		      {
		         // domain not found in DNS
		         $isValid = false;
		      }
		   }
		   return $isValid;
		}
		if($this->input->post('email') == '') {
			echo 0;
		} else {
			if(validEmail($this->input->post('email'))) {
				$this->db->where('email', $this->input->post('email'));
				$results = $this->db->get('email_list');
				if($results->num_rows() == 0) {
					$data = array('email' => $this->input->post('email'));
					$this->db->insert('email_list', $data);
					echo(3);
				} else {
					echo(1);
				}
			} else {
				echo(2);
			}
		}
	}

}
?>

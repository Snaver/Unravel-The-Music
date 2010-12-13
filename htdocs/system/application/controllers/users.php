<?php
class Users extends Controller
{
	// Used for registering and changing password form validation
	var $min_username = 4;
	var $max_username = 20;
	var $min_password = 4;
	var $max_password = 20;

	function __construct()
	{
		parent::Controller();
		$this->load->library('Form_validation');
	}
	
	function index()
	{
		$this->login();
	}
	
	function login()
	{
		if ( ! $this->dx_auth->is_logged_in())
		{
			$val = $this->form_validation;
			
			// Set form validation rules
			$val->set_rules('username', 'Username', 'trim|required|xss_clean');
			$val->set_rules('password', 'Password', 'trim|required|xss_clean');
			$val->set_rules('remember', 'Remember me', 'integer');

			// Set captcha rules if login attempts exceed max attempts in config
			if ($this->dx_auth->is_max_login_attempts_exceeded())
			{
				$val->set_rules('captcha', 'Confirmation Code', 'trim|required|xss_clean|callback_captcha_check');
			}
				
			if ($val->run() AND $this->dx_auth->login($val->set_value('username'), $val->set_value('password'), $val->set_value('remember')))
			{
				$this->load->model('usermodel_unravel');
				$this->usermodel_unravel->blockList($this->session->userdata('DX_username'));
				$this->load->helper('cookie');
				if(get_cookie('unravel_location', TRUE))
				{
					$location = get_cookie('unravel_location', true);
					redirect($location);
				} else {
					redirect('/home', 'location');
				}
			}
			else
			{
				// Check if the user is failed logged in because user is banned user or not
				if ($this->dx_auth->is_banned())
				{
					// Redirect to banned uri
					$this->dx_auth->deny_access('banned');
				}
				else
				{						
					// Default is we don't show captcha until max login attempts eceeded
					$data['show_captcha'] = FALSE;
				
					// Show captcha if login attempts exceed max attempts in config
					if ($this->dx_auth->is_max_login_attempts_exceeded())
					{
						// Create catpcha						
						$this->dx_auth->captcha();
						
						// Set view data to show captcha on view file
						$data['show_captcha'] = TRUE;
					}
					
					// Load login page view
					$this->template->write('title', 'Login');
					$this->template->write_view('content', $this->dx_auth->login_view, $data, true);
					$this->template->render();
				}
			}
		}
		else
		{
			$data['auth_message'] = 'You are already logged in.';
			$this->template->write('title', 'You are already logged in.');
			$this->template->write_view('content', $this->dx_auth->logged_in_view, $data, true);
			$this->template->render();			
		}
	}

	function logout()
	{
		$this->dx_auth->logout();
		
		$data['auth_message'] = 'You have been logged out.';		
		$this->template->write('title', 'You have been logged out.');
		$this->template->write_view('content', $this->dx_auth->logout_view, $data, true);
		$this->template->render();				
	}
	
	function register()
     {  
		if ( ! $this->dx_auth->is_logged_in() AND $this->dx_auth->allow_registration)
		{	
			$val = $this->form_validation;
			
			// Set form validation rules
			$val->set_rules('username', 'Username', 'trim|required|xss_clean|min_length['.$this->min_username.']|max_length['.$this->max_username.']|callback_username_check|alpha_dash');
			$val->set_rules('password', 'Password', 'trim|required|xss_clean|min_length['.$this->min_password.']|max_length['.$this->max_password.']|matches[confirm_password]');
			$val->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean');
			$val->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email|callback_email_check');
			
			// Is registration using captcha
			if ($this->dx_auth->captcha_registration)
			{
				// Set recaptcha rules.
				// IMPORTANT: Do not change 'recaptcha_response_field' because it's used by reCAPTCHA API,
				// This is because the limitation of reCAPTCHA, not DX Auth library
				$val->set_rules('recaptcha_response_field', 'Confirmation Code', 'trim|xss_clean|required|callback_recaptcha_check');
			}

			// Run form validation and register user if it's pass the validation
			if ($val->run() AND $this->dx_auth->register($val->set_value('username'), $val->set_value('password'), $val->set_value('email')))
			{	
				// Set success message accordingly
				if ($this->dx_auth->email_activation)
				{
					$data['auth_message'] = 'You have successfully registered. Check your email address to activate your account.';
				}
				else
				{					
					$data['auth_message'] = 'You have successfully registered. '.anchor(site_url($this->dx_auth->login_uri), 'Login');
				}
				
				// Load registration success page
				
				$this->template->write('title', 'You have been registered!');
				$this->template->write_view('content', $this->dx_auth->register_success_view, $data, true);
				$this->template->render();					
			}
             else  
             {  
                 // Load registration page 
				$data = null;
				$this->template->write('title', 'Register for Unravel The Music');
				$this->template->write_view('content', 'users/register_recaptcha_form', $data, true);
				$this->template->render();				 
             }  
         }  
         elseif ( ! $this->dx_auth->allow_registration)  
         {  
             $data['auth_message'] = 'Registration has been disabled.';  
             $this->load->view($this->dx_auth->register_disabled_view, $data);  
         }  
         else  
         {  
			$data['auth_message'] = 'You have to logout first, before registering.';  
			$this->template->write('title', 'You have to logout first, before registering.');
			$this->template->write_view('content', $this->dx_auth->logged_in_view, $data, true);
			$this->template->render();						 
         }  
     } 

	function register_small()
     {  
 		if ( ! $this->dx_auth->is_logged_in() AND $this->dx_auth->allow_registration)
		{	
			$val = $this->form_validation;
			
			// Set form validation rules			
			$val->set_rules('username', 'Username', 'trim|required|xss_clean|min_length['.$this->min_username.']|max_length['.$this->max_username.']|callback_username_check|alpha_dash');
			$val->set_rules('password', 'Password', 'trim|required|xss_clean|min_length['.$this->min_password.']|max_length['.$this->max_password.']|matches[confirm_password]');
			$val->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean');
			$val->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email|callback_email_check');
			
			if ($this->dx_auth->captcha_registration)
			{
				$val->set_rules('captcha', 'Confirmation Code', 'trim|xss_clean|required|callback_captcha_check');
			}

			// Run form validation and register user if it's pass the validation
			if ($val->run() AND $this->dx_auth->register($val->set_value('username'), $val->set_value('password'), $val->set_value('email')))
			{	
				// Set success message accordingly
				if ($this->dx_auth->email_activation)
				{
					$data['auth_message'] = 'You have successfully registered. Check your email address to activate your account.';
				}
				else
				{					
					$data['auth_message'] = 'You have successfully registered. '.anchor(site_url($this->dx_auth->login_uri), 'Login');
				}
				
				// Load registration success page
				$this->load->view($this->dx_auth->register_success_view, $data);
			}
			else
			{
				// Is registration using captcha
				if ($this->dx_auth->captcha_registration)
				{
					$this->dx_auth->captcha();										
				}

				// Load registration page
				$this->load->view($this->dx_auth->register_view);
			}
		}
		elseif ( ! $this->dx_auth->allow_registration)
		{
			$data['auth_message'] = 'Registration has been disabled.';
			$this->load->view($this->dx_auth->register_disabled_view, $data);
		}
		else
		{
			$data['auth_message'] = 'You have to logout first, before registering.';
			$this->template->write('title', 'You have to logout first, before registering.');
			$this->template->write_view('content', $this->dx_auth->logged_in_view, $data, true);
			$this->template->render();				
		}
     } 
	 
	function activate()
	{
		// Get username and key
		$username = $this->uri->segment(3);
		$key = $this->uri->segment(4);
		// Activate user
		if ($this->dx_auth->activate($username, $key)) 
		{
			$data['auth_message'] = 'Your account have been successfully activated. '.anchor(site_url($this->dx_auth->login_uri), 'Login');
			$this->template->write('title', 'Your account have been successfully activated.');
			$this->template->write_view('content', $this->dx_auth->activate_success_view, $data, true);
			$this->template->render();				
		}
		else
		{
			$data['auth_message'] = 'The activation code you entered was incorrect. Please check your email again.';
			$this->template->write('title', 'The activation code you entered was incorrect');
			$this->template->write_view('content', $this->dx_auth->activate_failed_view, $data, true);
			$this->template->render();					
		}
	}
	
	function forgot_password()
	{
		$val = $this->form_validation;

		// Set form validation rules
		$val->set_rules('login', 'Username or Email address', 'trim|required|xss_clean');

		// Validate rules and call forgot password function
		if ($val->run() AND $this->dx_auth->forgot_password($val->set_value('login')))
		{
			$data['auth_message'] = 'An email has been sent to your email with instructions with how to activate your new password.';
			$this->template->write('title', 'An email has been sent to your email with instructions');
			$this->template->write_view('content', $this->dx_auth->forgot_password_success_view, $data, true);
			$this->template->render();				
		}
		else
		{
			$this->template->write('title', 'Forgot Password');
			$this->template->write_view('content', $this->dx_auth->forgot_password_view, $data, true);
			$this->template->render();				
		}
	}
	
	function reset_password()
	{
		$data['title'] = 'Reset Password';
		// Get username and key
		$username = $this->uri->segment(3);
		$key = $this->uri->segment(4);

		// Reset password
		if ($this->dx_auth->reset_password($username, $key))
		{
			$data['auth_message'] = 'You have successfully reset you password, '.anchor(site_url($this->dx_auth->login_uri), 'Login');
			$this->template->write('title', 'You have successfully reset you password');
			$this->template->write_view('content', $this->dx_auth->reset_password_success_view, $data, true);
			$this->template->render();					
		}
		else
		{
			$data['auth_message'] = 'Reset failed. Your username and key are incorrect. Please check your email again and follow the instructions.';
			$this->template->write('title', 'Reset failed');
			$this->template->write_view('content', $this->dx_auth->reset_password_failed_view, $data, true);
			$this->template->render();					
		}
	}
	
	function change_password()
	{
		$data = null;
		// Check if user logged in or not
		if ($this->dx_auth->is_logged_in())
		{			
			$val = $this->form_validation;
			
			// Set form validation
			$val->set_rules('old_password', 'Old Password', 'trim|required|xss_clean|min_length['.$this->min_password.']|max_length['.$this->max_password.']');
			$val->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length['.$this->min_password.']|max_length['.$this->max_password.']|matches[confirm_new_password]');
			$val->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean');
			
			// Validate rules and change password
			if ($val->run() AND $this->dx_auth->change_password($val->set_value('old_password'), $val->set_value('new_password')))
			{
				$data['auth_message'] = 'Your password has successfully been changed.';
				$this->template->write('title', 'Your password has successfully been changed');
				$this->template->write_view('content', $this->dx_auth->change_password_success_view, $data, true);
				$this->template->render();							
			}
			else
			{
				$this->template->write('title', 'Change your password');
				$this->template->write_view('content', $this->dx_auth->change_password_view, $data, true);
				$this->template->render();					
			}
		}
		else
		{
			// Redirect to login page
			$this->dx_auth->deny_access('login');
		}
	}	
	
	function cancel_account()
	{
		// Check if user logged in or not
		if ($this->dx_auth->is_logged_in())
		{			
			$val = $this->form_validation;
			
			// Set form validation rules
			$val->set_rules('password', 'Password', "trim|required|xss_clean");
			
			// Validate rules and change password
			if ($val->run() AND $this->dx_auth->cancel_account($val->set_value('password')))
			{
				// Redirect to homepage
				redirect('', 'location');
			}
			else
			{
				$this->template->write('title', 'Cancel your account');
				$this->template->write_view('content', $this->dx_auth->cancel_account_view, $data, true);
				$this->template->render();					
			}
		}
		else
		{
			// Redirect to login page
			$this->dx_auth->deny_access('login');
		}
	}

	// Example how to get permissions you set permission in /backend/custom_permissions/
	
	function deny()
	{
		$data = null;
		$this->template->write('title', 'You cannot access this page');
		$this->template->write_view('content', 'users/deny', $data, true);
		$this->template->render();			
	
	}
	
	function view()
	{
		$data = null;
		$user = $this->uri->segment(3);
		$this->load->model('dx_auth/user_profile', 'user');
		$this->load->model('JournalModel');
		$this->load->model('usermodel_unravel', 'user_unravel');
		$query = $this->user->get_profile_by_username($user);
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$data['edit'] = NULL;
			if(strtoupper($user) == strtoupper($this->session->userdata('DX_username')))
			{
				$data['edit'] = anchor('users/edit/' . $user, 'Edit Profile');
			}
			
					
			
			$data['userId'] = $row->id;
			$data['karma'] = $row->karma;
			$data['username'] = $row->username;
			$data['location'] = $row->location;
			$data['website'] = $row->website;
			$data['interests'] = $row->interests;
			$data['name'] = $row->name;
			$data['birthday'] = $row->birthday;
			$data['avatar'] = $row->avatar;
			$data['bio'] = $row->bio;
			if($this->session->userdata('DX_username'))
			{
				$data['friends'] = $this->user_unravel->checkFriends($this->session->userdata('DX_user_id'), $row->id);	
				$data['blocked'] = $this->user_unravel->checkBlocked($this->session->userdata('DX_username'), $row->username);			
			} else {
				$data['friends'] = false;
				$data['blocked'] = null;
			}
			$journal = $this->JournalModel->loadNewestByUser($row->username);
			if($journal->num_rows() > 0)
			{
				$data['journal'] = $journal->row();
			} else {
				$data['journal'] = NULL;
			}			

			$data['comments'] = $this->user_unravel->loadComments($user);
			
			$this->template->write('title', $user . '\'s profile');
			$this->template->write_view('content', 'users/view', $data, true);
			$this->template->render();	
		
		
		} else {
			$data['error'] = 'User not found';
			$this->template->write('title', 'User not found');
			$this->template->write_view('content', '404', $data, true);
			$this->template->render();	
		}
	
	}

	function edit()
	{
		$data['title'] = 'Edit your profile';
		$this->load->model('dx_auth/user_profile', 'user');
		$this->dx_auth->check_uri_permissions();
		$user = $this->uri->segment(3);
		if($user == $this->session->userdata('DX_username'))
		{
			$query = $this->user->get_profile_by_username($user);
			if($query->num_rows() > 0)
			{			
				$row = $query->row();
				if($row->avatar != '')
				{
					$data['img'] = 'http://static.unravelthemusic.com/users/' . $row->avatar;
				} else {
					$data['img'] = 'http://www.unravelthemusic.com/assets/images/public/blankuser.png';
				}
				$this->load->model('usermodel_unravel');
				$data['points'] = $this->usermodel_unravel->getPoints($row->username);
				$data['username'] = $row->username;				
				$data['website'] = $row->website;
				$data['location'] = $row->location;
				$data['name'] = $row->name;
				$data['interests'] = $row->interests;
				if($row->birthday != null)
				{
					$birthday = explode('-', $row->birthday);
					$data['year'] = $birthday[0];
					$data['month'] = $birthday[1];
					$data['day']= $birthday[2];
				} else {
					$data['year'] = null;
					$data['month'] = null;
					$data['day'] = null;
				}
				
				$data['notify'] = $row->notify_by_default;

			}		
			if($this->input->post('submit'))
			{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('name', 'Name', 'trim|artist|max_length[50]|xss_clean');
				$this->form_validation->set_rules('month', 'Month', 'trim|min_length[1]|max_length[2]|is_natural_no_zero|xss_clean');
				$this->form_validation->set_rules('day', 'Day', 'trim|min_length[1]|max_length[2]|is_natural_no_zero|xss_clean');
				$this->form_validation->set_rules('year', 'Year', 'trim|exact_length[4]|is_natural_no_zero|xss_clean');
				$this->form_validation->set_rules('website', 'Website', 'trim|xss_clean|prep_url');
				$this->form_validation->set_rules('location', 'Location', 'trim|artist|max_length[60]xss_clean');


					
				if ($this->form_validation->run() == FALSE)
				{
					$data['title'] = 'Errors in Form';
					
				} else {	
					$insert['name'] = (!empty($_POST['name'])) ? $this->input->post('name') : null;
					$insert['location'] = (!empty($_POST['location'])) ? $this->input->post('location') : null;
					$insert['website'] = (!empty($_POST['website'])) ? $this->input->post('website') : null;
					$insert['interests'] = (!empty($_POST['interests'])) ? $this->input->post('interests') : null;
					$year = (!empty($_POST['year'])) ? $this->input->post('year') : null;
					$month = (!empty($_POST['month'])) ? $this->input->post('month') : null;
					$day = (!empty($_POST['day'])) ? $this->input->post('day') : null;
					$insert['location'] = $this->input->post('location');
					$insert['website'] = $this->input->post('website');
					$birthday = $this->input->post('year') . '-' . $this->input->post('month') . '-' . $this->input->post('day');
					if($year == null || $month == null || $day == null)
					{
						$birthday = null;
					}
					$insert['birthday'] = $birthday;
					if(isset($_POST['notify']))
					{
						$insert['notify_by_default'] = 1;
					} else {
						$insert['notify_by_default'] = 0;
					}

					if(!empty($_FILES['userfile']['name']))
					{
						if(is_dir("/var/www/static.unravelthemusic.com/htdocs/users/"))
						{
							$d = dir("/var/www/static.unravelthemusic.com/htdocs/users/");
						} else {
							mkdir("/var/www/static.unravelthemusic.com/htdocs/users/", 0777);
							$d = dir("/var/www/static.unravelthemusic.com/htdocs/users/");
						}
						$config['upload_path'] = $d->path;
						$config['allowed_types'] = 'gif|jpg|png';
						$config['max_size']	= '512';
						$config['max_width']  = '1024';
						$config['max_height']  = '768';
						$config['overwrite'] = true;
						
						
						$this->load->library('upload', $config);
						if(! $this->upload->do_upload())
						{
							$data['title'] = 'Errors in Form';
							$data['error'] = $this->upload->display_errors();
						} else {						

							
							$results = $this->upload->data();
							$filename = $results['file_path'] . $this->session->userdata('DX_username') . $results['file_ext'];
							rename($results['full_path'], $filename);
							//resize if more than 90
							if($results['image_width'] > 90 && $results['image_height'] > 90)
							{
								$configResize['image_library'] = 'GD2';
								$configResize['source_image'] = $filename;
								$configResize['create_thumb'] = TRUE;
								$configResize['maintain_ratio'] = TRUE;
								$configResize['width'] = 90;
								$configResize['height'] = 90;
								$configResize['quality'] = '70%';
								$this->load->library('image_lib', $configResize);

								
								
								$this->image_lib->resize();		
								if ( ! $this->image_lib->resize())
								{
									echo $this->image_lib->display_errors();
								}
								$results['file_ext'] = strtolower($results['file_ext']);
								if($results['file_ext'] == 'jpeg')
								{
									$results['file_ext'] = 'jpg';
								}
								rename($results['file_path'] . $this->session->userdata('DX_username') . '_thumb' . $results['file_ext'], $filename);
							}
							$insert['avatar'] = $this->session->userdata('DX_username') . $results['file_ext'];
						}
					}
					//$this->db->where('user_id', $this->session->userdata('DX_user_id'));
					//$this->db->update('user_profile', $insert);
					
					$this->user->set_profile($this->session->userdata('DX_user_id'), $insert);
					redirect('users/view/' . $user);

				}
			
				
				
			} 
			$this->template->write('title', 'Edit your profile');
			$this->template->write_view('content', 'users/edit', $data, true);
			$this->template->render();
		}
			else {
			redirect('/users/view/' . $user);
		}
	}	
	
	/* Callback function */
	function username_check($username)
	{
		$result = $this->dx_auth->is_username_available($username);
		if ( ! $result)
		{
			$this->form_validation->set_message('username_check', 'Username already exist. Please choose another username.');
		}
				
		return $result;
	}

	function email_check($email)
	{
		$result = $this->dx_auth->is_email_available($email);
		if ( ! $result)
		{
			$this->form_validation->set_message('email_check', 'Email is already used by another user. Please choose another email address.');
		}
				
		return $result;
	}

	function captcha_check($code)
	{
		$result = TRUE;
		
		if ($this->dx_auth->is_captcha_expired())
		{
			// Will replace this error msg with $lang
			$this->form_validation->set_message('captcha_check', 'Your confirmation code has expired. Please try again.');			
			$result = FALSE;
		}
		elseif ( ! $this->dx_auth->is_captcha_match($code))
		{
			$this->form_validation->set_message('captcha_check', 'Your confirmation code does not match the one in the image. Try again.');			
			$result = FALSE;
		}

		return $result;
	}
	
	function recaptcha_check()
	{
		$result = $this->dx_auth->is_recaptcha_match();		
		if ( ! $result)
		{
			$this->form_validation->set_message('recaptcha_check', 'Your confirmation code does not match the one in the image. Try again.');
		}
		
		return $result;
	}
	
	function block()
	{
		$this->dx_auth->check_uri_permissions();
		$this->load->model('usermodel_unravel');
		$blockee = $this->uri->segment(3);
		$this->db->where('username', $blockee);
		$query = $this->db->get('users');
		
		if($query->num_rows() == 1)
		{
			$blocker = $this->session->userdata('DX_username');
			$response['result'] = $this->usermodel_unravel->block($blocker, $blockee);
			$this->usermodel_unravel->blockList($blocker);
		} else {
			$response['result'] = false;
		}
		print json_encode($response);		
	
	}
	
	function unblock()
	{
		$this->dx_auth->check_uri_permissions();
		$this->load->model('usermodel_unravel');
		$blockee = $this->uri->segment(3);
		$blocker = $this->session->userdata('DX_username');	

		$response['result'] = $this->usermodel_unravel->unblock($blocker, $blockee);
		$this->usermodel_unravel->blockList($blocker);
		print json_encode($response);		
	
	}
	
	function tag()
	{
		$this->dx_auth->check_uri_permissions();
		$this->load->model('usermodel_unravel');
		$friend = $this->uri->segment(3);
		if(is_numeric($friend))
		{
			$userId = $this->session->userdata('DX_user_id');
			$checkFriends = $this->usermodel_unravel->checkFriends($userId, $friend);
			if($checkFriends == FALSE)
			{
				$this->load->model('dx_auth/usermodel', 'usermodel');
				$isUser = $this->usermodel->get_user_by_id($friend);
				if($isUser->num_rows() > 0)
				{
					$this->usermodel_unravel->addFriend($userId, $friend);
					$response['result'] = true;
				}
			} else {
				$response['result'] = false;
			}
		} else {
			$response['result'] = false;
		}
		print json_encode($response);
	}
	
	function untag()
	{
		$this->dx_auth->check_uri_permissions();	
		$this->load->model('usermodel_unravel');
		$friend = $this->uri->segment(3);
		if(is_numeric($friend))
		{
			$userId = $this->session->userdata('DX_user_id');
			$checkFriends = $this->usermodel_unravel->checkFriends($userId, $friend);
			if($checkFriends == TRUE)
			{
				$this->load->model('dx_auth/usermodel', 'usermodel');
				$isUser = $this->usermodel->get_user_by_id($friend);
				if($isUser->num_rows() > 0)
				{
					$this->usermodel_unravel->removeFriend($userId, $friend);
					$response['result'] = true;
				}
			} else {
				$response['result'] = false;
			}		
		
		} else {
			$response['result'] = false;
		}
		print json_encode($response);
	}
	
	function report($username)
	{
		$this->dx_auth->check_uri_permissions();
		$this->load->model('usermodel_unravel');
		$response['result'] = $this->usermodel_unravel->report($username);
		print json_encode($response);	
	}
	
}
?>
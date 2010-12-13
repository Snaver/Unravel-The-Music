<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {

	function MY_Form_validation($config = array())
    {
		parent::CI_Form_validation($config);
		$this->CI = get_instance();
    }

	
	function artist($str)
	{
		return ( ! preg_match("/^([-a-z0-9é\s\&'.*,!])+$/i", $str)) ? FALSE : TRUE;
		
	}
	
	function album($str)
	{
		return ( ! preg_match("/^([-a-z0-9,\s\'.&\/()*!])+$/i", $str)) ? FALSE : TRUE;
	}
	
	function song($str)
	{
		return ( ! preg_match("/^([-a-z0-9\s\)\(\'.*])+$/i", $str)) ? FALSE : TRUE;
		
	}   
	
	function lyrics($str)
	{
		return true;// ( ! preg_match("/^([-a-z0-9\s\'\"().?!,])+$/i", $str)) ? FALSE : TRUE;
		
	}   
	
	function journal($str)
	{
		return true; //( ! preg_match("/^([-a-z0-9\s\!\@\#\$\%\&\*\(\)\[\]\/\\\{\}\.\,\;\:\'\"`+-);+$/i", $str)) ? FALSE : TRUE;
		
	}
	
	function disallowed_words($str)
	{
		if(trim($str) != 'the')
		{
		
			return true;
		} else {
			return false;
		}
	
	}
} 
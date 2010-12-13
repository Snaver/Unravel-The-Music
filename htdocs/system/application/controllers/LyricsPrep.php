<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LyricsPrep {

    function addBreaks($lyrics)
    {
		$CI =& get_instance();
		// we need to replace the line break code with breaks so when it is pulled from the database
		$str = $lyrics;

		
		$str2 = htmlentities($str);

		
		//no we have to clean up the mess from the api sending hex code
		$clean = array('', "(background) ", '"', '"', "...", '.', "'" , "'", "'", "'");
		$wiki = array('&lt;/span&gt;', '&lt;span style=&quot;color: #696969;&quot;&gt;', '&acirc;€œ', '&acirc;€', '&Atilde;&cent;&Acirc;€&Acirc;&brvbar;', '&acirc;€&brvbar;', '&Atilde;&cent;&Acirc;€&Acirc;˜', '&Atilde;&cent;&Acirc;€&Acirc;™', '&Atilde;&cent;&Acirc;', '&acirc;€™');

		return html_entity_decode(str_replace($wiki, $clean, $str2));
    }
	
	function removeBreaks($lyrics)
	{
		$str = $lyrics;
		$order   = array("<br />");
		$replace = '"\n"';		
	
		return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $str);
	}
	
}

?>
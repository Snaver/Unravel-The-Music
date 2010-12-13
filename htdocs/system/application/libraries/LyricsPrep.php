<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LyricsPrep {

    function addBreaks($lyrics)
    {
		$CI =& get_instance();
		// we need to replace the line break code with breaks so when it is pulled from the database
		$str = $lyrics;

		
		$str2 = htmlentities($str);
		$order   = array("\r\n", "\n", "\r");
		$replace = '<br />';
		// Processes \r\n's first so they aren't converted twice.
		
		$withBreaks =  str_replace($order, $replace, $str2);	
		
		//no we have to clean up the mess from the api sending hex code
		$clean = array('', "(background) ", '"', '"', "...", '.', "'" , "'", "'", "'");
		$wiki = array('&lt;/span&gt;', '&lt;span style=&quot;color: #696969;&quot;&gt;', '&acirc;Äú', '&acirc;Äù', '&Atilde;&cent;&Acirc;Ä&Acirc;&brvbar;', '&acirc;Ä&brvbar;', '&Atilde;&cent;&Acirc;Ä&Acirc;ò', '&Atilde;&cent;&Acirc;Ä&Acirc;ô', '&Atilde;&cent;&Acirc;', '&acirc;Äô');

		return html_entity_decode(str_replace($wiki, $clean, $withBreaks));
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
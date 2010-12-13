<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Language file for Unravel
 */

//------------------------------------------------------------------
//WEBSITE STUFF
//------------------------------------------------------------------

/*
Validation Messages
*/
$lang['artist']				= "The %s field can only contain 0-9, A-Z, commas, dashes, apostrophes, spaces, and periods.";
$lang['song']				= "The %s field can only contain 0-9, A-Z, commas, dashes, apostrophes, spaces, and periods.";
$lang['album']				= "The %s field can only contain 0-9, A-Z and the following chracters '/,.()*!-";
$lang['lyrics']				= "The %s field can only contain 0-9, A-Z and the following characters '.,-!";
$lang['journal']			= "The %s field can only contain 0-9, A-Z and the following characters '.,-![]/";
$lang['disallowed_words']	= "The %s field contains a disallowed word";

/*
General Messages
*/
$lang['lyricsMessage']				= "All lyrics are used for the purposes of research only and are intended to further the discussion.\n\nIt is highly advisable that you are the copyright holder or have permission to upload lyrics.";
$lang['addThankYou']		= "Thank You for your submission!<br /><br />Your submission will be review shortly.";
$lang['loggedIP']			= "Your IP, Username, and user-agent have all been logged";

/*
Error Messages
*/
$lang['error-doesNotExist'] = 'This artist does not exist in our database.';
$lang['error-albumDoesNotExist'] = 'This album does not exist in our database.';
$lang['error-songDoesNotExist'] = 'This song does not exist in our database.';
$lang['error-notVerified'] 	= "This artist hasn\'t been verified yet, please try again later.";
$lang['error-noSongsAddedYet'] = "No Songs have been added for this album yet.";
?>
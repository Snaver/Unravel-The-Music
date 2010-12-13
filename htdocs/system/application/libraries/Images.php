<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Images {

	function loadTopPicture($artist)
	{
		$CI =& get_instance();
		$CI->db->where('artist_seo_name', $artist);
		$query = $CI->db->get('artists');
		if($query->num_rows() == 1)
		{
			$row = $query->row();
			if($row->artist_picture == null || $row->artist_picture_verified != 1)
			{
				return null;
			} else {
				if($row->artist_picture_verified == 1)
				{
					$picture = $row->artist_picture;
					$filename = substr($row->artist_picture, 0, -4);
					$extension = substr($row->artist_picture, -4);

					return $filename . '_thumb' . $extension;
				}			
			}	
		}

	}

}
?>

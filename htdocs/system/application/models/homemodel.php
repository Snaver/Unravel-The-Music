<?php
class HomeModel extends Model {


    function __construct()
    {
        // Call the Model constructor
        parent::Model();
		
    }
    
    function getFeed($artists, $songs)
    {
		
		$this->db->or_where('type', 0);
		$this->db->order_by('created_on', 'desc');
		$data = $this->db->get('feed', 40);    
		return $data;
      
    }
    function getFeedNoUser()
    {
		$this->db->join('artists', 'artists.artist_seo_name = feed.artist_seo_name');
		$this->db->order_by('created_on', 'desc');
		$data = $this->db->get('feed', 60);    
		return $data;
    }
	
	function getFeedSmall($type)
	{
		$this->db->where('type', $type);
		$this->db->order_by('created_on', 'desc');
		if($type == 2)
		{
			return $this->db->get('feed', 60);
		} else {
			return $this->db->get('feed', 40);
		}
	
	}
	
	function getTagged()
	{
		$this->db->where('user_id', $this->session->userdata('DX_user_id'));
		$this->db->join('artists', 'artists.artist_id = watched_artists.artist_id');
		$query = $this->db->get('watched_artists');

		
		$this->db->where('user_id', $this->session->userdata('DX_user_id'));
		$this->db->join('songs', 'songs.song_id = watched_songs.song_id');
		$query2 = $this->db->get('watched_songs');
		if($query->num_rows() > 0)
		{
			foreach($query2->result() as $feedItem2)
			{
				$this->db->or_where('song_seo_name', $feedItem2->song_seo_name);
			}
		}		
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $feedItem)
			{
				$this->db->or_where('artist_seo_name', $feedItem->artist_seo_name);
			}
		}		
		
		
		
		$this->db->order_by('created_on', 'desc');
		return $this->db->get('feed');
		
	}

	function addAlbumToFeed($artist, $album)
	{
		date_default_timezone_set('America/Chicago');
		$date = date('Y-n-d G:i:s');
		$plusOne = time() + (24*3600);
		$tomorrow = date('Y-n-d G:i:s', $plusOne);		
		
        $data = array(
                 'type' => 1,
                 'artist' => $artist,
				 'artist_seo_name' => url_title($artist),
                 'album' => $album,
				 'username' => $this->session->userdata('DX_username'),
				 'album_seo_name' => url_title($album),
                 'created_on' => $date,
                 'expires' => $tomorrow
                );
        $this->db->insert('feed', $data);	
	
	}//end addAlbumtoFeed Function
    function addArtistToFeed($artist, $username)
    {
		date_default_timezone_set('America/Chicago');
		$date = date('Y-n-d G:i:s');
		$plusOne = time() + (24*3600);
		$tomorrow = date('Y-n-d G:i:s', $plusOne);		
		
        $data = array(
                 'type' => 3,
                 'artist' => $artist,
                 'username' => $username,
				 'artist_seo_name' => url_title($artist),
                 'created_on' => $date,
                 'expires' => $tomorrow
                );
        $this->db->insert('feed', $data);
    
    }//end addToFeed Function

    function addCommentToFeed($album, $artist, $song, $meaningId, $info, $author)
    {
 		date_default_timezone_set('America/Chicago');
		$date = date('Y-n-d G:i:s');
		$plusOne = time() + (24*3600);
		$tomorrow = date('Y-n-d G:i:s', $plusOne);		
		
        $data = array(
				'related_id' => $meaningId,
				'info' => $info,
				'username' => $author,
				'type' => '2',
                'artist' => $artist,
				'artist_seo_name' => url_title($artist),
                'album' => $album,
				'album_seo_name' => url_title($album),
                'song' => $song,
				'song_seo_name' => url_title($song),
                'created_on' => $date,
                'expires' => $tomorrow
                );
        $this->db->insert('feed', $data);      
     

    
    }//end addToFeed Function
	
	function addNewsToFeed($title, $author, $newsId)
	{
 		date_default_timezone_set('America/Chicago');
		$date = date('Y-n-d G:i:s');
		$plusOne = time() + (24*3600);
		$tomorrow = date('Y-n-d G:i:s', $plusOne);		
		
		$data = array(
			'related_id' => $newsId,
			'info' => $title,
			'type' => '0',
			'username' => $author,
			'created_on' => $date,
			'expires' => $tomorrow
			);
			$this->db->insert('feed', $data);
	
	}
}
?>
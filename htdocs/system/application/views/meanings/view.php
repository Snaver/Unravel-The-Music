        
<?php $row = $query->row(); ?>
    <table border = 1>
    <?=anchor('artists/view/' . $row->artist_seo_name, $row->artist) . " - ". anchor('songs/view/' . $row->artist_seo_name . "/" . $row->album_seo_name . "/" . $row->song_seo_name, $row->song)?>
      <tr>
        <td>
          <?=$row->title?>
        </td>
      </tr>
      <tr>
        <td>
          <?=$row->created_on?>
        </td>
      </tr>
      <tr>
        <td>
          <?=$row->author?>
        </td>
      </tr>
      <tr>
        <td>
          <?=$row->body?>
        </td>
      </tr>      
    </table>
    


  </body>
</html>

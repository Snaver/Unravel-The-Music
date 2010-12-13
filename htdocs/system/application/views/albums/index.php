
    <table border = 1>
    <?php foreach($query->result() as $row): ?>
      <tr>
        <td>
          <h3><?=$row->artist?></h3>
        </td>
        <td>
          <p><?=anchor('albums/view/' . $row->artist . '/' . $row->album, 'view songs in ' . $row->album . ' by ' . $row->artist)?></p>
        </td>
        <td>
           image of band!  
        </td>     
      </tr>
      <?php endforeach; ?>
    </table>


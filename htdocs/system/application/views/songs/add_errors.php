<?php
if(isset($message) && !empty($message))
{
  echo($message);
  
}
if(isset($type))
{
  ?>
  <p>is it possible you meant one of these?</p>
  <ul>
  <?php 


  if($type == 'album')
  {
  foreach($suggestion as $album) : ?>
  <li>
    <?=anchor('songs/add/' . $artist . '/' . $album, $album)?>
    </li>
  <?php endforeach; }
  else {
  foreach($suggestion as $artist) : ?>
  <li>
    <?=anchor('songs/add/' . $artist . '/' . $album, $artist)?>
  </li>
  <?php endforeach;
  }
  
  ?>
  </ul>
  <?php
}
exit;
?>

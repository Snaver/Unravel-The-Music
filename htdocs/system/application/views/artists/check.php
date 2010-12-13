<html>
  <head>
    <title>
      UnravelTheMusic.com - We may already have this band
    </title>
  </head>
  <body>
    <h3>We think we may already have what you are trying to add.</h3>
    <p>take a look at the following artist(s) and see if that's what you were trying to add.</p>
    <table border='1'>
    
    <?php
    foreach($checks as $check):
    
    ?>
      <tr>
        <td>
        <?=anchor('artists/view/' . $check, $check)?>
        </td>

      </tr>
    <?php
    endforeach;?>
    </table>
    <?php
    $hidden = array('final' => 'true', 'name' => $name);
    echo form_open('artists/submit', '', $hidden);
    echo("<p>" . $name . "</p>");
    echo form_submit('submit artist', 'Yes, I\'m really sure I want to add this!');
    echo form_close();
    ?>


  </body>
</html>
<?php exit; ?>

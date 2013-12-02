<?php include 'header.php'; ?>
  <?php
    $dbOk = false;
    /* Create a new database connection object, passing in the host, username,
       password, and database to use. The "@" suppresses errors. */
    @ $db = new mysqli('localhost', 'root', 'admin', 'blitzpong');
    
    if ($db->connect_error) {
      echo '<div class="messages">Could not connect to the database. Error: ';
      echo $db->connect_errno . ' - ' . $db->connect_error . '</div>';
    } else {
      $dbOk = true; 
    }

  if ($dbOk) {

    $id = $_GET['user'];
    $query = 'select * from users where `userid`='.$id;
    $result = $db->query($query);
    $record = $result->fetch_assoc();
    

    echo '<div id="profileName" align="center">'.$record['username'].'</div>';

    echo '<h3>Record</h3>';
    echo '<table class="table" align="center">';
    echo '<tr><th>Wins-Losses</th><th>GamesPlayed</th><th>Win%</th></tr>';
    echo '<tr><td>';
    echo htmlspecialchars($record['wins'].'-'.$record['losses']);
    echo '</td><td>';
    // calculate total games and win %
    $totalgames = $record['wins'] + $record['losses'];
    if ($totalgames != 0){
      $winper = ($record['wins'] / $totalgames)*100; 
    }else{
      $winper = 0;
    }
    echo "$totalgames";
    echo '</td><td>';
    echo "$winper". '%';
    echo '</td></tr>';
    echo '</table';
  }else{
    echo 'WHO?';
  }
  ?>
<?php include 'footer.php'; ?>

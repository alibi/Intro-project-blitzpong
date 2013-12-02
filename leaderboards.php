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

    $query = 'select * from users order by wins desc';
    $result = $db->query($query);
    $numRecords = $result->num_rows;
    
    echo  '<table class="table" align="center">';
    echo '<tr><th>Username</th><th>Wins-Losses</th><th>GamesPlayed</th><th>Win%</th></tr>';
    for ($i=0; $i < $numRecords; $i++) {
      $record = $result->fetch_assoc();

      // $name = $record['userid'];
      // $score = $row['Score'];
      // $gp = $row['GamesPlayed'];
      // $winp = $row['WinP'];

      echo '<tr><td>';
      $name = $record['userid'];
      echo '<a href="playerpager.php?user='."$name".'">';
      echo htmlspecialchars($record['username']);
      echo '</a>';
      echo '</td><td>';
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
      // Uncomment the following three lines to see the underlying 
      // associative array for each record.
      /*echo '<tr><td colspan="3" style="white-space: pre;">';
      print_r($record);
      echo '</td></tr>';*/
    }
    
    $result->free();
    
    // Finally, let's close the database
    $db->close();
  }
?>
</table>



<?php include 'footer.php'; ?>

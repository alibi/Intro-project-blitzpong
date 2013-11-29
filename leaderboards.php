<?php include 'header.php'; ?>
    <!--
    <table align="center" border="1" class="table" id="leaderboard" cellpadding="0" cellspacing="0">
      <tr>
        <th> Player Name </th>
        <th> Wins-Losses </th>
        <th> Games Played </th>
        <th> Win % </th>
      </tr>
      <tr>
        <td> A </td>
        <td> 4-0 </td>
        <td> 4 </td>
        <td> 100% </td>
      </tr>
      <tr>
        <td> B </td>
        <td> 0-4 </td>
        <td> 4 </td>
        <td> 0% </td>
      </tr>
    </table>
    -->
<table class="table" align="center">
<?php
    $dbOk = false;
    /* Create a new database connection object, passing in the host, username,
       password, and database to use. The "@" suppresses errors. */
    @ $db = new mysqli('localhost', 'root', 'password', 'pong');
    
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
    
    echo '<tr><th>Username</th><th>Wins-Losses</th><th>GamesPlayed</th><th>Win%</th></tr>';
    for ($i=0; $i < $numRecords; $i++) {
      $record = $result->fetch_assoc();
      echo '<tr><td>';
      echo htmlspecialchars($record['Username']);
      echo '</td><td>';
      echo htmlspecialchars($record['Wins'].'-'.$record['Losses']);
      echo '</td><td>';
      // calculate total games and win %
      $totalgames = $record['Wins'] + $record['Losses'];
      if ($totalgames != 0){
        $winper = ($record['Wins'] / $totalgames)*100; 
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

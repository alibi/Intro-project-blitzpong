<?php include 'header.php'; ?>

  
<?php
  // We'll need a database connection both for retrieving records and for 
  // inserting them.  Let's get it up front and use it for both processes
  // to avoid opening the connection twice.  If we make a good connection, 
  // we'll change the $dbOk flag.
  $dbOk = false;
  
  /* Create a new database connection object, passing in the host, username,
     password, and database to use. The "@" suppresses errors. */
  @ $db = new mysqli('localhost', 'root', 'root', 'iit');
  
  if ($db->connect_error) {
    echo '<div class="messages">Could not connect to the database. Error: ';
    echo $db->connect_errno . ' - ' . $db->connect_error . '</div>';
  } else {
    $dbOk = true; 
  }

  // Now let's process our form:
  // Have we posted?
  $havePost = isset($_POST["save"]);
  
  // Let's do some basic validation
  $errors = '';
  if ($havePost) {
    
    // Get the output and clean it for output on-screen.
    // First, let's get the output one param at a time.
    // Could also output escape with htmlentities()
    $firstNames = htmlspecialchars(trim($_POST["firstNames"]));  
    $lastName = htmlspecialchars(trim($_POST["lastName"]));
    $dob = htmlspecialchars(trim($_POST["dob"]));
    
    // special handling for the date of birth
    $dobTime = strtotime($dob); // parse the date of birth into a Unix timestamp (seconds since Jan 1, 1970)
    $dateFormat = 'Y-m-d'; // the date format we expect, yyyy-mm-dd
    // Now convert the $dobTime into a date using the specfied format.
    // Does the outcome match the input the user supplied?  
    // The right side will evaluate true or false, and this will be assigned to $dobOk
    $dobOk = date($dateFormat, $dobTime) == $dob;  
    
    $focusId = ''; // trap the first field that needs updating, better would be to save errors in an array
    
    if ($firstNames == '') {
      $errors .= '<li>First name may not be blank</li>';
      if ($focusId == '') $focusId = '#firstNames';
    }
    if ($lastName == '') {
      $errors .= '<li>Last name may not be blank</li>';
      if ($focusId == '') $focusId = '#lastName';
    }
    if ($dob == '') {
      $errors .= '<li>Date of birth may not be blank</li>';
      if ($focusId == '') $focusId = '#dob';
    }
    if (!$dobOk) {
      $errors .= '<li>Enter a valid date in yyyy-mm-dd format</li>'; 
      if ($focusId == '') $focusId = '#dob';
    }
  
    if ($errors != '') {
      echo '<div class="messages"><h4>Please correct the following errors:</h4><ul>';
      echo $errors;
      echo '</ul></div>';
      echo '<script type="text/javascript">';
      echo '  $(document).ready(function() {';
      echo '    $("' . $focusId . '").focus();';
      echo '  });';
      echo '</script>';
    } else { 
      if ($dbOk) {
        // Let's trim the input for inserting into mysql
        // Note that aside from trimming, we'll do no further escaping because we
        // use prepared statements to put these values in the database.
        $firstNamesForDb = trim($_POST["firstNames"]);  
        $lastNameForDb = trim($_POST["lastName"]);
        $dobForDb = trim($_POST["dob"]);
        
        // Setup a prepared statement. Alternately, we could write an insert statement - but 
        // *only* if we escape our data using addslashes() or (better) mysqli_real_escape_string().
        $insQuery = "insert into actors (`last_name`,`first_names`,`dob`) values(?,?,?)";
        $statement = $db->prepare($insQuery);
        // bind our variables to the question marks
        $statement->bind_param("sss",$lastNameForDb,$firstNamesForDb,$dobForDb);
        // make it so:
        $statement->execute();
        
        // give the user some feedback
        echo '<div class="messages"><h4>Success: ' . $statement->affected_rows . ' actor added to database.</h4>';
        echo $firstNames . ' ' . $lastName . ', born ' . $dob . '</div>';
        
        // close the prepared statement obj 
        $statement->close();
      }
    } 
  }
?>

<h3>New User</h3>
<form id="addForm" name="addForm" action="create.php" method="post" onsubmit="return validate(this);">
  <fieldset> 
    <div class="formData">
                    
      <label class="field" for="firstNames">Username:</label>
      <div class="value"><input type="text" size="60" value="<?php if($havePost && $errors != '') { echo $firstNames; } ?>" name="firstNames" id="firstNames"/></div>
      
      <label class="field" for="lastName">Password:</label>
      <div class="value"><input type="text" size="60" value="<?php if($havePost && $errors != '') { echo $lastName; } ?>" name="lastName" id="lastName"/></div>
      <input type="submit" value="save" id="save" name="save"/>
    </div>
  </fieldset>
</form>


<?php include 'footer.php'; ?>

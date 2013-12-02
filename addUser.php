<?php 
  /*
  include('includes/init.inc.php');
  include('includes/functions.inc.php');
  */ 
?>
<title>Blitz Pong Userbase Storage</title>   

<?php 
  include('header.php'); 
?>


<?php
  $dbOk = false;

  @ $db = new mysqli('localhost', 'root', 'password', 'pong');
  
  if ($db->connect_error) {
    echo '<div class="messages">Could not connect to the database. Error: ';
    echo $db->connect_errno . ' - ' . $db->connect_error . '</div>';
  } else {
    $dbOk = true; 
  }

  $havePost = isset($_POST["save"]);
  
  $errors = '';
  if ($havePost) {
    
    $username = htmlspecialchars(trim($_POST["username"]));  
    $password = htmlspecialchars(trim($_POST["password"]));
    $passwordcheck = htmlspecialchars(trim($_POST["passwordcheck"]));
    
    $focusId = '';
    
    if ($username == '') {
      $errors .= '<li>Username may not be blank!</li>';
      if ($focusId == '') $focusId = '#username';
    }
    if ($password == '') {
      $errors .= '<li>Password may not be blank!</li>';
      if ($focusId == '') $focusId = '#password';
    }
    if ($passwordcheck == '' or $passwordcheck != $password) {
      $errors .= '<li>Password fields must match!</li>';
      if ($focusId == '') $focusId = '#password';
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
        
        $usernameForDb = trim($_POST["username"]);  
        $passwordForDb = trim($_POST["password"]);
        $userWins = 0;
        $userLosses = 0;
        
        $insQuery = "insert into users (`username`,`password`,`wins`, `losses`) values(?,?,?,?)";
        $statement = $db->prepare($insQuery);
        
        $statement->bind_param("sss",$usernameForDb,$passwordForDb,$userWins, $userLosses);
        
        $statement->execute();
        
        
        echo '<div class="messages"><h4>Success: ' . $statement->affected_rows . ' User added to database.</h4>';
        echo $username . '</div>';
        
        
        $statement->close();
      }
    } 
  }
?>

<h3>Add User</h3>
<form id="addForm" name="addForm" action="index.php" method="post" onsubmit="return validate(this);">
  <fieldset> 
    <div class="formData">
                    
      <label class="field" for="username">Username:</label>
      <div class="value"><input type="text" size="60" value="<?php if($havePost && $errors != '') { echo $username; } ?>" name="username" id="username"/></div>
      
      <label class="field" for="password">Password:</label>
      <div class="value"><input type="text" size="60" value="<?php if($havePost && $errors != '') { echo $password; } ?>" name="password" id="password"/></div>
      
      <label class="field" for="passwordcheck">Re-type Password:</label>
      <div class="value"><input type="text" size="10" maxlength="10" value="<?php if($havePost && $errors != '') { echo $passwordcheck; } ?>" name="passwordcheck" id="passwordcheck"/></div>
      
      <input type="submit" value="save" id="save" name="save"/>
    </div>
  </fieldset>
</form>


<?php include('footer.php'); 
?>

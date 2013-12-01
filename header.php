<!doctype html>
<html>
	<head>
		
		<!--<script src="pong.js"> </script>-->
		<script src="jquery-2.0.3.min.js"></script>
		<script src="pong.js" type="text/javascript"></script>
		<title>Blitz Pong
		<?php 
			if (basename($_SERVER['PHP_SELF']) == "ponghome.php") echo "Index";
			else if (basename($_SERVER['PHP_SELF']) == "leaderboards.php") echo "Leaderboards";
			else if (basename($_SERVER['PHP_SELF']) == "playerPager.php") echo "Profile";
			// etc
		?>
		</title>
		<link rel="stylesheet" type="text/css" href="pongBlitzStyle.css">
	</head>
	<body>

		<h1>Blitz Pong</h1> 
	    <ul id="navBar">
	      <li><a href="ponghome.php">Home</a></li>
	      <li> |</li>
	      <li><a href="leaderboards.php">Leaderboards</a></li>
	      <!--
	      <li> | </li>
	      <li><a href="playerPager.php">Me</a></li>
	      -->
	    </ul>
			



<?php
// Enter your Host, username, password, database below.
// I left password empty because i do not set password on localhost.
$con = new mysqli("localhost","root","Gnouhk99@","customer_management");
// Check connection
if ($con->connect_error)
  {
  echo "Failed to connect to MySQL";
  }
?>
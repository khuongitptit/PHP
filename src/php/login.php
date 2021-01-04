<?php
  include 'db.php';
  $username = $_POST['username'];
  $password = $_POST['password'];
  $sql = 'SELECT * FROM staff WHERE username = ? AND password = ?';
  $ps = $con->prepare($sql);
  $ps->bind_param("ss",$username,$password);
  $ps->execute();
  $result = $ps->get_result();
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $user->id = $row['id'];
      $user->name = $row['name'];
      $user->username = $row['username'];
      $userJSON = json_encode($user);
      echo $userJSON;  
      break;
    }
  } else {
    $error->success = false;
    $errorJSON = json_encode($error);
    echo $errorJSON;
  }
  $con->close();
?>
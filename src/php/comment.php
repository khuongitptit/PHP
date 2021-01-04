<?php
  include 'db.php';
  $requestMethod = $_SERVER['REQUEST_METHOD'];
  switch($requestMethod){
    case 'GET':
      $action = $_GET['action'];
      if($action == 'GET_BY_CUSTOMER_ID'){
        $customerId = $_GET['customerId'];
        $getCommentSQL = 'SELECT cmt.*,ctm.name FROM customerComment AS cmt, customer AS ctm WHERE cmt.customerId = ? AND cmt.customerId = ctm.id ';
        $ps = $con->prepare($getCommentSQL);
        $ps->bind_param("i",$customerId);
        $ps->execute();
        $result = $ps->get_result();
        if ($result->num_rows > 0) {
          $commentList = array();
          while($row = $result->fetch_object()) {
            array_push($commentList,$row);
          }
          $commentListJSON = json_encode($commentList);
            echo $commentListJSON;  
        } else {
          echo json_encode(array());
        }
      }
    break;
    case 'POST':
      $action = $_POST['action'];
      if($action == 'ADD'){
        $email = $_POST['email'];
        $comment = $_POST['comment'];
        $syncCustomerSQL = 'SELECT * FROM customer where email = ?';
        $syncCustomerPS = $con->prepare($syncCustomerSQL);
        $syncCustomerPS->bind_param("s",$email);
        $syncCustomerPS->execute();
        $syncCustomerResult = $syncCustomerPS->get_result();
        if ($syncCustomerResult->num_rows > 0) {
          $customer = $syncCustomerResult->fetch_object();
          //customer existed in mysql
          $addCommentSQL = 'INSERT INTO customerComment (customerId, comment, time) VALUES (?,?,now())';
          $addCommentPS = $con->prepare($addCommentSQL);
          $addCommentPS->bind_param("is",$customer->id, $comment );
          $addCommentPS->execute();
          $result->success = true;
          echo json_encode($result);
        } else {
          $addCommentSQL = 'INSERT INTO customerComment (comment, time) VALUES (?,now())';
          $addCommentPS = $con->prepare($addCommentSQL);
          $addCommentPS->bind_param("s", $comment );
          $addCommentPS->execute();
          $result->success = true;
          echo json_encode($result);
        }
        
      }
    break;
  }
  
?>
<?php
  include 'db.php';
  $requestMethod = $_SERVER['REQUEST_METHOD'];
  switch($requestMethod){
    case 'GET':
      $action = $_GET['action'];
      if($action == 'GET_ALL'){
        $sql = 'SELECT * FROM customer';
        $ps = $con->prepare($sql);
        $ps->execute();
        $result = $ps->get_result();
        if ($result->num_rows > 0) {
          $customerList = array();
          while($row = $result->fetch_object()) {
            array_push($customerList,$row);
          }
          $customerListJSON = json_encode($customerList);
            echo $customerListJSON;  
        } else {
          echo json_encode(array());
        }
      }
      if($action == 'GET_BY_NAME'){
        $keyword = $_GET['keyword'];
        $sql = 'SELECT * FROM customer WHERE name LIKE CONCAT("%",?,"%")';
        $ps = $con->prepare($sql);
        $ps->bind_param("s", $keyword);
        $ps->execute();
        $result = $ps->get_result();
        if ($result->num_rows > 0) {
          $customerList = array();
          while($row = $result->fetch_object()) {
            array_push($customerList,$row);
          }
          $customerListJSON = json_encode($customerList);
            echo $customerListJSON;  
        } else {
          echo json_encode(array());
        }
      }
      if($action == 'GET_BY_ID'){
        $customerId = $_GET['customerId'];
        $sql = 'SELECT * FROM customer WHERE id = ?';
        $ps = $con->prepare($sql);
        $ps->bind_param("i", $customerId);
        $ps->execute();
        $result = $ps->get_result();
        if ($result->num_rows > 0) {
          $customerList = array();
          while($row = $result->fetch_object()) {
            array_push($customerList,$row);
          }
          $customerListJSON = json_encode($customerList);
            echo $customerListJSON;  
        } else {
          echo json_encode(array());
        }
      }
      if($action == 'STAT_CUSTOMER'){
        $sql = 'SELECT p.customerId, p.serviceId, s.price, c.* FROM `purchase` AS p, `service` AS s, `customer` AS c WHERE p.serviceId = s.id AND p.customerId = c.id';
        $ps = $con->prepare($sql);
        $ps->execute();
        $result = $ps->get_result();
        if ($result->num_rows > 0) {
          $statList = array();
          while($row = $result->fetch_object()) {
            array_push($statList,$row);
          }
          $statListJSON = json_encode($statList);
            echo $statListJSON;  
        } else {
          echo json_encode(array());
        }
      }
    break;
    case 'POST':
      $action = $_POST['action'];
      if($action == 'ADD'){
        $name = $_POST['name'];
        $avatarURL = $_POST['avatarURL'];
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $sql = 'INSERT INTO customer (name,avatarURL,dob,gender,phone,email,address) VALUES (?,?,?,?,?,?,?)';
        $ps = $con->prepare($sql);
        $ps->bind_param("sssisss",$name,$avatarURL,$dob,$gender,$phone,$email,$address);
        $ps->execute();
        $result->success = true;
        echo json_encode($result);
      }
      if($action == 'DELETE'){
        $customerId = $_POST['customerId'];
        $sql = 'DELETE FROM customer WHERE id = ?';
        $ps = $con->prepare($sql);
        $ps->bind_param("i",$customerId);
        $ps->execute();
        $result->success = true;
        echo json_encode($result);
      }
      if($action == 'UPDATE'){
        $id = $_POST['customerId'];
        $name = $_POST['name'];
        $avatarURL = $_POST['avatarURL'];
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $sql = 'UPDATE customer SET name = ?, avatarURL = ?, dob = ?, gender = ?, address = ?, phone = ?, email = ? WHERE id = ?';
        $ps = $con->prepare($sql);
        $ps->bind_param("sssisssi",$name,$avatarURL,$dob,$gender,$address,$phone,$email,$id);
        $ps->execute();
        $result->success = true;
        echo json_encode($result);
      }
    break;
  }
  
?>
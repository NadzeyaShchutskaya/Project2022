<?php
  header('Content-Type: text/html; charset=UTF-8');
    if(isset($_POST) && !empty($_POST['user']) && !empty($_POST['time'])){
    $user = $_POST['user'];
    $record = $_POST['time'];

    $con=mysqli_connect("127.0.0.1","root","");

      if(!$con){
        exit("errdata");
    } else{
        echo "connected to server";
        
        if(!mysqli_select_db($con,"game")){
          exit("errdata");
      } else{
          mysqli_set_charset($con,'utf8');

          $userID=0;
          $stmt = $con->prepare("SELECT * FROM recordboard WHERE user = ?");
          $stmt->bind_param('s', $user);
          $stmt->execute();
          $result = $stmt->get_result();
          
          if ($result->num_rows == 1){
                
            $row = $result->fetch_assoc();

            $userID=$row["id"];
            
            if($row["record"]<$record){
              $stmt = $con->prepare("UPDATE recordboard SET record=? WHERE user = ?");
              $stmt->bind_param("ss", $record,$user);
              $stmt->execute();
          };
        } else{
            $stmt = $con->prepare("INSERT INTO recordboard (record,user) VALUES(?,?)");
            $stmt->bind_param("ss", $record,$user);
            $stmt->execute();
						$userID = $con->insert_id;
          };
            
            $stmt = $con->prepare("SELECT * FROM recordboard ORDER BY record ASC LIMIT 5");
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0){
              echo "success";
              $counter=0;
              $a="a";
              $tempID="";
              while($row = $result->fetch_assoc()){
                echo "tempu".$userID."temphe".$row["id"];
                if($row["id"]==$userID){
                  echo "exists";
                }
                $counter++;
                $tempID=$counter.$a;
                echo "userName".$tempID.$row["user"]."userRecord".$tempID.$row["record"]."finish".$tempID;
              }
                echo "totalstart".$counter."totalend";  
            } else{
                exit("empty");
              }
              $stmt->close();
          }             
        }
    } else {
        exit("errempty");
      };
?>
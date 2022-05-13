<?php
include('config/db_connect.php');
switch ($method) {
    case 'GET':
      if(isset($_GET["id"])){
        $id = $_GET['id'];  
      }     
      $sql = "select * from tbl_cat".($id?" where id=$id":''); 
      break;
    case 'POST':
        if(isset($_GET["id"])){
            $id = $_GET['id'];  
            $name = $_POST["name"];
            $email = $_POST["email"];
            $country = $_POST["country"];
            $city = $_POST["city"];
            $job = $_POST["job"];
            $sql = "UPDATE tbl_cat SET name='$name', email='$email', city='$city', country='$country', job='$job' WHERE id = $id"; 
        }else if(isset($_GET["delete"])){
            $delete = $_GET['delete'];  
            $sql = "DELETE FROM tbl_cat WHERE id = $delete"; 
        }else{  
          $picture = $_POST["picture"];
          $title = $_POST["title"];
          $description = $_POST["description"];
          $sql = "insert into blog (picture, title, description) values ('$picture', '$title', '$description')"; 
        }
      break;
}

// run SQL statement
$result = mysqli_query($connection,$sql);

// die if SQL statement failed
if(!$result) {
    http_response_code(404);
    die(mysqli_error($connection));
}

if($method == 'GET') {
    if(!$id) echo '[';
    for($i = 0; $i<mysqli_num_rows($result); $i++) {
        echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
    }
    if(!$id) echo ']';
} else if($method == 'POST'){
    echo json_encode($result);
} 
else {
    echo mysqli_affected_rows($connection);
}


$connection->close();

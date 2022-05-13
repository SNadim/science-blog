<?php
header("Access-Control-Allow-Origin: *"); // add this CORS header to enable any domain to send HTTP requests to there endpoints;
$serverName = "localhost:3307";
$userName = "root";
$password = "";
$dbName = "test";
$id = '';
$connection = mysqli_connect($serverName,$userName,$password,$dbName);
$method = $_SERVER['REQUEST_METHOD'];
if(!$connection) {
    die("Connection Failed ".mysqli_connect_error());
}

switch ($method) {
    case 'GET':
      if(isset($_GET["id"])){
        $id = $_GET['id'];  
      }     
      $sql = "select * from contacts".($id?" where id=$id":''); 
      break;
    case 'POST':
        if(isset($_GET["id"])){
            $id = $_GET['id'];  
            $name = $_POST["name"];
            $email = $_POST["email"];
            $country = $_POST["country"];
            $city = $_POST["city"];
            $job = $_POST["job"];
            $sql = "UPDATE contacts SET name='$name', email='$email', city='$city', country='$country', job='$job' WHERE id = $id"; 
        }else if(isset($_GET["delete"])){
            $delete = $_GET['delete'];  
            $sql = "DELETE FROM contacts WHERE id = $delete"; 
        }else{  
          $name = $_POST["name"];
          $email = $_POST["email"];
          $country = $_POST["country"];
          $city = $_POST["city"];
          $job = $_POST["job"];
 
          $sql = "insert into contacts (name, email, city, country, job) values ('$name', '$email', '$city', '$country', '$job')"; 
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

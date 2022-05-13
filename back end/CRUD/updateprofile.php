<?php 
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");
 
$response = array();
$upload_dir = 'userprofile/';
$server_url = 'http://localhost/CRUD/';
if($_FILES['avatar'])
{
    $avatar_name = $_FILES["avatar"]["name"];
    $avatar_tmp_name = $_FILES["avatar"]["tmp_name"];
    $error = $_FILES["avatar"]["error"];
 
    if($error > 0){
        $response = array(
            "status" => "error",
            "error" => true,
            "message" => "Error uploading the file!"
        );
    }else
    {
        $random_name = rand(1000,1000000)."-".$avatar_name;
        $upload_name = $upload_dir.strtolower($random_name);
        $upload_name = preg_replace('/\s+/', '-', $upload_name);
     
        if(move_uploaded_file($avatar_tmp_name , $upload_name)) {
            $response = array(
                "status" => "success",
                "error" => false,
                "message" => "File uploaded successfully",
                "url" => $server_url."/".$upload_name
              );
               
              $serverName = "localhost:3307";
              $userName = "root";
              $password = "";
              $dbName = "db_blog";
              $connection = mysqli_connect($serverName,$userName,$password,$dbName);   
              $name = $_POST['username'];
              $email = $_POST["email"];
              $pass = $_POST['password'];
              $desc =  $connection -> real_escape_string($_POST['desc']);
              $id = $_POST['id'];
              if(!$connection) {
                  die("Connection Failed ".mysqli_connect_error());
              }
  
              $sql = "UPDATE tbl_user SET username='$name', email='$email', password='$pass', picture='$upload_name', description='$desc'  WHERE id = $id"; 
              mysqli_query($connection, $sql);
        }else
        {
            $response = array(
                "status" => "error",
                "error" => true,
                "message" => "Error uploading the file!"
            );
        }
    }
 
}else{
    $response = array(
        "status" => "error",
        "error" => true,
        "message" => "No file was sent!"
    );
}
 
echo json_encode($response);
?>




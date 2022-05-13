<?php
include('config/db_connect.php');
switch ($method) {
    case 'GET':
      $sql = "select * from tbl_post where status = 'approved'";
      if(isset($_GET["id"])){
        $id = $_GET['id'];
        $sql = "select * from tbl_post".($id?" where id=$id":''); 
      }

      if(isset($_GET["cat_id"])){
        $id = $_GET['cat_id'];
        $sql = "select * from tbl_post".($id?" where cat_id=$id":''); 
      }
      if(isset($_GET["user"])){
        $id = $_GET['user'];
        $sql = "select * from tbl_post".($id?" where author='$id'":'');
      }
      
      
      break;
    case 'POST':
        if(isset($_GET["update"])){
            $id = $_GET['update'];  
            $title = $_POST["title"];
            $desc = $_POST["desc"];
            $sql = "UPDATE tbl_post SET title='$title', description='$desc' WHERE id = $id"; 
        }else if(isset($_GET["delete"])){
            $delete = $_GET['delete'];
            if(unlink($_POST['image'])) {
              echo "There is problem deleting image";
            } else {
              echo "Deleting image successfully";
            }
            $sql = "DELETE FROM tbl_post WHERE id = $delete"; 
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
     echo '[';
    for($i = 0; $i<mysqli_num_rows($result); $i++) {
        echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
    }
     echo ']';
} else if($method == 'POST'){
    echo json_encode($result);
}
else {
    echo mysqli_affected_rows($connection);
}


$connection->close();

<?php
session_start();
require "../config/config.php";
require "../config/common.php";

error_reporting(E_ERROR | E_WARNING | E_PARSE);
if (empty($_SESSION['user_id'] && $_SESSION['logged_in'])) {
    header('Location: login.php');
}
if($_SESSION['role'] != 1){
    header('Location: login.php');
  }

if ($_POST) { 
    if(empty($_POST['title']) || empty($_POST['description']) ){

        if(empty($_POST['title'])){
          $titleError = "Title cannot be null";
        }
        if(empty($_POST['description'])){
          $descError = "Description cannot be null";
        }
       
    
    }else{
    $id = $_POST['id'];
    $title = $_POST['title'];
    $desc = $_POST['description'];
    if ($_FILES['image']['name'] != null) {
        $file = 'upload/'.($_FILES['image']['name']);
        // $tmpFile = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $filetype = pathinfo($fileName, PATHINFO_EXTENSION);

        if ($filetype != 'png' && $filetype != 'jpg' && $filetype != 'jpeg') {
            echo "<script>alert('Image must be PNG,JPG or JPEG.');</script>";

        } else {

            $image = $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'],$file);
            $stmt = $pdo->prepare("UPDATE post SET title='$title',description='$desc',image='$image' WHERE id ='$id'");
            $result = $stmt->execute();
            if ($result) {
                echo "<script> alert('New todo is added');window.location.href='index.php';</script>";
            }

        }

    } else {
        $stmt = $pdo->prepare("UPDATE post SET title='$title',description='$desc' WHERE id ='$id'");
        $result = $stmt->execute();
        if ($result) {
            echo "<script> alert('New todo is added');window.location.href='index.php';</script>";
        }
    }
}
}

    $stmt = $pdo->prepare("SELECT * FROM post WHERE id=".$_GET['id']);
    $stmt->execute();
    $result = $stmt->fetchAll();

?>

<?php require "header.php"?>
    <div class="card">
        <div class="card-body">
            <h1>Create New Todo</h1>
            <form enctype="multipart/form-data" action="" method="post" >
            <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']?>">
                <input type="hidden" name="id" value="<?php echo $result[0]['id'] ?>">
                <div class="form-group">
                    <label for="title">Title</label><p style="color:red"><?php echo empty($titleError) ? '': "***".$titleError ?></p>
                    <input type="text" class="form-control" name="title" value="<?php echo escape($result[0]['title']) ?>">
                </div>
                <div class="form-group">
                    <label for="description">Description</label><p style="color:red"><?php echo empty($descError) ? '': "***".$descError ?></p>
                    <textarea name="description" class="form-control"   cols="80" rows="8"><?php echo escape($result[0]['description']) ?></textarea>
                </div>
                <div class="form-group">
                    <label for="file">Select file to upload :</label><br />
                    <img src="upload/<?php echo $result[0]['image'] ?>" width="300" height="250" alt=""><br>
                    <input  type="file"  name="image"><br /><br />Click to upload<br />
                </div><br>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" name="submit" value="Submit">
                    <a href="index.php" type="button" class="btn btn-warning">Back</a>
                </div>
            </form>
        </div>
    </div>

<?php require "footer.html"?>


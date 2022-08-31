<?php
session_start();
require "../config/config.php";
require "../config/common.php";



  if(empty($_SESSION['user_id'] && $_SESSION['logged_in'])){
    header('Location: login.php');

  }

  if($_SESSION['role'] != 1){
       header('Location: login.php');
   }

  if($_POST){
    if(empty($_POST['title']) || empty($_POST['description'] || empty($_FILES['file']))){

      if(empty($_POST['title'])){
        $titleError = "Title cannot be null";
      }
      if(empty($_POST['description'])){
        $descError = "Description cannot be null";
      }
      if(empty($_FILES['file'])){
        $imageError = "File cannot be null";
      }
    }else{
      $tmpFile=$_FILES['file']['tmp_name'];
      $fileName=$_FILES['file']['name'];
      $filetype = pathinfo($fileName,PATHINFO_EXTENSION);

      if($filetype !='png' && $filetype !='jpg' && $filetype !='jpeg') {
          echo "<script>alert('Image must be PNG,JPG or JPEG.');</script>";
        }else
          { 
            $title = $_POST['title'];
            $desc = $_POST['description'];
             
            $image=$_FILES['file']['name'];
            
            move_uploaded_file($tmpFile,'upload/'.$fileName);
  
            $sql = "INSERT INTO post(title,description,image) VALUES (:title,:description,:image)";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute(
              array(
                ":title" => $title,
                ":description" => $desc,
                ":image" => $image
              
              )
            );
            if($result){
              echo "<script> alert('New todo is added');window.location.href='index.php';</script>";
            }            
          }
    }  
  }
?>

<?php require "header.php" ?>
    <div class="card">
        <div class="card-body">
            <h1>Create New Todo</h1>
            <form enctype="multipart/form-data" action="add.php" method="post" >
                <div class="form-group">
                  <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']?>">
                    <label for="title">Title</label><p style="color:red"><?php echo empty($titleError) ? '': "***".$titleError ?></p>
                    <input type="text" class="form-control" name="title">
                </div>
                <div class="form-group">
                    <label for="description">Description</label><p style="color:red"><?php echo empty($descError) ? '': "***".$descError ?></p>
                    <textarea name="description" class="form-control"   cols="80" rows="8"></textarea>
                </div>
                <div class="form-group">                    
                    <label for=" file">Select file to upload :</label><p style="color:red"><?php echo empty($imageError) ? '': "***".$imageError ?></p>
                    <input  type="file" id="file" name="file"><br /><br />Click to upload<br />
                </div><br>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" name="submit" value="Submit">
                    <a href="index.php" type="button" class="btn btn-warning">Back</a>
                </div>
            </form>
        </div>
    </div>


    <?php require "footer.html" ?>


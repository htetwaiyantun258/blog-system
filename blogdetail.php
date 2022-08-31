<?php
session_start();
require "config/config.php";
require "config/common.php";





if(empty($_SESSION['user_id'] && $_SESSION['logged_in'])){
  header('Location: login.php');
}

$blogId = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM post WHERE id= $blogId");
$stmt->execute();
$result = $stmt->fetchAll();


$stmtcmt = $pdo->prepare("SELECT * FROM comment WHERE post_id = $blogId");
$stmtcmt->execute();
$cmResult = $stmtcmt->fetchAll();

$auResult = [];
if($cmResult){
 foreach ($cmResult as $key => $value) {
  $authorId = $cmResult[$key]['author_id'];
  $stmtau = $pdo->prepare("SELECT * FROM users WHERE id=$authorId");
  $stmtau->execute();
  $auResult[] = $stmtau->fetchAll();
  
 }
}
if($_POST){
  if(empty($_POST['comment'])){
    if(empty($_POST['title'])){
      $commentError = "You can first comment writing!";
    }
  }else{
    $comment = $_POST['comment'];

    $stmt = $pdo->prepare("INSERT INTO comment(content,author_id,post_id) VALUES (:content,:author_id,:post_id) ");
    $result = $stmt->execute(
        array(':content'=>$comment,':author_id'=>$_SESSION['user_id'],':post_id'=>$blogId)

    );
    if($result){
    header('Location: blogdetail.php?id='. $blogId);
  }

 


      }
}



?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 3 | Widgets</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="">
  <!-- Content Wrapper. Contains page content -->
  <div class="col-md-12" style="margin-left:0px !important" >
    <!-- Content Header (Page header) -->
    <!-- <section class="content-header">
      <div class="container-fluid">
      <h1 style="text-align: center">Blog Title</h1>
      </div>
    </section> -->
  
    <div class="row">
            <div class="col-md-12">
              <!-- Box Comment -->
              <div class="card card-widget">

                  <div class="card-header">
                      <div class="card-title" style="text-align:center !important;float:none;">
                          <h4><?php echo escape($result[0]['title']) ?></h4>
                      </div>
                  </div>
                <!-- /.card-header -->
                <div class="card-body" style="width:800px">
                <img src="admin/upload/<?php echo escape($result[0]['image']) ?>" style="width:600px; margin-left: 300px;"  alt="">
                <p style="width:600px; margin-left: 300px; margin-top: 40px"><?php echo escape($result[0]['description'])  ?></p>
                <h4>Comment</h4>  <hr>
                <a href="/ablog" class="btn btn-default">Go Back</a>
                </div>
                <!-- /.card-body -->
                <div class="card-footer card-comments">
                  <div class="card-comment">
                    <?php
                    if($cmResult){
                      ?>
                      <?php 
                      foreach ($cmResult as $key => $value) {
                        ?>
                        <div class="comment-text" style="margin-left:0px !important">
                        <span class="username">
                         <?php echo escape($auResult[$key][0]['name']) ?>
                        <span class="text-muted float-right"><?php echo escape($value['create_at']) ?></span>
                        </span><!-- /.username -->
                        <?php echo escape($value['content']) ?>
                      </div><br>
                    <?php
                      }
                    ?>

                   <?php
                    }
                     ?>
                    <!-- /.comment-text -->
                  </div>
                </div>
                <!-- /.card-footer -->
                <div class="card-footer" style="margin-left:0px !important;">
                  <form action="" method="post">
                  <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']?>">
                    <img class="img-fluid img-circle img-sm" src="dist/img/user4-128x128.jpg" alt="Alt Text">
                    <!-- .img-push is used to add margin to elements next to floating images -->
                    <div class="img-push">
                      <p style="color:red"><?php echo empty($commentError) ? '': "***".$commentError ?></p>
                      <input type="text" name="comment" class="form-control form-control-sm" placeholder="Press enter to post comment">
                    </div>
                  </form>
                </div>
                <!-- /.card-footer -->
              </div>
              <!-- /.card -->
            </div>
          </div>
          <!-- /.row -->

      <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
        <i class="fas fa-chevron-up"></i>
      </a>
    </div>
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.0.5
    </div>
    <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong> All rights
    reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>

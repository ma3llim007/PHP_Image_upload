<?php include 'db_connect.php'?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Image Upload</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <?php
              if (isset($_SESSION['errorMessage'])) {
              ?>
                <div class="alert alert-danger">
                    <strong>Action Denied! </strong> <?php echo $_SESSION['errorMessage']; ?>
                </div>
                <?php
                  unset($_SESSION['errorMessage']);
              }
              ?>
                <?php
              if (isset($_SESSION['SuccessMessage'])) {
              ?>
                <div class="alert alert-success">
                    <strong>Action Accepted! </strong> <?php echo $_SESSION['SuccessMessage']; ?>
                </div>
                <?php
                  unset($_SESSION['SuccessMessage']);
              }
              ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><strong>PHP Image CRUD - Insert Image In PHP</strong></h4>
                    </div>
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="student_name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="student_name" name="student_name"
                                        placeholder="Enter The Name" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="fileupload" class="form-label">Select Image</label>
                                    <input type="file" class="form-control" id="fileupload" name="fileupload">
                                </div>
                                <div class="form-group mt-4">
                                    <button name="submit" id="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4><strong>PHP Image CRUD - Fetch Image In PHP</strong></h4>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" class="w-25">Name</th>
                                    <th scope="col">Image</th>
                                    <th scope="col" class="w-25">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $selectquery = "select * from image where image_status = 1";
                                $query = mysqli_query($con, $selectquery);
                                while ($res = mysqli_fetch_assoc($query)) {
                                  ?>
                                <tr>
                                    <td><?php echo $res['student_name'];?></td>
                                    <td>
                                        <img src="<?php echo "upload/".$res['image_name'];?>" alt="image" width="30%">
                                    </td>
                                    <td>
                                        <a href="edit.php?id=<?php echo $res['image_id'];?>"
                                            class="btn btn-info">Edit</a> |
                                        <a href="delete.php?id=<?php echo $res['image_id'];?>"
                                            class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                                <?php
                                 }
                              ?>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php 
     
      if (isset($_POST['submit'])) {
        $student_name = $_POST['student_name'];
        $image = $_FILES['fileupload']['name'];
        $image_datime = date('d-m-y : h:i:s');

        $allowed_extension = array('gif','png','jpg','jpeg');
        $filename = $_FILES['fileupload']['name'];
        $file_extension = pathinfo($filename, PATHINFO_EXTENSION);
        if (!in_array($file_extension,$allowed_extension)) {
          $_SESSION['errorMessage'] = "Upload Valid Images. Only PNG, JPEG, JPG And GIF are Allowed.";
          ?>
    <script>
    window.location.href = "index.php";
    </script>
    <?php
        }
        else{
          if (file_exists("upload/".$image)) {
            $_SESSION['errorMessage'] = "Image Already Exists " .$image;
            ?>
    <script>
    window.location.href = "index.php";
    </script>
    <?php
          }
          else{
            $image_uplod = "INSERT INTO image VALUES('','$image','$student_name','1','$image_datime')";
            $image_uplod_query_run = mysqli_query($con, $image_uplod);
            if ($image_uplod_query_run) {
              move_uploaded_file($_FILES['fileupload']["tmp_name"], "./upload/" . $_FILES['fileupload']['name']);
              $_SESSION['SuccessMessage'] = "Data Inserted Successfully!";
              ?>
    <script>
    window.location.href = "index.php";
    </script>
    <?php
            }
            else {
              $_SESSION['errorMessage'] = "Data Not Inserted Successfully!";
              header('Location: index.php');
            }
          }
        }
      }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js">
    </script>
</body>

</html>
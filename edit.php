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
                    <div class="card-header bg-warning">
                        <h4><strong>PHP Image CRUD - Update Name And Image In PHP</strong></h4>

                    </div>
                    <div class="card-body">
                        <?php 
                        $image_id = $_GET['id'];
                        $selectquery = "select * from image where image_status = 1 AND image_id='$image_id'";
                        $query = mysqli_query($con, $selectquery);
                        if (mysqli_num_rows($query) > 0) {
                            foreach ($query as $row) {
                                ?>
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" name="record_id" value="<?php echo $row['image_id'];?>">
                            <div class="form-group">
                               <div class="col-md-6">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="student_name" name="student_name"
                                        value="<?php echo $row['student_name'];?>" placeholder="Enter The Name">
                                </div>
                                <div class="col-md-6 m-4">
                                    <img width="100%" src="http://localhost/php/imageupload/upload/<?php echo $row['image_name'];?>" alt="image">
                                </div>
                                <div class="col-md-4">
                                    <label for="fileupload" class="form-label">Select Image</label>
                                    <input type="file" class="form-control" id="fileupload" name="fileupload">
                                    <input type="hidden" name="image_old" value="<?php echo $row['image_name'];?>">
                                </div>
                                <div class="form-group mt-4">
                                    <button name="update" id="update" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                        <?php
                            }
                        }
                        else{
                            echo "No Record Found";
                        }

                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php 
    if (isset($_POST['update'])) {
        $record_id = $_POST['record_id'];
        $student_name = $_POST['student_name'];
        $image_datime = date('d-m-y : h:i:s');

        $image = $_FILES['fileupload']['name']; // new image value
        $image_old = $_POST['image_old']; // old image value
        // checking image is insert or not
        if (empty($_FILES['fileupload']['name'])) {
            $update_image = $image_old;
        }
        // validation the image which is insert
        else {
            $allowed_extension = array('gif','png','jpg','jpeg');
            $filename = $_FILES['fileupload']['name'];
            $file_extension = pathinfo($filename, PATHINFO_EXTENSION);
            if (!in_array($file_extension,$allowed_extension)) {
                $_SESSION['errorMessage'] = "Upload Valid Images. Only PNG, JPEG, JPG And GIF are Allowed.";
                header('location:edit.php?id='.$record_id);
            }
            if (file_exists("upload/".$image)) {
                $_SESSION['errorMessage'] = "Image Already Exists " .$image;
                header('location:edit.php?id='.$record_id);
            }
            $update_image = $filename;
        }
        // executed query
        $update_query = "UPDATE `image` SET `image_name`='$update_image',`student_name`='$student_name',`image_datetime`='$image_datime' WHERE `image_id`=$record_id";
        // run query
        $image_update_query_run = mysqli_query($con, $update_query);
        if ($image_update_query_run) {
            // upload new image if UPDATE Image
            if (!empty($image)) {
                // moveing file to the folder
                move_uploaded_file($_FILES['fileupload']["tmp_name"], "./upload/" . $update_image);
                // remove the old file from folder
                unlink("upload/".$image_old);
            }
            $_SESSION['SuccessMessage'] = "Update Successfully";
            header('Location:index.php');
        }
        else {
            $_SESSION['errorMessage'] = "Image Is Not Inserted";
            header('location:edit.php?id='.$record_id);
        }
    }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js">
    </script>
</body>

</html>
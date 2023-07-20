<?php 
    include 'db_connect.php';
    $id = $_GET['id'];

    $selectquery = "select * from image where `image_status` = 1 AND `image_id`='$id'";
    $query = mysqli_query($con, $selectquery);
    if ($query) {
        $res = mysqli_fetch_assoc($query);
        unlink("upload/".$res['image_name']);
        $delete_record = "DELETE FROM `image` WHERE `image_status`=1 AND `image_id`='$id'";
        $image_query_delete = mysqli_query($con, $delete_record);
        if ($image_query_delete) {
            $_SESSION['SuccessMessage'] = "Record Deleted Successfully!";
            header('Location: index.php');
        }
        else {
            $_SESSION['errorMessage'] = "Record Not Deleted Successfully!";
            header('Location: index.php');
        }
    }
?>
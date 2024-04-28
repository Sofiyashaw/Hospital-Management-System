<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Profile</title>
   
</head>
<body>

<?php
include("../include/header.php");
include("../include/connection.php");

$ad = $_SESSION['admin'];

$query = "SELECT * FROM admin WHERE username ='$ad'";
$res = mysqli_query($connect, $query);

while ($row = mysqli_fetch_array($res)) {
    $username = $row['username'];
    $profiles = $row['profile'];
}

if (isset($_POST['update'])) {
    $profileName = $_FILES['profile']['name'];

    if (!empty($profileName)) {
        $query = "UPDATE admin SET profile ='$profileName' WHERE username = '$ad'";
        $result = mysqli_query($connect, $query);

        if ($result) {
            move_uploaded_file($_FILES['profile']['tmp_name'], "img/$profileName");
        }
    }
}

if (isset($_POST['change'])) {
    $uname = $_POST['uname'];

    if (!empty($uname)) {
        $query = "UPDATE admin SET username= '$uname' WHERE username='$ad'";
        $res = mysqli_query($connect, $query);

        if ($res) {
            $_SESSION['admin'] = $uname;
        }
    }
}

if (isset($_POST['update_pass'])) {
    $old_pass = $_POST['old_pass'];
    $new_pass = $_POST['new_pass'];
    $con_pass = $_POST['con_pass'];

    $error = array();

    $old = mysqli_query($connect, "SELECT * FROM admin WHERE username='$ad'");
    $row = mysqli_fetch_array($old);
    $pass = $row['password'];

    if (empty($old_pass)) {
        $error['p'] = "Enter old password";
    } elseif (empty($new_pass)) {
        $error['p'] = "Enter New password";
    } elseif (empty($con_pass)) {
        $error['p'] = "Confirm Password";
    } elseif ($old_pass != $pass) {
        $error['p'] = "Invalid Old Password";
    } elseif ($new_pass != $con_pass) {
        $error['p'] = "Both passwords do not match";
    }

    if (count($error) == 0) {
        $query = "UPDATE admin SET password ='$new_pass' WHERE username='$ad'";
        mysqli_query($connect, $query);
    }
}

if (isset($error['p'])) {
    $e = $error['p'];
    $show = "<h5 class='text-center alert alert-danger'>$e </h5>";
} else {
    $show = "";
}
?>

<div class="container-fluid">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-2" style="margin-left:-12px;">
                <?php include("sidenav.php"); ?>
            </div>
            <div class="col-md-10">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Profile</h4>
                            <br>

                            <form method="post" enctype="multipart/form-data">
                                <?php echo "<img src ='img/$profiles' class='col-md-5' style='height: 300px;'> "; ?>
                                <br><br>
                                <div class="form-group">
                                    <label>UPDATE PROFILE</label>
                                    <input type="file" name="profile" class="form-control">
                                </div>
                                <br>
                                <input type="submit" name="update" value="UPDATE" class="btn btn-success">
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form method="post">
                                <h5 class="text-center my-4"> Change Username </h5>
                                
                                <input type="text" name="uname" class="form-control" autocomplete="off">
                                <br>
                                <input type="submit" name="change" class="btn btn-info" value="Change">
                            </form>
                            <br>
                            <br>
                            <form method="post">
                                <h5 class="text-center my-4">Change Password</h5>
                                <div>
                                    <?php echo $show; ?>
                                </div>
                                <div class="form-group">
                                    <label>Old Password</label>
                                    <input type="password" name="old_pass" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" name="new_pass" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input type="password" name="con_pass" class="form-control">
                                </div>
                                <br>
                                <input type="submit" name="update_pass" value="Update Password" class="btn btn-info">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
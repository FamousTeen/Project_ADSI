<?php
include('db_connect.php');

if (isset($_POST['signup'])) {
    $username = mysqli_real_escape_string($mysqli, $_POST['username']);
    $email = mysqli_real_escape_string($mysqli, $_POST['email']);
    $password = mysqli_real_escape_string($mysqli, $_POST['pass']);
    $cpassword = mysqli_real_escape_string($mysqli, $_POST['cpass']);

    $sql = "SELECT * FROM accounts WHERE username='$username'";
    $result = mysqli_query($mysqli, $sql);
    $count_user = mysqli_num_rows($result);

    $sql = "SELECT * FROM accounts WHERE email='$email'";
    $result = mysqli_query($mysqli, $sql);
    $count_email = mysqli_num_rows($result);

    if ($count_user == 0 && $count_email == 0) {

        if ($password == $cpassword) {

            // Set the default user type to 'user'
            $user_type = 'user';
            

            $sql = "INSERT INTO accounts(username, email, password, user_type) VALUES('$username', '$email', '$password', '$user_type')";

            $result = mysqli_query($mysqli, $sql);

            if ($result) {
                header("Location: login_page.php");
            }
        } else {
            echo '<script>
                        alert("Passwords do not match")
                        window.location.href = "login_page.php";
                    </script>';
        }
    } else {
        if ($count_user > 0) {
            echo '<script>
                        window.location.href = "login_page.php";
                        alert("Username already exists!!")
                    </script>';
        }
        if ($count_email > 0) {
            echo '<script>
                        window.location.href = "login_page.php";
                        alert("Email already exists!!")
                    </script>';
        }
    }
}
?>
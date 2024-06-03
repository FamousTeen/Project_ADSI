<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include("db_connect.php");
echo isset($_POST["Login"]) ? 'Form is submitted' : 'Form is not submitted';
if (isset($_POST["Login"])) {
  $name = mysqli_real_escape_string($mysqli, $_POST['name']);
  $password = mysqli_real_escape_string($mysqli, $_POST['pass']);

  if ($_POST["role"] == "manager") {
    $sql = "SELECT * FROM manager WHERE managerName ='$name' AND password = '$password'";
    $result = mysqli_query($mysqli, $sql);
  
    if (mysqli_num_rows($result) == 1) {
      $row = mysqli_fetch_assoc($result);
  
      // Start a session
      session_start();
      $_SESSION['password'] = $row['password'];
      $_SESSION['name'] = $row['managerName'];
      $_SESSION['dept_name'] = $row['departmentName'];
      $_SESSION['empList'] = $row['empList'];
      $_SESSION['no_telp'] = $row['no_telp'];
      $_SESSION['status'] = $row['status'];
  
      header("Location: createProject_page.php");
      exit();
    } else {
      echo '<script>
                          window.location.href = "login_page.php";
                          alert("Login failed. Invalid username or password.")
                      </script>';
    }
  } else {
    $sql = "SELECT * FROM employee WHERE empName ='$name' AND password = '$password'";
    $result = mysqli_query($mysqli, $sql);
  
    if (mysqli_num_rows($result) == 1) {
      $row = mysqli_fetch_assoc($result);
  
      // Start a session
      session_start();
      $_SESSION['password'] = $row['password'];
      $_SESSION['name'] = $row['empName'];
      $_SESSION['dept_name'] = $row['departmentName'];
      $_SESSION['no_telp'] = $row['no_telp'];
      $_SESSION['credit_score'] = $row['credit_score'];
      $_SESSION['status'] = $row['status'];
  
      header("Location: createProject.html");
      exit();
    } else {
      echo '<script>
                          window.location.href = "login_page.php";
                          alert("Login failed. Invalid username or password.")
                      </script>';
    }
  }
}
mysqli_close($mysqli);
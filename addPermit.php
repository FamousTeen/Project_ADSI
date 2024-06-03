<?php
include("db_connect.php");

session_start();

if (isset($_POST["Send"])) {
  $permitTitle = mysqli_real_escape_string($mysqli, $_POST['permitTitle']);
  $permitDesc = mysqli_real_escape_string($mysqli, $_POST['permitDesc']);
  $permitDate = $_POST['permitDate'];

  $dept_name = $_SESSION['dept_name'];

  $sql = "SELECT * FROM manager WHERE departmentName ='$dept_name'";
  $result = mysqli_query($mysqli, $sql);

  $row;
  if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
  }

  $manID = $row['idManager'];
  $status = "Unapprove";

  $sql = "INSERT INTO permit (`permitTitle`, `description`, `man`, `emp`, `status`, `permitDate`) 
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ssiiss", $permitTitle, $permitDesc, $manID, $_SESSION['idEmp'], $status, $permitDate);

    $res = $stmt->execute();

    if ($res) {
        $_SESSION['msg'] = "Berhasil menambah permit";
        header("Location: createPermitReq.php");
        die();
    } else {
        echo "Tidak dapat menambah permit";
        die();
    }
}
mysqli_close($mysqli);
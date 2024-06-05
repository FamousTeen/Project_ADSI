<?php
include("db_connect.php");
session_start();

if (isset($_POST['Send'])) {
$permitT = $_POST['permitTitle'];
$sql = "SELECT * FROM permit WHERE permitTitle ='$permitT'";
$result = mysqli_query($mysqli, $sql);

$row;
if (mysqli_num_rows($result) == 1) {
  $row = mysqli_fetch_assoc($result);
}

$permitId  = $row['permitId'];
$permitT = $row['permitTitle'];
$permitDesc = $row['description'];
$manID = $row['man'];
$empID = $row['emp'];
$status = $row['status'];
$permitDate = $row['permitDate'];

}
class Permit {
  public $permitId;
  public $permitTitle;
  public $permitDesc;
  public $permitDate;
  public $manID;
  public $empID;
  public $status;

  public function __construct($permitId, $permitTitle, $permitDesc, $permitDate, $manID, $empID, $status) {
    $this->permitId = $permitId;
    $this->permitTitle = $permitTitle;
    $this->permitDesc = $permitDesc;
    $this->permitDate = $permitDate;
    $this->manID = $manID;
    $this->empID = $empID;
    $this->status = $status;
  }

  public function createPermit() {
    include("db_connect.php");
    if (isset($_POST["Send"])) {
      $dept_name = $_SESSION['dept_name'];
    
      $sql = "SELECT * FROM manager WHERE departmentName ='$dept_name'";
      $result = mysqli_query($mysqli, $sql);
    
      $row;
      if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
      }
    
      $manID = $row['idManager'];
    
      $sql = "INSERT INTO permit (`permitTitle`, `description`, `man`, `emp`, `status`, `permitDate`) 
                VALUES (?, ?, ?, ?, ?, ?)";
    
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ssiiss", $this->permitTitle, $this->permitDesc, $manID, $_SESSION['idEmp'], $this->status, $this->permitDate);
    
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
  }
  public function sendNotif() {
    include("db_connect.php");
    $result2 = null;
    if (isset($_SESSION['idMan'])) {
      $idMan = $_SESSION['idMan'];
      $sql = "SELECT * FROM permit WHERE man = $idMan";
      $result2 = mysqli_query($mysqli, $sql);
      $data = array();
    }

    $data = array();

    if(isset($_SESSION['idMan'])) {
      return array($data, $result2);
    }
  }
}
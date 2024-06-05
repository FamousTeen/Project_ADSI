<?php
include("db_connect.php");
include("creditClass.php");

$employeeManager = new EmployeeManager($mysqli);

$data = $employeeManager->fetchEmployees();

echo json_encode($data);

$mysqli->close();
?>

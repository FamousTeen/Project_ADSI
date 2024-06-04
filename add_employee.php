<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include('db_connect.php');

if (isset($_POST['addEmployee'])) {
    $employeeId = $_POST['employeeId'];
    $projectId = $_POST['projectId'];

    // Insert the employee into the project (assuming you have a project_employee table)
    $query = "INSERT INTO employeeProject (idEmp, idProject) VALUES ('$employeeId', '$projectId')";
    
    if (mysqli_query($mysqli, $query)) {
        echo "Employee added successfully.";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($mysqli);
    }

    mysqli_close($mysqli);
    header("Location: createProject_page.php");
    exit();
}
?>

<?php
include("db_connect.php");
include("creditClass.php");

if (isset($_POST['empName']) && isset($_POST['creditScore'])) {
    $employeeManager = new EmployeeManager($mysqli);

    $empName = $_POST['empName'];
    $creditScore = $_POST['creditScore'];

    $response = $employeeManager->updateCreditScore($empName, $creditScore);
    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
}

$mysqli->close();
?>

<?php
include("db_connect.php");

// Check if POST data exists
if (isset($_POST['empName']) && isset($_POST['creditScore'])) {
    // Sanitize input data
    $empName = $mysqli->real_escape_string($_POST['empName']);
    $creditScore = $mysqli->real_escape_string($_POST['creditScore']);

    // Update the credit score in the database
    $sql = "UPDATE employee SET credit_score = '$creditScore' WHERE empName = '$empName'";
    if ($mysqli->query($sql) === TRUE) {
        $response = array('success' => true);
    } else {
        $response = array('success' => false);
    }

    // Output JSON response
    echo json_encode($response);
} else {
    // Invalid request
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
}

$mysqli->close();
?>

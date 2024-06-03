<?php
// Include database connection
include 'db_connect.php';

// Fetch employees from the Employee table
$sql = "SELECT * FROM Employee";
$result = $mysqli->query($sql);

$employees = array();

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
} else {
    // No results
    $employees[] = array("error" => "No employees found");
}

// Close the database connection
$mysqli->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($employees);
?>

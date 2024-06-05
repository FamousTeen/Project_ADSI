<?php
include("db_connect.php");

// Fetch employees' names and credit scores from the database
$sql = "SELECT empName, credit_score FROM Employee";
$result = $mysqli->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            'empName' => $row['empName'],
            'credit_score' => $row['credit_score']
        );
    }
}

echo json_encode($data);

$mysqli->close();
?>
